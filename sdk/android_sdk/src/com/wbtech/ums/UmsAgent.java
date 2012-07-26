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
package com.wbtech.ums;

import java.io.File;
import java.io.FileInputStream;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.text.ParseException;
import java.util.Iterator;
import java.util.Locale;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.bluetooth.BluetoothAdapter;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.location.LocationManager;
import android.net.wifi.WifiManager;
import android.os.Build;
import android.os.Environment;
import android.os.Handler;
import android.os.HandlerThread;
import android.telephony.TelephonyManager;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.WindowManager;
import android.widget.Toast;

import com.wbtech.common.CommonUtil;
import com.wbtech.common.GetInfoFromFile;
import com.wbtech.common.MyMessage;
import com.wbtech.common.SCell;
import com.wbtech.common.SaveInfo;
import com.wbtech.common.UmsConstants;
import com.wbtech.dao.NetworkUitlity;

public class UmsAgent {
	public static boolean mUseLocationService = true;
	public static String start_millis = null;// 开始的时间点 yyyy-MM-dd HH:mm:ss
	public static long start = 0;// 开始的时间点 毫秒表示
	public static String end_millis = null;// 结束的时间点 yyyy-MM-dd HH:mm:ss
	public static long end = 0;// 结束的时间点 毫秒表示
	public static String duration = null;// 运行时间
	public static String session_id = null;
	public static String activities = null;// 当前activity名称
	public static String appkey = "";
	public static String stacktrace = null;// 错误信息
	public static String time = null; // 错误发生时间
	public static String os_version = null;// android 版本
	public static String deviceID = null;// 设备型号
	
	public static String curVersion = null;// 程序版本
	public static String packagename = null;// 应用程序的包名
	public static String sdk_version = null;// Sdk 的版本号
	
	private static UmsAgent umsAgentEntity = new UmsAgent();
	public static boolean mUpdateOnlyWifi = true;
	private static int defaultReportMode = 0;//0  为下次启动发送模式，为默认模式   1 实时发送模式
	private static  Handler handler;
	private static boolean isPostFile=true;
	private static boolean isFirst=true;
	
	
	
	public static void setAutoLocation(boolean AutoLocation) {
		UmsAgent.mUseLocationService = AutoLocation;
	}
	 private    UmsAgent() {
		 	HandlerThread localHandlerThread = new HandlerThread("UmsAgent");
		    localHandlerThread.start();
		    this.handler = new Handler(localHandlerThread.getLooper());
	}
	public static UmsAgent getUmsAgent(){
		return umsAgentEntity;
	}
	public static void onError(final Context context) {
//		ReadErrorLog read = new ReadErrorLog(paramcContext);
//		new Thread(read).start();
		Thread.setDefaultUncaughtExceptionHandler(new Thread.UncaughtExceptionHandler() {
			
			@Override
			public void uncaughtException(Thread thread, Throwable ex) {
//				ex.getLocalizedMessage();
				StringWriter sw = new StringWriter();
				PrintWriter p = new PrintWriter(sw);
				ex.printStackTrace(p);
				String s = sw.toString();
				p.close();
				stacktrace=s;
				activities = CommonUtil.getActivityName(context);
				time = CommonUtil.getTime();
				appkey = CommonUtil.getAppKey(context);
				os_version = CommonUtil.getOsVersion(context);

				JSONObject errorInfo = new JSONObject();
				
				try {
					 Build bd = new Build();
					errorInfo.put("stacktrace", stacktrace);
					errorInfo.put("time", time);
					errorInfo.put("version", CommonUtil.getVersion(context));
					errorInfo.put("activity", activities);
					errorInfo.put("appkey", appkey);
					errorInfo.put("os_version", os_version);
					errorInfo.put("deviceid", bd.MANUFACTURER+bd.PRODUCT);
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				Log.d("xdz", errorInfo.toString());
				// 根据不同的发送方式处理
				if(1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)){
					if(!stacktrace.equals("")){
						MyMessage message=	NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.errorUrl, errorInfo.toString());
						Log.d("xdz", message.getMsg());
						if(!message.isFlag()){
							saveInfoToFile("errorInfo", errorInfo, context);
							if(UmsConstants.DebugMode){
								Log.e("error", message.getMsg());
							}
						}
					}
				}else{
					saveInfoToFile("errorInfo", errorInfo, context);
				}
			}
		});
	}

	public static void onError(Context context, String error) {

		stacktrace = error;
		activities = CommonUtil.getActivityName(context);
		time = CommonUtil.getTime();
		appkey = CommonUtil.getAppKey(context);
		os_version = CommonUtil.getOsVersion(context);
		deviceID = CommonUtil.getDeviceID(context);
		
		JSONObject errorInfo = new JSONObject();

		try {
			 Build bd = new Build();
			errorInfo.put("stacktrace", stacktrace);
			errorInfo.put("time", time);
			errorInfo.put("activity", activities);
			errorInfo.put("appkey", appkey);
			errorInfo.put("os_version", os_version);
			errorInfo.put("deviceid", bd.MANUFACTURER+bd.PRODUCT);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		

		// 根据不同的发送方式处理
		
		if( 1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)){
			
			MyMessage message=NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.errorUrl, errorInfo.toString());	
			if(!message.isFlag()){
				saveInfoToFile("errorInfo",errorInfo,context);
				if(UmsConstants.DebugMode){
					Log.e("error", message.getMsg());
				}
				
			}
		}else{	
			saveInfoToFile("errorInfo",errorInfo,context);
		}
	}
