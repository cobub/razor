
package com.wbtech.test_sample;

//import com.wbtech.ums.UmsAgent.SendPolicy;
//import com.wbtech.ums.UmsAgent.SendPolicy;

//import com.tesla.tmd.UmsAgent;
//import com.tesla.tmd.UmsAgent.SendPolicy;

//import com.tesla.tmd.UmsAgent;
//import com.tesla.tmd.UmsAgent.SendPolicy;

//import com.tendcloud.tenddata.TCAgent;
import java.util.Random;

import  com.wbtech.ums.UmsAgent;
import com.wbtech.ums.UmsAgent.SendPolicy;

//import com.tesla.tmd.UmsAgent;
//import com.tesla.tmd.UmsAgent.SendPolicy;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
      
/**
 * Before you run App,you need check: 
 * 1.Import appkey (generated in server ) to
 * AndroidManifest.xml such as <meta-data android:name="UMS_APPKEY"
 * android:value="bb08202a625c2b5cae5e2632f604352f "/> 
 * 
 * 2.Permissions in AndroidManifest.xml 
 * <uses-permission
 * android:name="android.permission.INTERNET"/> <uses-permission
 * android:name="android.permission.WRITE_EXTERNAL_STORAGE"/> <uses-permission
 * android:name="android.permission.READ_PHONE_STATE"/> <uses-permission
 * android:name="android.permission.ACCESS_FINE_LOCATION"/> <uses-permission
 * android:name="android.permission.ACCESS_WIFI_STATE"/> <uses-permission
 * android:name="android.permission.GET_TASKS"/> <uses-permission
 * android:name="android.permission.READ_LOGS"/> <uses-permission
 * android:name="android.permission.ACCESS_NETWORK_STATE"/> <uses-permission
 * android:name="android.permission.INSTALL_PACKAGES"/>
 */
public class CobubSampleActivity extends Activity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
       
        Button button_activity = (Button) findViewById(R.id.button_activity);
        Button button_event  = (Button)findViewById(R.id.button_event);
        Button button_error = (Button)findViewById(R.id.button_error);
        /**
         * Call UmsAgent.init(String url) before all other APIs. url:
         * CobubRazor web server
         */        

        
        String appkey = "22add6fdcc0c6788a5c0c8cc5ec5d929";// Demo 
       
        com.wbtech.ums.UmsAgent.init(this,"http://demo.cobub.com/razor/index.php?/ums",appkey);
       
//      UmsAgent.onEvent(CobubSampleActivity.this, "bxfw_YH");
        UmsAgent.setDebugEnabled(true);
        UmsAgent.update(this);
       
       // UmsAgent.setDebugLevel(UmsAgent.LogLevel.Verbose);

        /**
         * When we need to update App every time, we just need to modify
         * VersionCode and upload App APK to server. The UmsAgent.update(Context
         * context) method can check whether there is a new version App
         * automatically, and notify user whether to ungrade SDK if there is a
         * new App SDK. SDK will upgrade after user choose to ungrade. (whether
         * to ungrade according to version code) Call UmsAgent.update(this) in
         * onCreate() method of Activity. In consideration of limitation of the
         * user's traffic, we set that the function of auto prompting is started
         * only under Wi-Fi
         */

        UmsAgent.updateOnlineConfig(this);

        
        UmsAgent.postTags(this,"test tags");

       

        /**
         * Data sending We have two modes: 1.Start sending(recommended)
         * 2.Real-time Sending Start sending(recommended): App only sends a
         * message to server when it starts and all messages produced during the
         * App runtime will be sent to server on next time start. If the App
         * starts without network, then the messages will be stored in local
         * position and App will try to send next time. Real-time Sending : Once
         * App produces a message, sending it to server immediately. Call
         * UmsAgent.setDefaultReportPolicy(Context,int) to select mode of
         * sending data param: int 0:Start sending 1:Real-time Sending
         */
        UmsAgent.setDefaultReportPolicy(this, SendPolicy.POST_NOW);


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
                 */
              String errorinfo = "PasswordErrorException: \n\t错误信息 at com.wbtech.test_sample.CobubSampleActivity";
              UmsAgent.onError(CobubSampleActivity.this, "PasswordErrorException",errorinfo);
              System.out.println(errorinfo);
              
                UmsAgent.onEvent(CobubSampleActivity.this, "gywm_GD");  
                UmsAgent.onEvent(CobubSampleActivity.this, "wdyy_YH");  
                UmsAgent.onEvent(CobubSampleActivity.this, "jrzs_GD");  
                UmsAgent.onEvent(CobubSampleActivity.this, "lxwm_GD");  
                UmsAgent.onEvent(CobubSampleActivity.this, "DLYSJ_YH");  
            }
        });

        button_error.setOnClickListener(new OnClickListener() {

            @Override
            public void onClick(View v) {

                /**
                 * Handle the system exception and sent the crash log to Cobub
                 * Razor
                 */
                int a = 0;
                int b = 100 / a;
                Log.d("Cobub Sample", "divide zero exception "+String.valueOf(b));
                

            }
        });
        
        button_activity.setOnClickListener(new OnClickListener() {
            
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(CobubSampleActivity.this,SecondActivity.class);
                startActivity(intent);
                finish();
            }
        });
    }  

    @Override
    protected void onResume() {
        super.onResume();
        UmsAgent.onResume(this);
    }
    
    

    @Override
    protected void onPause() {
       
        super.onPause();
        UmsAgent.onPause(this);
    }

    @Override
    protected void onDestroy() {

        super.onDestroy();
    }
}
