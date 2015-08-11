
package com.wbtech.ums;

import static org.junit.Assert.*;

import android.Manifest;
import android.bluetooth.BluetoothAdapter;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.hardware.SensorManager;
import android.location.Location;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.os.Build;
import android.telephony.CellLocation;
import android.telephony.TelephonyManager;

import mockit.*;
import mockit.integration.junit4.JMockit;

import org.junit.Test;
import org.junit.runner.RunWith;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

@RunWith(JMockit.class)

public class testDeviceInfo {

    @Test
    public void testLanguage() {
        final Locale l = Locale.getDefault();
        
        new NonStrictExpectations(Locale.class) {{ 
            Locale.getDefault(); result = l;
            l.getLanguage(); result = "en";
         }};
        assertEquals("en", DeviceInfo.getLanguage());  
        
        new NonStrictExpectations(Locale.class) {{ 
            l.getLanguage(); result = "";
         }}; 
       assertEquals("", DeviceInfo.getLanguage());  
       
       new NonStrictExpectations(Locale.class) {{ 
           l.getLanguage(); result = null;
        }}; 
      assertEquals("", DeviceInfo.getLanguage());  
    }
    
//    @Test
//    public void testResolution() throws Exception {
////        Context context = mock(Context.class);
////        DeviceInfo.init(context);
////        WindowManager wm = mock(WindowManager.class);
////        when(context.getSystemService(Context.WINDOW_SERVICE)).thenReturn(wm);
////        final DisplayMetrics dis = mock(DisplayMetrics.class);
////        Display display = mock(Display.class);
////        
////        when(wm.getDefaultDisplay()).thenReturn(display);
////        
////        PowerMockito.whenNew(DisplayMetrics.class).withNoArguments().thenReturn(dis);
////        
////        Whitebox.setInternalState(dis,"widthPixels",1024);
////        Whitebox.setInternalState(dis,"heightPixels",800);
////        assertEquals("1024x800", DeviceInfo.getResolution());
////        
////        Whitebox.setInternalState(dis,"widthPixels",0);
////        Whitebox.setInternalState(dis,"heightPixels",0);
////        assertEquals("0x0", DeviceInfo.getResolution());
//        
//    }
//
// 
//    

    @Test
    public void testDeviceProduct() throws Exception {
        Deencapsulation.setField(Build.class,"PRODUCT","Samsung 9001");
        
        assertEquals("Samsung 9001", DeviceInfo.getDeviceProduct());
        
        Deencapsulation.setField(Build.class,"PRODUCT","");
        assertEquals("", DeviceInfo.getDeviceProduct());
        
        Deencapsulation.setField(Build.class,"PRODUCT",null);
        assertEquals("", DeviceInfo.getDeviceProduct());
    }
    
    @Mocked Context context;
    @Test
    public void testBluetoothAvailable(@Mocked final BluetoothAdapter b) throws Exception {
        
        new NonStrictExpectations(BluetoothAdapter.class) {{ 
            BluetoothAdapter.getDefaultAdapter(); result = null;
         }};
        
         DeviceInfo.init(context);
        assertEquals(false, DeviceInfo.getBluetoothAvailable());  
        
     
        
        new Expectations(BluetoothAdapter.class) {{ 
            BluetoothAdapter.getDefaultAdapter(); result = b;
         }};
         
        DeviceInfo.init(context);
        assertEquals(true, DeviceInfo.getBluetoothAvailable());
       
    }
    
    @Test
    public void testOSVersion() {
        
        Deencapsulation.setField(Build.VERSION.class,"RELEASE","1.0");
        assertEquals("1.0", DeviceInfo.getOsVersion());
        
        Deencapsulation.setField(Build.VERSION.class,"RELEASE","");
        assertEquals("", DeviceInfo.getOsVersion());
        
        Deencapsulation.setField(Build.VERSION.class,"RELEASE",null);
        assertEquals("", DeviceInfo.getOsVersion());
    }
    
    @Test
    public void testGetDeviceIMSI(@Mocked final TelephonyManager t) {
        new NonStrictExpectations(CommonUtil.class) {{
            
            CommonUtil.checkPermissions(context, Manifest.permission.READ_PHONE_STATE); result = false;
         }};
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getIMSI());
        
