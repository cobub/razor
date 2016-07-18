/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package     Cobub Razor
 * @author      WBTECH Dev Team
 * @copyright   Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license     http://www.cobub.com/products/cobub-razor/license
 * @link        http://www.cobub.com/products/cobub-razor/
 * @since       Version 0.1
 * @filesource 
 */
package com.wbtech.ums;

import android.Manifest;
import android.bluetooth.BluetoothAdapter;
import android.content.Context;
import android.hardware.SensorManager;
import android.location.Location;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.os.Build;
import android.telephony.CellLocation;
import android.telephony.TelephonyManager;
import android.telephony.cdma.CdmaCellLocation;
import android.telephony.gsm.GsmCellLocation;
import android.util.DisplayMetrics;
import android.view.WindowManager;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.UUID;

import com.wbtech.ums.UmsConstants;


/**
 * @author apple
 */
class DeviceInfo {
    private static Context context;
    private static Location location;
    private static TelephonyManager telephonyManager;
    private static LocationManager locationManager;
    private static BluetoothAdapter bluetoothAdapter;
    private static SensorManager sensorManager;
    private static String DEVICE_ID = "";
    private static String DEVICE_NAME = "";

    public static void init(Context context) {
        DeviceInfo.context = context;

        try {
            telephonyManager = (TelephonyManager) (context
                    .getSystemService(Context.TELEPHONY_SERVICE));
            locationManager = (LocationManager) context
                    .getSystemService(Context.LOCATION_SERVICE);
            bluetoothAdapter = BluetoothAdapter.getDefaultAdapter();

        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, DeviceInfo.class, e.toString());
        }
        getLocation();
    }

    public static String getLanguage() {
        String language = Locale.getDefault().getLanguage();
        CobubLog.i(UmsConstants.LOG_TAG,    DeviceInfo.class, "getLanguage()=" + language);
        if (language == null)
            return "";
        return language;
    }


