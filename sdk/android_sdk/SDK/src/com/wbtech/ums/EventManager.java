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

class EventManager {
    private Context context;
    private String eventid;
    private String label;
    private int acc;
    private final String tag = "EventManager";
//    private final String EVENT_URL = "/ums/event.php";
    private final String EVENT_URL = "/ums/postEvent";

    public EventManager(Context context, String eventid, String label, int acc) {
        this.context = context;
        this.eventid = eventid;
        this.label = label;
        this.acc = acc;
    }

    private JSONObject prepareEventJSON() {
    
        JSONObject localJSONObject = new JSONObject();
        try
        {
            localJSONObject.put("time", DeviceInfo.getDeviceTime());
            localJSONObject.put("version", AppInfo.getAppVersion());
            localJSONObject.put("event_identifier", eventid);
            localJSONObject.put("appkey", AppInfo.getAppKey());
            SharedPrefUtil sp = new SharedPrefUtil(context);
            localJSONObject.put("activity", sp.getValue("CurrentPage", CommonUtil.getActivityName(context)));
            localJSONObject.put("label", label);
            localJSONObject.put("acc", acc);
            localJSONObject.put("attachment", "");
            localJSONObject.put("userid", CommonUtil.getUserIdentifier(context));
            localJSONObject.put("deviceid", DeviceInfo.getDeviceId());
        } catch (JSONException e)
        {
            CobubLog.e(tag, e.toString());
        }
        return localJSONObject;
    }

    public void postEventInfo() {
        JSONObject localJSONObject;
        try {
            localJSONObject = prepareEventJSON();
        } catch (Exception e) {
            CobubLog.e(tag, e.toString());
            return;
        }
        if (CommonUtil.getReportPolicyMode(context) == SendPolicy.REALTIME
                && CommonUtil.isNetworkAvailable(context)) {

            String result = NetworkUtil.Post(UmsConstants.urlPrefix + EVENT_URL,
                    localJSONObject.toString());
            MyMessage message = NetworkUtil.parse(result);
            if (message == null) {
                CommonUtil.saveInfoToFile("eventInfo", localJSONObject, context);
                return;
            }
            if (message.getFlag() < 0) {
                CobubLog.e(tag, "Error Code=" + message.getFlag() + ",Message=" + message.getMsg());
                if (message.getFlag() == -4)
                    CommonUtil.saveInfoToFile("eventInfo", localJSONObject, context);
                return;
            }

        } else {
            CommonUtil.saveInfoToFile("eventInfo", localJSONObject, context);
            return;
        }

    }
}