        new NonStrictExpectations(CommonUtil.class) {{
            context.getSystemService(Context.TELEPHONY_SERVICE);result = t;
            t.getSubscriberId();result = "";
            CommonUtil.checkPermissions(context, Manifest.permission.READ_PHONE_STATE); result = true;
         }};
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getIMSI());
        
        new NonStrictExpectations(CommonUtil.class) {{
            context.getSystemService(Context.TELEPHONY_SERVICE);result = t;
            t.getSubscriberId();result = "abcdefg";
            CommonUtil.checkPermissions(context, Manifest.permission.READ_PHONE_STATE); result = true;
         }};
        DeviceInfo.init(context);
        assertEquals("abcdefg", DeviceInfo.getIMSI());
        
        
        new NonStrictExpectations(CommonUtil.class) {{
            context.getSystemService(Context.TELEPHONY_SERVICE);result = t;
            t.getSubscriberId();result = null;
            CommonUtil.checkPermissions(context, Manifest.permission.READ_PHONE_STATE); result = true;
         }};
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getIMSI());
        
        new NonStrictExpectations(CommonUtil.class) {{
            context.getSystemService(Context.TELEPHONY_SERVICE);result = null;
            
            CommonUtil.checkPermissions(context, Manifest.permission.READ_PHONE_STATE); result = true;
         }};
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getIMSI());
        
    }

    @Test
    public void testGetPhoneType(@Mocked final TelephonyManager t) {
        new NonStrictExpectations() {{
            context.getSystemService(Context.TELEPHONY_SERVICE);result = t;
            t.getPhoneType();result = 1;

         }};
        DeviceInfo.init(context);
        assertEquals(1, DeviceInfo.getPhoneType());
        
        new NonStrictExpectations() {{
            context.getSystemService(Context.TELEPHONY_SERVICE);result = null;
            

         }};
        DeviceInfo.init(context);
        assertEquals(-1, DeviceInfo.getPhoneType());
    }
    
    @Test
    public void testGetGravityAvailable(@Mocked final SensorManager s) {
        new MockUp<DeviceInfo>(){
            @Mock boolean isSimulator() {
                return true;
            }
        };
        new NonStrictExpectations() {{
            
            context.getSystemService(Context.SENSOR_SERVICE);result = null;
         }};
        DeviceInfo.init(context);
        assertEquals(false, DeviceInfo.getGravityAvailable());
        
        new MockUp<DeviceInfo>(){
            @Mock boolean isSimulator() {
                return false;
            }
        };
        new NonStrictExpectations() {{
            context.getSystemService(Context.SENSOR_SERVICE);result = s;
         }};
        DeviceInfo.init(context);
        assertEquals(true, DeviceInfo.getGravityAvailable());
        
        new NonStrictExpectations() {{
            context.getSystemService(Context.SENSOR_SERVICE);result = null;
         }};
         DeviceInfo.init(context);
         assertEquals(false, DeviceInfo.getGravityAvailable());
    }
    
    @Test
    public void testGetDeviceName() {
        Deencapsulation.setField(Build.class,"MANUFACTURER","");
        Deencapsulation.setField(Build.class,"MODEL","");
        assertEquals("", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER",null);
        Deencapsulation.setField(Build.class,"MODEL",null);
        assertEquals("", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER","");
        Deencapsulation.setField(Build.class,"MODEL",null);
        assertEquals("", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER",null);
        Deencapsulation.setField(Build.class,"MODEL","");
        assertEquals("", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER","Samsang");
        Deencapsulation.setField(Build.class,"MODEL",null);
        assertEquals("Samsang", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER","samsang");
        Deencapsulation.setField(Build.class,"MODEL","9001");
        assertEquals("Samsang 9001", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER","Samsang");
        Deencapsulation.setField(Build.class,"MODEL","Samsang 9001");
        assertEquals("Samsang 9001", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER","Samsang");
        Deencapsulation.setField(Build.class,"MODEL","Samsang 9001");
        assertEquals("Samsang 9001", DeviceInfo.getDeviceName());
        
        Deencapsulation.setField(Build.class,"MANUFACTURER","samsang");
        Deencapsulation.setField(Build.class,"MODEL","samsang 9001");
        assertEquals("Samsang 9001", DeviceInfo.getDeviceName());
        

    }
    
    @Test
    public void testGetWifiMac(@Mocked final WifiManager w,@Mocked final WifiInfo wi) {
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.WIFI_SERVICE);
                result = null;
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getWifiMac());

        new NonStrictExpectations() {
            {
                context.getSystemService(Context.WIFI_SERVICE);
                result = w;
                w.getConnectionInfo();
                result = wi;
                wi.getMacAddress();
                result = "11:11:11:11";
            }
        };
        DeviceInfo.init(context);
        assertEquals("11:11:11:11", DeviceInfo.getWifiMac());

        new NonStrictExpectations() {
            {
                wi.getMacAddress();
                result = "";
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getWifiMac());

        new NonStrictExpectations() {
            {
                wi.getMacAddress();
                result = null;
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getWifiMac());

        new NonStrictExpectations() {
            {
                context.getSystemService(Context.WIFI_SERVICE);
                result = w;
                w.getConnectionInfo();
                result = null;
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getWifiMac());
    }
    
    @Test
    public void testGetNetworktype(@Mocked final ConnectivityManager cm,@Mocked final NetworkInfo ni) {
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.CONNECTIVITY_SERVICE);
                result = cm;
                cm.getActiveNetworkInfo();
                result = ni;
                ni.getTypeName();
                result = "wifi";
            }
        };
        DeviceInfo.init(context);
        assertEquals("wifi", DeviceInfo.getNetworkTypeWIFI2G3G());
        
        new NonStrictExpectations() {
            {
                ni.getTypeName();
                result = "Wifi";
            }
        };
        DeviceInfo.init(context);
        assertEquals("wifi", DeviceInfo.getNetworkTypeWIFI2G3G());
        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.CONNECTIVITY_SERVICE);
                result = cm;
                cm.getActiveNetworkInfo();
                result = ni;
                ni.getTypeName();
                result = "GPRS";
                cm.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);
                result = ni;
                ni.getExtraInfo();
                result = "GPRS";
            }
        };
        DeviceInfo.init(context);
        assertEquals("GPRS", DeviceInfo.getNetworkTypeWIFI2G3G());
        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.CONNECTIVITY_SERVICE);
                result = null;
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getNetworkTypeWIFI2G3G());
        
    }
    
    @Test
    public void testgetLongitude(
            @Mocked final Context context,
            @Mocked final TelephonyManager tm,
            @Mocked final LocationManager lm, @Mocked final Location l) {
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");

                lm.getAllProviders();
                result = matchingProviders;
                lm.getLastKnownLocation("test");
                result = l;
            }
        };
        
        DeviceInfo.init(context);
        assertEquals("0.0", DeviceInfo.getLongitude());
        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");

                lm.getAllProviders();
                result = matchingProviders;
                lm.getLastKnownLocation("test");
                result = null;
            }
        };
        
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getLongitude());
        
