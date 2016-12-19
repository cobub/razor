package com.wbtech.ums;

import android.util.Log;

import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

class NetworkUtil {

	    /*SDK会运行在如下两种环境中:
		 * 1,CPOS:需要进行双向ssl校验；SDK_SSL=true;此时还要验证dn(如果不想验dn，设置SDK_HTTPS_DN为none)
		 * 2,一般移动设备，是用HTTPS正常发送即可，也能接受非标准证书的https服务端
		 * 3,测试：使用http
		 */
	public static MyMessage Post(String url, String data) {
		CobubLog.d(UmsConstants.LOG_TAG, NetworkUtil.class,"URL = " + url);
		CobubLog.d(UmsConstants.LOG_TAG, NetworkUtil.class, "LENGTH:" + data.length() + " *Data = " + data + "*");
		HttpURLConnection httpURLConnection;
		URL realUrl;
		MyMessage message = new MyMessage();
		try {
			realUrl = new URL(url);
			httpURLConnection = (HttpURLConnection) realUrl.openConnection();
			httpURLConnection.setConnectTimeout(5000);
			httpURLConnection.setReadTimeout(3000);
			httpURLConnection.setDoOutput(true);
			httpURLConnection.setDoInput(true);
			httpURLConnection.setRequestMethod("POST");
			httpURLConnection.setRequestProperty("connection", "Keep-Alive");
			httpURLConnection.setRequestProperty("Content-Type",
					"application/x-www-form-urlencoded");
			//发送数据包
			DataOutputStream dos = new DataOutputStream(httpURLConnection.getOutputStream());
			String s = "content=" + URLEncoder.encode(data, "UTF-8");
			dos.write(s.getBytes());
			dos.flush();
			dos.close();
			int status = httpURLConnection.getResponseCode();
			CobubLog.d(UmsConstants.LOG_TAG,NetworkUtil.class, "Status code="
					+ status);
			//接收数据包
			InputStream is = httpURLConnection.getInputStream();
			String result = inputStreamToString(is);
			CobubLog.d(UmsConstants.LOG_TAG,NetworkUtil.class, "returnString = " + result);
			//TODO:只要服務端有返回200ok并且返回的是json字符串即可认为发送成功；因为如果发送的数据不完整服务端会返回flag<0；
			// 这部分数据按照flag来判断会导致错误数据始终保存在本地
			switch (status) {
				case 200:
					message.setSuccess(isJson(result));
					message.setMsg(result);
					break;
				default:
					Log.e("error", status + result);
					message.setSuccess(false);
					message.setMsg(result);
					break;
			}
		} catch (IOException e) {
			message.setSuccess(false);
			message.setMsg(e.toString());
		}
		return message;
	}

	private static String inputStreamToString(final InputStream stream) throws IOException {
		ByteArrayOutputStream os = new ByteArrayOutputStream();
		byte[] buffer = new byte[1024];
		int len;
		while ((len = stream.read(buffer)) != -1) {
			os.write(buffer, 0, len);
		}
		stream.close();
		String state = os.toString();
		os.close();
		return state;
	}

	private static boolean isJson(String strForValidating) {
		try {
			new JSONObject(strForValidating);
			return true;

		} catch (Exception e) {
			return false;
		}
	}

}
