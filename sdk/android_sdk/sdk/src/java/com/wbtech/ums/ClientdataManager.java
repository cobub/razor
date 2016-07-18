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

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.wbtech.ums.UmsConstants;

import android.content.Context;


class ClientdataManager {
    private Context context;
    private final String PLATFORM = "android";

    public ClientdataManager(Context context) {
        this.context = context;
        DeviceInfo.init(context);
    }

    JSONObject prepareClientdataJSON() throws JSONException {
        JSONObject jsonClientdata = new JSONObject();

        jsonClientdata.put("deviceid", DeviceInfo.getDeviceId());
        jsonClientdata.put("os_version", DeviceInfo.getOsVersion());
        jsonClientdata.put("platform", PLATFORM);
        jsonClientdata.put("language", DeviceInfo.getLanguage());
        jsonClientdata.put("appkey", AppInfo.getAppKey(context));
        jsonClientdata.put("resolution", DeviceInfo.getResolution());
        jsonClientdata.put("ismobiledevice", true);
        jsonClientdata.put("phonetype", DeviceInfo.getPhoneType());
        jsonClientdata.put("imsi", DeviceInfo.getIMSI());


        jsonClientdata.put("mccmnc", DeviceInfo.getMCCMNC());
        jsonClientdata.put("cellid", DeviceInfo.getCellInfoofCID());
        jsonClientdata.put("lac", DeviceInfo.getCellInfoofLAC());


        jsonClientdata.put("network", DeviceInfo.getNetworkTypeWIFI2G3G());
        jsonClientdata.put("time", DeviceInfo.getDeviceTime());
        jsonClientdata.put("version", AppInfo.getAppVersion(context));
        jsonClientdata.put("useridentifier",
                CommonUtil.getUserIdentifier(context));
        jsonClientdata.put("modulename", DeviceInfo.getDeviceProduct());
        jsonClientdata.put("devicename", DeviceInfo.getDeviceName());
        jsonClientdata.put("wifimac", DeviceInfo.getWifiMac());
        jsonClientdata.put("havebt", DeviceInfo.getBluetoothAvailable());
        jsonClientdata.put("havewifi", DeviceInfo.getWiFiAvailable());
        jsonClientdata.put("havegps", DeviceInfo.getGPSAvailable());
        jsonClientdata.put("havegravity", DeviceInfo.getGravityAvailable());
        jsonClientdata.put("session_id", CommonUtil.getSessionid(context));
        jsonClientdata.put("salt", CommonUtil.getSALT(context));
        jsonClientdata.put("lib_version", UmsConstants.LIB_VERSION);

        if (CommonUtil.isSupportlocation(context)) {
            jsonClientdata.put("latitude", DeviceInfo.getLatitude());
            jsonClientdata.put("longitude", DeviceInfo.getLongitude());
        }

        return jsonClientdata;
    }
    public void judgeSession(final Context context){
    	CobubLog.i(UmsConstants.LOG_TAG,UsinglogManager.class, "judgeSession on clientdata");
        try {
            if (CommonUtil.isNewSession(context)) {
              String  session_id = CommonUtil.generateSession(context);
                CobubLog.i(UmsConstants.LOG_TAG, UsinglogManager.class,"New Sessionid is " + session_id);
            }
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }
    }
    public void postClientData() {
        JSONObject clientData;
        judgeSession(context);
        try {
            clientData = prepareClientdataJSON();
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
            return;
        }
        JSONObject postdata = new JSONObject();
        try {
            postdata.put("data", new JSONArray().put(clientData));
        } catch (JSONException e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }

        if (CommonUtil.isNetworkAvailable(context)) {
            MyMessage message = NetworkUtil.Post(UmsConstants.BASE_URL
                    + UmsConstants.CLIENTDATA_URL, postdata.toString());

            if (!message.isSuccess()) {
                CobubLog.e(UmsConstants.LOG_TAG, ClientdataManager.class, "Error Code=" + message.getMsg());
                CommonUtil.saveInfoToFile("clientData", clientData, context);
            }
        } else {
            CommonUtil.saveInfoToFile("clientData", clientData, context);
        }
    }
}
