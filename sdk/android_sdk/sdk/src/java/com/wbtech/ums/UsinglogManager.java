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

import java.lang.ref.WeakReference;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;

import com.wbtech.ums.UmsConstants;
import com.wbtech.ums.UmsAgent.SendPolicy;

class UsinglogManager {
	private static WeakReference<Context> contextWR;
    private String session_id = "";

    public UsinglogManager(Context context) {
    	 contextWR = new WeakReference<Context>(context);
        
    }

    JSONObject prepareUsinglogJSON(String start_millis, String end_millis,
            String duration, String activities) throws JSONException {
        JSONObject jsonUsinglog = new JSONObject();
        if (session_id.equals("")) {
            session_id = CommonUtil.getSessionid(contextWR.get());     
        }

        jsonUsinglog.put("session_id", session_id);
        jsonUsinglog.put("start_millis", start_millis);
        jsonUsinglog.put("end_millis", end_millis);
        jsonUsinglog.put("duration", duration);
        jsonUsinglog.put("version", AppInfo.getAppVersion(contextWR.get()));
        jsonUsinglog.put("activities", activities);
        jsonUsinglog.put("appkey", AppInfo.getAppKey(contextWR.get()));
        jsonUsinglog.put("useridentifier",
                CommonUtil.getUserIdentifier(contextWR.get()));
        jsonUsinglog.put("deviceid", DeviceInfo.getDeviceId());
        jsonUsinglog.put("lib_version", UmsConstants.LIB_VERSION);

        return jsonUsinglog;
    }
    public void judgeSession(final Context context){
    	CobubLog.i(UmsConstants.LOG_TAG,UsinglogManager.class, "Call onResume()");
        try {
            if (CommonUtil.isNewSession(context)) {
                session_id = CommonUtil.generateSession(context);
                ClientdataManager cm = new ClientdataManager(context);
                cm.postClientData();
                CobubLog.i(UmsConstants.LOG_TAG, UsinglogManager.class,"New Sessionid is " + session_id);
            }
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }
    }
    /**
     * activity onResume
     * @param context
     */
    public void onResume(final Context context) {
    	judgeSession(context);
        CommonUtil.savePageName(context, CommonUtil.getActivityName(context));
    }
    /**
     * fragment onResume
     * @param context
     * @param pageName
     */
    public void onFragmentResume(final Context context,String pageName) {
    	judgeSession(context);
        CommonUtil.savePageName(context, pageName);
    }

    public void onPause(final Context context) {
        CobubLog.i(UmsConstants.LOG_TAG,UsinglogManager.class, "Call onPause()");

        SharedPrefUtil sp = new SharedPrefUtil(context);
        String pageName = sp.getValue("CurrentPage", CommonUtil.getActivityName(context));

        long start = sp.getValue("session_save_time",
                System.currentTimeMillis());
        String start_millis = CommonUtil.getFormatTime(start);

        long end = System.currentTimeMillis();
        String end_millis = CommonUtil.getFormatTime(end);

        String duration = end - start + "";
        CommonUtil.saveSessionTime(context);

        JSONObject info;
        try {
            info = prepareUsinglogJSON(start_millis, end_millis, duration,
                    pageName);
            CommonUtil.saveInfoToFile("activityInfo", info, context);
        } catch (JSONException e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }
    }

    public void onWebPage(String pageName,final Context context) {
        SharedPrefUtil sp = new SharedPrefUtil(context);
        String lastView = sp.getValue("CurrentWenPage", "");
        if (lastView.equals("")) {
            sp.setValue("CurrentWenPage", pageName);
            sp.setValue("session_save_time", System.currentTimeMillis());
        } else {
            long start = sp.getValue("session_save_time",
                    System.currentTimeMillis());
            String start_millis = CommonUtil.getFormatTime(start);

            long end = System.currentTimeMillis();
            String end_millis = CommonUtil.getFormatTime(end);

            String duration = end - start + "";

            sp.setValue("CurrentWenPage", pageName);
            sp.setValue("session_save_time", end);

            JSONObject obj;
            try {
                obj = prepareUsinglogJSON(start_millis, end_millis, duration,
                        lastView);
                CommonUtil.saveInfoToFile("activityInfo", obj, context);
            } catch (JSONException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            }
        }
    }
}
