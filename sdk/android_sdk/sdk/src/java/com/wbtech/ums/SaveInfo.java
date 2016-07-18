/**
 * Cobub Razor
 * <p/>
 * An open source analytics android sdk for mobile applications
 *
 * @package Cobub Razor
 * @author WBTECH Dev Team
 * @copyright Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license http://www.cobub.com/products/cobub-razor/license
 * @link http://www.cobub.com/products/cobub-razor/
 * @filesource
 * @since Version 0.1
 */

package com.wbtech.ums;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.concurrent.locks.ReentrantReadWriteLock;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.wbtech.ums.UmsConstants;

/**
 * save data to local file
 *
 * @author duzhou.xu
 */
class SaveInfo extends Thread {
    public JSONArray arrObj;
    private String filetype;
    private String filePath;
    private SharedPrefUtil prefUtil;

    public SaveInfo(JSONArray objArray, String filetype,
                    String cacheFilePath, SharedPrefUtil prefUtil) {
        super();
        this.arrObj = objArray;
        this.filePath = cacheFilePath;
        this.filetype = filetype;
        this.prefUtil = prefUtil;
    }

    @Override
    public void run() {
        CobubLog.d(UmsConstants.LOG_TAG, SaveInfo.class, "Save cache file " + filePath);
        CobubLog.d(UmsConstants.LOG_TAG, SaveInfo.class, "json data " + arrObj.toString());
        if (arrObj.length() == 0) {
            return;
        }
        File file = new File(filePath);

        long filesize = prefUtil.getValue("file_size",
                UmsConstants.defaultFileSize);

        JSONArray usefulData = null;
        JSONArray wholeJsonArray;
        if (file.length() > filesize) {
            if (filetype.equals("activityInfo")) {
                usefulData = retrieveDataFromFile(filePath);
            }
            file.delete();
        }
        if (usefulData != null) {
            wholeJsonArray = mergeJsonArray(usefulData, arrObj);
            fileappend(wholeJsonArray);
        } else {
            fileappend(arrObj);
        }
    }

    private JSONArray mergeJsonArray(JSONArray usefulData, JSONArray arrObj) {
        JSONArray wholeArray = new JSONArray();
        for (int i = 0; i < usefulData.length(); i++) {
            JSONObject jsonObject = new JSONObject();
            try {
                jsonObject = (JSONObject) usefulData.get(i);
            } catch (JSONException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            }
            wholeArray.put(jsonObject);
        }
        for (int j = 0; j < arrObj.length(); j++) {
            JSONObject jsonObject = new JSONObject();
            try {
                jsonObject = (JSONObject) arrObj.get(j);
            } catch (JSONException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            }
            wholeArray.put(jsonObject);
        }
        return wholeArray;
    }

    //从文件取回与将保存data的session关联的部分data,并将其追加到文件中
    private JSONArray retrieveDataFromFile(String filePath) {
        String data = CommonUtil.readDataFromFile(filePath);
        JSONArray usefulData = new JSONArray();
        if (data.length() > 0) {
            String[] dataarr = data.split(UmsConstants.fileSep);
            //dataarr[0]为分隔符"@_@",跳过
            //取出arrObj第一个sessionId,与文件取出的最后一个sessionId对比
            try {
                JSONObject retrieveObj = new JSONObject(dataarr[dataarr.length - 1])
                        .getJSONObject("activityInfo");
                if (arrObj.getJSONObject(0).getString("session_id").equals
                        (retrieveObj.getString("session_id"))) {
                    usefulData.put(retrieveObj);
                }
            } catch (JSONException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            }
        }
        return usefulData;
    }

    public void fileappend(JSONArray array) {
        FileWriter writer = null;
        ReentrantReadWriteLock rwl = CommonUtil.getRwl();
        while (true) {
            //上写锁,此时必须等待写锁释放，其他线程才能读和写操作
            if (!rwl.writeLock().tryLock()) {
                continue;
            }
            rwl.writeLock().lock();
            try {
                writer = new FileWriter(filePath, true);
                for (int i = 0; i < array.length(); i++) {
                    JSONObject jsonObject = new JSONObject();
                    try {
                        jsonObject.put(filetype, array.get(i));
                        writer.write(UmsConstants.fileSep + jsonObject.toString());
                    } catch (JSONException e) {
                        CobubLog.e(UmsConstants.LOG_TAG, e);
                    }
                }
            } catch (IOException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            } finally {
                try {
                    if (writer != null) {
                        writer.close();
                    }
                } catch (IOException e) {
                    CobubLog.e(UmsConstants.LOG_TAG, e);
                }
                //解写锁
                rwl.writeLock().unlock();
            }
            break;
        }
    }
}
