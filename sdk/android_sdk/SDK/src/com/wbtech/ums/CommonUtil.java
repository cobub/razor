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

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.security.MessageDigest;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;
import java.util.UUID;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ActivityManager;
import android.content.ComponentName;
import android.content.Context;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Environment;
import android.provider.Settings.Secure;
import android.telephony.TelephonyManager;

import com.wbtech.ums.UmsAgent.SendPolicy;

class CommonUtil {
	private static String tag = "CommonUtil";

	public static void saveInfoToFile(String type, JSONObject info,
			Context context) {
		JSONArray newdata = new JSONArray();
		try {
			newdata.put(0, info);
			JSONObject jsonObject = new JSONObject();
			jsonObject.put(type, newdata);
			 String cacheFile = context.getCacheDir()+"/cobub.cache";
			Thread t = new SaveInfo( jsonObject,cacheFile);
			t.run();
			
		} catch (Exception e) {
			CobubLog.e(tag, e);
		}
	}
	
	public static void saveInfoToFileinMain(String type, JSONObject info,
           Context context) {
       JSONArray newdata = new JSONArray();
       try {
           newdata.put(0, info);
           JSONObject jsonObject = new JSONObject();
           jsonObject.put(type, newdata);
            String cacheFile = context.getCacheDir()+"/cobub.cache";
            SaveInfo.saveData(jsonObject, cacheFile);
           
       } catch (Exception e) {
           CobubLog.e(tag, e);
       }
   }

	/**
	 * checkPermissions
	 * 
	 * @param context
	 * @param permission
	 * @return true or false
	 */
	public static boolean checkPermissions(Context context, String permission) {
		PackageManager pm = context.getPackageManager();
		return pm.checkPermission(permission, context.getPackageName()) == PackageManager.PERMISSION_GRANTED;
	}

	/**
	 * return UserIdentifier
	 */
	public static String getUserIdentifier(Context context) {
		try {
			SharedPrefUtil sp = new SharedPrefUtil(context);
			return sp.getValue("identifier", "");
		} catch (Exception e) {
			CobubLog.e(tag, e);
			return "";
		}
	}

	/**
	 * Get the current send model
	 * 
	 * @param context
	 * @return
	 */
	public static SendPolicy getReportPolicyMode(Context context) {
		return UmsConstants.mReportPolicy;
	}

	/**
	 * Testing equipment networking and networking WIFI
	 * 
	 * @param context
	 * @return true or false
	 */
	public static boolean isNetworkAvailable(Context context) {
		if (checkPermissions(context, "android.permission.INTERNET")) {
			ConnectivityManager cManager = (ConnectivityManager) context
					.getSystemService(Context.CONNECTIVITY_SERVICE);
			if (cManager == null)
				return false;

			NetworkInfo info = cManager.getActiveNetworkInfo();

			if (info != null && info.isAvailable()) {
				CobubLog.i(tag, "Network is available.");
				return true;
			} else {
				CobubLog.i(tag, "Network is not available.");
				return false;
			}

		} else {
			CobubLog.e(
					tag,
					"android.permission.INTERNET permission should be added into AndroidManifest.xml.");

			return false;
		}

	}

	/**
	 * get currnet activity's name
	 * 
	 * @param context
	 * @return
	 */
	public static String getActivityName(Context context) {
		if (context == null) {
			return "";
		}
		if (context instanceof Activity) {
			String name = "";
			try {
				name = ((Activity) context).getComponentName()
						.getShortClassName();
			} catch (Exception e) {
				CobubLog.e("can not get name", e.toString());
			}

			return name;
		} else {
			ActivityManager am = (ActivityManager) context
					.getSystemService(Context.ACTIVITY_SERVICE);
			if (checkPermissions(context, "android.permission.GET_TASKS")) {
				ComponentName cn = am.getRunningTasks(1).get(0).topActivity;
				String name = cn.getShortClassName();

				return name;
			} else {
				CobubLog.e("lost permission", "android.permission.GET_TASKS");

				return "";
			}
		}
	}

	/**
	 * Get the version number of the current program
	 * 
	 * @param context
	 * @return
	 */

	public static String getCurVersion(Context context) {
		String curversion = "";
		try {
			PackageManager pm = context.getPackageManager();
			PackageInfo pi = pm.getPackageInfo(context.getPackageName(), 0);
			curversion = pi.versionName;
			if (curversion == null || curversion.length() <= 0) {
				return "";
			}
		} catch (Exception e) {
			CobubLog.e(tag, e.toString());
		}
		return curversion;
	}

