package com.wbtech.ums;

import java.lang.ref.WeakReference;
import java.util.Timer;
import java.util.TimerTask;

import com.wbtech.ums.UmsConstants;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.Application;
import android.content.Context;
import android.os.Bundle;
import android.os.Handler;
import android.os.HandlerThread;
import android.util.Log;

public class UmsAgent {

    private static Handler handler;
    private static boolean isFirst = true;
    private static Timer timer = null;
    private static WeakReference<Context> contextWR;
    private static UsinglogManager usinglogManager;
    private static boolean INIT = false;// init sdk
    private static final String EVENT_DEFAULT = "default_maadmin_event";

    public enum LogLevel {
        Info, // equals Log.INFO, for less important info
        Debug, // equals Log.DEBUG, for some debug information
        Warn, // equals Log.WARN, for some warning info
        Error, // equals Log.ERROR, for the exceptions errors
        Verbose // equals Log.VERBOSE, for the verbose info
    }

    /**
     * 数据发送模式 <br>
     * POST_ONSTART 下次启动发送 <br>
     * POST_NOW 实时发送 <br>
     * POST_INTERVAL 定时发送
     */
    public enum SendPolicy {
        POST_ONSTART, POST_NOW, POST_INTERVAL
    }

    static {
        HandlerThread localHandlerThread = new HandlerThread("UmsAgent");
        localHandlerThread.start();
        handler = new Handler(localHandlerThread.getLooper());
    }

    /**
     * @param context
     */
    private static void init(Context context) {
        updateContent(context);

        UmsAgent.postHistoryLog();
        UmsAgent.postClientData();
        
        UmsAgent.onError();
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call init();BaseURL = " + UmsConstants.BASE_URL);
        SharedPrefUtil spu = new SharedPrefUtil(contextWR.get());
        spu.setValue("system_start_time", System.currentTimeMillis());
        // registerActivityCallback(context);
    }

    /**
     * 要求API 14+ （Android 4.0+） 注册activityCallback;
     *
     * @param context
     */
//	@SuppressLint("NewApi")
//	private static void registerActivityCallback(Context context) {
//		UmsAgent agent = new UmsAgent();
//		LifecycleCallbacks callback = agent.new LifecycleCallbacks();
//		final Application app = (Application) context.getApplicationContext();
//		app.registerActivityLifecycleCallbacks(callback);
//	}

