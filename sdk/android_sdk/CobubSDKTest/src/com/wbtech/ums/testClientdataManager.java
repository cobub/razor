package com.wbtech.ums;


import static org.junit.Assert.*;
import android.content.Context;
import android.os.Handler;

import mockit.Mocked;
import mockit.NonStrictExpectations;
import mockit.integration.junit4.JMockit;

import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;
import org.junit.runner.RunWith;


@RunWith(JMockit.class)
public class testClientdataManager {

    @Test
    public void test(@Mocked final Context context,@Mocked final Handler handler) throws JSONException {

        new NonStrictExpectations() {
            {
                DeviceInfo.init(context);
                AppInfo.init(context);
            }
        };
        ClientdataManager cm = new ClientdataManager(context);
        JSONObject obj = cm.prepareClientdataJSON();
        String r = obj.toString();
        assertEquals("",r);
    }

}
