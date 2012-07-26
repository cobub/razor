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

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import com.wbtech.common.UmsConstants;

import android.util.Log;

public class MD5Utility {
	
 public static String md5Appkey(String paramString)
 {
	/*paramString 要加密的字符串
	 * parmboolean  根据要加密字符串的内容长度，true false
	 * **/ 
	    try
	    {
	      MessageDigest localMessageDigest = MessageDigest.getInstance("MD5");
	      localMessageDigest.update(paramString.getBytes());
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
	      if (UmsConstants.DebugMode)
	      {
	        Log.i("MD5Utility", "getMD5 error");
	        localNoSuchAlgorithmException.printStackTrace();
	      }
	    }
	    return "";
	  }

}
