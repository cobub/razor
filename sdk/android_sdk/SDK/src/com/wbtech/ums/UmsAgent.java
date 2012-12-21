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
import android.view.WindowManager;
import android.widget.Toast;

import com.wbtech.ums.common.CommonUtil;
import com.wbtech.ums.common.EventThread;
import com.wbtech.ums.common.MD5Utility;
import com.wbtech.ums.common.MyCrashHandler;
import com.wbtech.ums.common.NetworkUitlity;
import com.wbtech.ums.common.UmsConstants;
import com.wbtech.ums.dao.GetInfoFromFile;
import com.wbtech.ums.dao.SaveInfo;
import com.wbtech.ums.objects.MyMessage;
import com.wbtech.ums.objects.SCell;

public class UmsAgent {
	private static boolean mUseLocationService = true;
	private static String start_millis = null;// The start time point
	private static long start = 0;
	private static String end_millis = null;// The end time point 
	private static long end = 0;// 
	private static String duration = null;// run time
	private static String session_id = null;
	private static String activities = null;// currnet  activity's name
	private static String appkey = "";
	private static String stacktrace = null;// error info
	private static String time = null; // error time
	private static String os_version = null;
	private static String deviceID = null;
	
	private static String curVersion = null;// app version
	private static String packagename = null;//app packagename
	private static String sdk_version = null;// Sdk version
	
	private static UmsAgent umsAgentEntity = new UmsAgent();
	private static boolean mUpdateOnlyWifi = true;
	private static int defaultReportMode = 0;//0  send at next time，defaultmode  1 send at now
	private static  Handler handler;
	private static boolean isPostFile=true;
	private static boolean isFirst=true;
	
	private static long tcp_sndofbegin=0;
	private static long tcp_rcvofbegin=0;
	private static long tcp_snd=0;
	private static long tcp_rcv=0;
	
	
	
	/**
	 * set base URL like http://81.30.76.26/pluggy/ums/index.php?
	 * @param url
	 */
	public static void setBaseURL(String url){
		UmsConstants.preUrl = url;
	}
	
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
		 MyCrashHandler handler = MyCrashHandler.getInstance();
	        handler.init(context.getApplicationContext());
	        Thread.setDefaultUncaughtExceptionHandler(handler);
	}
	public static void onError(final Context context,final String error){
		Runnable postErrorInfoRunnable = new Runnable() {
			
			@Override
			public void run() {
				postErrorInfo(context,error);
			}
		};
		handler.post(postErrorInfoRunnable);
	}
	private static  void postErrorInfo(Context context, String error) {

		stacktrace = error;
		activities = CommonUtil.getActivityName(context);
		time = CommonUtil.getTime();
		appkey = CommonUtil.getAppKey(context);
		os_version = CommonUtil.getOsVersion(context);
		deviceID = CommonUtil.getDeviceID(context);
		
		JSONObject errorInfo = getErrorInfoJSONObj();
		
		if( 1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)){
			
			MyMessage message=NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.errorUrl, errorInfo.toString());	
			if(!message.isFlag()){
				saveInfoToFile("errorInfo",errorInfo,context);
					CommonUtil.printLog("error", message.getMsg());
				
			}
		}else{	
			saveInfoToFile("errorInfo",errorInfo,context);
		}
	}
private static JSONObject getErrorInfoJSONObj() {
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
		e.printStackTrace();
	}
		return errorInfo;
	}
