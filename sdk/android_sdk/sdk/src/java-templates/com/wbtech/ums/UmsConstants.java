/**
 * Cobub Razor
 * <p>
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

import com.wbtech.ums.UmsAgent.LogLevel;
import com.wbtech.ums.UmsAgent.SendPolicy;

public class UmsConstants {
    public  static String BASE_URL = "";
    
    public final static String CLIENTDATA_URL = "/clientdata";
    public final static String ERROR_URL = "/errorlog";
    public final static String EVENT_URL = "/eventlog";
    public final static String TAG_URL = "/tag";
    public final static String USINGLOG_URL = "/usinglog";
    public final static String UPDATE_URL = "/appupdate.json";
    public final static String CONFIG_URL = "/pushpolicyquery.json";
    public final static String PARAMETER_URL = "/getAllparameters.json";
    public final static String LOG_TAG = "UMSAgent";

    // Set the SDK Logs output. If DebugEnabled == true, the log will be
    // output depends on DebugLevel. If DebugEnabled == false, there is 
    // no any outputs.
    public static boolean DebugEnabled = false;
    // Default Log Level is Debug, no log information will be output in Logcat
    public static LogLevel DebugLevel = LogLevel.Debug;

    // Default settings for continue Session duration. If user quit the app and 
    // then re-entry the app in 30 seconds, it will be seemed as the same session.
    public static long kContinueSessionMillis = 30000L; // Default is 30s.

    public static boolean mProvideGPSData = false; // Default is false, not use GPS data. 

    public static boolean mUpdateOnlyWifi = true; // Default is true, only wifi update

    // Report policy: 1 means sent the data to server immediately
    // 0 means the data will be cached and sent to server when next app's start up.
    public static SendPolicy mReportPolicy = SendPolicy.POST_NOW; //Default is 1, real-time  


    public static long defaultFileSize = 1024 * 1024;//1M

    public static String fileSep = "@_@";
    //other settings
    public static String SDK_VERSION = "${sdk.version}";
    //security level:0=http;1=https;2=https+dn
    public static String SDK_SECURITY_LEVEL = "0";// "${sdk.security.level}";
    //ssl pos name
    public static String SDK_POS_NAME = "${sdk.pos.name}";
    //csr alias
    public static String SDK_CSR_ALIAS = "${sdk.csr.alias}";
    //for cpos dn check
    public static String SDK_HTTPS_DN = "${sdk.https.dn}";

    public static String LIB_VERSION = "1.0";

}


