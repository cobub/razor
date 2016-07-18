/**
 * Cobub Razor
 * <p/>
 * An open source analytics android sdk for mobile applications
 *
 * @package Cobub Razor
 * @author WBTECH Dev Team
 * @copyright Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license http://www.cobub.com/products/cobub-razor/license
 * @link http://www.cobub.com/products/cobub-razor/
 * @filesource
 * @since Version 0.1
 */
package com.wbtech.ums;

import java.util.Iterator;

import android.content.Context;

import com.wbtech.ums.UmsConstants;
import com.wbtech.ums.UmsAgent.SendPolicy;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

class EventManager {
    private Context context;
    private String eventid;
    private String label;
    private String acc;
    private String json = "";

    public EventManager(Context context, String eventid, String label,
                        String acc) {
        this.context = context;
        this.eventid = eventid;
        this.label = label;
        this.acc = acc;
    }

    public EventManager(Context context, String eventid, String label,
                        String acc, String json) {
        this.context = context;
        this.eventid = eventid;
        this.label = label;
        this.acc = acc;
        this.json = json;
    }

    private JSONObject prepareEventJSON() {
        JSONObject localJSONObject = new JSONObject();
        try {
            localJSONObject.put("time", DeviceInfo.getDeviceTime());
            localJSONObject.put("version", AppInfo.getAppVersion(context));
            localJSONObject.put("event_identifier", eventid);
            localJSONObject.put("appkey", AppInfo.getAppKey(context));
            SharedPrefUtil sp = new SharedPrefUtil(context);
            localJSONObject.put(
                    "activity",
                    sp.getValue("CurrentPage",
                            CommonUtil.getActivityName(context)));
            localJSONObject.put("label", label);
            localJSONObject.put("acc", acc);
            localJSONObject.put("attachment", "");
            localJSONObject.put("useridentifier",
                    CommonUtil.getUserIdentifier(context));
            localJSONObject.put("deviceid", DeviceInfo.getDeviceId());
            localJSONObject.put("session_id", CommonUtil.getSessionid(context));
            localJSONObject.put("lib_version", UmsConstants.LIB_VERSION);

            //key值可能会与上面的重复，加一个前缀V_
            if (json != null && json.length() > 0) {
                //如果不是json串，丢弃这部分内容并告警
                try {
                    JSONObject obj = new JSONObject(json);
                    Iterator<String> iterator = obj.keys();
                    while (iterator.hasNext()) {
                        String key = iterator.next();
                        localJSONObject.put("V_" + key, obj.get(key));
                    }
                } catch (JSONException e) {
                    CobubLog.e(UmsConstants.LOG_TAG, EventManager.class, e.toString());
                }
            }
        } catch (JSONException e) {
            CobubLog.e(UmsConstants.LOG_TAG, EventManager.class, e.toString());
        }
        return localJSONObject;
    }

    public void postEventInfo() {
        JSONObject localJSONObject;
        try {
            localJSONObject = prepareEventJSON();
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, EventManager.class, e.toString());
            return;
        }

        JSONObject postdata = new JSONObject();
        try {
            postdata.put("data", new JSONArray().put(localJSONObject));
        } catch (JSONException e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }

        if (CommonUtil.getReportPolicyMode(context) == SendPolicy.POST_NOW
                && CommonUtil.isNetworkAvailable(context)) {
            MyMessage message = NetworkUtil.Post(UmsConstants.BASE_URL
                    + UmsConstants.EVENT_URL, postdata.toString());

            if (!message.isSuccess()) {
                CobubLog.e(UmsConstants.LOG_TAG, EventManager.class, "Message="
                        + message.getMsg());
                CommonUtil
                        .saveInfoToFile("eventInfo", localJSONObject, context);
            }
        } else {
            CommonUtil.saveInfoToFile("eventInfo", localJSONObject, context);
        }
    }
}