	/**
	 * Get the current networking
	 * 
	 * @param context
	 * @return WIFI or MOBILE
	 */
	public static String getNetworkType(Context context) {
		TelephonyManager manager = (TelephonyManager) context
				.getSystemService(Context.TELEPHONY_SERVICE);
		int type = manager.getNetworkType();
		String typeString = "UNKNOWN";
		if (type == TelephonyManager.NETWORK_TYPE_CDMA) {
			typeString = "CDMA";
		}
		if (type == TelephonyManager.NETWORK_TYPE_EDGE) {
			typeString = "EDGE";
		}
		if (type == TelephonyManager.NETWORK_TYPE_EVDO_0) {
			typeString = "EVDO_0";
		}
		if (type == TelephonyManager.NETWORK_TYPE_EVDO_A) {
			typeString = "EVDO_A";
		}
		if (type == TelephonyManager.NETWORK_TYPE_GPRS) {
			typeString = "GPRS";
		}
		if (type == TelephonyManager.NETWORK_TYPE_HSDPA) {
			typeString = "HSDPA";
		}
		if (type == TelephonyManager.NETWORK_TYPE_HSPA) {
			typeString = "HSPA";
		}
		if (type == TelephonyManager.NETWORK_TYPE_HSUPA) {
			typeString = "HSUPA";
		}
		if (type == TelephonyManager.NETWORK_TYPE_UMTS) {
			typeString = "UMTS";
		}
		if (type == TelephonyManager.NETWORK_TYPE_UNKNOWN) {
			typeString = "UNKNOWN";
		}
		if (type == TelephonyManager.NETWORK_TYPE_1xRTT) {
			typeString = "1xRTT";
		}
		if (type == 11) {
			typeString = "iDen";
		}
		if (type == 12) {
			typeString = "EVDO_B";
		}
		if (type == 13) {
			typeString = "LTE";
		}
		if (type == 14) {
			typeString = "eHRPD";
		}
		if (type == 15) {
			typeString = "HSPA+";
		}

		return typeString;
	}

	static boolean isNewSession(Context context) {
		try {
			long currenttime = System.currentTimeMillis();
			SharedPrefUtil sp = new SharedPrefUtil(context);
			long session_save_time = sp.getValue("session_save_time", 0);
			CobubLog.i(tag, "currenttime=" + currenttime);
			CobubLog.i(tag, "session_save_time=" + session_save_time);
			if (currenttime - session_save_time > UmsConstants.kContinueSessionMillis) {
				CobubLog.i(tag, "return true,create new session.");
				return true;
			}
			CobubLog.i(tag, "return false.At the same session.");
			return false;
		} catch (Exception e) {
			CobubLog.e(tag, e);
			return true;
		}
	}

	/**
	 * Determine the current network type
	 * 
	 * @param context
	 * @return
	 */
	public static boolean isNetworkTypeWifi(Context context) {

		if (checkPermissions(context, "android.permission.INTERNET")) {
			ConnectivityManager cManager = (ConnectivityManager) context
					.getSystemService(Context.CONNECTIVITY_SERVICE);
			if (cManager == null)
				return false;

			NetworkInfo info = cManager.getActiveNetworkInfo();

			if (info != null && info.isAvailable()
					&& info.getType() == ConnectivityManager.TYPE_WIFI) {
				CobubLog.i(tag, "Active Network type is wifi");
				return true;
			} else {
				CobubLog.i(tag, "Active Network type is not wifi");
				return false;
			}
		} else {
			CobubLog.e(
					tag,
					"android.permission.INTERNET permission should be added into AndroidManifest.xml.");
			return false;
		}

	}

	// public static SharedPreferences getSharedPreferences(Context context) {
	// return context.getSharedPreferences("ums_agent_online_setting_"
	// + context.getPackageName(), 0);
	// }

	public static String md5Appkey(String str) {
		try {
			MessageDigest localMessageDigest = MessageDigest.getInstance("MD5");
			localMessageDigest.update(str.getBytes());
			byte[] arrayOfByte = localMessageDigest.digest();
			StringBuffer localStringBuffer = new StringBuffer();
			for (int i = 0; i < arrayOfByte.length; i++) {
				int j = 0xFF & arrayOfByte[i];
				if (j < 16)
					localStringBuffer.append("0");
				localStringBuffer.append(Integer.toHexString(j));
			}
			return localStringBuffer.toString();
		} catch (Exception e) {
			CobubLog.e(tag, e);
		}
		return "";
	}

