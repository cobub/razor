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

import android.content.Context;

class UploadHistoryLog extends Thread {
    public Context context;
//    private static final String UPLOAD_URL = "/ums/uploadlog.php";
    private static final String UPLOAD_URL = "/ums/uploadLog";
    
    private final String tag = "UploadHistoryLog";
    
    public UploadHistoryLog(Context context) {
        super();
        this.context = context;
    }

    @Override
    public void run() {
        String cacheFile = context.getCacheDir()+"/cobub.cache";
        CobubLog.i(tag,"Get cache file "+cacheFile);
        File file1;
        FileInputStream in;
        try {
            file1 = new File(cacheFile);
            if (!file1.exists()) {
                CobubLog.d(tag, "No history log file found!");
                return;
            }
            in = new FileInputStream(file1);
            StringBuffer sb = new StringBuffer();

            int i = 0;
            byte[] s = new byte[1024 * 4];

            while ((i = in.read(s)) != -1) {
                sb.append(new String(s, 0, i));
            }

            String result = NetworkUtil.Post(
                    UmsConstants.urlPrefix + UPLOAD_URL, sb.toString());
            CobubLog.i(tag, sb.toString());
            MyMessage message = NetworkUtil.parse(result);
            if (message == null) {
                return;
            }
            if (message.getFlag()>0) {
                File file = new File(cacheFile);
                file.delete();
            }
        } catch (Exception e) {
            CobubLog.e(tag,e);
        }
    }

}