//        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");

                lm.getAllProviders();
                result = matchingProviders;
                lm.getLastKnownLocation("test");
                result = l;
//                Deencapsulation.setField(DeviceInfo.class,"location",l);
                l.getLongitude();
                result = 12.89;
            }
        };
        DeviceInfo.init(context);
        assertEquals("12.89", DeviceInfo.getLongitude());
    }
    
    @Test
    public void testgetLatitude(
            @Mocked final Context context,
            @Mocked final TelephonyManager tm,
            @Mocked final LocationManager locationManager,
            @Mocked final Location l) {
        
        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = locationManager;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");
                locationManager.getAllProviders();
                result = matchingProviders;
                locationManager.getLastKnownLocation("test");
                result = null;
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getLatitude());
        
        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = locationManager;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");
                locationManager.getAllProviders();
                result = matchingProviders;
                locationManager.getLastKnownLocation("test");
                result = l;
                l.getLatitude();
                result = 12.89;
            }
        };
        DeviceInfo.init(context);
        assertEquals("12.89", DeviceInfo.getLatitude());
    }
    
    @Test
    public void testGetDeviceID(
            @Mocked final Context context,
            @Mocked final TelephonyManager tm,
            @Mocked final LocationManager lm,
            @Mocked final SharedPreferences preferences,
            @Mocked final Editor editor
            
            ) {
        
        new MockUp<DeviceInfo>(){
            @Mock String getDeviceIMEI() {
                return "imei";
            }
            
            @Mock String getWifiMac() {
                return "mac";
            }
            
            @Mock String getSSN() {
                return "ssn";
            }
            
        };
        
        new Expectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                context.getSharedPreferences("CobubRazor_SharedPref",Context.MODE_PRIVATE);
                result = preferences;
                preferences.getString("uniqueuid", "");
                result = "abcdefg";
                
            }
        };
        DeviceInfo.init(context);
        assertEquals("abcdefg", DeviceInfo.getDeviceId());
        
        
        new Expectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                context.getSharedPreferences("CobubRazor_SharedPref",Context.MODE_PRIVATE);
                result = preferences;
                preferences.getString("uniqueuid", "");
                result = "";
                preferences.edit();
                result = editor;
                editor.putString("uniqueuid", "6afdc7ad1ec7df1bf1debafbc0295227");
                result = editor;
                editor.commit();
                result = true;
                
            }
        };
        DeviceInfo.init(context);
        assertEquals("6afdc7ad1ec7df1bf1debafbc0295227", DeviceInfo.getDeviceId());
        
        new MockUp<DeviceInfo>(){
            @Mock String getDeviceIMEI() {
                return null;
            }
            
            @Mock String getWifiMac() {
                return null;
            }
            
            @Mock String getSSN() {
                return null;
            }
            
        };
        new MockUp<CommonUtil>(){
            @Mock String md5Appkey(String a) {
                return "dcc3e0ab085917fd00596a24d20e8cdb";
            }
        };
        new Expectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                context.getSharedPreferences("CobubRazor_SharedPref",Context.MODE_PRIVATE);
                result = preferences;
                preferences.getString("uniqueuid", "");
                result = "";
                preferences.edit();
                result = editor;
                editor.putString("uniqueuid", "dcc3e0ab085917fd00596a24d20e8cdb");
                result = editor;
                editor.commit();
                result = true;
                
            }
        };
        DeviceInfo.init(context);
        assertEquals("dcc3e0ab085917fd00596a24d20e8cdb", DeviceInfo.getDeviceId());
        
        new MockUp<DeviceInfo>(){
            @Mock String getDeviceIMEI() {
                return null;
            }
            
            @Mock String getWifiMac() {
                return "1";
            }
            
            @Mock String getSSN() {
                return "";
            }
            
        };
        new Expectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                context.getSharedPreferences("CobubRazor_SharedPref",Context.MODE_PRIVATE);
                result = preferences;
                preferences.getString("uniqueuid", "");
                result = "";
                preferences.edit();
                result = editor;
                editor.putString("uniqueuid", "dcc3e0ab085917fd00596a24d20e8cdb");
                result = editor;
                editor.commit();
                result = true;
                
            }
        };
        DeviceInfo.init(context);
        assertEquals("dcc3e0ab085917fd00596a24d20e8cdb", DeviceInfo.getDeviceId());
        
        new MockUp<DeviceInfo>(){
            @Mock String getDeviceIMEI() {
                return null;
            }
            
            @Mock String getWifiMac() {
                return null;
            }
            
            @Mock String getSSN() {
                return "2";
            }
            
        };
        new Expectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = lm;
                context.getSharedPreferences("UMS_sessionID",Context.MODE_PRIVATE);
                result = preferences;
                preferences.getString("uniqueuid", "");
                result = "";
                preferences.edit();
                result = editor;
                editor.putString("uniqueuid", "dcc3e0ab085917fd00596a24d20e8cdb");
                result = editor;
                editor.commit();
                result = true;
                
            }
        };
        DeviceInfo.init(context);
        assertEquals("dcc3e0ab085917fd00596a24d20e8cdb", DeviceInfo.getDeviceId());
        
    }
    
    @Test
    public void testGetGPSAvailable( 
            @Mocked final Context context,
            @Mocked final TelephonyManager tm,
            @Mocked final LocationManager locationManager,
            @Mocked final Location location) {
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = locationManager;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");
                locationManager.getAllProviders();
                result = matchingProviders;
                locationManager.getLastKnownLocation("test");
                result = null;
            }
        };
        DeviceInfo.init(context);
        assertEquals("false", DeviceInfo.getGPSAvailable());
        
        new NonStrictExpectations() {
            {
                locationManager.getLastKnownLocation("test");
                result = location;
            }
        };
        DeviceInfo.init(context);
        assertEquals("true", DeviceInfo.getGPSAvailable());
        
    }
    
    @Test
    public void testCellID( @Mocked final Context context,
            @Mocked final TelephonyManager tm,
            @Mocked final LocationManager locationManager,
            @Mocked final Location location,
            @Mocked final CellLocation cellLocation) {
        
        new NonStrictExpectations() {
            {
                context.getSystemService(Context.TELEPHONY_SERVICE);
                result = tm;
                context.getSystemService(Context.LOCATION_SERVICE);
                result = locationManager;
                
                List<String> matchingProviders = new ArrayList<String>();
                matchingProviders.add("test");
                locationManager.getAllProviders();
                result = matchingProviders;
                locationManager.getLastKnownLocation("test");
                result = location;
                tm.getCellLocation();
                result = null;
            }
        };
        DeviceInfo.init(context);
        
        new NonStrictExpectations() {
            {
                tm.getCellLocation();
                result = cellLocation;
            }
        };
        DeviceInfo.init(context);
        assertEquals("", DeviceInfo.getMCCMNC());
        
        new NonStrictExpectations() {
            {
                tm.getNetworkOperator();
                result = "46001";
            }
        };
        DeviceInfo.init(context);
        assertEquals("46001", DeviceInfo.getMCCMNC());

    }
}

