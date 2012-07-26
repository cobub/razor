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
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.Iterator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;
import android.os.Environment;
import android.util.Log;
/**
 * 保存数据到本地
 * @author duzhou.xu
 *
 */
public class SaveInfo extends Thread {
	public Context context;
	public JSONObject object;
	
	
 public SaveInfo(Context context,JSONObject object) {
		super();
		this.object = object;
		this.context = context;
	}


@Override
public void run() {
	// TODO Auto-generated method stub
	super.run();
	
	JSONObject existJSON=null;
	try {
		if(Environment.getExternalStorageState().equals(Environment.MEDIA_MOUNTED)&&CommonUtil.checkPermissions(context, "android.permission.WRITE_EXTERNAL_STORAGE")){
			
			File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
//			System.out.println(file.getAbsolutePath());
			if(file.exists())
			{
				Log.d("path", file.getAbsolutePath());
			}
			else
			{
				file.createNewFile();
				Log.d("path", "No path");
			}
			
			FileInputStream in = new FileInputStream(Environment.getExternalStorageDirectory().getAbsolutePath()+"/mobclick_agent_cached_"+context.getPackageName());
			StringBuffer sb = new StringBuffer();

			int i=0;
			byte[] s = new byte[1024*4];
			
			while((i=in.read(s))!=-1){
				
				sb.append(new String(s,0,i));
			}
			if(sb.length()!=0){
//				System.out.println("filestring"+sb.toString());
				 existJSON = new JSONObject(sb.toString());
				 
				  	Iterator iterator = object.keys();
					
					while(iterator.hasNext()){
						String key = (String) iterator.next();
						JSONArray newData = object.getJSONArray(key);
						
						if(existJSON.has(key)){
							JSONArray newDataArray = existJSON.getJSONArray(key);
							Log.d("SaveInfo", newData+"");
							newDataArray.put(newData.get(0));
							//array2.put(array2.length(),array);
							//obj.put(key, array2);
						}else{
							existJSON.put(key, object.getJSONArray(key));
							Log.d("SaveInfo", "jsonobject"+existJSON);
						}
						
					}
					FileOutputStream fileOutputStream = new FileOutputStream(Environment.getExternalStorageDirectory()+"/mobclick_agent_cached_"+context.getPackageName(),false);
					fileOutputStream.write(existJSON.toString().getBytes());
					fileOutputStream.flush();
					fileOutputStream.close();
					
			}else{
//				object.put("appkey", CommonUtil.getAppKey(context));
				Iterator iterator = object.keys();
				JSONObject jsonObject = new JSONObject();
				while(iterator.hasNext()){
					String key = (String) iterator.next();
					JSONArray array = object.getJSONArray(key);
					
					jsonObject.put(key, array);
					
				}
				jsonObject.put("appkey", CommonUtil.getAppKey(context));
//				System.out.println(jsonObject.toString());
				
				FileOutputStream fileOutputStream = new FileOutputStream(Environment.getExternalStorageDirectory()+"/mobclick_agent_cached_"+context.getPackageName(),false);
				fileOutputStream.write(jsonObject.toString().getBytes());
				fileOutputStream.flush();
				fileOutputStream.close();
			}
		}
}  catch (IOException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	} catch (JSONException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	}
	
	
	

}
}