/**
 * Information is saved to a file by type
 * @param type  errorInfo/activityInfo/eventInfo/clinetDataInfo
 * @param info
 * @param context
 */
	public static void saveInfoToFile(String type, JSONObject info,Context context) {
		JSONArray  newdata = new JSONArray();
		try {
			newdata.put(0, info);
			if(handler!=null){
				JSONObject jsonObject = new JSONObject();
				jsonObject.put(type, newdata);
				handler.post(new SaveInfo(context, jsonObject));
			}else{
					CommonUtil.printLog(CommonUtil.getActivityName(context), "handler--null");
				
			}
		} catch (JSONException e) {
			e.printStackTrace();
		}
	}

	public static void onEvent(final Context context,final String event_id){
		Runnable postEventInfo=new Runnable() {
			public void run() {
				postEventInfo(context, event_id);
			}
		}; 
		handler.post(postEventInfo);
	}
	public static void onEvent(final Context context, final String event_id, final String label ){
		Runnable postEventRunnable = new Runnable() {
			
			@Override
			public void run() {
				postEventInfo(context, event_id, label);
			}
		};
		handler.post(postEventRunnable);
	}
	
	/**
	 * 
	 * @param context									
	 * @param event_id
	 */
	private static void postEventInfo(Context context, String event_id) {
		onEvent(context, event_id, 1);
	}

	private static void onEvent(Context context, String event_id, int acc) {
		postEventInfo(context, event_id, null, acc);
	}
	 private static void postEventInfo(Context context, String event_id, String label)
	  {
	    if ((label == null) || (label == ""))
	    {
	      CommonUtil.printLog("UMSAgent", "label is null or empty in onEvent(4p)");
	      return;
	    }
	    postEventInfo(context, event_id, label, 1);
	  }
	private  static void postEventInfo(Context context, String event_id,
			String label, int acc) {
		try {
			String appkey=CommonUtil.getAppKey(context);
		      if (acc <= 0)
		      {
		    	  CommonUtil.printLog("UMSAgent", "Illegal value of acc in postEventInfo");
		        return;
		      }
		      new EventThread(context, appkey, event_id, label, acc).start();
		} catch (Exception e) {
			CommonUtil.printLog("UMSAgent", "Exception occurred in postEventInfo()");
		        e.printStackTrace();
		}
	}
	/**
	 * Save the event time
	 */
	@SuppressWarnings("unused")
	public static void saveEvent(UmsAgent umsAgent, Context context, String appkey,String event_id, String label, int acc)
	{
		umsAgentEntity=umsAgent;
		umsAgentEntity.saveEvent(context, appkey, event_id, label, acc);
	}
	private  void saveEvent( Context context, String appkey,String event_id, String label, int acc)
	{
		
		JSONObject localJSONObject = getEventJOSNobj(context,label, event_id, acc);
		
	    if (1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)) {
			try {
				String eventUrl=UmsConstants.preUrl+UmsConstants.eventUrl;
				CommonUtil.printLog("UMSAgent","call post method. " + eventUrl);
			    MyMessage info=	NetworkUitlity.post(eventUrl, localJSONObject.toString());
			    CommonUtil.printLog("UmsAgent", info.getMsg().toString());
			 if(!info.isFlag()){
				 saveInfoToFile("eventInfo", localJSONObject, context);
				 CommonUtil.printLog("error", info.getMsg());
			 }
			 CommonUtil.printLog("UmsAgent", "errorInfo"+info.getMsg());
			} catch (Exception e) {
				CommonUtil.printLog("UmsAgent", "fail to post eventContent");
			}
		}else {
			
			saveInfoToFile("eventInfo", localJSONObject, context);
		}
	}

	private JSONObject getEventJOSNobj(Context context,String label ,String event_id,int acc) {
		JSONObject localJSONObject =  new JSONObject();
		String time=CommonUtil.getTime();
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
	    	CommonUtil.printLog("UmsAgent", "json error in emitCustomLogReport");
	        localJSONException.printStackTrace();
	    }
		return localJSONObject;
	}
	public static void onPause(final Context context){
		Runnable postOnPauseinfoRunnable = new Runnable() {
			
			@Override
			public void run() {
				postOnPauseInfo(context);
			}
		};
		handler.post(postOnPauseinfoRunnable);
	}
	
	/**
	 * 
	 * @param context
	 */
	private static void postOnPauseInfo(Context context) {
		
		end_millis = CommonUtil.getTime();
		end = Long.valueOf(System.currentTimeMillis());
		duration = end - start + "";
		appkey = CommonUtil.getAppKey(context);
		JSONObject info =  getJSONObject(context); 
		
		
		CommonUtil.printLog("UmsAgent", info+"");

		if(1==CommonUtil.getReportPolicyMode(context)&&CommonUtil.isNetworkAvailable(context)){
				CommonUtil.printLog("activityInfo", info.toString());
			
			MyMessage message=	NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.activityUrl, info.toString());
			if(!message.isFlag()){
				saveInfoToFile("activityInfo", info, context);
				CommonUtil.printLog("error", message.getMsg());
				
			}
		}else{
			saveInfoToFile("activityInfo", info, context);
		}
		

	}
	private static JSONObject getJSONObject(Context context) {
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
			e.printStackTrace();
		}
		return info;
	}
	public static void onResume(final Context context) {
		Runnable postOnResumeinfoRunnable = new Runnable() {
			
			@Override
			public void run() {
				postonResume(context);
			}
		};
		handler.post(postOnResumeinfoRunnable);
	}
	/**
	 * 
	 * @param context
	 */
	private static void postonResume(Context context) {
		if(!CommonUtil.isNetworkAvailable(context)){
			setDefaultReportPolicy(context, 0);
		}else{
			if(UmsAgent.isPostFile){
				Thread thread = new GetInfoFromFile(context);
				thread.run();
				UmsAgent.isPostFile=false;
			}
			
		}
		
		activities = CommonUtil.getActivityName(context); 
		try {
			if(session_id==null){
				session_id =generateSeesion(context);
			}
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		start_millis = CommonUtil.getTime();
		start = Long.valueOf(System.currentTimeMillis());

	}
	
	/**
	 * Automatic Updates
	 * @param context
	 */
	public static void update(final Context context){
		Runnable isupdateRunnable = new Runnable() {
			
			@Override
			public void run() {
				isupdate(context);
			}
		};
		handler.post(isupdateRunnable);
	}
	
	

	private static void isupdate(Context context) {
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
					e.printStackTrace();
				}
			}else{
				CommonUtil.printLog("error", message.getMsg());
				
			}
		}
	}
		
	public static void updateOnlineConfig(final Context context){
		Runnable updateOnlineConfigRunnable = new Runnable() {
			
			@Override
			public void run() {
				updateOnlineConfigs(context);
			}
		};
		handler.post(updateOnlineConfigRunnable);
	}
	/**
	 * get KEY-VALUE   
	 * 
	 * @param context
	 */
	private static void updateOnlineConfigs(Context context) {
		appkey = CommonUtil.getAppKey(context);
		JSONObject map = new JSONObject();
		try {
			map.put("appkey", appkey);
		} catch (JSONException e1) {
			e1.printStackTrace();
		}
		String appkeyJSON = map.toString();
		SharedPreferences preferences = context.getSharedPreferences(
				"ums_agent_online_setting_"
						+ CommonUtil.getPackageName(context), 0);
		Editor editor = preferences.edit();
		
		if(CommonUtil.isNetworkAvailable(context)){
			MyMessage message = NetworkUitlity
					.post(UmsConstants.preUrl+UmsConstants.onlineConfigUrl, appkeyJSON);
			try {CommonUtil.printLog("message", message.getMsg());
				if(message.isFlag()){
					JSONObject object = new JSONObject(message.getMsg());
				
					if(UmsConstants.DebugMode){
						CommonUtil.printLog("uploadJSON",object.toString() );
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
					CommonUtil.printLog("error", message.getMsg());
					
				}
			} catch (JSONException e) {
				e.printStackTrace();
			}
		}else{
				CommonUtil.printLog("UMSAgent", " updateOnlineConfig network error");
			
		}
		

	}
	

	/**
	 * get online value by key
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
					e.printStackTrace();
				}
			}else{
				CommonUtil.printLog("error", "getConfigParams error");
			}
		}else{
			CommonUtil.printLog("NetworkError", "Network, not work");
		}
		return "";
	}
/**
 * 
 * @param isUpdateonlyWifi
 */
	public static void setUpdateOnlyWifi(boolean isUpdateonlyWifi) {
		UmsAgent.mUpdateOnlyWifi = isUpdateonlyWifi;
			CommonUtil.printLog("mUpdateOnlyWifi value", UmsAgent.mUpdateOnlyWifi+"");
	}

	
	/**
	 * Setting data transmission mode
	 * @param context
	 * @param reportModel
	 */
	public static void setDefaultReportPolicy(Context context, int reportModel) {
		CommonUtil.printLog("reportType", reportModel+"");
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
	 * create sessionID 
	 * @param context
	 * @return sessionId
	 * @throws ParseException
	 */
	private  static String generateSeesion(Context context)
			throws ParseException {
		String sessionId = "";
		String str = CommonUtil.getAppKey(context);
		if (str != null) {
			String localDate = CommonUtil.getTime();
			str = str + localDate;
			sessionId = MD5Utility.md5Appkey(str);
			return sessionId;
		}
		return null;
	}
	
	
	/**
	 * Upload all data
	 * @param context
	 */
	public static void uploadLog(final Context context){
		Runnable uploadLogRunnable = new Runnable() {
			
			@Override
			public void run() {
				uploadAllLog(context);
			}
		};
		handler.post(uploadLogRunnable);
	}
	
	
	private  static void uploadAllLog(Context context){
		
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
						CommonUtil.printLog("uploadError","uploadLog Error");
					}
				}else{
					CommonUtil.printLog("NetworkError", "Network, not work");
				}
				
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
	}
	/**
	 * \ upload client device information
	 * @param context
	 */
	public static void postClientData(final Context context){
		Runnable postClientDataRunnable = new Runnable() {
			
			@Override
			public void run() {
				postClientDatas(context);
			}
		};
		handler.post(postClientDataRunnable);
		
	}
	
	private   static void postClientDatas(Context context){
		if(isFirst){
			 
			 JSONObject clientData =getClientDataJSONObject(context); 
		
			    if(1==CommonUtil.getReportPolicyMode(context)&CommonUtil.isNetworkAvailable(context)){
			  MyMessage message= NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.clientDataUrl, clientData.toString());
			    if(!message.isFlag()){
			    	saveInfoToFile("clientData", clientData, context);
			    	CommonUtil.printLog("Errorinfo", message.getMsg());
			    }
			    }else{
			    	saveInfoToFile("clientData", clientData, context);
			    }
			    isFirst=false;
			
		
		}
	}
	private static JSONObject getClientDataJSONObject(Context context) {
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
				clientData.put("deviceid", tm.getDeviceId()==null?"":tm.getDeviceId());//
			    clientData.put("appkey", CommonUtil.getAppKey(context));
			    clientData.put("resolution", displaysMetrics.widthPixels+"x"+displaysMetrics.heightPixels);
			    clientData.put("ismobiledevice", true);
			    clientData.put("phonetype", tm.getPhoneType());//
			    clientData.put("imsi", tm.getSubscriberId());
			    clientData.put("network", CommonUtil.getNetworkType(context));
			    clientData.put("version", CommonUtil.getVersion(context));
			    
			    
			    SCell sCell = CommonUtil.getCellInfo(context);
			    
			    clientData.put("mccmnc", sCell!=null?""+sCell.MCCMNC:"");
			    clientData.put("cellid",sCell!=null?sCell.CID+"":"");
			    clientData.put("lac", sCell!=null?sCell.LAC+"":"");
			    clientData.put("latitude", CommonUtil.getItude(sCell,UmsAgent.mUseLocationService).latitude);
			    clientData.put("longitude", CommonUtil.getItude(sCell,UmsAgent.mUseLocationService).longitude);
			    clientData.put("time", CommonUtil.getTime());
			    Build bd = new Build();
			    
			    clientData.put("modulename", bd.MODEL);
			    clientData.put("devicename", bd.MANUFACTURER+bd.PRODUCT);
			    clientData.put("wifimac", wifiManager.getConnectionInfo().getMacAddress());
			    clientData.put("havebt", adapter==null ? false:true);
			    clientData.put("havewifi", CommonUtil.isWiFiActive(context));
			    clientData.put("havegps", locationManager==null? false:true);
			    clientData.put("havegravity", CommonUtil.isHaveGravity(context));//
			    CommonUtil.printLog("clientData---------->",clientData.toString());
			 	} catch (JSONException e) {
					e.printStackTrace();
				} catch (Exception e) {
					e.printStackTrace();
				} 
		return clientData;
	}
	
	
}