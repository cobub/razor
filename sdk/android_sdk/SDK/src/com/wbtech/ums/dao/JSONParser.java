package com.wbtech.ums.dao;

import com.wbtech.ums.common.CommonUtil;
import com.wbtech.ums.objects.MyMessage;

import org.json.JSONException;
import org.json.JSONObject;

public class JSONParser {
    public static MyMessage parse(String str) {
        
        MyMessage message = new MyMessage();
        
        JSONObject jsonObject = null;
        
        try {
            jsonObject =  new JSONObject(str);
            String flag = jsonObject.getString("flag");
            
            if (Integer.parseInt(flag) > 0) {
                message.setFlag(true);
            } else {
                message.setFlag(false);
            }
            message.setMsg(jsonObject.getString("msg")); 
        } catch (JSONException e1) {
            CommonUtil.printLog("JSONParser", e1.toString());
        } catch (NumberFormatException e2) {
            CommonUtil.printLog("JSONParser", e2.toString());
        } catch (Exception e3) {
            CommonUtil.printLog("JSONParser", e3.toString());
        }
        
        return message;
    }
}
