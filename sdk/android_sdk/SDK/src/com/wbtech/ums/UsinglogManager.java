/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package     Cobub Razor
 * @author      WBTECH Dev Team
 * @copyright   Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license     http://www.cobub.com/products/cobub-razor/license
 * @link        http://www.cobub.com/products/cobub-razor/
 * @since       Version 0.1
 * @filesource 
 */

package com.wbtech.ums;

import android.content.Context;

import com.wbtech.ums.UmsAgent.SendPolicy;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;

public class UsinglogManager {

    private Context context;
    private final String tag = "UsinglogManager";
    private final String USINGLOG_URL = "/ums/usinglog.php";
    private String session_id;
//    private String activities;
//    private String start_millis = null;
//    private String end_millis = null;
//    private long end = 0;
//    private long start = 0;
//    private String duration;
//    private String pageName;

    public UsinglogManager(Context context) {
        this.context = context;
    }

    JSONObject prepareUsinglogJSON(String start_millis,String end_millis,String duration,String activities) throws JSONException {
        JSONObject jsonUsinglog = new JSONObject();

        if (session_id == null) {
            try {
                session_id = CommonUtil.generateSession(context);
            } catch (ParseException e) {
                e.printStackTrace();
            }
        }

        jsonUsinglog.put("session_id", session_id);
        jsonUsinglog.put("start_millis", start_millis);
        jsonUsinglog.put("end_millis", end_millis);
        jsonUsinglog.put("duration", duration);
        jsonUsinglog.put("version", AppInfo.getAppVersion());
        jsonUsinglog.put("activities", activities);
        jsonUsinglog.put("appkey", AppInfo.getAppKey());
        jsonUsinglog.put("userid", CommonUtil.getUserIdentifier(context));
        jsonUsinglog.put("deviceid", DeviceInfo.getDeviceId());

        return jsonUsinglog;
    }

    public void onResume() {
        CobubLog.i(tag, "Call onResume()");
        try {
            if (CommonUtil.isNewSession(context)) {
                session_id = CommonUtil.generateSession(context);
                CobubLog.i(tag, "New Sessionid is " + session_id);

                Thread thread = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        
                        CobubLog.i(tag, "Start postClientdata thread");
                        ClientdataManager cm = new ClientdataManager(context);
                        cm.postClientData();
                    }
                });
                thread.run();
            }
        } catch (Exception e) {
            CobubLog.e(tag, e);
        }
        CommonUtil.saveSessionTime(context);
        CommonUtil.savePageName(context, CommonUtil.getActivityName(context));
//        activities = CommonUtil.getActivityName(context);

//        start_millis = DeviceInfo.getDeviceTime();
//        start = Long.valueOf(System.currentTimeMillis());
    }

    public void onPause() {
        CobubLog.i(tag, "Call onPause()");
        
        SharedPrefUtil sp = new SharedPrefUtil(context);
        
        String pageName = sp.getValue("CurrentPage", "");
        
        long start = sp.getValue("session_save_time", System.currentTimeMillis());
        String start_millis = CommonUtil.getFormatTime(start);
        
        long end = System.currentTimeMillis();
        String end_millis = CommonUtil.getFormatTime(end);
        
        String duration = end - start + "";
        
        CommonUtil.saveSessionTime(context);

        JSONObject info;
        try {
            info = prepareUsinglogJSON(start_millis,end_millis,duration,pageName);
        } catch (JSONException e) {
            CobubLog.e(tag, e);
            return;
        }

        if (CommonUtil.getReportPolicyMode(context) == SendPolicy.REALTIME
                && CommonUtil.isNetworkAvailable(context)) {
            CobubLog.i(tag, "post activity info");
            String result = NetworkUtil.Post(UmsConstants.urlPrefix
                    + USINGLOG_URL,
                    info.toString());
            MyMessage message = NetworkUtil.parse(result);
            if (message == null) {
                CommonUtil.saveInfoToFile("activityInfo", info, context);
                return;
            }

            if (message.getFlag() < 0) {
                CobubLog.e(tag, "Error Code=" + message.getFlag() + ",Message=" + message.getMsg());
                if (message.getFlag() == -4 || message.getFlag() == -5)
                    CommonUtil.saveInfoToFile("activityInfo", info, context);
            }
        } else {
            CommonUtil.saveInfoToFile("activityInfo", info, context);
        }
    }

    void onWebPage(String pageName) {

        SharedPrefUtil sp = new SharedPrefUtil(context);
        String lastView = sp.getValue("CurrentPage", "");
        if (lastView.equals("")) {
            sp.setValue("CurrentPage", pageName);
            sp.setValue("session_save_time", System.currentTimeMillis());
        } else {
            long start = sp.getValue("session_save_time", Long.valueOf(System.currentTimeMillis()));
            String start_millis = CommonUtil.getFormatTime(start);
            
            long end = System.currentTimeMillis();
            String end_millis = CommonUtil.getFormatTime(end);
            
            String duration = end - start + "";

            sp.setValue("CurrentPage", pageName);
            sp.setValue("session_save_time", end);

            JSONObject obj;
            try {
                obj = prepareUsinglogJSON(start_millis,end_millis,duration,pageName);
            } catch (JSONException e) {
                CobubLog.e(tag, e);
                return;
            }

            if (CommonUtil.getReportPolicyMode(context) == SendPolicy.REALTIME
                    && CommonUtil.isNetworkAvailable(context)) {

                CobubLog.i(tag, "post activity info");
                String result = NetworkUtil.Post(UmsConstants.urlPrefix
                        + USINGLOG_URL,
                        obj.toString());
                MyMessage message = NetworkUtil.parse(result);
                if (message == null) {
                    CommonUtil.saveInfoToFile("activityInfo", obj, context);
                    return;
                }

                if (message.getFlag() < 0) {
                    CobubLog.e(tag,
                            "Error Code=" + message.getFlag() + ",Message=" + message.getMsg());
                    if (message.getFlag() == -4 || message.getFlag() == -5)
                        CommonUtil.saveInfoToFile("activityInfo", obj, context);
                }
            } else {
                CommonUtil.saveInfoToFile("activityInfo", obj, context);
            }

        }

    }
}
