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

import java.io.PrintWriter;
import java.io.StringWriter;

import android.content.Context;
import android.content.SharedPreferences;

public class ReadErrorLog extends Thread{
	Context context=null;
	
	
	public ReadErrorLog(Context context) {
		super();
		this.context = context;
	}



	
	@Override
	public void run() {
		Thread.setDefaultUncaughtExceptionHandler(new Thread.UncaughtExceptionHandler() {
			
			@Override
			public void uncaughtException(Thread thread, Throwable ex) {
				StringWriter sw = new StringWriter();
				PrintWriter p = new PrintWriter(sw);
				ex.printStackTrace(p);
				String s = sw.toString();
				SharedPreferences sharedPreferences =context.getSharedPreferences("error", 0);
				String info = sharedPreferences.getString("info", "");
				info = info+s+"\n";
				sharedPreferences.edit().putString("info", info).commit();
				p.close();
			}
		});
        
    
	}
}
