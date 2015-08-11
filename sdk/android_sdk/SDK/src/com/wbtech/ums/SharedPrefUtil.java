/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package     Cobub Razor
 * @author      WBTECH Dev Team
 * @copyright   Copyright (c) 2011 - 2015, NanJing Western Bridge Co.,Ltd.
 * @license     http://www.cobub.com/products/cobub-razor/license
 * @link        http://www.cobub.com/products/cobub-razor/
 * @since       Version 0.1
 * @filesource 
 */
package com.wbtech.ums;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.util.Log;

public class SharedPrefUtil {
    private SharedPreferences sp = null;
    private Editor edit = null;

    @SuppressLint("CommitPrefEdits")
    public SharedPrefUtil(Context context) {
        this.sp = context.getSharedPreferences("CobubRazor_SharedPref", Context.MODE_PRIVATE);
        edit = sp.edit();
    }

//    public void setValue(String key, int value) {
//        edit.putInt(key, value);
//        edit.commit();
//    }

    public void setValue(String key, long value) {
        edit.putLong(key, value);
        edit.commit();
    }
    
    public void removeKey(String key) {
        edit.remove(key);
        edit.commit();
    }

    public void setValue(String key, String value) {
        edit.putString(key, value);
        edit.commit();
    }
    
    public void setValue(String key, Boolean value) {
        edit.putBoolean(key, value);
        edit.commit();
    }

//    public int getValue(String key, int defaultValue) {
//        return sp.getInt(key, defaultValue);
//    }

    public long getValue(String key, long defaultValue) {
        
        return sp.getLong(key, defaultValue);
        
    }

    public String getValue(String key, String defaultValue) {
        return sp.getString(key, defaultValue);
    }
    
    public Boolean getValue(String key, Boolean defaultValue) {
        return sp.getBoolean(key, defaultValue);
    }
}
