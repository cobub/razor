package com.tesla.tmd;

import org.json.JSONException;
import org.json.JSONObject;

import junit.framework.Assert;
import android.test.AndroidTestCase;

public class Test extends AndroidTestCase {
	
//	public void testAPPINFO_getAppKey1(){
//		String key =AppInfo.getAppKey(null);
//		Assert.assertEquals("", key);
//	}
//	
//	public void testAPPINFO_getAppKey2(){
//		String key =AppInfo.getAppKey(getContext());
//		Assert.assertEquals("1898f6bbdbd39ac0d89b21ce0f9b7226", key);
//	}
//	public void testAPPINFO_getAppVersion1(){
//		String version = AppInfo.getAppVersion(null);
//		Assert.assertEquals("", version);
//	}
//	public void testAPPINFO_getAppVersion2(){
//		String version = AppInfo.getAppVersion(getContext());
//		Assert.assertEquals("1.0", version);
//	}
//	public void testClientdataMManager_prepareClientdataJSON1(){
//		ClientdataManager cm = new ClientdataManager(getContext());
//		try {
//			UmsConstants.mProvideGPSData = true;
//			JSONObject obj = cm.prepareClientdataJSON();
//			Assert.assertEquals(true, obj.has("latitude")&&obj.has("longitude"));
//		} catch (JSONException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		}
//	}
//	public void testClientdataMManager_prepareClientdataJSON2(){
//		ClientdataManager cm = new ClientdataManager(getContext());
//		try {
//			UmsConstants.mProvideGPSData = false;
//			JSONObject obj = cm.prepareClientdataJSON();
//			Assert.assertEquals(false, obj.has("latitude")&&obj.has("longitude"));
//		} catch (JSONException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		}
//	}
//	public void testCommonUtil_checkPermissions1(){
//		boolean flag = CommonUtil.checkPermissions(getContext()
//				, "android.permission.INTERNET");
//		Assert.assertEquals(true, flag);
//	}
//	public void testCommonUtil_checkPermissions2(){
//		boolean flag = CommonUtil.checkPermissions(getContext()
//				, "android.permission.INTERNET");
//		Assert.assertEquals(false, flag);
//	}
	
//	//第一次 先绑定 再获取  uid为 testuser
//	public void testCommonUtil_getUserIdentifier1(){
//		UmsAgent.bindUserIdentifier(getContext(), "testuser");
//		String uid = CommonUtil.getUserIdentifier(getContext());
//		Assert.assertEquals("testuser", uid);
//	}
//	//直接获取到uid
//	public void testCommonUtil_getUserIdentifier2(){
//		String uid = CommonUtil.getUserIdentifier(getContext());
//		Assert.assertEquals("testuser", uid);
//	}
	//先设置在获取
//	public void testCommonUtil_setUserIdentifier(){
//		CommonUtil.setUserIdentifier(getContext(), "testuserid2");
//		Assert.assertEquals("testuserid2", CommonUtil.getUserIdentifier(getContext()));
//	}
	
//	public void testCommonUtil_isNetworkAvailable(){
//		boolean flag = CommonUtil.isNetworkAvailable(getContext());
//		Assert.assertEquals(true, flag);
//	}
//	public void testCommonUtil_isNetworkAvailable2(){
//		boolean flag = CommonUtil.isNetworkAvailable(getContext());
//		Assert.assertEquals(false, flag);
//	}
	//
//	public void testCommonUtil_getActivityName(){
//		String activity_name = CommonUtil.getActivityName(null);
//		Assert.assertEquals("", activity_name);
//	}
	
//	public void testCommonUtil_getActivityName1(){
//		String activity_name = CommonUtil.getActivityName(getContext());
//		Assert.assertEquals("CopyOfMainActivity", activity_name);
//	}
	
//	public void testCommonUtil_getCurVersionCode(){
//		String vcode = CommonUtil.getCurVersionCode(getContext());
//		Assert.assertEquals("1", vcode);
//	}
//	
//	public void testCommonUtil_getCurVersionCode1(){
//		String vcode = CommonUtil.getCurVersionCode(null);
//		Assert.assertEquals("", vcode);
//	}
	
//	public void testCommonUtil_getNetworkType(){
//		String type = CommonUtil.getNetworkType(getContext());
//		Assert.assertEquals("UNKNOWN", type);
//	}
//	public void testCommonUtil_getNetworkType1(){
//		String type = CommonUtil.getNetworkType(getContext());
//		Assert.assertEquals("CDMA", type);
//	}
//	public void testCommonUtil_getNetworkType2(){
//		String type = CommonUtil.getNetworkType(getContext());
//		Assert.assertEquals("GPRS", type);
//	}
	
//	public void testCommonUtil_isNewSession(){
//		boolean flag = CommonUtil.isNewSession(null);
//		Assert.assertEquals(false, flag);
//	}
//	public void testCommonUtil_isNewSession1(){
//		boolean flag = CommonUtil.isNewSession(getContext());
//		Assert.assertEquals(true, flag);
//	}
	
//	public void testCommonUtil_isNetworkTypeWifi(){
//		boolean flag = CommonUtil.isNetworkTypeWifi(null);
//		Assert.assertEquals(false, flag);
//	}
//	public void testCommonUtil_isNetworkTypeWifi1(){
//		boolean flag = CommonUtil.isNetworkTypeWifi(getContext());
//		Assert.assertEquals(true, flag);
//	}
//	public void testCommonUtil_isNetworkTypeWifi2(){
//		boolean flag = CommonUtil.isNetworkTypeWifi(getContext());
//		Assert.assertEquals(false, flag);
//	}
	
	
	
}
