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

import com.wbtech.ums.UmsAgent.LogLevel;
import com.wbtech.ums.UmsAgent.SendPolicy;

class UmsConstants {

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
    public static SendPolicy mReportPolicy = SendPolicy.REALTIME; //Default is 1, real-time
    
    // Cobub Server URL prefix, must be ended with ?, like "http://localhost/index.php?"
    public static String urlPrefix = "";
    
}


