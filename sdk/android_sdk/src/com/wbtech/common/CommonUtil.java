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
package com.wbtech.common;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.text.SimpleDateFormat;
import java.util.Date;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONObject;
import android.app.ActivityManager;
import android.content.ComponentName;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.hardware.SensorManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.telephony.TelephonyManager;
import android.telephony.gsm.GsmCellLocation;
import android.util.Log;

public class CommonUtil {
	/**
	 * 检查权限
	 * @param context
	 * @param permission  被检查的权限
	 * @return true 有权限  false没权限
	 */
	public static boolean checkPermissions(Context context, String permission) {
		PackageManager localPackageManager = context.getPackageManager();
		return localPackageManager.checkPermission(permission, context
				.getPackageName()) == PackageManager.PERMISSION_GRANTED;
	}
/**
 * 判断wifi是否可用
 * @param inContext
 * @return
 */
    public static boolean isWiFiActive(Context inContext) { 
    	if(checkPermissions(inContext, "android.permission.ACCESS_WIFI_STATE")){
    		 Context context = inContext.getApplicationContext();  
    	        ConnectivityManager connectivity = (ConnectivityManager) context  
    	                .getSystemService(Context.CONNECTIVITY_SERVICE);  
    	        if (connectivity != null) {  
    	            NetworkInfo[] info = connectivity.getAllNetworkInfo();  
    	            if (info != null) {  
    	                for (int i = 0; i < info.length; i++) {  
    	                    if (info[i].getTypeName().equals("WIFI") && info[i].isConnected()) {  
    	                        return true;  
    	                    }  
    	                }  
    	            }  
    	        }  
    	        return false;
    	}else{
    		if(UmsConstants.DebugMode){
    			Log.e("lost permission", "lost--->android.permission.ACCESS_WIFI_STATE");
    		}
    		
    		return false;
    	}
    }  
	/**
	 * 获取版本信息
	 * @return
	 */
    public static String fetch_version_info() { 
		String result = null; 
		CMDExecute cmdexe = new CMDExecute(); 
		try { 
		String[ ] args = {"/system/bin/cat", "/proc/version"}; 
		result = cmdexe.run(args, "system/bin/"); 
		} catch (IOException ex) { 
		ex.printStackTrace(); 
		} 
		return result; 
		} 
	
	
	/**
	 * 检测设备是否联网
	 * 
	 * @param context
	 * @return true 已联网  false 无联网
	 */
	public static boolean isNetworkAvailable(Context context) {
		if(checkPermissions(context, "android.permission.INTERNET")){
			ConnectivityManager cManager=(ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE); 
			NetworkInfo info = cManager.getActiveNetworkInfo(); 
			
//				if (info != null && info.isAvailable()&&info.getTypeName().equals("WIFI")){ 
				if (info != null && info.isAvailable()){     
			        return true; 
			  }else{ 
				  if(UmsConstants.DebugMode){
					  Log.e("error", "Network error");
				  }
			       
			        return false; 
			  } 
			
			
			
			  
		}else{
			if(UmsConstants.DebugMode){
				Log.e(" lost  permission", "lost----> android.permission.INTERNET");
			}
			
			return false;
		}
		

	}

	/**
	 * 获取当前时间 格式yyyy-MM-dd HH:mm:ss
	 * 
	 * @return
	 */
	public static String getTime() {
		Date date = new Date();
		SimpleDateFormat localSimpleDateFormat = new SimpleDateFormat(
				"yyyy-MM-dd HH:mm:ss");
		return localSimpleDateFormat.format(date);
	}

	/**
	 * 获取APPKEY
	 * 
	 * @param context
	 * @return  appkey
	 */
	public static String getAppKey(Context paramContext) {
		String umsAppkey;
		try {
			PackageManager localPackageManager = paramContext
					.getPackageManager();
			ApplicationInfo localApplicationInfo = localPackageManager
					.getApplicationInfo(paramContext.getPackageName(), 128);
			if (localApplicationInfo != null) {
				String str = localApplicationInfo.metaData
						.getString("UMS_APPKEY");
				if (str != null) {
					umsAppkey = str;
					return umsAppkey.toString();
				}
				if (UmsConstants.DebugMode)
					Log.e("UmsAgent","Could not read UMS_APPKEY meta-data from AndroidManifest.xml.");
			}
		} catch (Exception localException) {
			if (UmsConstants.DebugMode) {
				Log.e("UmsAgent","Could not read UMENG_APPKEY meta-data from AndroidManifest.xml.");
				localException.printStackTrace();
			}
		}
		return null;
	}

