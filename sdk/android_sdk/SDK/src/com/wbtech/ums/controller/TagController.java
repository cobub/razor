package com.wbtech.ums.controller;

import android.content.Context;
import android.os.Handler;
import android.util.Log;

import com.wbtech.ums.common.AssembJSONObj;
import com.wbtech.ums.common.CommonUtil;
import com.wbtech.ums.common.NetworkUitlity;
import com.wbtech.ums.common.UmsConstants;
import com.wbtech.ums.dao.JSONParser;
import com.wbtech.ums.dao.Poster;
import com.wbtech.ums.objects.MyMessage;

import org.json.JSONObject;

public class TagController {
    
    static final private String POSTURL = UmsConstants.preUrl + UmsConstants.tagUser;
    
    static public void PostTag(Context context,String tags,Handler handler) {
        JSONObject object = AssembJSONObj.getpostTagsJSONObj(Poster.GenerateTagObj(context, tags));
        
        if (1 == CommonUtil.getReportPolicyMode(context)
                && CommonUtil.isNetworkAvailable(context)) {

            String result = NetworkUitlity.Post(
                    POSTURL, 
                    object.toString());
            
            MyMessage message = JSONParser.parse(result);
            
            if (!message.isFlag()) {
                CommonUtil.saveInfoToFile(handler,"tags", object, context);
            }
        } else {
            CommonUtil.saveInfoToFile(handler,"tags", object, context);
        }
    }

}
