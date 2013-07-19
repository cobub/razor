package com.wbtech.ums.controller;

import java.util.Date;

import org.json.JSONObject;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.Handler;

import com.wbtech.ums.common.AssembJSONObj;
import com.wbtech.ums.common.CommonUtil;
import com.wbtech.ums.common.NetworkUitlity;
import com.wbtech.ums.common.UmsConstants;
import com.wbtech.ums.dao.JSONParser;
import com.wbtech.ums.objects.MyMessage;
import com.wbtech.ums.objects.PostObjEvent;

public class EventController {
	static final String EVENTURL = UmsConstants.preUrl + UmsConstants.eventUrl;

	public static boolean postEventInfo(Handler handler, Context context, PostObjEvent event) {
		try {
			if (!event.verification()) {
				CommonUtil.printLog("UMSAgent", "Illegal value of acc in postEventInfo");
				return false;
			}

			JSONObject localJSONObject = AssembJSONObj.getEventJOSNobj(event);
			if (!checkEventOnline(context, event.getEvent_id())) {
				return false;
			}

			if (1 == CommonUtil.getReportPolicyMode(context) && CommonUtil.isNetworkAvailable(context)) {
				try {
					String reslut = NetworkUitlity.Post(EVENTURL, localJSONObject.toString());
					MyMessage info = JSONParser.parse(reslut);
					if (!info.isFlag()) {
						if (info.getFlagCode() == UmsConstants.FLAG_EVENT_NOT_REGISTER) {
							// event not register, cache status for check
							SharedPreferences umsConfig = context.getSharedPreferences(UmsConstants.PREFERENCE_CONFIG,
									Context.MODE_PRIVATE);
							Editor editor = umsConfig.edit();
							editor.putBoolean(UmsConstants.EVENT_ONLINE_PREFIX + event.getEvent_id(), false);
							editor.putLong(UmsConstants.EVENT_ONLINE_UPDATEDATE, new Date().getTime());
							editor.commit();
						} else {
							CommonUtil.saveInfoToFile(handler, "eventInfo", localJSONObject, context);
						}
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

	/**
	 * check the event online status
	 * */
	private static boolean checkEventOnline(Context context, String event_id) {
		SharedPreferences config = context.getSharedPreferences(UmsConstants.PREFERENCE_CONFIG, Context.MODE_PRIVATE);
		boolean onlineStatus = config.getBoolean(UmsConstants.EVENT_ONLINE_PREFIX + event_id, true);
		if (onlineStatus) {
			return true;
		}
		// check the last update date, if more than 1 day, send event again and
		// the status will be saved after event send
		long lastUpdateDate = config.getLong(UmsConstants.EVENT_ONLINE_UPDATEDATE, 0);
		long now = new Date().getTime();
		if (now - lastUpdateDate > 86400000) {
			return true;
		}
		return false;
	}
}
