/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */

package com.wbtech.ums;

import org.json.JSONArray;
import org.json.JSONObject;

import com.wbtech.ums.UmsConstants;

import android.content.Context;

class UploadHistoryLog extends Thread {
    public Context context;

    public UploadHistoryLog(Context context) {
        super();
        this.context = context;
    }

    @Override
    public void run() {
        String baseDir = context.getCacheDir().getAbsolutePath()
                + "/cobub.cache";
        String cachfileclientdata = baseDir + "clientData";
        String cachfileerror = baseDir + "errorInfo";
        String cachfileeventInfo = baseDir + "eventInfo";
        String cachfiletags = baseDir + "tags";

        postdata(cachfiletags, "tags", UmsConstants.TAG_URL);
        postdata(cachfileclientdata, "clientData", UmsConstants.CLIENTDATA_URL);
        postdata(cachfileerror, "errorInfo", UmsConstants.ERROR_URL);
        postdata(cachfileeventInfo, "eventInfo", UmsConstants.EVENT_URL);
    }

    private void postdata(String cachfile, String type, String url) {
        //首先判断是否能发送，如果不能发送就没必要读文件了
        if (!CommonUtil.isNetworkAvailable(context)) {
            return;
        }

        //可以发送，读文件
        JSONObject postdata = new JSONObject();
        JSONArray arr = new JSONArray();
        try {
            arr = CommonUtil.getJSONdata(cachfile, type);
            if (arr.length() == 0) {
                return;
            }
            postdata.put("data", arr);
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }
        if (postdata != null) {
            //增强判断，以免网络突然中断
            if (CommonUtil.isNetworkAvailable(context)) {
                MyMessage message = NetworkUtil.Post(UmsConstants.BASE_URL
                        + url, postdata.toString());

                if (!message.isSuccess()) {
                    CobubLog.e(UmsConstants.LOG_TAG, UploadHistoryLog.class," Message=" + message.getMsg());
                    CommonUtil.saveInfoToFile(type, arr, context);
                }
            } else {
                CommonUtil.saveInfoToFile(type, arr, context);
            }
        }
    }

}
