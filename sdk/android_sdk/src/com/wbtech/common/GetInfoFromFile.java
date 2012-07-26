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

import java.io.File;
import java.io.FileInputStream;

import org.json.JSONObject;

import android.content.Context;
import android.os.Environment;
import android.util.Log;

import com.wbtech.dao.NetworkUitlity;

public class GetInfoFromFile extends Thread{
	public static Context context;
	

	public GetInfoFromFile(Context context) {
		super();
		this.context = context;
	}
	
	@Override
	public void run() {
		// TODO Auto-generated method stub
		
		FileInputStream in;
		try {
			 in = new FileInputStream(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
			 StringBuffer sb = new StringBuffer();

				int i=0;
				byte[] s = new byte[1024*4];
				
				while((i=in.read(s))!=-1){
					
					sb.append(new String(s,0,i));
				}

			//上传数据
			MyMessage message=	NetworkUitlity.post(UmsConstants.preUrl+UmsConstants.uploadUrl, sb.toString());
			Log.d("xdz", message.getMsg().toString());
			if(message.isFlag()){
				File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
			file.delete();
			}
			
			
			
			
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	
}