	/**
	 * 获取当前activity的名称
	 * @param context
	 * @return
	 */
	public static String getActivityName(Context context) {
		ActivityManager am = (ActivityManager) context
				.getSystemService(Context.ACTIVITY_SERVICE);
		if(checkPermissions(context, "android.permission.GET_TASKS")){
			ComponentName cn = am.getRunningTasks(1).get(0).topActivity;
			return cn.getShortClassName();
		}else{
			if(UmsConstants.DebugMode){
				Log.e("lost permission", "android.permission.GET_TASKS");
			}
			
			return null;
		}
		
		
	}

	/**
	 * 获得PackageName
	 * @param context
	 * @return
	 */
	public static String getPackageName(Context context) {
		ActivityManager am = (ActivityManager) context
				.getSystemService(Context.ACTIVITY_SERVICE);
		
		if(checkPermissions(context, "android.permission.GET_TASKS")){
			ComponentName cn = am.getRunningTasks(1).get(0).topActivity;
			return cn.getPackageName();
		}else{
			if(UmsConstants.DebugMode){
				Log.e("lost permission", "android.permission.GET_TASKS");
			}
			
			return null;
		}
		
	}


	/**
	 * 获取OS 版本号
	 * @param paramContext
	 * @return
	 */
	public static String getOsVersion(Context paramContext) {
		// android.os.build.version.sdk或sdk_int包含当前os版本号
		String osVersion = "";
		if (checkPhoneState(paramContext)) {
			osVersion = android.os.Build.VERSION.RELEASE;
			if(UmsConstants.DebugMode){
				Log.d("android_osVersion", "OsVerson" + osVersion);
			}
			
			return osVersion;
		} else {
			if(UmsConstants.DebugMode){
				Log.e("android_osVersion", "OsVerson get failed");
			}
			
			return null;
		}
	}

	/**
	 * 获取deviceid
	 * @param context
	 *            上下文环境 <uses-permission android:name="READ_PHONE_STATE" /> 需添加
	 * @return
	 */
	public static String getDeviceID(Context context) {
		if(checkPermissions(context, "android.permission.READ_PHONE_STATE")){
			String deviceId = "";
			if (checkPhoneState(context)) {
				TelephonyManager tm = (TelephonyManager) context
						.getSystemService(Context.TELEPHONY_SERVICE);
				deviceId = tm.getDeviceId();
			}
			if (deviceId != null) {
				if(UmsConstants.DebugMode){
					Log.d("commonUtil", "deviceId:" + deviceId);
				}
				
				return deviceId;
			} else {
				if(UmsConstants.DebugMode){
					Log.e("commonUtil", "deviceId is null");
				}
				
				return null;
			}
		}else{
			if(UmsConstants.DebugMode){
				Log.e("lost permissioin", "lost----->android.permission.READ_PHONE_STATE");
			}
			
			return "";
		}
	}

	/**
	 * check phone _state is readied ;
	 * 
	 * @param context
	 * @return
	 */
	public static boolean checkPhoneState(Context context) {
		PackageManager packageManager = context.getPackageManager();
		if (packageManager.checkPermission("android.permission.READ_PHONE_STATE", context
				.getPackageName()) != 0) {
			return false;
		}
		return true;
	}

	/**
	 * 获取sdk 版本号
	 * @param paramContext
	 * @return
	 */
	public static String getSdkVersion(Context paramContext) {
		// android.os.build.version.sdk或sdk_int包含当前os版本号
		String osVersion = "";
		if (!checkPhoneState(paramContext)) {
			osVersion = android.os.Build.VERSION.RELEASE;
			if(UmsConstants.DebugMode){
				Log.e("android_osVersion", "OsVerson" + osVersion);
			}
			
			return osVersion;
		} else {
			if(UmsConstants.DebugMode){
				Log.e("android_osVersion", "OsVerson get failed");
			}
			
			return null;
		}
	}

