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

public class ConfigManager {
    private Context context;
    private final String tag = "ConfigManager";
    private final String CONFIG_URL = "/ums/onlineconfig.php";

    public ConfigManager(Context context) {
        this.context = context;
    }

    JSONObject prepareConfigJSON() throws JSONException {
        JSONObject jsonConfig = new JSONObject();

        jsonConfig.put("appkey", AppInfo.getAppKey());

        return jsonConfig;
    }

    public void updateOnlineConfig() {
        JSONObject jsonConfig;
        try {
            jsonConfig = prepareConfigJSON();
        } catch (Exception e) {
            return;
        }

        if (CommonUtil.isNetworkAvailable(context)) {
            String result = NetworkUtil
                    .Post(UmsConstants.urlPrefix + CONFIG_URL, jsonConfig.toString());
            MyMessage message = NetworkUtil.parse(result);
            if (message == null)
                return;
            try {

                if (message.getFlag() > 0) {
                    JSONObject object = new JSONObject(result);
                    int isLocation = object.getInt("autogetlocation");
                    if (isLocation == 0)
                        UmsAgent.setAutoLocation(false);
                    else
                        UmsAgent.setAutoLocation(true);

                    int isOnlyWifi = object.getInt("updateonlywifi");
                    if (isOnlyWifi == 0)
                        UmsAgent.setUpdateOnlyWifi(false);
                    else
                        UmsAgent.setUpdateOnlyWifi(true);

                    int reportPolicy = object.getInt("reportpolicy");
                    if (reportPolicy == 0)
                        UmsAgent.setDefaultReportPolicy(context, SendPolicy.BATCH);
                    if (reportPolicy == 1)
                        UmsAgent.setDefaultReportPolicy(context, SendPolicy.REALTIME);

                    int session = object.getInt("sessionmillis");
                    UmsAgent.setSessionContinueMillis(session * 1000);
                }
            } catch (JSONException e) {
                CobubLog.e(tag, e);
            }
        }
    }
}
