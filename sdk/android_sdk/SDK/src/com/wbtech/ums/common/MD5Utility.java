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
package com.wbtech.ums.common;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;


import android.util.Log;

public class MD5Utility {
	
 public static String md5Appkey(String str)
 {
	/*str   To encrypt a string
	 * parmboolean  string's length，true false
	 * **/ 
	    try
	    {
	      MessageDigest localMessageDigest = MessageDigest.getInstance("MD5");
	      localMessageDigest.update(str.getBytes());
	      byte[] arrayOfByte = localMessageDigest.digest();
	      StringBuffer localStringBuffer = new StringBuffer();
	      for (int i = 0; i < arrayOfByte.length; i++)
	      {
	        int j = 0xFF & arrayOfByte[i];
	        if (j < 16)
	          localStringBuffer.append("0");
	        localStringBuffer.append(Integer.toHexString(j));
	      }
	      return localStringBuffer.toString();
	    }
	    catch (NoSuchAlgorithmException localNoSuchAlgorithmException)
	    {
	        CommonUtil.printLog("MD5Utility", "getMD5 error");
	        localNoSuchAlgorithmException.printStackTrace();
	    }
	    return "";
	  }

}