	/**
	 * 获取当前程序的版本号
	 * @param paramContext
	 * @return
	 */

	public static String getCurVersion(Context paramContext) {
		String curversion = "";
		try {
			// ---get the package info---
			PackageManager pm = paramContext.getPackageManager();
			PackageInfo pi = pm
					.getPackageInfo(paramContext.getPackageName(), 0);
			curversion = pi.versionName;
			if (curversion == null || curversion.length() <= 0) {
				return "";
			}
		} catch (Exception e) {
			if(UmsConstants.DebugMode){
				Log.e("VersionInfo", "Exception", e);
			}
			
		}
		return curversion;
	}
/**
 * 获取当前的发送方式
 * @param context
 * @return
 */
	public static int getReportPolicyMode(Context context){
		String str = context.getPackageName();
		SharedPreferences localSharedPreferences = context
				.getSharedPreferences("ums_agent_online_setting_" + str, 0);
		int type = localSharedPreferences.getInt("ums_local_report_policy", 0);
		return type;
	}
	
	 /**
     * 获取基站信息
     * @throws Exception
     */
    public static SCell getCellInfo(Context context) throws Exception {
        SCell cell = new SCell();  
        /** 调用API获取基站信息 */
        TelephonyManager mTelNet = (TelephonyManager) context.getSystemService(Context.TELEPHONY_SERVICE);
        GsmCellLocation location = (GsmCellLocation) mTelNet.getCellLocation();
        if (location == null){
        	if(UmsConstants.DebugMode){
        		Log.e("GsmCellLocation Error", "GsmCellLocation is null");
        	}
        	return null;
        }
            
     
        String operator = mTelNet.getNetworkOperator();
//        System.out.println("operator------>"+operator.toString());
        int mcc = Integer.parseInt(operator.substring(0, 3));
        int mnc = Integer.parseInt(operator.substring(3));
        int cid = location.getCid();
        int lac = location.getLac();
     
        /** 将获得的数据放到结构体中 */
        cell.MCC=mcc;
        cell.MCCMNC = Integer.parseInt(operator);
        cell.MNC = mnc;
        cell.LAC = lac;
        cell.CID = cid;
     
        return cell;
    }
    
    
    
    /**
     * 获取经纬度
     * @throws Exception
     */
    public static SItude getItude(SCell cell,boolean mUseLocationService) throws Exception {
        SItude itude = new SItude();
     if(cell==null){
    	 if(UmsConstants.DebugMode){
    		  Log.e("getItude Error", "cell is null");
    	 }
    	

    	 itude.latitude = "";
         itude.longitude = "";
         return itude;
     }
        if(mUseLocationService){
        	/** 采用Android默认的HttpClient */
            HttpClient client = new DefaultHttpClient();
            /** 采用POST方法 */
            HttpPost post = new HttpPost("http://www.google.com/loc/json");
            try {
                /** 构造POST的JSON数据 */
                JSONObject holder = new JSONObject();
                holder.put("version", "1.1.0");
                holder.put("host", "maps.google.com");
                holder.put("address_language", "zh_CN");
                holder.put("request_address", true);
                holder.put("radio_type", "gsm");
                holder.put("carrier", "HTC");
         
                JSONObject tower = new JSONObject();
                tower.put("mobile_country_code", cell.MCC);
                tower.put("mobile_network_code", cell.MNC);
                tower.put("cell_id", cell.CID);
                tower.put("location_area_code", cell.LAC);
         
                JSONArray towerarray = new JSONArray();
                towerarray.put(tower);
                holder.put("cell_towers", towerarray);
         
                StringEntity query = new StringEntity(holder.toString());
                post.setEntity(query);
         
                /** 发出POST数据并获取返回数据 */
                HttpResponse response = client.execute(post);
                HttpEntity entity = response.getEntity();
                BufferedReader buffReader = new BufferedReader(new InputStreamReader(entity.getContent()));
                StringBuffer strBuff = new StringBuffer();
                String result = null;
                while ((result = buffReader.readLine()) != null) {
                    strBuff.append(result);
                }
         
                /** 解析返回的JSON数据获得经纬度 */
                JSONObject json = new JSONObject(strBuff.toString());
                JSONObject subjosn = new JSONObject(json.getString("location"));
         
                itude.latitude = subjosn.getString("latitude");
                itude.longitude = subjosn.getString("longitude");
                 
                Log.i("Itude", itude.latitude + itude.longitude);
                 
            } catch (Exception e) {
            	if(UmsConstants.DebugMode){
            		Log.e(e.getMessage(), e.toString());
            	}
                
                throw new Exception("获取经纬度出现错误:"+e.getMessage());
            } finally{
                post.abort();
                client = null;
            }
             
            return itude;
        }else
        {
        	 itude.latitude = "";
             itude.longitude = "";
             if(UmsConstants.DebugMode){
            	 Log.d("getItude", "not auto getItude, value is \"\"");
             }
             
             return itude;
        }
        
    }
    