/**
     * 
     * @throws Exception
     */
    public static String getCellInfoofLAC(){
            CellLocation cl = telephonyManager.getCellLocation();
            if (cl instanceof GsmCellLocation) {
                GsmCellLocation location = (GsmCellLocation) cl;
                int lac = location.getLac();
                
                return lac+"";
            } else if (cl instanceof CdmaCellLocation) {
                CdmaCellLocation cdma = (CdmaCellLocation) cl;
                int lac = cdma.getNetworkId();   
                
                return lac+"";
            }
       
        return "";        
    }
    public static String getCellInfoofCID(){
            CellLocation cl = telephonyManager.getCellLocation();
            if (cl instanceof GsmCellLocation) {
                GsmCellLocation location = (GsmCellLocation) cl;
                int cid = location.getCid();
                return cid+"";
            } else if (cl instanceof CdmaCellLocation) {
                CdmaCellLocation cdma = (CdmaCellLocation) cl;
                int cid = cdma.getBaseStationId(); 
                return cid+"";
            }
        return "";        
    }

    public static String getResolution() {
        DisplayMetrics displaysMetrics = new DisplayMetrics();
        WindowManager wm = (WindowManager) context
                .getSystemService(Context.WINDOW_SERVICE);
        wm.getDefaultDisplay().getMetrics(displaysMetrics);
        CobubLog.i(UmsConstants.LOG_TAG,    DeviceInfo.class, "getResolution()=" + displaysMetrics.widthPixels + "x"
                + displaysMetrics.heightPixels);
        return displaysMetrics.widthPixels + "x" + displaysMetrics.heightPixels;
    }

    public static String getDeviceProduct() {
        String result = Build.PRODUCT;
        CobubLog.i(UmsConstants.LOG_TAG,  DeviceInfo.class,   "getDeviceProduct()=" + result);
        if (result == null)
            return "";
        return result;
    }

    public static boolean getBluetoothAvailable() {
        return bluetoothAdapter != null;
    }

    private static boolean isSimulator() {
        return getDeviceIMEI().equals("000000000000000");
    }

    public static boolean getGravityAvailable() {
        try {
            // This code getSystemService(Context.SENSOR_SERVICE);
            // often hangs out the application when it runs in Android
            // Simulator.
            // so in simulator, this line will not be run.
            if (isSimulator())
                sensorManager = null;
            else
                sensorManager = (SensorManager) context
                        .getSystemService(Context.SENSOR_SERVICE);
            CobubLog.i(UmsConstants.LOG_TAG,   DeviceInfo.class, "getGravityAvailable()");
            return sensorManager != null;
        } catch (Exception e) {
            return false;
        }
    }

    public static String getOsVersion() {
        String result = Build.VERSION.RELEASE;
        CobubLog.i(UmsConstants.LOG_TAG,  DeviceInfo.class,"getOsVersion()=" + result);
        if (result == null)
            return "";

        return result;
    }

    /**
     * Returns a constant indicating the device phone type. This indicates the
     * type of radio used to transmit voice calls.
     * 
     * @return PHONE_TYPE_NONE //0 PHONE_TYPE_GSM //1 PHONE_TYPE_CDMA //2
     *         PHONE_TYPE_SIP //3
     */
    public static int getPhoneType() {
        if (telephonyManager == null)
            return -1;
        int result = telephonyManager.getPhoneType();
        CobubLog.i(UmsConstants.LOG_TAG,   DeviceInfo.class, "getPhoneType()=" + result);
        return result;
    }

    /**
     * get IMSI for GSM phone, return "" if it is unavailable.
     * 
     * @return IMSI string
     */
    public static String getIMSI() {
        String result = "";
        try {
            if (!CommonUtil.checkPermissions(context,
                    Manifest.permission.READ_PHONE_STATE)) {
                CobubLog.e(UmsConstants.LOG_TAG,  DeviceInfo.class,
                        "READ_PHONE_STATE permission should be added into AndroidManifest.xml.");
                return "";
            }
            result = telephonyManager.getSubscriberId();
            CobubLog.i(UmsConstants.LOG_TAG,  DeviceInfo.class, "getIMSI()=" + result);
            if (result == null)
                return "";
            return result;

        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,   e);
        }
      
        return result;
    }

    public static String getWifiMac() {
        try {
            WifiManager wifiManager = (WifiManager) context
                    .getSystemService(Context.WIFI_SERVICE);
            WifiInfo wi = wifiManager.getConnectionInfo();
            String result = wi.getMacAddress();
            if (result == null)
                result = "";
            CobubLog.i(UmsConstants.LOG_TAG,  DeviceInfo.class, "getWifiMac()=" + result);
            return result;
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,  e);
            return "";
        }
    }

    public static String getDeviceTime() {
        try {
            Date date = new Date();
            SimpleDateFormat localSimpleDateFormat = new SimpleDateFormat(
                    "yyyy-MM-dd HH:mm:ss", Locale.US);
            return localSimpleDateFormat.format(date);
        } catch (Exception e) {
            return "";
        }
    }

    public static String getDeviceName() {
        if (DEVICE_NAME.equals("")) {
            try {
                String manufacturer = Build.MANUFACTURER;
                if (manufacturer == null)
                    manufacturer = "";
                String model = Build.MODEL;
                if (model == null)
                    model = "";

                if (model.startsWith(manufacturer)) {
                    DEVICE_NAME = capitalize(model).trim();
                } else {
                    DEVICE_NAME = (capitalize(manufacturer) + " " + model)
                            .trim();
                }
            } catch (Exception e) {
                CobubLog.e(UmsConstants.LOG_TAG,   e);
                return "";
            }
        }
        return DEVICE_NAME;
    }

    public static String getNetworkTypeWIFI2G3G() {
        try {
            ConnectivityManager cm = (ConnectivityManager) context
                    .getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo ni = cm.getActiveNetworkInfo();
            String type = "";
            if (ni != null && ni.getTypeName() != null) {
                type = ni.getTypeName().toLowerCase(Locale.US);
                if (!type.equals("wifi")) {
                    type = cm.getNetworkInfo(ConnectivityManager.TYPE_MOBILE)
                            .getExtraInfo();
                }
            }

            return type;
        } catch (Exception e) {
            return "";
        }
    }

    public static boolean getWiFiAvailable() {
        try {
            if (!CommonUtil.checkPermissions(context,
                    Manifest.permission.ACCESS_WIFI_STATE)) {
                CobubLog.e(UmsConstants.LOG_TAG,    DeviceInfo.class,
                        "ACCESS_WIFI_STATE permission should be added into AndroidManifest.xml.");
                return false;
            }
            ConnectivityManager connectivity = (ConnectivityManager) context
                    .getSystemService(Context.CONNECTIVITY_SERVICE);
            if (connectivity != null) {
                NetworkInfo[] info = connectivity.getAllNetworkInfo();
                if (info != null) {
                    for (NetworkInfo anInfo : info) {
                        if (anInfo.getType() == ConnectivityManager.TYPE_WIFI
                                && anInfo.isConnected()) {
                            return true;
                        }
                    }
                }
            }
            return false;
        } catch (Exception e) {
            return false;
        }
    }

    private static String getDeviceIMEI() {
        String result = "";
        try {
            if (!CommonUtil.checkPermissions(context,
                    Manifest.permission.READ_PHONE_STATE)) {
                CobubLog.e(UmsConstants.LOG_TAG,  DeviceInfo.class,
                        "READ_PHONE_STATE permission should be added into AndroidManifest.xml.");
                return "";
            }
            result = telephonyManager.getDeviceId();
            if (result == null)
                result = "";
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,  e);
        }
        return result;
    }

    public static String getPhoneNum() {
        String result = "";
        try {
            if (!CommonUtil.checkPermissions(context,
                    Manifest.permission.READ_PHONE_STATE)) {
                CobubLog.e(UmsConstants.LOG_TAG,  DeviceInfo.class,
                        "READ_PHONE_STATE permission should be added into AndroidManifest.xml.");
                return "";
            }
            result = telephonyManager.getLine1Number();
            if (result == null)
                result = "";
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,  e);
        }
        return result;
    }

    private static String getSSN() {
        String result = "";
        try {
            if (!CommonUtil.checkPermissions(context,
                    Manifest.permission.READ_PHONE_STATE)) {
                CobubLog.e(UmsConstants.LOG_TAG,  DeviceInfo.class,
                        "READ_PHONE_STATE permission should be added into AndroidManifest.xml.");
                return "";
            }
            result = telephonyManager.getSimSerialNumber();
            if (result == null)
                result = "";
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG, e);
        }
        return result;
    }

    public static void setDeviceId(String did) {
        DEVICE_ID = did;
    }
    
    public static String getDeviceId() {
        if (DEVICE_ID.equals("")) {
            try {
                SharedPrefUtil sp = new SharedPrefUtil(context);
                String uniqueid = sp.getValue("uniqueuid", "");

                if (!uniqueid.equals("")) {
                    DEVICE_ID = uniqueid;
                } else {
                	 String imei = getDeviceIMEI();
                     String imsi = getIMSI();
                     String salt = CommonUtil.getSALT(context);
                     DEVICE_ID = CommonUtil.md5(imei + imsi + salt);
                    sp.setValue("uniqueuid", DEVICE_ID);
                }
            } catch (Exception e) {
                CobubLog.e(UmsConstants.LOG_TAG,e);
            }
          
            
        }
        return DEVICE_ID;
    }
        
    public static String getLatitude() {
        if (location == null)
            return "";
        return String.valueOf(location.getLatitude());
    }

    public static String getLongitude() {
        if (location == null)
            return "";
        return String.valueOf(location.getLongitude());

    }

    public static String getGPSAvailable() {
        if (location == null)
            return "false";
        else
            return "true";
    }

    private static void getLocation() {
        CobubLog.i(UmsConstants.LOG_TAG,  DeviceInfo.class, "getLocation");
        try {
            List<String> matchingProviders = locationManager.getAllProviders();
            for (String prociderString : matchingProviders) {
                location = locationManager.getLastKnownLocation(prociderString);
                if (location != null)
                    break;
            }
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,  DeviceInfo.class, e.toString());
        }
    }

    public static String getMCCMNC() {
        String result;
        try {

            String operator = telephonyManager.getNetworkOperator();
            if (operator == null)
                result = "";
            else
                result = operator;
        } catch (Exception e) {
            result = "";
            CobubLog.e(UmsConstants.LOG_TAG,  DeviceInfo.class, e.toString());
        }
        return result;
    }

    /**
     * Capitalize the first letter
     * 
     * @param s
     *            model,manufacturer
     * @return Capitalize the first letter
     */
    private static String capitalize(String s) {
        if (s == null || s.length() == 0) {
            return "";
        }
        char first = s.charAt(0);
        if (Character.isUpperCase(first)) {
            return s;
        } else {
            return Character.toUpperCase(first) + s.substring(1);
        }
    }
}
