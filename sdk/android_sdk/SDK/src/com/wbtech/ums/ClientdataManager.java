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

class ClientdataManager {
    private Context context;
    private final String tag = "ClientdataManager";
    private final String PLATFORM = "android";
//    private final String CLIENTDATA_URL = "/ums/clientdata.php";
    private final String CLIENTDATA_URL = "/ums/postClientData";

    public ClientdataManager(Context context) {
        this.context = context;
        DeviceInfo.init(context);
        AppInfo.init(context);
    }

    JSONObject prepareClientdataJSON() throws JSONException {

        JSONObject jsonClientdata = new JSONObject();

        jsonClientdata.put("deviceid", DeviceInfo.getDeviceId());
        jsonClientdata.put("os_version", DeviceInfo.getOsVersion());
        jsonClientdata.put("platform", PLATFORM);
        jsonClientdata.put("language", DeviceInfo.getLanguage());
        jsonClientdata.put("appkey", AppInfo.getAppKey());
        jsonClientdata.put("resolution", DeviceInfo.getResolution());
        jsonClientdata.put("ismobiledevice", true);
        jsonClientdata.put("phonetype", DeviceInfo.getPhoneType());
        jsonClientdata.put("imsi", DeviceInfo.getIMSI());
        jsonClientdata.put("mccmnc", DeviceInfo.getMCCMNC());
        jsonClientdata.put("network", DeviceInfo.getNetworkTypeWIFI2G3G());
        jsonClientdata.put("time", DeviceInfo.getDeviceTime());
        jsonClientdata.put("version", AppInfo.getAppVersion());
        jsonClientdata.put("userid", CommonUtil.getUserIdentifier(context));
        jsonClientdata.put("modulename", DeviceInfo.getDeviceProduct());
        jsonClientdata.put("devicename", DeviceInfo.getDeviceName());
        jsonClientdata.put("wifimac", DeviceInfo.getWifiMac());
        jsonClientdata.put("havebt", DeviceInfo.getBluetoothAvailable());
        jsonClientdata.put("havewifi", DeviceInfo.getWiFiAvailable());
        jsonClientdata.put("havegps", DeviceInfo.getGPSAvailable());
        jsonClientdata.put("havegravity", DeviceInfo.getGravityAvailable());
        jsonClientdata.put("imei", DeviceInfo.getDeviceIMEI());
        jsonClientdata.put("salt", CommonUtil.getSALT(context));

        if (UmsConstants.mProvideGPSData) {
            jsonClientdata.put("latitude", DeviceInfo.getLatitude());
            jsonClientdata.put("longitude", DeviceInfo.getLongitude());
        }

        return jsonClientdata;
    }

    public void postClientData() {
        // Message for Push Service
        // Intent intent = new Intent();
        // intent.setAction("cobub.razor.message");
        // intent.putExtra("deviceid", DeviceInfo.getDeviceId());
        // context.sendBroadcast(intent);

        JSONObject clientData;
        try {
            clientData = prepareClientdataJSON();
        } catch (Exception e) {
            CobubLog.e(tag, e);
            return;
        }

        if (CommonUtil.getReportPolicyMode(context) == SendPolicy.REALTIME
                & CommonUtil.isNetworkAvailable(context)) {
            String result = NetworkUtil.Post(UmsConstants.urlPrefix + CLIENTDATA_URL
                    , clientData.toString());
            MyMessage message = NetworkUtil.parse(result);
            if (message == null) {
                CommonUtil.saveInfoToFile("clientData", clientData, context);
                return;
            }

            if (message.getFlag() < 0) {
                CobubLog.e(tag, "Error Code=" + message.getFlag() + ",Message=" + message.getMsg());
                if (message.getFlag() == -4)
                    CommonUtil.saveInfoToFile("clientData", clientData, context);
            }
        } else {
            CommonUtil.saveInfoToFile("clientData", clientData, context);
        }

    }
}
