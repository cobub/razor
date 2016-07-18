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

class UploadActivityLog extends Thread {
    public Context context;

    public UploadActivityLog(Context context) {
        super();
        this.context = context;
    }

    @Override
    public void run() {
        String cachfileactivity = context.getCacheDir().getAbsolutePath()
                + "/cobub.cache" + "activityInfo";
        postactivityinfo(cachfileactivity);
    }

    private void postactivityinfo(String cachfileactivity) {
        // 首先判断是否能发送，如果不能发送就没必要读文件了
        if (!CommonUtil.isNetworkAvailable(context)) {
            return;
        }
        // 可以发送，读文件
        String data = CommonUtil.readDataFromFile(cachfileactivity);

        try {
            String[] dataarr = data.split(UmsConstants.fileSep);
            JSONArray jsonarr = new JSONArray();
            JSONObject postdata = new JSONObject();
            for (int i = 1; i < dataarr.length; i++) {
                try {
                    JSONObject obj = new JSONObject(dataarr[i])
                            .getJSONObject("activityInfo");
                    int jsonarrlength = jsonarr.length();
                    jsonarr.put(jsonarrlength, obj);
                } catch (Exception e) {
                    CobubLog.e(UmsConstants.LOG_TAG, UploadActivityLog.class, "Message=" + e.getMessage());
                    continue;
                }
            }
            if (jsonarr.length() == 0) {
                return;
            }
            postdata.put("data", jsonarr);

            // 发送之前再度判断
            if (CommonUtil.isNetworkAvailable(context)) {
                CobubLog.i(UmsConstants.LOG_TAG, UploadActivityLog.class, "post activity info");
                MyMessage message = NetworkUtil.Post(UmsConstants.BASE_URL
                        + UmsConstants.USINGLOG_URL, postdata.toString());

                if (!message.isSuccess()) {
                    CobubLog.e(UmsConstants.LOG_TAG, UploadActivityLog.class, message.getMsg());
                    for (int i = 0; i < jsonarr.length(); i++) {
                        CommonUtil.saveInfoToFile("activityInfo",
                                jsonarr.getJSONObject(i), context);
                    }
                }

            } else {
                for (int i = 0; i < jsonarr.length(); i++) {
                    CommonUtil.saveInfoToFile("activityInfo",
                            jsonarr.getJSONObject(i), context);
                }

            }

        } catch (JSONException e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }

    }
}