	/**
	 * create sessionID
	 * 
	 * @param context
	 * @return sessionId
	 * @throws ParseException
	 */
	static String generateSession(Context context) throws ParseException {

		String sessionId = "";
		String str = AppInfo.getAppKey();
		if (str != null) {
			str = str + DeviceInfo.getDeviceTime();
			sessionId = CommonUtil.md5Appkey(str);

			SharedPrefUtil sp = new SharedPrefUtil(context);
			sp.setValue("session_id", sessionId);

			saveSessionTime(context);
			return sessionId;
		}
		return sessionId;
	}

	static void saveSessionTime(Context context) {
		SharedPrefUtil sp = new SharedPrefUtil(context);
		sp.setValue("session_save_time", System.currentTimeMillis());
	}

	static void savePageName(Context context, String pageName) {
		SharedPrefUtil sp = new SharedPrefUtil(context);
		sp.setValue("CurrentPage", pageName);
	}

	static String getFormatTime(long timestamp) {
		try {
			Date date = new Date(timestamp);
			SimpleDateFormat localSimpleDateFormat = new SimpleDateFormat(
					"yyyy-MM-dd HH:mm:ss", Locale.US);
			String result = localSimpleDateFormat.format(date);
			return result;
		} catch (Exception e) {
			return "";
		}
	}


	/**
	 * 返回该设备在此程序上的随机数。
	 * 
	 * @param context
	 *            Context对象。
	 * @return 表示该设备在此程序上的随机数。
	 */
	public synchronized static String getSALT(Context context) {
		String file_name = context.getPackageName().replace(".", "");
		String sdCardRoot = Environment.getExternalStorageDirectory()
				.getAbsolutePath();
		int apiLevel = Integer.parseInt(android.os.Build.VERSION.SDK);
		File fileFromSDCard = new File(sdCardRoot +File.separator, "."+file_name);
		File fileFromDData = new File(context.getFilesDir(),file_name);// 获取data/data/<package>/files
		//4.4之後 /storage/emulated/0/Android/data/<package>/files
		if(apiLevel>=19){
			sdCardRoot = context.getExternalFilesDir(null).getAbsolutePath();
			fileFromSDCard = new File(sdCardRoot , file_name);
		}
		
		String saltString = "";
		if (Environment.getExternalStorageState().equals(
				android.os.Environment.MEDIA_MOUNTED)) {
			// sdcard存在
			if (!fileFromSDCard.exists()) {
				
				String saltId =getSaltOnDataData(fileFromDData, file_name);
				try {
					writeToFile(fileFromSDCard, saltId);
				} catch (Exception e) {
					CobubLog.e(tag, e);
				}
				return saltId;
				
			} else {
				// SD卡上存在salt
				saltString=getSaltOnSDCard(fileFromSDCard);
				try {
					writeToFile(fileFromDData, saltString);
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				return saltString;
			}

		} else {
			// sdcard 不可用
			return getSaltOnDataData(fileFromDData, file_name);
		}

	}

	private static String getSaltOnSDCard(File fileFromSDCard) {
		// TODO Auto-generated method stub
		try {
			String saltString = readSaltFromFile(fileFromSDCard);
			return saltString;
		} catch (IOException e) {
			CobubLog.e(tag, e);
		}
		return null;
	}

	private static String getSaltOnDataData(File fileFromDData, String file_name) {
		try {
			if (!fileFromDData.exists()) {
				String uuid = getUUID();
				writeToFile( fileFromDData, uuid);
				return uuid;
			}
		return	readSaltFromFile(fileFromDData);

		} catch (IOException e) {
			CobubLog.e(tag, e);
		}
		return "";
	}

	private static String getUUID() {
		// TODO Auto-generated method stub
		return UUID.randomUUID().toString().replace("-", "");
	}

	/**
	 * 读出保存在程序文件系统中的表示该设备在此程序上的唯一标识符。。
	 * 
	 * @param file
	 *            保存唯一标识符的File对象。
	 * @return 唯一标识符。
	 * @throws IOException
	 *             IO异常。
	 */
	private static String readSaltFromFile(File file) throws IOException {
		RandomAccessFile accessFile = new RandomAccessFile(file, "r");
		byte[] bs = new byte[(int) accessFile.length()];
		accessFile.readFully(bs);
		accessFile.close();
		return new String(bs);
	}

	/**
	 * 将表示此设备在该程序上的唯一标识符写入程序文件系统中
	 * 
	 * @param context
	 *            Context对象。
	 * @param file
	 *            保存唯一标识符的File对象。
	 * @throws IOException
	 *             IO异常。
	 */
	private static void writeToFile( File file, String uuid)
			throws IOException {
		file.createNewFile();
		FileOutputStream out = new FileOutputStream(file);

		out.write(uuid.getBytes());
		out.close();

	}

}
