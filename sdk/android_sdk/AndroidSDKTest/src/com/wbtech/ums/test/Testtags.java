package com.wbtech.ums.test;

import android.test.AndroidTestCase;

import com.wbtech.ums.dao.JSONParser;
import com.wbtech.ums.objects.MyMessage;

public class Testtags  extends AndroidTestCase {
    public static void testTagJSON() {
        String str = "{\"flag\":\"1\",\"msg\":\"asdf\"}";
        MyMessage m = JSONParser.parse(str);
        assertEquals(m.isFlag(), true);
       
    }
    
    public static void testTagJSON2() {
        String str = "{\"flag\":\"-1\",\"msg\":\"asdf\"}";
        MyMessage m = JSONParser.parse(str);
        assertEquals(m.isFlag(), false);
       
    }
    
    public static void testTagJSON3() {
        String str = "{\"flag\":\"\",\"msg\":\"asdf\"}";
        MyMessage m = JSONParser.parse(str);
        assertEquals(m.isFlag(), false);
       
    }
    
    public static void testTagJSON4() {
        String str = "";
        MyMessage m = JSONParser.parse(str);
        assertEquals(m.isFlag(), false);
       
    }
    
    public static void testTagJSON5() {
        String str = null;
        MyMessage m = JSONParser.parse(str);
        assertEquals(m.isFlag(), false);
       
    }
}