    /**
     * 初始化sdk
     *
     * @param context
     */
    public static void init(Context context,String baseUrl, String appkey) {
        if (appkey.length() == 0 || baseUrl.length() ==0) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "appkey and baseUrl are required");
            return;
        }
        UmsConstants.BASE_URL = baseUrl;
        updateContent(context);
        INIT = true;
        SharedPrefUtil sp = new SharedPrefUtil(contextWR.get());
        sp.setValue("app_key", appkey);
        init(context);
    }

   

    /**
     * Default settings for continue Session duration. If user quit the app and
     * then re-entry the app in [interval] seconds, it will be seemed as the
     * same session.<br>
     * 设置session存活时间
     *
     * @param interval session存活时间 单位为毫秒
     */
    public static void setSessionContinueMillis(Context context, long interval) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class,
                "setSessionContinueMillis = " + String.valueOf(interval));
        if (interval > 0) {
            UmsConstants.kContinueSessionMillis = interval;
            SharedPrefUtil spu = new SharedPrefUtil(contextWR.get());
            spu.setValue("SessionContinueMillis", interval);
        } else {
            CobubLog.e(
                    UmsConstants.LOG_TAG, UmsAgent.class,
                    "Incorrect parameters setSessionContinueMillis = "
                            + String.valueOf(interval));
        }
    }

    /**
     * 设置是否上传位置信息<br>
     * true:上传<br>
     * false:不上传<br>
     *
     * @param isLocation
     */
    public static void setAutoLocation(boolean isLocation) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        UmsConstants.mProvideGPSData = isLocation;
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "setAutoLocation = " + String.valueOf(isLocation));
    }

    /**
     * upload startup and device information
     *

     */
    static void postClientData() {
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                if (isFirst) {
                    CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Start postClientdata thread");
                    ClientdataManager cm = new ClientdataManager(contextWR.get());
                    cm.postClientData();
                    isFirst = false;
                }
            }
        });
        handler.post(thread);
    }

    /**

     */
    static void postHistoryLog() {
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "postHistoryLog");
        if (CommonUtil.isNetworkAvailable(contextWR.get())) {
            Thread thread = new UploadHistoryLog(contextWR.get());
            handler.post(thread);
        }
    }

    /**
     * bind user 绑定用户
     *
     * @param identifier
     */
    public static void bindUserIdentifier(Context context,
                                          final String identifier) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Bind user identifier");
        CommonUtil.setUserIdentifier(contextWR.get(), identifier);
    }

    private static void setSystemStartTime(final Context context) {
        if (CommonUtil.getReportPolicyMode(context) == SendPolicy.POST_INTERVAL) {
            long app_start_time = new SharedPrefUtil(context).getValue(
                    "system_start_time", System.currentTimeMillis());
            long running_time = System.currentTimeMillis() - app_start_time;
            timer = new Timer();
            SharedPrefUtil sp = new SharedPrefUtil(context);
            int start_time = Integer.parseInt(sp.getValue("interval_time",
                    60 * 1000) + "");// 1m
            timer.schedule(new TimerTask() {
                @Override
                public void run() {
                    Thread thread = new UploadHistoryLog(context);
                    handler.post(thread);
                }
            }, start_time - (running_time % start_time), start_time);
        }
    }

    /**
     * activity onResume 在activity的生命周期函数 {@link #onResume(Context)}
     *

     */
    public static void onResume(Context context) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        // 定时发送模式
        setSystemStartTime(contextWR.get());

        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onResume()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(contextWR.get());
                usinglogManager.onResume(contextWR.get());
            }
        });
        handler.post(thread);
    }

    /**
     * 针对使用Fragment的应用，在对应的生命周期函数里调用
     *
    * @param PageName
     */
    public static void onFragmentResume(Context context,
                                        final String PageName) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        setSystemStartTime(contextWR.get());

        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onFragmentResume()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(contextWR.get());
                usinglogManager.onFragmentResume(contextWR.get(), PageName);
            }
        });
        handler.post(thread);
    }

    /**
     * 在Activity的生命周期函数{@link #onPause(Context)}中调用
     *
     */
    public static void onPause(Context context) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        if (timer != null) {
            timer.cancel();
        }

        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onPause()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(contextWR.get());
                usinglogManager.onPause(contextWR.get());
            }
        });
        handler.post(thread);
    }

    /**
     * Call this function to send the uncatched crash exception stack
     * information to server
     *
     */
    static void onError() {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }

        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onError()");
                MyCrashHandler crashHandler = MyCrashHandler.getInstance();
                crashHandler.init(contextWR.get().getApplicationContext());
                Thread.setDefaultUncaughtExceptionHandler(crashHandler);
            }
        });
        handler.post(thread);
    }

    /**
     * Call this function to send the catched exception stack information to
     * server 手动上传捕捉到的error信息
     *
     * @param errorType
     * @param errorinfo
     */
    public static void onError(Context context, final String errorType, final String errorinfo) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        final String error = errorType + "\n" + errorinfo;
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onError(context,errorinfo)");
                ErrorManager em = new ErrorManager(contextWR.get());
                em.postErrorInfo(error);
            }
        });
        handler.post(thread);
    }

    /**
     * Call this function to send the tags which bind to useridentifier 发送用户标签
     *
     * @param context
     * @param tags
     */
    public static void postTags(Context context, final String tags) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call postTags()");
                TagManager tm = new TagManager(contextWR.get(), tags);
                tm.PostTag();
            }
        });
        handler.post(thread);
    }

    /**
     * 发送event事件数据
     *
     * @param context
     */
    public static void onEvent(Context context, final String event_id) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        Thread thread = new Thread(new Runnable() {
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onEvent(context,event_id)");
                onEvent(contextWR.get(), event_id, 1);
            }
        });
        handler.post(thread);
    }

    /**
     * 发送event事件数据
     *
     * @param context
     * @param event_id
     * @param acc
     */
    public static void onEvent(Context context, final String event_id,
                               final int acc) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onEvent(event_id,acc)");
                onEvent(contextWR.get(), event_id, "", acc);
            }
        });
        handler.post(thread);
    }

    /**
     * 发送event事件数据
     *
     * @param event_id
     * @param label
     * @param acc
     */
    public static void onEvent(Context context, final String event_id,
                               final String label, final int acc) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        if (event_id == null || event_id.length() == 0) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "Valid event_id is required");
        }
        if (acc < 1) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "Event acc should be greater than zero");
        }

        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onEvent(event_id,label,acc)");
                EventManager em = new EventManager(contextWR.get(), event_id, label,
                        acc + "");
                em.postEventInfo();
            }
        });
        handler.post(thread);
    }

    /**
     * 发送event事件数据
     *
     * @param context
     * @param event_id
     * @param label
     * @param json
     */
    public static void onEvent(Context context, final String event_id,
                               final String label, final String json) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call onEvent(context,event_id,label,acc)");
                EventManager em = new EventManager(contextWR.get(), event_id, label,
                        "1", json);
                em.postEventInfo();
            }
        });
        handler.post(thread);
    }

    public static void onGenericEvent(Context context,
                                      final String label, final String value) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        Runnable postEventRunnable = new Runnable() {
            @Override
            public void run() {

                EventManager em = new EventManager(contextWR.get(),
                        UmsAgent.EVENT_DEFAULT, label, value);
                em.postEventInfo();
            }
        };
        handler.post(postEventRunnable);
    }

    /**
     * Automatic Updates 检测是否更新应用
     *
     * @param context
     */
    public static void update(Context context) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call update()");
                UpdateManager um = new UpdateManager(contextWR.get());
                um.postUpdate();
            }
        });
        handler.post(thread);
    }

    /**
     * 设置是否打卡debug模式<br>
     * true:打卡debug模式<br>
     * false:关闭debug模式<br>
     *
     * @param isEnableDebug
     */
    public static void setDebugEnabled(boolean isEnableDebug) {
        if (!INIT) {
            Log.e(UmsConstants.LOG_TAG, "sdk is not init!");
        }
        UmsConstants.DebugEnabled = isEnableDebug;
    }

    /**
     * 设置debug 打印log等级<br>
     * LogLevel等级如下<br>
     * LogLevel {<br>
     * Info, // equals Log.INFO, for less important info<br>
     * Debug, // equals Log.DEBUG, for some debug information<br>
     * Warn, // equals Log.WARN, for some warning info<br>
     * Error, // equals Log.ERROR, for the exceptions errors<br>
     * Verbose // equals Log.VERBOSE, for the verbose info<br>
     * }<br>
     *
     * @param level
     */
    public static void setDebugLevel(LogLevel level) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        UmsConstants.DebugLevel = level;
    }


    /**
     * 获取在线配置参数,参数如下<br>
     * 1、是否获取位置信息<br>
     * 2、是否只在WIFI状态下更新<br>
     * 3、获取session时长<br>
     * 4、获取数据发送间隔时间<br>
     * 5、获取客户端缓存文件大小<br>
     *
     * @param context
     */
    public static void updateOnlineConfig(Context context) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        final Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call updaeOnlineConfig");
                ConfigManager cm = new ConfigManager(contextWR.get());
                cm.updateOnlineConfig();
            }
        });
        handler.post(thread);
    }

    /**
     * 是否只在WIFI状态下更新应用 <br>
     * true : 只在WIFI状态下更新应用 false :在非WIFI状态下也可以更新应用
     *
     * @param isUpdateonlyWifi
     */
    public static void setUpdateOnlyWifi(boolean isUpdateonlyWifi) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        UmsConstants.mUpdateOnlyWifi = isUpdateonlyWifi;
        if (contextWR.get() == null) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class,
                    "UmsAgent.context is null,please call init() before ");
            return;
        }
        SharedPrefUtil spu = new SharedPrefUtil(contextWR.get());
        spu.setValue("updateOnlyWifiStatus", isUpdateonlyWifi);
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class,
                "setUpdateOnlyWifi = " + String.valueOf(isUpdateonlyWifi));
    }

    /**
     * Setting data transmission mode 设置数据发送模式
     *
     * @param context
     * @param sendPolicy {@link SendPolicy}
     */
    public static void setDefaultReportPolicy(Context context,
                                              SendPolicy sendPolicy) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        UmsConstants.mReportPolicy = sendPolicy;
        int type = 1;

        if (sendPolicy == SendPolicy.POST_ONSTART) {
            type = 0;
        }
        if (sendPolicy == SendPolicy.POST_INTERVAL) {
            type = 2;
        }
        SharedPrefUtil spu = new SharedPrefUtil(contextWR.get());
        spu.setValue("DefaultReportPolicy", type);

        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class,
                "setDefaultReportPolicy = " + String.valueOf(sendPolicy));

    }

    /**
     * 发送webPage的名称
     *
     * @param pageName
     */
    public static void postWebPage(final String pageName) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "Call postWebPage()");
                if (usinglogManager == null)
                    usinglogManager = new UsinglogManager(contextWR.get());
                usinglogManager.onWebPage(pageName, contextWR.get());
            }
        });
        handler.post(thread);
    }

    /**
     * 设置按间隔发送模式的时间 单位为毫秒 默认为60000毫秒
     *
     * @param context
     * @param interval 发送时间间隔 单位毫秒
     */
    public static void setPostIntervalMillis(Context context, long interval) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        SharedPrefUtil sp = new SharedPrefUtil(contextWR.get());
        sp.setValue("interval_time", interval);
        CobubLog.i(UmsConstants.LOG_TAG, UmsAgent.class, "interval_time = " + String.valueOf(interval));
    }

    /**
     * 获取用户在服务端自定义参数并保存在本地，而后可以调用<br>
     * {@link #getConfigParameter(String)} 获取自定义参数的值
     *
     * @param context
     */
    public static void updateCustomParameters(Context context) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        updateContent(context);
        Thread thread = new Thread(new Runnable() {
            @Override
            public void run() {
                CustomParameterManager cpm = new CustomParameterManager(contextWR.get());
                cpm.getParameters();
            }
        });
        handler.post(thread);
    }

    /**
     * 获取自定义参数的值，<br>
     * 调用此方法前需先调用{@link #updateCustomParameters(Context)}方法获取所有的自定义参数。
     *
     * @param key 自定义参数的键值
     * @return 对应参数值
     */
    public static String getConfigParameter(String key) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return "";
        }
        SharedPrefUtil spu = new SharedPrefUtil(contextWR.get());
        return spu.getValue(key, "");
    }

    /**
     * 设置deviceID
     *
     * @param deviceID
     */
    public static void setDeviceId(String deviceID) {
        if (!INIT) {
            CobubLog.e(UmsConstants.LOG_TAG, UmsAgent.class, "sdk is not init!");
            return;
        }
        DeviceInfo.setDeviceId(deviceID);
    }

    /**
     * 要求API 14+ （Android 4.0+）
     *
     * @author Administrator
     */
