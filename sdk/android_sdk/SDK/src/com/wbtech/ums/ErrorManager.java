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

class ErrorManager {
    private Context context;
    private final String tag = "ErrorManager";
//    public static final String ERROR_URL = "/ums/error.php";
    public static final String ERROR_URL = "/ums/postErrorLog";

    public ErrorManager(Context context) {
        this.context = context;
    }

    private JSONObject prepareErrorJSON(String errorinfo) {
        String activities = CommonUtil.getActivityName(context);

        String appkey = AppInfo.getAppKey();
        String os_version = DeviceInfo.getOsVersion();
        JSONObject errorInfo = new JSONObject();
        try {

            errorInfo.put("stacktrace", errorinfo);
            errorInfo.put("time", DeviceInfo.getDeviceTime());
            errorInfo.put("activity", activities);
            errorInfo.put("appkey", appkey);
            errorInfo.put("os_version", os_version);
            errorInfo.put("deviceid", DeviceInfo.getDeviceName());
            errorInfo.put("userid", CommonUtil.getUserIdentifier(context));

        } catch (JSONException e) {
            CobubLog.e(tag, e.toString());
            return null;
        }
        return errorInfo;
    }

    public void postErrorInfo(String error) {
        JSONObject errorJSON;
        try {
            errorJSON = prepareErrorJSON(error);
        } catch (Exception e) {
            CobubLog.e(tag, e);
            return;
        }
        if (CommonUtil.getReportPolicyMode(context) == SendPolicy.REALTIME
                && CommonUtil.isNetworkAvailable(context)) {

            String result = NetworkUtil.Post(UmsConstants.urlPrefix + ERROR_URL,
                    errorJSON.toString());
            MyMessage message = NetworkUtil.parse(result);
            if (message == null) {
                CommonUtil.saveInfoToFile("errorInfo", errorJSON, context);
                return;
            }
            if (message.getFlag() < 0) {
                CobubLog.e(tag, "Error Code=" + message.getFlag() + ",Message=" + message.getMsg());
                if (message.getFlag() == -4)
                    CommonUtil.saveInfoToFile("errorInfo", errorJSON, context);
            }
        } else {
            CommonUtil.saveInfoToFile("errorInfo", errorJSON, context);
        }

    }
}
