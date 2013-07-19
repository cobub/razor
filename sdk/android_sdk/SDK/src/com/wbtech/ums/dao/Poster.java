package com.wbtech.ums.dao;

import android.content.Context;

import com.wbtech.ums.common.CommonUtil;
import com.wbtech.ums.objects.PostObjEvent;

public class Poster {
    
    public static PostObjEvent GenerateEventObj(Context context,PostObjEvent event){
        PostObjEvent event2 = new PostObjEvent(event);
        event2.setActivity(CommonUtil.getActivityName(context));
        event2.setAppkey(CommonUtil.getAppKey(context));
        event2.setTime(CommonUtil.getTime());
        event2.setVersion(CommonUtil.getVersion(context));
        return event2;
    }

}
