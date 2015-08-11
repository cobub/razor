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

import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;

class AppInfo {
    private static Context context;
    private static final String TAG = "AppInfo";
    private static final String UMS_APPKEY = "UMS_APPKEY";

    static void init(Context context) {
        AppInfo.context = context;
    }

    static String getAppKey() {
        String umsAppkey = "";
        try {
            PackageManager pm = context.getPackageManager();

            ApplicationInfo ai = pm.getApplicationInfo(
                    context.getPackageName(),
                    PackageManager.GET_META_DATA);

            if (ai != null) {
                umsAppkey = ai.metaData.getString(UMS_APPKEY);
                if (umsAppkey == null)
                    CobubLog.e(TAG, "Could not read UMS_APPKEY meta-data from AndroidManifest.xml.");
            }
        } catch (Exception e) {
            CobubLog.e(TAG, "Could not read UMS_APPKEY meta-data from AndroidManifest.xml.");
            CobubLog.e(TAG, e);
        }
        return umsAppkey;
    }
    
    static String getAppVersion() {
        String versionName = "";
        try {
            PackageManager pm = context.getPackageManager();
            PackageInfo pi = pm.getPackageInfo(context.getPackageName(), 0);
            versionName = pi.versionName;
            if (versionName == null)
                versionName = "";
        } catch (Exception e) {
            CobubLog.e(TAG, e.toString());
            }
        return versionName;
    }
}
