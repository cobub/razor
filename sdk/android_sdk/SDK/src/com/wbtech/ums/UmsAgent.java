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

import android.content.Context;
import android.os.Handler;
import android.os.HandlerThread;

import java.text.ParseException;

public class UmsAgent {

    private static Handler handler;
    static boolean isPostFile = true;
    static boolean isFirst = true;
    private static final String tag = "UMSAgent";
    private static UsinglogManager usinglogManager;
    private static Context globalContext;

    public enum LogLevel {
        Info, // equals Log.INFO, for less important info
        Debug, // equals Log.DEBUG, for some debug information
        Warn, // equals Log.WARN, for some warning info
        Error, // equals Log.ERROR, for the exceptions errors
        Verbose // equals Log.VERBOSE, for the verbose info
    }
    
    public enum SendPolicy {
        BATCH,
        REALTIME
    }

    static {
        HandlerThread localHandlerThread = new HandlerThread("UmsAgent");
        localHandlerThread.start();
        handler = new Handler(localHandlerThread.getLooper());
    }
    
    /**
     * The prefix URL of Cobub server, must be ended with ? For example, the
     * prefix URL is "http://localhost/index.php?"
     * 
     * @param url: The Prefix URL of Cobub server
     * @throws Exception 
     */
    public static void init(final Context context, final String urlPrefix) {
        globalContext = context;
        UmsConstants.urlPrefix = urlPrefix;
        UmsAgent.postHistoryLog(context);
        UmsAgent.postClientData(context);
        UmsAgent.onError(context);
        CobubLog.i(tag, "Call init();BaseURL = " + urlPrefix);
    }

    /**
     * Default settings for continue Session duration. If user quit the app and 
     * then re-entry the app in [interval] seconds, it will be seemed as the same session.
     * @param interval
     */
    public static void setSessionContinueMillis(long interval) {
        CobubLog.i(tag, "setSessionContinueMillis = " + String.valueOf(interval));
        if (interval > 0) {
            UmsConstants.kContinueSessionMillis = interval;
        }
    }

    /**
     * @param isLocation
     */
    public static void setAutoLocation(boolean isLocation) {
        UmsConstants.mProvideGPSData = isLocation;
        CobubLog.i(tag, "setAutoLocation = " + String.valueOf(isLocation));
    }

