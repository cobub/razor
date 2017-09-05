/**
 * Cobub Razor
 * <p/>
 * An open source analytics android sdk for mobile applications
 *
 * @package Cobub Razor
 * @author WBTECH Dev Team
 * @copyright Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license http://www.cobub.com/products/cobub-razor/license
 * @link http://www.cobub.com/products/cobub-razor/
 * @filesource
 * @since Version 0.1
 */

package com.wbtech.ums;

import android.app.Activity;
import android.app.ActivityManager;
import android.content.ComponentName;
import android.content.Context;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
import android.os.Environment;
import android.telephony.TelephonyManager;
import android.util.Log;

import com.wbtech.ums.UmsAgent.SendPolicy;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.security.MessageDigest;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;
import java.util.UUID;
import java.util.concurrent.locks.ReentrantReadWriteLock;

class CommonUtil {
    private static String USER_ID = "";
    private static String curversion = "";
    private static ReentrantReadWriteLock rwl
            = new ReentrantReadWriteLock();

    public static ReentrantReadWriteLock getRwl() {
        return rwl;
    }

    public static void saveInfoToFile(String type, JSONObject info,
                                      Context context) {
        JSONArray array = new JSONArray();
        array.put(info);
        saveInfoToFile(type, array, context);
    }

    public static void saveInfoToFile(String type, JSONArray info,
                                      Context context) {
        try {
            // /context不带入线程中，尽量早点释放资源
            String filePath = context.getCacheDir().getAbsolutePath()
                    + "/cobub.cache" + type;
            SharedPrefUtil sp = new SharedPrefUtil(context);
            Thread t = new SaveInfo(info, type, filePath, sp);
            t.run();
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
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
        if (context == null || permission.equals("") || permission.equals("")) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "Incorrect parameters");
            return false;
        }
        PackageManager pm = context.getPackageManager();
        return pm.checkPermission(permission, context.getPackageName())
                == PackageManager.PERMISSION_GRANTED;
    }

    /**
     * 逻辑：如果有sim卡，则用ssn；否则用serial
     * return UserIdentifier
     */
    public static String getUserIdentifier(Context context) {
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return "";
        }
        if (USER_ID.equals("")) {
            SharedPrefUtil sp = new SharedPrefUtil(context);
            USER_ID = sp.getValue("identifier", "");
            //如果user_id为空，先使用电话号码。没有再使用serial
            if (USER_ID.equals("")) {
                USER_ID = md5(DeviceInfo.getPhoneNum());
                if ("".equals(DeviceInfo.getPhoneNum()) && Build.VERSION.SDK_INT >= 9) {
                    USER_ID = Build.SERIAL;
                } // beyond android os 2.3
                //回写
                sp.setValue("identifier", USER_ID);
            }
        }
        return USER_ID;

    }

    /**
     * 增加此方法，避免USER_ID的缓存无法更新
     *
     * @param identifier
     */
    public static void setUserIdentifier(Context context, String identifier) {
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
        }
        SharedPrefUtil sp = new SharedPrefUtil(context);
        sp.setValue("identifier", identifier);
        USER_ID = identifier;
    }

    /**
     * Get the current send model
     *
     * @param context
     * @return
     */
    public static SendPolicy getReportPolicyMode(Context context) {
        int type = getlocalDefaultReportPolicy(context);
        switch (type) {
            case 0:
                UmsAgent.setDefaultReportPolicy(context, SendPolicy.POST_ONSTART);
                break;
            case 1:
                UmsAgent.setDefaultReportPolicy(context, SendPolicy.POST_NOW);
                break;
            case 2:
                UmsAgent.setDefaultReportPolicy(context, SendPolicy.POST_INTERVAL);
                break;

            default:
                break;
        }
        return UmsConstants.mReportPolicy;
    }

    /**
     * Testing equipment networking and networking WIFI
     *
     * @param context
     * @return true or false
     */
    public static boolean isNetworkAvailable(Context context) {
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return false;
        }
        if (checkPermissions(context, "android.permission.INTERNET")) {
            ConnectivityManager cManager = (ConnectivityManager) context
                    .getSystemService(Context.CONNECTIVITY_SERVICE);
            if (cManager == null)
                return false;

            NetworkInfo info = cManager.getActiveNetworkInfo();

            if (info != null && info.isAvailable()) {
                CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "Network is available.");
                return true;
            } else {
                CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "Network is not available.");
                return false;
            }

        } else {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class,
                    "android.permission.INTERNET permission should be "
                            + "added into AndroidManifest.xml.");

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
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return "";
        }
        if (context instanceof Activity) {
            String name = "";
            try {
                name = ((Activity) context).getComponentName()
                        .getShortClassName();
            } catch (Exception e) {
                CobubLog.e("can not get name", e);
            }
            if (name.startsWith(".")) {
                name = name.replaceFirst(".", "");
            }
            return name;
        } else {
            ActivityManager am = (ActivityManager) context
                    .getSystemService(Context.ACTIVITY_SERVICE);
            if (checkPermissions(context, "android.permission.GET_TASKS")) {
                ComponentName cn = am.getRunningTasks(1).get(0).topActivity;
                String name = cn.getShortClassName();
                if (name.startsWith(".")) {
                    name = name.replaceFirst(".", "");
                }
                return name;
            } else {
                CobubLog.e("lost permission", CommonUtil.class
                        , "android.permission.GET_TASKS");

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
    public static String getCurVersionCode(Context context) {
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return "";
        }
        if (curversion.equals("")) {
            try {
                PackageManager pm = context.getPackageManager();
                PackageInfo pi = pm.getPackageInfo(context.getPackageName(), 0);
                curversion = pi.versionCode + "";
                if (curversion.length() <= 0) {
                    return "";
                }
            } catch (Exception e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            }
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
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return "";
        }
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

    /**
     * 判断是否是新的session
     * cobub之前规则是，只要在【时间间隔】内，算一次session
     * 1，app启动后，关闭，在【时间间隔】内启动session值相同
     * 2，app启动后，后台运行，在【时间间隔】内启动session值相同
     * 应修改：当app启动时，session重置
     * @param context
     * @return
     */
    static boolean isNewSession(Context context) {
        Log.i("longtest", "-----------------------------------------isNewSession");
        /**
         * 此处有疑问？
         */
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return false;
        }
        try {
            long currenttime = System.currentTimeMillis();
            SharedPrefUtil sp = new SharedPrefUtil(context);
            long session_save_time = sp.getValue("session_save_time", 0);
            CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "currenttime=" + currenttime);
            CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "session_save_time=" + session_save_time);
            /**
             * 若当前时间-之前session最后一次保留时间 > 时间间隔
             * 则 创建新session
             */
            if (currenttime - session_save_time > getSessionContinueMillis(context)) {
                CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "return true,create new session.");
                return true;
            }
            // 否则为同一个session
            CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "return false.At the same session.");
            return false;
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
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
        if (context == null) {
            CobubLog.e(UmsConstants.LOG_TAG, CommonUtil.class, "context is null");
            return false;
        }
        if (checkPermissions(context, "android.permission.INTERNET")) {
            ConnectivityManager cManager = (ConnectivityManager) context
                    .getSystemService(Context.CONNECTIVITY_SERVICE);
            if (cManager == null)
                return false;

            NetworkInfo info = cManager.getActiveNetworkInfo();

            if (info != null && info.isAvailable()
                    && info.getType() == ConnectivityManager.TYPE_WIFI) {
                CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "Active Network type is wifi");
                return true;
            } else {
                CobubLog.i(UmsConstants.LOG_TAG, CommonUtil.class, "Active Network type is not wifi");
                return false;
            }
        } else {
            CobubLog.e(
                    UmsConstants.LOG_TAG, CommonUtil.class,
                    "android.permission.INTERNET permission should be added into AndroidManifest.xml.");
            return false;
        }

    }

    public static String md5(String str) {
        try {
            MessageDigest localMessageDigest = MessageDigest.getInstance("MD5");
            localMessageDigest.update(str.getBytes());
            byte[] arrayOfByte = localMessageDigest.digest();
            StringBuffer localStringBuffer = new StringBuffer();
            for (byte anArrayOfByte : arrayOfByte) {
                int j = 0xFF & anArrayOfByte;
                if (j < 16)
                    localStringBuffer.append("0");
                localStringBuffer.append(Integer.toHexString(j));
            }
            return localStringBuffer.toString();
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
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
    static String generateSession(Context context) {

        String sessionId = "";
        String str = DeviceInfo.getDeviceId();

        str = str + DeviceInfo.getDeviceTime();
        sessionId = CommonUtil.md5(str);

        SharedPrefUtil sp = new SharedPrefUtil(context);
        sp.setValue("session_id", sessionId);

        saveSessionTime(context);
        Thread threadactivity = new UploadActivityLog(context);
        threadactivity.run();
        return sessionId;
    }

    public static String getSessionid(Context context) {
        SharedPrefUtil sp = new SharedPrefUtil(context);
        String session_id = sp.getValue("session_id", "");
        if(session_id.equals("")){
        	session_id =  generateSession(context);
        }
        return session_id;
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

    static void saveDefaultReportPolicy(Context context, int i) {
        SharedPrefUtil spu = new SharedPrefUtil(context);
        spu.setValue("DefaultReportPolicy", i);
    }

    static int getlocalDefaultReportPolicy(Context context) {
        SharedPrefUtil spu = new SharedPrefUtil(context);
        return (int) spu.getValue("DefaultReportPolicy", 1);// 默认POST_NOW
    }

    static long getSessionContinueMillis(Context context) {
        // UmsConstants.kContinueSessionMillis
        SharedPrefUtil spu = new SharedPrefUtil(context);
        return spu.getValue("SessionContinueMillis",
                UmsConstants.kContinueSessionMillis);

    }

    static boolean isUpdateOnlyWIFI(Context context) {
        SharedPrefUtil spu = new SharedPrefUtil(context);
        return spu.getValue("updateOnlyWifiStatus",
                UmsConstants.mUpdateOnlyWifi);

    }

    static boolean isSupportlocation(Context context) {
        SharedPrefUtil spu = new SharedPrefUtil(context);
        return spu.getValue("locationStatus", UmsConstants.mProvideGPSData);
    }

    static JSONArray getJSONdata(String cachfileclientdata, String key) {
        JSONArray jsonarr = new JSONArray();

        String data = readDataFromFile(cachfileclientdata);
        if (data.length() > 0) {
            String[] dataarr = data.split(UmsConstants.fileSep);

            for (String datastr : dataarr) {
                if (datastr.equals("")) {
                    continue;
                }
                try {
                    JSONObject obj = new JSONObject(datastr).getJSONObject(key);
                    jsonarr.put(obj);
                } catch (Exception e) {
                    CobubLog.e(UmsConstants.LOG_TAG, e);
                } // try
            } // for
        } // if

        return jsonarr;
    }

    static String readDataFromFile(String fileName) {
        File fileclientdata = new File(fileName);
        if (!fileclientdata.exists()) {
            return "";
        }
        FileInputStream in = null;
        StringBuffer dataBuffer = new StringBuffer();

        ReentrantReadWriteLock rwl = CommonUtil.getRwl();
        if (rwl.readLock().tryLock()) {
            //上读锁,此时其他线程只能读，写操作必须等待读锁释放
            rwl.readLock().lock();
            try {
                in = new FileInputStream(fileclientdata);
                byte[] buffer = new byte[2048];
                int readByte;
                while ((readByte = in.read(buffer)) != -1) {
                    dataBuffer.append(new String(buffer, 0, readByte));
                }
            } catch (Exception e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            } finally {
                if (in != null) {
                    try {
                        in.close();
                    } catch (IOException e) {
                        CobubLog.e(UmsConstants.LOG_TAG, e);
                    }
                }
                //解读锁
                rwl.readLock().unlock();
                fileclientdata.delete();
            }
        }
        return dataBuffer.toString();
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
		int apiLevel = Integer.parseInt(Build.VERSION.SDK);
		File fileFromSDCard = new File(sdCardRoot +File.separator, "."+file_name);
		File fileFromDData = new File(context.getFilesDir(),file_name);// 获取data/data/<package>/files
		//4.4之後 /storage/emulated/0/Android/data/<package>/files
		if(apiLevel>=19){
			sdCardRoot = context.getExternalFilesDir(null).getAbsolutePath();
			fileFromSDCard = new File(sdCardRoot , file_name);
		}
		
		String saltString = "";
		if (Environment.getExternalStorageState().equals(
				Environment.MEDIA_MOUNTED)) {
			// sdcard存在
			if (!fileFromSDCard.exists()) {
				
				String saltId =getSaltOnDataData(fileFromDData, file_name);
				try {
					writeToFile(fileFromSDCard, saltId);
				} catch (Exception e) {
					CobubLog.e(UmsConstants.LOG_TAG, e);
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
			return readSaltFromFile(fileFromSDCard);
		} catch (IOException e) {
			CobubLog.e(UmsConstants.LOG_TAG, e);
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
			CobubLog.e(UmsConstants.LOG_TAG, e);
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
	 * @param uuid
	 *            uuid
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