/**
 * 将信息按 类型保存到文件中
 * @param type  errorInfo/activityInfo/eventInfo/clinetDataInfo
 * @param info
 * @param context
 */
	private static void saveInfoToFile(String type, JSONObject info,Context context) {
		// TODO Auto-generated method stub
		JSONArray  newdata = new JSONArray();
		try {
			newdata.put(0, info);
			if(handler!=null){
				JSONObject jsonObject = new JSONObject();
				jsonObject.put(type, newdata);
//				System.out.println(jsonObject.toString());
				handler.post(new SaveInfo(context, jsonObject));
			}else{
				if(UmsConstants.DebugMode){
					Log.e(CommonUtil.getActivityName(context), "handler--null");
				}
				
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	/**
	 * 自定义事件
	 * 
	 * @param context
	 * @param event_id
	 */
	public static void onEvent(Context context, String event_id) {
		onEvent(context, event_id, 1);
	}

	public static void onEvent(Context context, String event_id, int acc) {
		onEvent(context, event_id, null, acc);
	}
	 public static void onEvent(Context context, String event_id, String label)
	  {
	    if ((label == null) || (label == ""))
	    {
	      if (UmsConstants.DebugMode)
	        Log.e("UMSAgent", "label is null or empty in onEvent(4p)");
	      return;
	    }
	    onEvent(context, event_id, label, 1);
	  }
	public static void onEvent(Context context, String event_id,
			String label, int acc) {
		try {
			String appkey=CommonUtil.getAppKey(context);
			  if (appkey==null||(appkey.length()==0)) {
				  if (UmsConstants.DebugMode)
			          Log.e("UMSAgent", "unexpected empty appkey in onEvent(4p)");
			        return;
			}
			  if (context==null) {
				  Log.e("UMSAgent", "unexpected null context in onEvent(4p)");
			        return;
			}
			  if ((event_id == null) || (event_id == ""))
		      {
		        if (UmsConstants.DebugMode)
		          Log.e("UMSAgent", "tag is null or empty in onEvent(4p)");
		        return;
		      }
		      if (acc <= 0)
		      {
		        if (UmsConstants.DebugMode)
		          Log.e("UMSAgent", "Illegal value of acc in onEvent(4p)");
		        return;
		      }
//新启动一个线程处理自定义事件
		      new EventThread(context, appkey, event_id, label, acc).start();
		} catch (Exception e) {
			 if (UmsConstants.DebugMode)
		      {
		        Log.e("UMSAgent", "Exception occurred in onEvent()");
		        e.printStackTrace();
		      }
		}
	}
	/**
	 * 保存所发生的event 时间信息
	 */
	public static void saveEvent(UmsAgent umsAgent, Context context, String appkey,String event_id, String label, int acc)
	{
		umsAgentEntity=umsAgent;
		umsAgentEntity.saveEvent(context, appkey, event_id, label, acc);
	}
	public  void saveEvent( Context context, String appkey,String event_id, String label, int acc)
	{
		String time=CommonUtil.getTime();
		JSONObject localJSONObject = new JSONObject();
	    try
	    {
	      
	      localJSONObject.put("time", time);
	      localJSONObject.put("version", CommonUtil.getVersion(context));
	      localJSONObject.put("event_identifier", event_id);
	      localJSONObject.put("appkey", appkey);
	      localJSONObject.put("activity", CommonUtil.getActivityName(context));
	      if (label != null)
	        localJSONObject.put("label", label);
	      localJSONObject.put("acc", acc);
	      
	    }
	    catch (JSONException localJSONException)
	    {
	      if (UmsConstants.DebugMode)
	      {
	        Log.i("UmsAgent", "json error in emitCustomLogReport");
	        localJSONException.printStackTrace();
	      }
	      return;
	    }
	    if (1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)) {
			try {
				String eventUrl=UmsConstants.preUrl+UmsConstants.eventUrl;
				Log.d("UMSAgent","call post method. " + eventUrl);
			    MyMessage info=	NetworkUitlity.post(eventUrl, localJSONObject.toString());
			 Log.d("xdz", info.getMsg().toString());
			 if(!info.isFlag()){
				 saveInfoToFile("eventInfo", localJSONObject, context);
				 Log.e("error", info.getMsg());
			 }
			Log.d("UMSAgent", "errorInfo"+info.getMsg());
			} catch (Exception e) {
				Log.d("UmsAgent", "fail to post eventContent");
			}
		}else {
			
			saveInfoToFile("eventInfo", localJSONObject, context);
		}
	}

	/**
	 * 
	 * @param context
	 */
	public static void onPause(Context context) {
		// 将数据上传
		
		end_millis = CommonUtil.getTime();// 结束的时间点
		end = Long.valueOf(System.currentTimeMillis());// 结束时间点毫秒表示
		duration = end - start + "";
		appkey = CommonUtil.getAppKey(context);
		// 数据上传 或 保存
		JSONObject info = new JSONObject();
		try {
			info.put("session_id", session_id);
			info.put("start_millis", start_millis);
			info.put("end_millis", end_millis);
			info.put("duration", duration);
			info.put("version", CommonUtil.getVersion(context));
			info.put("activities", activities);
			info.put("appkey", appkey);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
Log.d("xdz", info+"");

		// 根据数据上传方式 处理info 保存在本地或上传服务器
		// 根据不同的发送方式处理
		if(1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)){
			if(UmsConstants.DebugMode){
				Log.d("activityInfo", info.toString());
			}
			
			MyMessage message=	NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.activityUrl, info.toString());
			if(!message.isFlag()){
				saveInfoToFile("activityInfo", info, context);
				if(UmsConstants.DebugMode){
					Log.e("error", message.getMsg());
				}
				
			}
		}else{
			saveInfoToFile("activityInfo", info, context);
		}
		

	}

	/**
	 * 
	 * @param context
	 */
	public static void onResume(Context context) {
//		boolean isfirstonResume = true;
		if(!CommonUtil.isNetworkAvailable(context)){
			setDefaultReportPolicy(context, 0);
		}else{
			if(UmsAgent.isPostFile){
				Thread thread = new GetInfoFromFile(context);
				thread.run();
				UmsAgent.isPostFile=false;
			}
			
		}
		
		activities = CommonUtil.getActivityName(context); // 获取当前activity的名称
//		SharedPreferences session = paramContext.getSharedPreferences(
//				CommonUtil.getPackageName(paramContext) + "session", 0);
//		String last_get_session_time = session.getString("sessiontime", (System
//				.currentTimeMillis())+"");
		try {
			if(session_id==null){
				session_id =generateSeesion(context);
			}
			
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
//		long nowtime = Long.valueOf(System.currentTimeMillis());
//		if (nowtime - Long.parseLong(last_get_session_time) > UmsConstants.kContinueSessionMillis) {
//			//不是同一个session
//			try {
//				session_id=generateSeesion(paramContext);
//				
//			} catch (ParseException e) {
//				// TODO Auto-generated catch block
//				e.printStackTrace();
//			}
//
//		}
		start_millis = CommonUtil.getTime();// 开始的时间点
		start = Long.valueOf(System.currentTimeMillis());// 开始时间点毫秒表示
		

	}

	
	/**
	 * 自动更新
	 * @param context
	 */

	public static void update(Context context) {
		try {
			
		
		appkey = CommonUtil.getAppKey(context);
		
		} catch (Exception e) {
          String aString = end_millis.toString();	
          Toast.makeText(context, aString, 1).show();

		}
		curVersion=CommonUtil.getCurVersion(context);

		JSONObject updateObject = new JSONObject();

		try {
			updateObject.put("appkey", appkey);
			updateObject.put("version_code", curVersion);
		} catch (JSONException e) {
			e.printStackTrace();
		}
		if(CommonUtil.isNetworkAvailable(context)&&CommonUtil.isNetworkTypeWifi(context)){
			MyMessage message = NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.updataUrl, updateObject.toString());
			if(message.isFlag()){
				try {
					JSONObject object =new JSONObject(message.getMsg());
					String flag =object.getString("flag");
					if (Integer.parseInt(flag)>0) {	
						String fileurl=object.getString("fileurl");
						String msg =object.getString("msg");
						String forceupdate=object.getString("forceupdate");
						String description=object.getString("description");
						String time=object.getString("time");
						String version=object.getString("version");
					    UpdateManager manager = new UpdateManager(context, version, forceupdate, fileurl, description);
					    manager.showNoticeDialog(context);
					}

				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}else{
				if(UmsConstants.DebugMode){
					Log.e("error", message.getMsg());
				}
				
			}
		}
	}

	/**
	 * 获取服务器配置的键值对 并保存在Ums_agent_online_Setting_PACKAGENAME.xml文件中
	 * 
	 * @param context
	 */
	public static void updateOnlineConfig(Context context) {
		// 上传appkey获取 服务器的键值对 解析
		appkey = CommonUtil.getAppKey(context);
		JSONObject map = new JSONObject();
		try {
			map.put("appkey", appkey);
		} catch (JSONException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		String appkeyJSON = map.toString();
		SharedPreferences preferences = context.getSharedPreferences(
				"ums_agent_online_setting_"
						+ CommonUtil.getPackageName(context), 0);
		Editor editor = preferences.edit();
		
		if(CommonUtil.isNetworkAvailable(context)){
			// 请求地址url
			MyMessage message = NetworkUitlity
					.post(UmsConstants.preUrl+UmsConstants.onlineConfigUrl, appkeyJSON);
			try {Log.d("message", message.getMsg());
				if(message.isFlag()){
					JSONObject object = new JSONObject(message.getMsg());
				
					if(UmsConstants.DebugMode){
						Log.d("uploadJSON",object.toString() );
					}
					
					Iterator<String> iterator = object.keys();

					while (iterator.hasNext()) {
						String key = iterator.next();
						String value = object.getString(key);
						editor.putString(key, value);
						if(key.equals("autogetlocation")&&(!value.equals("1"))){
							setAutoLocation(false);
						}					
						
						if(key.equals("updateonlywifi")&&(!value.equals("1"))){
							setUpdateOnlyWifi(false);
						}
						if(key.equals("reportpolicy")&&(value.equals("1"))){
							setDefaultReportPolicy(context, 1);
						}
						if(key.equals("sessionmillis")){
							UmsConstants.kContinueSessionMillis=Integer.parseInt(value)*1000;
						}
					}
					editor.commit();
	
				}else{
					if(UmsConstants.DebugMode){
						Log.e("error", message.getMsg());
					}
					
				}
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}else{
			if(UmsConstants.DebugMode){
				Log.d("UMSAgent", " updateOnlineConfig network error");
			}
			
		}
		

	}

//	public static void updateOnlineConfig(Context paramContext,
//			String paramString) {
//
//	}

	public static void setSessionContinueMillis(long sessionContinueMillis) {
		UmsConstants.kContinueSessionMillis = sessionContinueMillis;
	}

	public static void setDebugMode(boolean isDebugMode) {
		UmsConstants.DebugMode = isDebugMode;
	}

//	public static boolean isDownloadingAPK() {
//		return true;
//	}



	/**
	 * 根据在线配置的键值对中的key取得value
	 * @param context
	 * @param onlineKey
	 * @return
	 */
	public static String getConfigParams(Context context,
			String onlineKey) {

		appkey = CommonUtil.getAppKey(context);
		JSONObject json = new JSONObject();
		try {
			json.put("appkey", appkey);
		} catch (JSONException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		String appkeyJSON = json.toString();
		if(CommonUtil.isNetworkAvailable(context)){
			MyMessage message = NetworkUitlity
				.post(UmsConstants.preUrl+UmsConstants.onlineConfigUrl, appkeyJSON);
			if(message.isFlag()){
				try {
					JSONObject object = new JSONObject(message.getMsg());
					return object.getString(onlineKey);

				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}else{
				if(UmsConstants.DebugMode){
					Log.e("error", "getConfigParams error");
				}
			}
		}else{
			if(UmsConstants.DebugMode){
				Log.e("NetworkError", "Network, not work");
			}
		}
		return "";
	}
/**
 * 是否只在wifi状态下更新
 * @param isUpdateonlyWifi
 */
	public static void setUpdateOnlyWifi(boolean isUpdateonlyWifi) {
		UmsAgent.mUpdateOnlyWifi = isUpdateonlyWifi;
		if(UmsConstants.DebugMode){
			Log.d("mUpdateOnlyWifi value", UmsAgent.mUpdateOnlyWifi+"");
		}
	}

	
	/**
	 * 设置数据发送模式
	 * @param context
	 * @param reportModel
	 */
	public static void setDefaultReportPolicy(Context context, int reportModel) {
		Log.d("reportType", reportModel+"");
		if ((reportModel == 0) || (reportModel == 1)) {
			
			UmsAgent.defaultReportMode = reportModel;
			String str = context.getPackageName();
			SharedPreferences localSharedPreferences = context
					.getSharedPreferences("ums_agent_online_setting_" + str, 0);
			synchronized (UmsConstants.saveOnlineConfigMutex) {
				localSharedPreferences.edit().putInt("ums_local_report_policy",
						reportModel).commit();
			}
		}
	}

	
	/**
	 * 
	 * 生成sessionID 格式 appkey+date 
	 * @param context
	 * @return sessionId
	 * @throws ParseException
	 */
	public static String generateSeesion(Context context)
			throws ParseException {
		String sessionId = "";
		String str = CommonUtil.getAppKey(context);
		if (str != null) {
			String localDate = CommonUtil.getTime();
			str = str + localDate;
			sessionId = MD5Utility.md5Appkey(str);
//			String ActivityName = CommonUtil.getActivityName(paramContext);
//			SharedPreferences ss = paramContext.getSharedPreferences(
//					CommonUtil.getPackageName(paramContext) + "session", 0);
//			Editor editor = ss.edit();
//			editor.putString("sessionid", sessionId);
//			editor.putString("sessiontime", System.currentTimeMillis()+"");
//			editor.commit();
			return sessionId;
		}
		return null;
	}
	/**
	 * 上传所有数据
	 * @param context
	 */
	public static void uploadLog(Context context){
		
		File file1 = new File(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
		if(file1.exists()){
			try {
				FileInputStream in = new FileInputStream(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
				StringBuffer sb = new StringBuffer();

				int i=0;
				byte[] s = new byte[1024*4];
				
				while((i=in.read(s))!=-1){
					
					sb.append(new String(s,0,i));
				}
				if(CommonUtil.isNetworkAvailable(context)){
					MyMessage message=	NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.uploadUrl, sb+"");
					if(message.isFlag()){
						File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
						file.delete();
					}else{
						if(UmsConstants.DebugMode){
							Log.e("uploadError","uploadLog Error");
						}
					}
				}else{
					if(UmsConstants.DebugMode){
						Log.e("NetworkError", "Network, not work");
					}
				}
				
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
	/**
	 * 上传客户端设备信息
	 * @param context
	 */
	public  static void postClientData(Context context){
		if(isFirst){
			 TelephonyManager tm = (TelephonyManager) (context.getSystemService(Context.TELEPHONY_SERVICE)); 
			 WifiManager wifiManager =(WifiManager)context.getSystemService(Context.WIFI_SERVICE);
			 WindowManager manager = (WindowManager) context.getSystemService(Context.WINDOW_SERVICE);
			 DisplayMetrics displaysMetrics = new DisplayMetrics();
			 manager.getDefaultDisplay().getMetrics(displaysMetrics);
		     LocationManager locationManager = (LocationManager)context.getSystemService(Context.LOCATION_SERVICE);
			 BluetoothAdapter adapter = BluetoothAdapter.getDefaultAdapter();
			 JSONObject clientData = new JSONObject();
			 try {
				clientData.put("os_version",CommonUtil.getOsVersion(context));
				clientData.put("platform", "android");
				clientData.put("language", Locale.getDefault().getLanguage());
				clientData.put("deviceid", tm.getDeviceId());//
			    clientData.put("appkey", CommonUtil.getAppKey(context));
			    clientData.put("resolution", displaysMetrics.widthPixels+"*"+displaysMetrics.heightPixels);
			    clientData.put("ismobiledevice", true);
			    clientData.put("phonetype", tm.getPhoneType());//
			    clientData.put("imsi", tm.getSubscriberId());
			    clientData.put("network", CommonUtil.getNetworkType(context));//手机的联网方式  wifi/2G/3G
			    clientData.put("version", CommonUtil.getVersion(context));//获取versionName
			    
			    
			    SCell sCell = CommonUtil.getCellInfo(context);
			    
			    clientData.put("mccmnc", sCell!=null?""+sCell.MCCMNC:"");//
			    clientData.put("cellid",sCell!=null?sCell.CID+"":"");
			    clientData.put("lac", sCell!=null?sCell.LAC+"":"");
			    clientData.put("latitude", CommonUtil.getItude(sCell,UmsAgent.mUseLocationService).latitude);
			    clientData.put("longitude", CommonUtil.getItude(sCell,UmsAgent.mUseLocationService).longitude);
			    clientData.put("time", CommonUtil.getTime());
			    Build bd = new Build();
			    
			    clientData.put("modulename", bd.MODEL);
			    clientData.put("devicename", bd.MANUFACTURER+bd.PRODUCT);//将 制造商  和  型号  分开上传*****************
			    clientData.put("wifimac", wifiManager.getConnectionInfo().getMacAddress());
			    clientData.put("havebt", adapter==null ? false:true);
			    clientData.put("havewifi", CommonUtil.isWiFiActive(context));
			    clientData.put("havegps", locationManager==null? false:true);
			    clientData.put("havegravity", CommonUtil.isHaveGravity(context));//
			    System.out.println("clientData---------->"+clientData.toString());
			    
			    if(1==CommonUtil.getReportPolicyMode(context)&CommonUtil.isNetworkAvailable(context)){
			  MyMessage message= NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.clientDataUrl, clientData.toString());
			    if(!message.isFlag()){
			    	saveInfoToFile("clientData", clientData, context);
			    	Log.e("Errorinfo", message.getMsg());
			    }
			    }else{
			    	saveInfoToFile("clientData", clientData, context);
			    }
			    isFirst=false;
			} catch (JSONException e) {
				e.printStackTrace();
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		
		}
	}
	
	
}