    /**
     * 判断是否含有陀螺仪
     * @return
     */
  public static boolean isHaveGravity(Context context){
	  SensorManager manager = (SensorManager) context.getSystemService(Context.SENSOR_SERVICE);
	  if(manager==null){
		  return false;
	  }
	return true;
  }
  
  /**
   * 获取当前的联网方式
   * @param context
   * @return  返回WIFI 或者MOBILE
   */
  public static String getNetworkType(Context context){
//	  ConnectivityManager connectionManager = (ConnectivityManager)
//      context.getSystemService(Context.CONNECTIVITY_SERVICE);    
//      NetworkInfo networkInfo = connectionManager.getActiveNetworkInfo();
      TelephonyManager manager = (TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE);
    int type=  manager.getNetworkType();
    String typeString="UNKOWN";
    if(type==TelephonyManager.NETWORK_TYPE_CDMA){
    	typeString ="CDMA";
    }
    if(type==TelephonyManager.NETWORK_TYPE_EDGE){
    	typeString ="EDGE";
    }
    if(type==TelephonyManager.NETWORK_TYPE_EVDO_0){
    	typeString ="EVDO_0";
    }
    if(type==TelephonyManager.NETWORK_TYPE_EVDO_A){
    	typeString ="EVDO_A";
    }
    if(type==TelephonyManager.NETWORK_TYPE_GPRS){
    	typeString ="GPRS";
    }
    if(type==TelephonyManager.NETWORK_TYPE_HSDPA){
    	typeString ="HSDPA";
    }
    if(type==TelephonyManager.NETWORK_TYPE_HSPA){
    	typeString ="HSPA";
    }
    if(type==TelephonyManager.NETWORK_TYPE_HSUPA){
    	typeString ="HSUPA";
    }
    if(type==TelephonyManager.NETWORK_TYPE_UMTS){
    	typeString ="UMTS";
    }
    if(type==TelephonyManager.NETWORK_TYPE_UNKNOWN){
    	typeString ="UNKOWN";
    }
   
	return typeString;
  }
  /**
   * 判断当前 网络类型  是否wifi连接
   * @param context
   * @return
   */
public static boolean isNetworkTypeWifi(Context context) {
	// TODO Auto-generated method stub
	

	if(checkPermissions(context, "android.permission.INTERNET")){
		ConnectivityManager cManager=(ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE); 
		NetworkInfo info = cManager.getActiveNetworkInfo(); 
		
			if (info != null && info.isAvailable()&&info.getTypeName().equals("WIFI")){ 
		        return true; 
		  }else{ 
			  if(UmsConstants.DebugMode){
				  Log.e("error", "Network not wifi");
			  }
		        return false; 
		  } 
	}else{
		if(UmsConstants.DebugMode){
			Log.e(" lost  permission", "lost----> android.permission.INTERNET");
		}
		return false;
	}
	


	
	
	
}
/**
 * 获取当前应用的版本号
 * @param context
 * @return
 */
public static String getVersion(Context context) {
	String versionName = "";  
	try {  
		PackageManager pm = context.getPackageManager();  
		PackageInfo pi = pm.getPackageInfo(context.getPackageName(), 0);  
		versionName = pi.versionName;  
		if (versionName == null || versionName.length() <= 0) {  
			return "";  
		}  
	} catch (Exception e) {  
		if(UmsConstants.DebugMode){
			Log.e("UmsAgent", "Exception", e);  
		}
    
	}  
	return versionName;
 }
  
  
}
