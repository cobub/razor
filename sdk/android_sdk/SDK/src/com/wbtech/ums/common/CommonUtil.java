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

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.text.SimpleDateFormat;
import java.util.Date;

import android.location.Location;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONObject;

import com.wbtech.ums.objects.LatitudeAndLongitude;
import com.wbtech.ums.objects.SCell;

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
	 * checkPermissions
	 * @param context
	 * @param permission  
	 * @return true or  false
	 */
	public static boolean checkPermissions(Context context, String permission) {
		PackageManager localPackageManager = context.getPackageManager();
		return localPackageManager.checkPermission(permission, context
				.getPackageName()) == PackageManager.PERMISSION_GRANTED;
	}
	/**
	 * Determine the current networking is WIFI
	 * @param context
	 * @return
	 */
	public  static boolean CurrentNoteworkTypeIsWIFI(Context context){
		ConnectivityManager connectionManager = (ConnectivityManager)context.
                getSystemService(Context.CONNECTIVITY_SERVICE);   
		return	connectionManager.getActiveNetworkInfo().getType()==ConnectivityManager.TYPE_WIFI;
	}
	
	
/**
 * Judge wifi is available
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
	 * Testing equipment networking and networking WIFI
	 * 
	 * @param context
	 * @return true or false 
	 */
	public static boolean isNetworkAvailable(Context context) {
		if(checkPermissions(context, "android.permission.INTERNET")){
			ConnectivityManager cManager=(ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE); 
			NetworkInfo info = cManager.getActiveNetworkInfo(); 
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
	 * Get the current time     format  yyyy-MM-dd HH:mm:ss
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
	 *get   APPKEY
	 * 
	 * @param paramContext
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
				Log.e("UmsAgent","Could not read UMS_APPKEY meta-data from AndroidManifest.xml.");
				localException.printStackTrace();
			}
		}
		return null;
	}

	/**
	 * get currnet activity's name
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
	 * get  PackageName
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
	 * get  OS number
	 * @param context
	 * @return
	 */
	public static String getOsVersion(Context context) {
		String osVersion = "";
		if (checkPhoneState(context)) {
			osVersion = android.os.Build.VERSION.RELEASE;
			if(UmsConstants.DebugMode){
				printLog("android_osVersion", "OsVerson" + osVersion);
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
	 * get deviceid
	 * @param context
	 *            add  <uses-permission android:name="READ_PHONE_STATE" /> 
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
					printLog("commonUtil", "deviceId:" + deviceId);
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
	 * get sdk number
	 * @param paramContext
	 * @return
	 */
	public static String getSdkVersion(Context paramContext) {
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
	 * Get the version number of the current program
	 * @param context
	 * @return
	 */

	public static String getCurVersion(Context context) {
		String curversion = "";
		try {
			// ---get the package info---
			PackageManager pm = context.getPackageManager();
			PackageInfo pi = pm
					.getPackageInfo(context.getPackageName(), 0);
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
 * Get the current send model
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
     * Get the base station information
     * @throws Exception
     */
    public static SCell getCellInfo(Context context) throws Exception {
        SCell cell = new SCell();  
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
     
        cell.MCC=mcc;
        cell.MCCMNC = Integer.parseInt(operator);
        cell.MNC = mnc;
        cell.LAC = lac;
        cell.CID = cid;
     
        return cell;
    }
    
    
    
    /**
     * Get latitude and longitude
     * @param cell
     * @param mUseLocationService
     * @param context
     * @return LatitudeAndLongitude
     */
    public static LatitudeAndLongitude getLatitudeAndLongitude(SCell cell, boolean mUseLocationService, Context context) {

        LatitudeAndLongitude coordinates = new LatitudeAndLongitude();

        if(cell==null){

            if(UmsConstants.DebugMode){
                Log.e("LatitudeAndLongitude Error", "cell is null");
    	    }
    	
            coordinates.latitude = "";
            coordinates.longitude = "";

            return coordinates;
        }

        if(mUseLocationService){

            int minDistance = 5000; // 5 kilometres
            int minTime = 300000; // 5 minutes

            LegacyLastLocationFinder finder = new LegacyLastLocationFinder(context);
            Location location = finder.getLastBestLocation(minTime, minDistance);

            double latitude = location.getLatitude();
            double longitude = location.getLongitude();

            coordinates.latitude = Double.toString(latitude);
            coordinates.longitude = Double.toString(longitude);

            return coordinates;

        } else {

            coordinates.latitude = "";
            coordinates.longitude = "";

            if(UmsConstants.DebugMode){
                printLog("LatitudeAndLongitude", "not able to find LatitudeAndLongitude, value is \"\"");
            }
             
            return coordinates;
        }
    }
    
    /**
     * To determine whether it contains a gyroscope
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
   * Get the current networking
   * @param context
   * @return  WIFI or MOBILE
   */
  public static String getNetworkType(Context context){
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
   * Determine the current network type  
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
 * Get the current application version number
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

/**
 * Set the output log
 * @param tag
 * @param log
 */

  public static void printLog(String tag,String log) {
	if(UmsConstants.DebugMode==true){
		Log.d(tag, log);
	}
}
  
}
