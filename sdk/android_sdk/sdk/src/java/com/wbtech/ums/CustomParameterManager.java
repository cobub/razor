package com.wbtech.ums;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.wbtech.ums.UmsConstants;

import android.content.Context;

public class CustomParameterManager {
    Context context;
  
    public CustomParameterManager(Context context) {
        super();
        this.context = context;
    }

    public void getParameters() {
        JSONObject obj = new JSONObject();
        try {
            obj.put("appKey", AppInfo.getAppKey(context));
            if (CommonUtil.isNetworkAvailable(context)
                    && CommonUtil.isNetworkTypeWifi(context)) {
                MyMessage message = NetworkUtil.Post(UmsConstants.BASE_URL
                        + UmsConstants.PARAMETER_URL, obj.toString());
                try {
                    CobubLog.d(UmsConstants.LOG_TAG,CustomParameterManager.class, message.getMsg());
                    JSONObject result_obj = new JSONObject(message.getMsg())
                            .getJSONObject("reply");
                    if (result_obj.has("parameters")) {
                        JSONArray arr = result_obj.getJSONArray("parameters");
                        for (int i = 0; i < arr.length(); i++) {
                            JSONObject item = arr.getJSONObject(i);
                            SharedPrefUtil spu = new SharedPrefUtil(context);
                            spu.setValue(item.getString("key"),
                                    item.getString("value"));
                        }
                    }
                } catch (JSONException e1) {
                    CobubLog.e(UmsConstants.LOG_TAG, e1);
                }
            }

        } catch (JSONException e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }
    }

}