//	@SuppressLint("NewApi")
//	class LifecycleCallbacks implements Application.ActivityLifecycleCallbacks {
//
//		@SuppressLint("NewApi")
//		public LifecycleCallbacks() {
//
//		}
//
//		@Override
//		public void onActivityCreated(Activity activity, Bundle bundle) {
//		}
//
//		@Override
//		public void onActivityStarted(Activity activity) {
//		}
//
//		@Override
//		public void onActivityResumed(Activity activity) {
//			// 定时发送模式
//			setSystemStartTime(context);
//
//			Thread thread = new Thread(new Runnable() {
//				@Override
//				public void run() {
//					CobubLog.i(UmsConstants.LOG_TAG, "Call onResume()");
//					if (usinglogManager == null)
//						usinglogManager = new UsinglogManager(context);
//					usinglogManager.onResume(context);
//				}
//			});
//			handler.post(thread);
//		}
//
//		@Override
//		public void onActivityPaused(Activity activity) {
//			if (timer != null) {
//				timer.cancel();
//			}
//
//			Thread thread = new Thread(new Runnable() {
//				@Override
//				public void run() {
//					CobubLog.i(UmsConstants.LOG_TAG, "Call onPause()");
//					if (usinglogManager == null)
//						usinglogManager = new UsinglogManager(context);
//					usinglogManager.onPause(context);
//				}
//			});
//			handler.post(thread);
//		}
//
//		@Override
//		public void onActivityStopped(Activity activity) {
//		}
//
//		@Override
//		public void onActivitySaveInstanceState(Activity activity, Bundle bundle) {
//		}
//
//		@Override
//		public void onActivityDestroyed(Activity activity) {
//		}
//
//	}
    private static void updateContent(Context context) {
        UmsAgent.contextWR = new WeakReference<Context>(context);
        context = null;
    }
}
