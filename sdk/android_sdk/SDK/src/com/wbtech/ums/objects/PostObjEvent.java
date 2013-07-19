
package com.wbtech.ums.objects;

import android.content.Context;
import android.util.Log;

import com.wbtech.ums.common.CommonUtil;

public class PostObjEvent {
   private String event_id;
   private String label;
   private String acc;
   private String time;
   private String activity;
   private String version;
   private String  appkey;
   private Context context;
    

    public PostObjEvent( PostObjEvent event) {
        super();
        if(event==null){
            this.acc = "";
            this.label ="";
            this.event_id = "";
        }else{
            this.acc = event.getAcc();
            this.label = event.getLabel();
            this.event_id = event.getEvent_id();
        }
        
    }

    public PostObjEvent(String event_id, String label, String acc,Context context) {
        super();
        this.event_id = event_id;
        this.label = label;
        this.acc = acc;
        this.context = context;
        this.time = CommonUtil.getTime();
        this.activity = CommonUtil.getActivityName(context);
        this.appkey = CommonUtil.getAppKey(context);
        this.version = CommonUtil.getVersion(context);
        
    }

    public PostObjEvent(String event_id, String label, String acc, String time, String activity,
            String version, String appkey) {
        super();
        this.event_id = event_id;
        this.label = label;
        this.acc = acc;
        this.time = time;
        this.activity = activity;
        this.version = version;
        this.appkey = appkey;
    }

    public boolean verification() {
        if (this.getAcc().contains("-") || this.getAcc() == null || this.getAcc().equals("")) {
           Log.d("test", this.getAcc());
            return false;
        } else {
            return true;
        }
    }

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getActivity() {
        return activity;
    }

    public void setActivity(String activity) {
        this.activity = activity;
    }

    public String getVersion() {
        return version;
    }

    public void setVersion(String version) {
        this.version = version;
    }

    public String getAppkey() {
        return appkey;
    }

    public void setAppkey(String appkey) {
        this.appkey = appkey;
    }

    public String getEvent_id() {
        return event_id;
    }

    public void setEvent_id(String event_id) {
        this.event_id = event_id;
    }

    public String getLabel() {
        return label;
    }

    public void setLabel(String label) {
        this.label = label;
    }

    public String getAcc() {
        return acc;
    }

    public void setAcc(String acc) {
        this.acc = acc;
    }

    @Override
    public int hashCode() {
        final int prime = 31;
        int result = 1;
        result = prime * result + ((acc == null) ? 0 : acc.hashCode());
        result = prime * result + ((activity == null) ? 0 : activity.hashCode());
        result = prime * result + ((appkey == null) ? 0 : appkey.hashCode());
        result = prime * result + ((event_id == null) ? 0 : event_id.hashCode());
        result = prime * result + ((label == null) ? 0 : label.hashCode());
        result = prime * result + ((time == null) ? 0 : time.hashCode());
        result = prime * result + ((version == null) ? 0 : version.hashCode());
        return result;
    }

    @Override
    public boolean equals(Object obj) {
        if (this == obj)
            return true;
        if (obj == null)
            return false;
        if (getClass() != obj.getClass())
            return false;
        PostObjEvent other = (PostObjEvent) obj;
        if (acc == null) {
            if (other.acc != null)
                return false;
        } else if (!acc.equals(other.acc))
            return false;
        if (activity == null) {
            if (other.activity != null)
                return false;
        } else if (!activity.equals(other.activity))
            return false;
        if (appkey == null) {
            if (other.appkey != null)
                return false;
        } else if (!appkey.equals(other.appkey))
            return false;
        if (event_id == null) {
            if (other.event_id != null)
                return false;
        } else if (!event_id.equals(other.event_id))
            return false;
        if (label == null) {
            if (other.label != null)
                return false;
        } else if (!label.equals(other.label))
            return false;
        if (time == null) {
            if (other.time != null)
                return false;
        } else if (!time.equals(other.time))
            return false;
        if (version == null) {
            if (other.version != null)
                return false;
        } else if (!version.equals(other.version))
            return false;
        return true;
    }
    
}
