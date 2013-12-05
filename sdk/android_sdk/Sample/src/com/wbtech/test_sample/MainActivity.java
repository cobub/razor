package com.wbtech.test_sample;

import com.wbtech.ums.UmsAgent;//

import android.os.Bundle;
import android.app.Activity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

/**
 * 
 * Before you run App,you need check:
 * 
 * 1.Import appkey (generated in server ) to AndroidManifest.xml such as
 * <meta-data android:name="UMS_APPKEY"
 * android:value="bb08202a625c2b5cae5e2632f604352f "/>
 * 
 * 2.Permissions in AndroidManifest.xml
 * 
 * <uses-permission android:name="android.permission.INTERNET"/>
 * <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE"/>
 * <uses-permission android:name="android.permission.READ_PHONE_STATE"/>
 * <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION"/>
 * <uses-permission android:name="android.permission.ACCESS_WIFI_STATE"/>
 * <uses-permission android:name="android.permission.GET_TASKS"/>
 * <uses-permission android:name="android.permission.READ_LOGS"/>
 * <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE"/>
 * <uses-permission android:name="android.permission.INSTALL_PACKAGES"/>
 * 
 */
public class MainActivity extends Activity {

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		final String event_name = "btn_click";
		Button button_event = (Button) findViewById(R.id.button_event);
		Button button_error = (Button) findViewById(R.id.button_error);

		/**
		 * Call UmsAgent.setBaseURL(String url) before all other APIs. url:
		 * CobubRazor web server
		 * 
		 * */
		UmsAgent.setBaseURL("http://192.168.1.55/razor/web/index.php?");
		UmsAgent.onError(this);
		/**
		 * When we need to update App every time, we just need to modify
		 * VersionCode and upload App APK to server.
		 * 
		 * The UmsAgent.update(Context context) method can check whether there
		 * is a new version App automatically, and notify user whether to
		 * ungrade SDK if there is a new App SDK. SDK will upgrade after user
		 * choose to ungrade. (whether to ungrade according to version code)
		 * 
		 * Call UmsAgent.update(this) in onCreate() method of Activity. In
		 * consideration of limitation of the user's traffic, we set that the
		 * function of auto prompting is started only under Wi-Fi
		 * 
		 * */

		UmsAgent.update(this);

		/**
		 * SDK could help you catch exit exception during App usage and send
		 * error report to server.
		 * 
		 * Error report includes App version, OS version, device type and
		 * stacktrace of exception.
		 * 
		 * These data will help you modify App bug.
		 * 
		 * We provide two ways to report error info.
		 * 
		 * One is catched automatically by system and another is passed by
		 * developers.
		 * 
		 * For the former, you need to add android.permission.READ_LOGS
		 * permission in AndroidManifest.xml and call UmsAgent.onError(Context)
		 * in onCreate of Main Activity
		 * 
		 * 
		 * For the latter, developers need to call
		 * UmsAgent.onError(Context,String) and pass error info catched by their
		 * own to the second parameter. You can view error report in product
		 * page of Cobub Razor system.
		 * */
		

		/**
		 * Data sending We have two modes: 1.Start sending(recommended)
		 * 2.Real-time Sending
		 * 
		 * Start sending(recommended): App only sends a message to server when
		 * it starts and all messages produced during the App runtime will be
		 * sent to server on next time start. If the App starts without network,
		 * then the messages will be stored in local position and App will try
		 * to send next time.
		 * 
		 * Real-time Sending : Once App produces a message, sending it to server
		 * immediately.
		 * 
		 * Call UmsAgent.setDefaultReportPolicy(Context,int) to select mode of
		 * sending data param: int 0:Start sending 1:Real-time Sending
		 * 
		 * */
		UmsAgent.setDefaultReportPolicy(this, 1);

		/**
		 * Call UmsAgent.postClientData(this) in onCreate method of every
		 * Activity. Parameter is context of current context. Then, client data
		 * could be posted to Cobub Razor system.
		 * 
		 * */
		UmsAgent.postClientData(this);

		button_event.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {

				/**
				 * Call following UmsAgent.onEvent(Context context,String
				 * event_id) could send event logs to server. It will analyze
				 * times and changing trends of event, eg ad_clicks. For
				 * example, we now monitor the event of ad clicks in App and the
				 * context is MainActivity Event ID is " ad_click" defined in
				 * server . Once ad is clicked, you need call
				 * UmsAgent.onEvent(MainActivity.this,"ad_click") in App Then we
				 * will observe that the "Number of Messages" has changed
				 * according to the Event ID of "ad_click" in server.
				 * 
				 * */
				UmsAgent.onEvent(MainActivity.this, event_name);
			}
		});

		button_error.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {

				/**
				 * Handle the system exception and sent the crash log to Cobub
				 * Razor
				 * */
				int a = 0;
				int b = 100 / a;

			}
		});
	}

	@Override
	protected void onResume() {

		super.onResume();

		/**
		 * 
		 * Call UmsAgent.onResume(Context) in onResume method of every Activity.
		 * The parameter is the current context. This method will read AppKey
		 * from AndroidManifest.xml automatically. Do not pass global
		 * application context.
		 * 
		 * We recommend you Calling this method in all Activities If not,some
		 * informations of corresponding Activities will be lost,eg time
		 * */
		UmsAgent.onResume(this);
	}

	@Override
	protected void onPause() {

		super.onPause();

		/**
		 * Call UmsAgent.onPause(Context) in onPause method of every Activity.
		 * Parameter is current context.
		 * 
		 * We recommend you Calling this method in all Activities If not,some
		 * informations of corresponding Activities will be lost,eg time
		 * */
		UmsAgent.onPause(this);
	}

	@Override
	protected void onDestroy() {

		super.onDestroy();
	}
}
