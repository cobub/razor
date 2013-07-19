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
package com.wbtech.ums.dao;

import java.io.File;
import java.io.FileInputStream;

import android.content.Context;
import android.os.Environment;

import com.wbtech.ums.common.NetworkUitlity;
import com.wbtech.ums.common.UmsConstants;
import com.wbtech.ums.objects.MyMessage;

public class GetInfoFromFile extends Thread{
	public static Context context;
	

	@SuppressWarnings("static-access")
	public GetInfoFromFile(Context context) {
		super();
		this.context = context;
	}
	
	@Override
	public void run() {
		File file1 ;
		FileInputStream in;
		try {
			file1 = new File(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
			if(!file1.exists()){
				return;
			}
			in = new FileInputStream(file1);
			 StringBuffer sb = new StringBuffer();

				int i=0;
				byte[] s = new byte[1024*4];
				
				while((i=in.read(s))!=-1){
					
					sb.append(new String(s,0,i));
				}

			MyMessage message=	NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.uploadUrl, sb.toString());
			if(message.isFlag()){
				File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
			file.delete();
			}
			
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	
}
