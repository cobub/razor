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
import android.widget.ProgressBar;

import com.wbtech.ums.common.CommonUtil;
public class UpdateManager {
	String appkey;
	public static Context mContext;
	public static String force;
	public  static ProgressDialog progressDialog ;
	private static String Msg = "Found  new version , update?";
	private static String updateMsg = null;

	public static String apkUrl =null;

	private static Dialog noticeDialog;

	private static final String savePath = "/sdcard/";

	private static String saveFile=null;

	private static ProgressBar mProgress;

	private static final int DOWN_UPDATE = 1;

	private static final int DOWN_OVER = 2;

	private static int progress;

	private static Thread downLoadThread;

	private static boolean interceptFlag = false;
	public String newVersion;
	public String newtime;
  
	
	private static Handler mHandler = new Handler() {
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
	public static String now()
	  {
	    Time localTime = new Time("Asia/Beijing");
	    localTime.setToNow();
	    return localTime.format("%Y-%m-%d");
	  }
	public static String nametimeString= now();
      
	public UpdateManager(Context context, String version,String force,String apkurl,String description) {
		appkey = CommonUtil.getAppKey(context);
		this.newVersion = version;
		this.force=force;
		this.apkUrl=apkurl;
		this.mContext = context;
		this.updateMsg= this.Msg+"\n"+version+":"+description;
		this.saveFile=savePath+nametimeString;
	}

	public static  void showNoticeDialog(final Context context) {
		System.out.println(UpdateManager.apkUrl);
		AlertDialog.Builder builder = new Builder(context);
		builder.setTitle("Update software");
		builder.setMessage(updateMsg);
		builder.setPositiveButton("OK", new OnClickListener() {
			@Override
			public void onClick(DialogInterface dialog, int which) {
				dialog.dismiss();
				showDownloadDialog(context);
			}
		});
		builder.setNegativeButton("Cancel", new OnClickListener() {
			@Override
			public void onClick(DialogInterface dialog, int which) {
				if(UpdateManager.force.equals("true")){
					System.exit(0);
				}else{
					dialog.dismiss();
				}				
			}
		});
		noticeDialog = builder.create();
		noticeDialog.show();
	}
	public static  void showSdDialog(final Context context) {
		AlertDialog.Builder builder = new Builder(context);
		builder.setTitle("point");
		builder.setMessage("SD card does not exist");
		builder.setNegativeButton("OK", new OnClickListener() {
			@Override
			public void onClick(DialogInterface dialog, int which) {
					System.exit(0);
			}
		});
		noticeDialog = builder.create();
		noticeDialog.show();
	}
	
	private static void showDownloadDialog(Context context) {
		  progressDialog   =  new ProgressDialog(context);
		  progressDialog.setTitle("Update software");
		 
		  progressDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
	progressDialog.setButton("Cancel", new OnClickListener() {
		
		@Override
		public void onClick(DialogInterface dialog, int which) {

			 dialog.dismiss();
			 interceptFlag = true;
			 
		}
	});
	progressDialog.show();
	downloadApk();
	
		
		
	}

	private static Runnable mdownApkRunnable = new Runnable() {
		@Override
		public void run() {
			try {
				URL url = new URL(apkUrl);

				HttpURLConnection conn = (HttpURLConnection) url
						.openConnection();
				conn.connect();
				int length = conn.getContentLength();
				InputStream is = conn.getInputStream();

				File file = new File(savePath);
				boolean sdCardExist =Environment.getExternalStorageState()
				.equals(android.os.Environment.MEDIA_MOUNTED);
				if (!sdCardExist)
				{
					showSdDialog(mContext);					
				}
				String apkFile = saveFile;
				File ApkFile = new File(apkFile);
				FileOutputStream fos = new FileOutputStream(ApkFile);

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

				fos.close();
				is.close();
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			}

		}
	};
	

	/**
	 * download apk
	 * 
	 * @param url
	 */

	private static void downloadApk() {
		downLoadThread = new Thread(mdownApkRunnable);
		downLoadThread.start();
	}

	/**
	 * install apk
	 * 
	 * @param url
	 */
	private static void installApk() {
		File apkfile = new File(saveFile);
		if (!apkfile.exists()) {
			return;
		}
		Intent i = new Intent(Intent.ACTION_VIEW);
		i.setDataAndType(Uri.parse("file://" + apkfile.toString()),
				"application/vnd.android.package-archive");
		mContext.startActivity(i);

	}
	
}
