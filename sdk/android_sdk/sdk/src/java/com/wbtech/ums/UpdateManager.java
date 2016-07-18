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
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.app.AlertDialog.Builder;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnClickListener;
import android.net.Uri;
import android.os.Environment;
import android.os.Handler;
import android.os.Message;
import android.text.format.Time;

import org.json.JSONException;
import org.json.JSONObject;

import com.wbtech.ums.UmsConstants;

class UpdateManager {
   
    private static String force;
    private static ProgressDialog progressDialog; 
    private static Dialog noticeDialog;    
    private String saveFile = null;
    private static final int DOWN_UPDATE = 1;
    private static final int DOWN_OVER = 2;
    private static int progress;
    private static Thread downLoadThread;
    private static boolean interceptFlag = false;
    private String msg = "发现新版本,是否更新?";
    private String updateMsg = null;
    private String apkUrl = null;   
    private Context context;
    private static final String SAVEPATH = Environment.getExternalStorageDirectory().getPath();
   

    public UpdateManager(Context context) {
        this.context = context;
    }

    JSONObject prepareUpdateJSON() throws JSONException {
        JSONObject jsonUpdate = new JSONObject();

        jsonUpdate.put("appKey", AppInfo.getAppKey(context));
        jsonUpdate.put("versionCode", CommonUtil.getCurVersionCode(context));
        return jsonUpdate;
    }

    public void postUpdate() {
        JSONObject updateData;
        try {
            updateData = prepareUpdateJSON();
        } catch (Exception e) {
            CobubLog.e(UmsConstants.LOG_TAG,UpdateManager.class, e.toString());
            return;
        }
        
        if (CommonUtil.isNetworkAvailable(context) && CommonUtil.isNetworkTypeWifi(context)&&CommonUtil.isUpdateOnlyWIFI(context)) {
            MyMessage message = NetworkUtil.Post(UmsConstants.BASE_URL + UmsConstants.UPDATE_URL,
                    updateData.toString());
            try {
                JSONObject result_obj = new JSONObject(message.getMsg()).getJSONObject("reply");
                if (result_obj.getInt("flag") > 0) {
                    try {
                        this.apkUrl = result_obj.getString("fileUrl");

                        String description = result_obj
                                .getString("description");

                        String version = result_obj.getString("versionName");
                        this.updateMsg = this.msg + "\n" + version + ":"
                                + description;
                        this.saveFile = SAVEPATH + nametimeString;
                        showNoticeDialog(context);
                    } catch (JSONException e) {
                        CobubLog.e(UmsConstants.LOG_TAG, e);
                    }
                }

            } catch (JSONException e1) {
                e1.printStackTrace();
            }
        }
    }

    private Handler mHandler = new Handler() {
        public void handleMessage(Message msg) {
            switch (msg.what) {
            case DOWN_UPDATE:
                progressDialog.setProgress(progress);
                break;
            case DOWN_OVER:
                installApk();
                break;
            default:
                break;
            }
        };
    };

    public String now() {
        Time localTime = new Time("Asia/Beijing");
        localTime.setToNow();
        return localTime.format("%Y-%m-%d");
    }

    public String nametimeString = now();

    public void showNoticeDialog(final Context context) {

        AlertDialog.Builder builder = new Builder(context);
        builder.setTitle("应用更新");
        builder.setMessage(updateMsg);
        builder.setPositiveButton("确定", new OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.dismiss();
                showDownloadDialog(context);
            }
        });
        builder.setNegativeButton("取消", new OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                if (UpdateManager.force.equals("true")) {
                    System.exit(0);
                } else {
                    dialog.dismiss();
                }
            }
        });
        noticeDialog = builder.create();
        noticeDialog.show();
    }

    private void showSdDialog(final Context context) {
        AlertDialog.Builder builder = new Builder(context);
        builder.setTitle("提示");
        builder.setMessage("SD卡不存在");
        builder.setNegativeButton("OK", new OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                System.exit(0);
            }
        });
        noticeDialog = builder.create();
        noticeDialog.show();
    }

    private void showDownloadDialog(Context context) {
        progressDialog = new ProgressDialog(context);
        progressDialog.setTitle("应用更新");

        progressDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
        progressDialog.setButton("取消", new OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.dismiss();
                interceptFlag = true;

            }
        });
        progressDialog.show();
        downloadApk();

    }

    private Runnable mdownApkRunnable = new Runnable() {
        @Override
        public void run() {
            FileOutputStream fos = null;
            InputStream is = null;
            try {
                URL url = new URL(apkUrl);

                HttpURLConnection conn = (HttpURLConnection) url
                        .openConnection();
                conn.connect();
                int length = conn.getContentLength();
                is = conn.getInputStream();
              
                boolean sdCardExist = Environment.getExternalStorageState()
                        .equals(android.os.Environment.MEDIA_MOUNTED);
                if (!sdCardExist) {
                    showSdDialog(context);
                }
                String apkFile = saveFile;
                File ApkFile = new File(apkFile);
                fos = new FileOutputStream(ApkFile);

                int count = 0;
                byte buf[] = new byte[1024];

                do {
                    int numread = is.read(buf);
                    count += numread;
                    progress = (int) (((float) count / length) * 100);
                    mHandler.sendEmptyMessage(DOWN_UPDATE);
                    if (numread <= 0) {
                        progressDialog.dismiss();

                        mHandler.sendEmptyMessage(DOWN_OVER);
                        break;
                    }
                    fos.write(buf, 0, numread);
                } while (!interceptFlag);

            } catch (MalformedURLException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            } catch (IOException e) {
                CobubLog.e(UmsConstants.LOG_TAG, e);
            } finally {
                try {
                    if (fos != null) {
                        fos.close();
                    }
                    
                } catch (IOException e) {
                    CobubLog.e(UmsConstants.LOG_TAG, e);
                }
                
                try {
                    if (is != null) {
                        is.close();
                    }
                } catch (Exception e) {
                     CobubLog.e(UmsConstants.LOG_TAG, e);
                }

            }

        }
    };

    /**
     * download apk
     * 
     */

    private void downloadApk() {
        downLoadThread = new Thread(mdownApkRunnable);
        downLoadThread.start();
    }

    /**
     * install apk
     * 
     */
    private void installApk() {
        File apkfile = new File(saveFile);
        if (!apkfile.exists()) {
            return;
        }
        Intent intent = new Intent(Intent.ACTION_VIEW);
        intent.setDataAndType(Uri.parse("file://" + apkfile.toString()),
                "application/vnd.android.package-archive");
        context.startActivity(intent);
    }
}
