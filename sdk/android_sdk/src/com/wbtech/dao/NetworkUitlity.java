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
package com.wbtech.dao;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URLDecoder;
import java.util.zip.GZIPInputStream;
import java.util.zip.GZIPOutputStream;

import org.apache.http.Header;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.params.HttpClientParams;
import org.apache.http.entity.AbstractHttpEntity;
import org.apache.http.entity.ByteArrayEntity;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import android.util.Log;

import com.wbtech.common.MyMessage;
/**上传数据
 * */
public class NetworkUitlity {
	public static long paramleng = 256L;
	public static String DEFAULT_CHARSET=" HTTP.UTF_8";
	public static MyMessage post(String url, String data) {
		// TODO Auto-generated method stub
		String returnContent = "";
		MyMessage message=new MyMessage();
		HttpClient httpclient = new DefaultHttpClient();
		HttpPost httppost = new HttpPost(url);
		try {
			StringEntity se = new StringEntity("content="+data, HTTP.UTF_8);
			Log.d("postdata", "content="+data);
			se.setContentType("application/x-www-form-urlencoded");
			httppost.setEntity(se);
			HttpResponse response = httpclient.execute(httppost);
			int status = response.getStatusLine().getStatusCode();
			
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
			
			if (e.getMessage().equalsIgnoreCase("no route to host")) {
				try {
					jsonObject.put("err", "服务器暂时不能访问,稍后再试");
					returnContent = jsonObject.toString();
					message.setFlag(false);
					message.setMsg(returnContent);
				} catch (JSONException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
				
			}
			
			else if (e.getMessage().equalsIgnoreCase("network unreachable")||e.getMessage().equalsIgnoreCase("www.cobub.com")) {
				try {
					jsonObject.put("err", "无网络连接");
					returnContent = jsonObject.toString();
		        	message.setFlag(false);
					message.setMsg(returnContent);
				} catch (JSONException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
				
			}
			
	        else {
	        	try {
					jsonObject.put("err", "未知错误");
					returnContent =jsonObject.toString() ;
					message.setFlag(false);
					message.setMsg(returnContent);
				} catch (JSONException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
				
			}
			
			
		}
		return message;
	}
	/**
	 * 数据压缩
	 * @param str
	 * @return
	 */
	public static byte[] compressToByte(String str)
	{  
		         if (str == null || str.length() == 0) {  
		             return null;
		         }  
		         ByteArrayOutputStream out = new ByteArrayOutputStream();  
		         GZIPOutputStream gzip;  
		         try {  
		             gzip = new GZIPOutputStream(out);  
		             gzip.write(str.getBytes("utf-8"));  
		             gzip.close();  
		         } catch (IOException e) {  
		             e.printStackTrace();  
		         }  
		        return out.toByteArray();  
	    }  
	
	
	
	
	// byt[]数据转换为ByteArrayEntity 并采用gzip压缩
	public static AbstractHttpEntity initEntity(byte[] paramArrayOfByte) {
		ByteArrayEntity localByteArrayEntity = null;
		ByteArrayOutputStream localByteArrayOutputStream = new ByteArrayOutputStream();
		GZIPOutputStream localGZIPOutputStream;
		if (paramArrayOfByte.length < paramleng) {
			localByteArrayEntity = new ByteArrayEntity(paramArrayOfByte);
		} else {

			try {
				localGZIPOutputStream = new GZIPOutputStream(
						localByteArrayOutputStream);
				localGZIPOutputStream.write(paramArrayOfByte);
				localGZIPOutputStream.close();
				localByteArrayEntity = new ByteArrayEntity(
						localByteArrayOutputStream.toByteArray());
				localByteArrayEntity.setContentEncoding("gzip");
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return localByteArrayEntity;
	}

	// HttpEntity转换为object类型的
	public static Object returnInputStream(HttpEntity paramHttpEntity)
			throws IllegalStateException, IOException {
		Object localObject = paramHttpEntity.getContent();
		if (localObject == null)
			return localObject;
		Header localHeader = paramHttpEntity.getContentEncoding();
		if (localHeader == null)
			return localObject;
		String str = localHeader.getValue();
		if (str == null)
			return localObject;
		if (str.contains("gzip"))
			localObject = new GZIPInputStream((InputStream) localObject);
		return localObject;
	}
	
	  public static String convertStreamToString(InputStream is) {   
		  /*   To convert the InputStream to String we use the BufferedReader.readLine()  
	         */  
	        BufferedReader reader = new BufferedReader(new InputStreamReader(is));   
	        StringBuilder sb = new StringBuilder();   
	        String line = null;   
	        try {   
	            while ((line = reader.readLine()) != null) {   
	                sb.append(line + "\n");   
	            }   
	        } catch (IOException e) {   
	            e.printStackTrace();   
	        } finally {   
	            try {   
	                is.close();   
	            } catch (IOException e) {   
	                e.printStackTrace();   
	            }   
	        }   
	        return sb.toString();   
	    }   
}
