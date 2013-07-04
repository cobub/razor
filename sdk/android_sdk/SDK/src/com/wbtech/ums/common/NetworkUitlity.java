/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */
package com.wbtech.ums.common;

import java.net.URLDecoder;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import android.util.Log;

import com.wbtech.ums.objects.MyMessage;
public class NetworkUitlity {
	public static long paramleng = 256L;
	public static String DEFAULT_CHARSET=" HTTP.UTF_8";
	public static MyMessage post(String url, String data) {
		// TODO Auto-generated method stub
		CommonUtil.printLog("ums",url);
		String returnContent = "";
		MyMessage message=new MyMessage();
		HttpClient httpclient = new DefaultHttpClient();
		HttpPost httppost = new HttpPost(url);
		try {
			StringEntity se = new StringEntity("content="+data, HTTP.UTF_8);
			CommonUtil.printLog("postdata", "content="+data);
			se.setContentType("application/x-www-form-urlencoded");
			httppost.setEntity(se);
			HttpResponse response = httpclient.execute(httppost);
			int status = response.getStatusLine().getStatusCode();
			CommonUtil.printLog("ums",status+"");
			String returnXML = EntityUtils.toString(response.getEntity());
			returnContent = URLDecoder.decode(returnXML);
			switch (status) {
			case 200:
				message.setFlag(true);
				message.setMsg(returnContent);
				break;
				
			default:
				Log.e("error", status+returnContent);
				message.setFlag(false);
				message.setMsg(returnContent);
				break;
			}
		} catch (Exception e) {	
			JSONObject jsonObject = new JSONObject();
			
				try {
					jsonObject.put("err", e.toString());
					returnContent = jsonObject.toString();
					message.setFlag(false);
					message.setMsg(returnContent);
				} catch (JSONException e1) {
					e1.printStackTrace();
				}
				
			
		}
		CommonUtil.printLog("UMSAGENT", message.getMsg());
		return message;
	}
	
	public static String Post(String url, String data) {

        CommonUtil.printLog("ums",url);


        HttpClient httpclient = new DefaultHttpClient();
        HttpPost httppost = new HttpPost(url);
        try {
            StringEntity se = new StringEntity("content="+data, HTTP.UTF_8);
            CommonUtil.printLog("postdata", "content="+data);
            se.setContentType("application/x-www-form-urlencoded");
            httppost.setEntity(se);
            HttpResponse response = httpclient.execute(httppost);
            int status = response.getStatusLine().getStatusCode();
            CommonUtil.printLog("ums",status+"");
            String returnXML = EntityUtils.toString(response.getEntity());
            Log.d("returnString", URLDecoder.decode(returnXML));
            return URLDecoder.decode(returnXML);
            
        } catch (Exception e) { 
            CommonUtil.printLog("ums",e.toString());
        }
        return null;
    }
	
	

	
}
