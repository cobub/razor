package com.wbtech.ums;

import org.json.JSONObject;

public abstract interface UmsOnlineConfigureListener
{
  public abstract void onDataReceived(JSONObject paramJSONObject);
}