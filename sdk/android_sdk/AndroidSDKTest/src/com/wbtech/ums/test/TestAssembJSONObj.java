
package com.wbtech.ums.test;

import android.test.AndroidTestCase;

import com.wbtech.ums.common.AssembJSONObj;
import com.wbtech.ums.dao.Poster;
import com.wbtech.ums.objects.PostObjTag;

import org.json.JSONException;
import org.json.JSONObject;

public class TestAssembJSONObj extends AndroidTestCase {
    
    public void testGetpostTagsJSONObj() throws JSONException{
       PostObjTag tagobj= Poster.GenerateTagObj(getContext(), "");
       JSONObject object=  AssembJSONObj.getpostTagsJSONObj(tagobj);
       JSONObject object1 = new JSONObject("{\"tags\":\"\",\"deviceid\":\"860856012153353\",\"productkey\":\"sdsdsdddsdsddsds\"}");
       boolean d = false;
       if(object.get("tags").equals(object1.getString("tags"))&&
               object.get("deviceid").equals(object1.getString("deviceid"))&&
                       object.get("productkey").equals(object1.getString("productkey") )){
           d=true;
       }
       assertEquals(d, true);
    }
    
    public void testGetpostTagsJSONObj1() throws JSONException{
        JSONObject object=  AssembJSONObj.getpostTagsJSONObj(null);
        JSONObject object1 = new JSONObject("{\"tags\":\"\",\"deviceid\":\"\",\"productkey\":\"\"}");
        boolean d = false;
        if(object.get("tags").equals(object1.getString("tags"))&&
                object.get("deviceid").equals(object1.getString("deviceid"))&&
                        object.get("productkey").equals(object1.getString("productkey") )){
            d=true;
        }
        assertEquals(d, true);
     }
    
}
