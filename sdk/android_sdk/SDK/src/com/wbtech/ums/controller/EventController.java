
package com.wbtech.ums.controller;

import android.content.Context;
import android.os.Handler;

import com.wbtech.ums.common.AssembJSONObj;
import com.wbtech.ums.common.CommonUtil;
import com.wbtech.ums.common.NetworkUitlity;
import com.wbtech.ums.common.UmsConstants;
import com.wbtech.ums.dao.JSONParser;
import com.wbtech.ums.objects.MyMessage;
import com.wbtech.ums.objects.PostObjEvent;

import org.json.JSONObject;

public class EventController {
    static final String EVENTURL = UmsConstants.preUrl + UmsConstants.eventUrl;
    public static boolean postEventInfo(Handler handler, Context context, PostObjEvent event) {
        try {
            if (!event.verification())
            {
                CommonUtil.printLog("UMSAgent", "Illegal value of acc in postEventInfo");
                return false;
            }

            JSONObject localJSONObject = AssembJSONObj.getEventJOSNobj( event);

            if (1 == CommonUtil.getReportPolicyMode(context)
                    && CommonUtil.isNetworkAvailable(context)) {
                try {
                    String reslut = NetworkUitlity.Post(EVENTURL, localJSONObject.toString());
                    MyMessage info = JSONParser.parse(reslut);
                    if (!info.isFlag()) {
                        CommonUtil.saveInfoToFile(handler, "eventInfo", localJSONObject, context);
                        return false;
                    }
                } catch (Exception e) {
                    CommonUtil.printLog("UmsAgent", "fail to post eventContent");
                }
            } else {

                CommonUtil.saveInfoToFile(handler, "eventInfo", localJSONObject, context);
                return false;
            }
        } catch (Exception e) {
            CommonUtil.printLog("UMSAgent", "Exception occurred in postEventInfo()");
            e.printStackTrace();
            return false;
        }
        return true;
    }
}
