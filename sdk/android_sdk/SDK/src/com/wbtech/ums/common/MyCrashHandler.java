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

import java.io.PrintWriter;
import java.io.StringWriter;
import java.io.Writer;
import java.lang.Thread.UncaughtExceptionHandler;

import org.json.JSONException;
import org.json.JSONObject;

import com.wbtech.ums.UmsAgent;
import com.wbtech.ums.objects.MyMessage;

import android.content.Context;
import android.os.Build;
import android.os.Looper;
import android.util.Log;

public class MyCrashHandler implements UncaughtExceptionHandler {
	private static MyCrashHandler myCrashHandler;
	private Context context;
	private Object stacktrace;
	private String activities;
	private String time;
	private String appkey;
	private String os_version;

	private MyCrashHandler() {

	}

	public static synchronized MyCrashHandler getInstance() {
		if (myCrashHandler != null) {
			return myCrashHandler;
		} else {
			myCrashHandler = new MyCrashHandler();
			return myCrashHandler;
		}
	}

	public void init(Context context) {
		this.context = context;
		// this.service = service;
	}

	public void uncaughtException(Thread thread, final Throwable arg1) {
		Log.d("ums-threadname", thread.getName());
		new Thread() {
			@Override
			public void run() {
				super.run();
				
				Looper.prepare();
				String errorinfo = getErrorInfo(arg1);

				String[] ss = errorinfo.split("\n\t");
				String headstring = ss[0] + "\n\t" + ss[1] + "\n\t" + ss[2]
						+ "\n\t";
				String newErrorInfoString = headstring + errorinfo;

				stacktrace = newErrorInfoString;
				activities = CommonUtil.getActivityName(context);
				time = CommonUtil.getTime();
				appkey = CommonUtil.getAppKey(context);
				os_version = CommonUtil.getOsVersion(context);
				JSONObject errorInfo = getErrorInfoJSONString(context);
				CommonUtil.printLog("UmsAgent", errorInfo.toString());

				if (1 == CommonUtil.getReportPolicyMode(context)
						&& CommonUtil.isNetworkAvailable(context)) {
					if (!stacktrace.equals("")) {
						MyMessage message = NetworkUitlity.post(
								UmsConstants.preUrl + UmsConstants.errorUrl,
								errorInfo.toString());
						CommonUtil.printLog("UmsAgent", message.getMsg());
						if (!message.isFlag()) {
							UmsAgent.saveInfoToFile("errorInfo", errorInfo,
									context);
							CommonUtil.printLog("error", message.getMsg());
						}
					}
				} else {
					UmsAgent.saveInfoToFile("errorInfo", errorInfo, context);
				}
				android.os.Process.killProcess(android.os.Process.myPid());
				Looper.loop();
			}

		}.start();
	}

	private JSONObject getErrorInfoJSONString(Context context) {
		JSONObject errorInfo = new JSONObject();
		try {
			errorInfo.put("stacktrace", stacktrace);
			errorInfo.put("time", time);
			errorInfo.put("version", CommonUtil.getVersion(context));
			errorInfo.put("activity", activities);
			errorInfo.put("appkey", appkey);
			errorInfo.put("os_version", os_version);
			errorInfo.put("deviceid", CommonUtil.getDeviceName());
		} catch (JSONException e) {
			e.printStackTrace();
		}
		return errorInfo;
	}

	private String getErrorInfo(Throwable arg1) {
		Writer writer = new StringWriter();
		PrintWriter pw = new PrintWriter(writer);
		arg1.printStackTrace(pw);
		pw.close();
		String error = writer.toString();
		return error;
	}

}
