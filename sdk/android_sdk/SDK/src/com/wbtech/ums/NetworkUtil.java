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

import java.net.URLDecoder;
import java.net.URLEncoder;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

class NetworkUtil {
    private static final String TAG = "NetworkUtil";

    public static String Post(String url, String data) {

        CobubLog.d(TAG, "URL = " + url);
        CobubLog.d(TAG, "Data = " + data);

        try {
            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost(url);
            StringEntity se = new StringEntity("content=" +URLEncoder.encode( data), HTTP.UTF_8);
            se.setContentType("application/x-www-form-urlencoded");
            httppost.setEntity(se);

            HttpResponse response = httpclient.execute(httppost);

            CobubLog.d(TAG, "Status code=" + response.getStatusLine().getStatusCode());

            String returnXML = EntityUtils.toString(response.getEntity());

            CobubLog.d(TAG, "returnString = " + URLDecoder.decode(returnXML));

            return URLDecoder.decode(returnXML);

        } catch (Exception e) {
            CobubLog.e(TAG, e);
            return null;
        }
    }

    public static MyMessage parse(String str) {
        try {
            if (str == null)
                return null;
            JSONObject jsonObject = new JSONObject(str);
            MyMessage message = new MyMessage();
            message.setFlag(jsonObject.getInt("flag"));
            message.setMsg(jsonObject.getString("msg"));
            return message;
        } catch (JSONException e) {
            CobubLog.e(TAG, e);
            return null;
        } catch (Exception e1) {

            CobubLog.e(TAG, e1);
            return null;
        }
    }

}