    /**
     * upload startup and device information
     * 
     * @param context
     */
    static void postClientData(final Context context) {

        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                if (isFirst) {
                    CobubLog.i(tag, "Start postClientdata thread");
                    ClientdataManager cm = new ClientdataManager(context);
                    cm.postClientData();
                    try {
                        CommonUtil.generateSession(context);
                    } catch (ParseException e) {
                        CobubLog.e(tag,e);
                    }
                    isFirst = false;
                }
            }
        });
        handler.post(thread);
    }
    
    /**
     * @param context
     */
    static void postHistoryLog(final Context context) {
        CobubLog.i(tag, "postHistoryLog");
        if (CommonUtil.isNetworkAvailable(context)) {
            if (UmsAgent.isPostFile) {
                Thread thread = new UploadHistoryLog(context);
                handler.post(thread);
                UmsAgent.isPostFile = false;
            }

        }
    }

    /**
     * bind user
     * 
     * @param identifier
     */
    public static void bindUserid(final Context context, final String identifier) {
        CobubLog.i(tag, "Bind user identifier");
        SharedPrefUtil sp = new SharedPrefUtil(context);
        sp.setValue("identifier", identifier);
        UmsAgent.postUserId(context, identifier);
    }

    /**
     * @param context
     */
    public static void onResume(final Context context) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call onResume()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(context);
                usinglogManager.onResume(context);
            }
        });
        handler.post(thread);
    }

    /**
     * @param context
     */
    public static void onPause(final Context context) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call onPause()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(context);
                usinglogManager.onPause();
            }
        });
        handler.post(thread);
    }

    /**
     * Call this function to send the uncatched crash exception stack
     * information to server
     * 
     * @param context
     */
    static void onError(final Context context) {
        Thread thread = new Thread(new Runnable() {

            @Override
            public void run() {
                CobubLog.i(tag, "Call onError()");
                MyCrashHandler crashHandler = MyCrashHandler.getInstance();
                crashHandler.init(context.getApplicationContext());
                Thread.setDefaultUncaughtExceptionHandler(crashHandler);
            }
        });
        handler.post(thread);
    }

    /**
     * Call this function to send the catched exception stack information to
     * server
     * 
     * @param context
     * @param error
     */
     static void onError(final Context context, final String errorinfo) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call onError(context,errorinfo)");
                ErrorManager em = new ErrorManager(context);
                em.postErrorInfo(errorinfo);
            }
        });
        handler.post(thread);
    }

    /**
     * Call this function to send the tags which bind to useridentifier
     * 
     * @param context
     * @param tags
     */
    public static void postTags(final Context context, final String tags) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call postTags()");
                TagManager tm = new TagManager(context, tags);
                tm.PostTag();
            }
        });
        handler.post(thread);
    }

    /**
     * Information is saved to a file by type
     * 
     * @param type errorInfo/activityInfo/eventInfo/clinetDataInfo
     * @param info
     * @param context
     */
    public static void onEvent(final Context context, final String event_id) {
        Thread thread = new Thread(new Runnable() {
            public void run() {
                CobubLog.i(tag, "Call onEvent(context,event_id)");
                onEvent(context, event_id, 1);
            }
        });
        handler.post(thread);
    }
    
    /**
     * @param context
     * @param event_id
     * @param acc
     */
    public static void onEvent(final Context context, final String event_id, final int acc) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call onEvent(event_id,acc)");
                onEvent(context, event_id, "", acc);
            }
        });
        handler.post(thread);
    }

    /**
     * @param event_id
     * @param label
     * @param acc
     */
    static void onEvent(final Context context, final String event_id, final String label,final int acc) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call onEvent(event_id,label,acc)");
                EventManager em = new EventManager(context, event_id, label, acc);
                em.postEventInfo();
            }
        });
        handler.post(thread);
    }



    /**
     * Automatic Updates
     * 
     * @param context
     */
    public static void update(final Context context) {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call update()");
                UpdateManager um = new UpdateManager(context);
                um.postUpdate();
            }
        });
        handler.post(thread);
    }

    /**
     * @param isEnableDebug
     */
    public static void setDebugEnabled(boolean isEnableDebug) {
        UmsConstants.DebugEnabled = isEnableDebug;
    }

    /**
     * @param level
     */
    public static void setDebugLevel(LogLevel level) {
        UmsConstants.DebugLevel = level;
    }

    /**
     * @param context
     */
    public static void updateOnlineConfig(final Context context) {
        final Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call updaeOnlineConfig");
                ConfigManager cm = new ConfigManager(context);
                cm.updateOnlineConfig();
            }
        });
        handler.post(thread);
    }

    /**
     * @param isUpdateonlyWifi
     */
    public static void setUpdateOnlyWifi(boolean isUpdateonlyWifi) {
        UmsConstants.mUpdateOnlyWifi = isUpdateonlyWifi;
        CobubLog.i(tag, "setUpdateOnlyWifi = " + String.valueOf(isUpdateonlyWifi));
    }

    /**
     * Setting data transmission mode
     * 
     * @param context
     * @param sendPolicy
     */
    public static void setDefaultReportPolicy(Context context, SendPolicy sendPolicy) {
        UmsConstants.mReportPolicy = sendPolicy;
        CobubLog.i(tag, "setDefaultReportPolicy = " + String.valueOf(sendPolicy));
    }
    
    static void postUserId(final Context context, final String userid){
        final Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call postUserIdentifier");
                OtherManager om = new OtherManager(context);
                om.postUserId();
            }
        });
        handler.post(thread);
    }
    
   public static void postPushID(final Context context, final String cid){
        final Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(tag, "Call postCID");
                OtherManager om = new OtherManager(context,cid);
                om.postCID();
            }
        });
        handler.post(thread);
    }
    
    public static void postWebPage(final String pageName){
        Thread thread = new Thread(new Runnable() {
            
            @Override
            public void run() {
                CobubLog.i(tag, "Call postWebPage()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(globalContext);
                usinglogManager.onWebPage(pageName);
            }
        });
        handler.post(thread);
        }

}
