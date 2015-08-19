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

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.Iterator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;

/**
 * save data to local file
 * 
 * @author duzhou.xu
 */
class SaveInfo extends Thread {
    public Context context;
    public JSONObject object;
    private final String tag = "SaveInfo";

    public SaveInfo(Context context, JSONObject object) {
        super();
        this.object = object;
        this.context = context;
    }

    @Override
    public void run() {
        super.run();
        File file;

        String cacheFile = context.getCacheDir()+"/cobub.cache";
        CobubLog.d(tag,"Save cache file "+cacheFile);
        CobubLog.d(tag,"json data "+object.toString());

        JSONObject existJSON = null;
        try {

                file = new File(cacheFile);
                if (file.exists())
                {
                    CobubLog.i(tag, "file exist " + file.getAbsolutePath());
                }
                else
                {
                    file.createNewFile();
                }
                //
                if (file.length() > 1024 * 1024) {
                    file.delete();
                    file = new File(cacheFile);
                    file.createNewFile();
                }
                FileInputStream in = new FileInputStream(cacheFile);
                StringBuffer sb = new StringBuffer();

                int i = 0;
                byte[] s = new byte[1024 * 4];

                while ((i = in.read(s)) != -1) {

                    sb.append(new String(s, 0, i));
                }
                in.close();
                if (sb.length() != 0) {
                    existJSON = new JSONObject(sb.toString());

                    Iterator<String> iterator = object.keys();

                    while (iterator.hasNext()) {
                        String key = iterator.next();
                        JSONArray newData = object.getJSONArray(key);

                        if (existJSON.has(key)) {
                            JSONArray newDataArray = existJSON.getJSONArray(key);
                            newDataArray.put(newData.get(0));
                        } else {
                            existJSON.put(key, object.getJSONArray(key));
                        }
                    }
                    FileOutputStream fileOutputStream = new FileOutputStream(
                            cacheFile, false);
                    fileOutputStream.write(existJSON.toString().getBytes());
                    fileOutputStream.flush();
                    fileOutputStream.close();

                } else {
                    Iterator<String> iterator = object.keys();
                    JSONObject jsonObject = new JSONObject();
                    while (iterator.hasNext()) {
                        String key = iterator.next();
                        JSONArray array = object.getJSONArray(key);

                        jsonObject.put(key, array);

                    }
                    jsonObject.put("appkey", AppInfo.getAppKey());

                    FileOutputStream fileOutputStream = new FileOutputStream(
                            cacheFile, false);
                    fileOutputStream.write(jsonObject.toString().getBytes());
                    fileOutputStream.flush();
                    fileOutputStream.close();
                    CobubLog.i(tag,"seve info finshed");
//                }
            }
        } catch (IOException e) {
            CobubLog.e(tag,e);
        } catch (JSONException e) {
            CobubLog.e(tag,e);
        } catch (Exception e) {
            CobubLog.e(tag,e);
        } 
    }
}
