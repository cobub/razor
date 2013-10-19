
package com.wbtech.ums.common;

import android.content.Context;
import android.os.Build;

import com.wbtech.ums.objects.PostObjEvent;
import com.wbtech.ums.objects.PostObjTag;

import org.json.JSONException;
import org.json.JSONObject;

public class AssembJSONObj {

    public static JSONObject getErrorInfoJSONObj(String error, Context context) {
        String stacktrace = error;
        String activities = CommonUtil.getActivityName(context);
        String time = CommonUtil.getTime();
        String appkey = CommonUtil.getAppKey(context);
        String os_version = CommonUtil.getOsVersion(context);
        String deviceID = CommonUtil.getDeviceID(context);
        JSONObject errorInfo = new JSONObject();
        try {
            Build bd = new Build();
            errorInfo.put("stacktrace", stacktrace);
            errorInfo.put("time", time);
            errorInfo.put("activity", activities);
            errorInfo.put("appkey", appkey);
            errorInfo.put("os_version", os_version);
            errorInfo.put("deviceid", bd.MANUFACTURER + bd.PRODUCT);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return errorInfo;
    }
    
    public static JSONObject getpostTagsJSONObj(PostObjTag tagobj){
        JSONObject object = new JSONObject();
        try {
            object.put("tags", tagobj==null?"":tagobj.getTags());
            object.put("deviceid",tagobj==null?"": tagobj.getDeviceid());
            object.put("productkey",tagobj==null?"": tagobj.getProductkey());
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return object;
    }
    
    
    public static JSONObject getEventJOSNobj(PostObjEvent event) {
        JSONObject localJSONObject = new JSONObject();
        try
        {

            localJSONObject.put("time", event.getTime());
            localJSONObject.put("version", event.getVersion());
            localJSONObject.put("event_identifier", event.getEvent_id());
            localJSONObject.put("appkey", event.getAppkey());
            localJSONObject.put("activity", event.getActivity());
            // localJSONObject.put(UserIdentifier,
            // CommonUtil.getUserIdentifier(context));
            if (event.getLabel() != null)
                localJSONObject.put("label", event.getLabel());
            localJSONObject.put("acc", event.getAcc());

        } catch (JSONException localJSONException)
        {
            CommonUtil.printLog("UmsAgent", "json error in emitCustomLogReport");
            localJSONException.printStackTrace();
        }
        return localJSONObject;
    }
    


    
}
