
package com.wbtech.ums.test;

import android.content.Context;
import android.test.AndroidTestCase;

import com.wbtech.ums.UmsAgent;
import com.wbtech.ums.dao.JSONParser;
import com.wbtech.ums.objects.MyMessage;
import com.wbtech.ums.objects.SCell;

import junit.framework.TestCase;

public class CobubTest extends AndroidTestCase {
    @Override
    protected void setUp() throws Exception {

    }
    
    public void testBindUserIdentifier(){
        Context context = getContext();
        String uid = UmsAgent.bindUserIdentifier(context, "uid8899");
        assertEquals("uid8899",uid);
    }
    public void testTags(){
        Testtags.testTagJSON();
        Testtags.testTagJSON2();
        Testtags.testTagJSON3();
        Testtags.testTagJSON4();
        Testtags.testTagJSON5();
    }
    public void testOnEvent(){
        TestOnEvent testOnEvent = new TestOnEvent();
        testOnEvent.testPostEventInfo();
        testOnEvent.testPostEventInfo1();
        testOnEvent.testPostEventInfo2();
        testOnEvent.testPostEventInfo3();
        testOnEvent.testPostEventInfo4();
    }
   
    
}
