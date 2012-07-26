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

import android.content.Context;
import android.content.pm.PackageManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.util.Log;

public class CheckNetState {
	/*
	 * 检查androidManifest.xml中是否添加网络访问权限 *
	 */
	public static String checkNetStateInfo(Context paramContext) {
		if (paramContext != null) {

			PackageManager localPackageManager = paramContext
					.getPackageManager();
			if (localPackageManager.checkPermission(
					"android.permission.ACCESS_NETWORK_STATE", paramContext
							.getPackageName()) != 0)
				return null;
			try {
				ConnectivityManager localConnectivityManager = (ConnectivityManager) paramContext
						.getSystemService("connectivity");
				NetworkInfo localNetworkInfo = localConnectivityManager
						.getActiveNetworkInfo();
				if (localNetworkInfo == null)
					return null;
				if (localNetworkInfo.getType() == 1)
					return null;
				String str = localNetworkInfo.getExtraInfo();
				if (UmsConstants.DebugMode)
					Log.i("TAG", "net type:" + str);
				if (str == null)
					return null;
				if ((str.equals("cmwap")) || (str.equals("3gwap"))
						|| (str.equals("uniwap")))
					return "10.0.0.172";
			} catch (Exception localException) {
				localException.printStackTrace();
			}
			return null;
		}
		return null;
	}
}
