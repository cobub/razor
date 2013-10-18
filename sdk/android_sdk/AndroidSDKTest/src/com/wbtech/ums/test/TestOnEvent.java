package com.wbtech.ums.test;

import android.content.Context;
import android.os.Handler;
import android.test.AndroidTestCase;

import com.wbtech.ums.controller.EventController;
import com.wbtech.ums.objects.PostObjEvent;

public class TestOnEvent extends AndroidTestCase {
        Handler handler = new Handler();
    
    public void testPostEventInfo(){
        Context context = getContext();
        PostObjEvent event = new PostObjEvent( "sss", "xx", 23+"",context);
        
        boolean d = EventController.postEventInfo(handler,context,event);
        assertEquals(true,d);
    }
    public void testPostEventInfo1(){
        Context context = getContext();
        PostObjEvent event = new PostObjEvent("sss", "xx", 23.3+"",context);
        boolean d = EventController.postEventInfo(handler,context, event);
        assertEquals(true,d);
    }
    public void testPostEventInfo2(){
        Context context = getContext();
        PostObjEvent event = new PostObjEvent("sss", "xx", -23.3+"",context);
        boolean d = EventController.postEventInfo(handler,context, event);
        assertEquals(false,d);
    }
    public void testPostEventInfo3(){
        Context context = getContext();
        PostObjEvent event = new PostObjEvent("sss", "xx", "",context);
        boolean d = EventController.postEventInfo(handler,context, event);
        assertEquals(false,d);
    }
    public void testPostEventInfo4(){
        Context context = getContext();
        PostObjEvent event = new PostObjEvent("sss", "xx", null,context);
        boolean d = EventController.postEventInfo(handler,context,event );
        assertEquals(false,d);
    }
    
}
