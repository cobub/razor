
package com.wbtech.ums.test;

import android.test.AndroidTestCase;

import com.wbtech.ums.dao.Poster;
import com.wbtech.ums.objects.PostObjTag;

public class TestGenerateTagObj extends AndroidTestCase {
    
    public void testGTO(){
        PostObjTag tagobj= Poster.GenerateTagObj(getContext(), "");
        PostObjTag tag = new PostObjTag();
        tag.setDeviceid("860856012153353");
        tag.setProductkey("sdsdsdddsdsddsds");
        tag.setTags("");
        boolean d=false;
        if(tag.getDeviceid().equals(tagobj.getDeviceid())&&tag.getTags().equals(tagobj.getTags())&&tag.getProductkey().equals(tagobj.getProductkey())){
            d=true;
        }
        assertEquals(d, true);
    }
    public void testGTO1(){
        PostObjTag tagobj= Poster.GenerateTagObj(getContext(), null);
        PostObjTag tag = new PostObjTag();
        tag.setDeviceid("860856012153353");
        tag.setProductkey("sdsdsdddsdsddsds");
        tag.setTags(null);
        boolean d=false;
        if(tag.getDeviceid().equals(tagobj.getDeviceid())&&tag.getTags()==tagobj.getTags()&&tag.getProductkey().equals(tagobj.getProductkey())){
            d=true;
        }
        assertEquals(d, true);
    }
    public void testGTO2(){
        PostObjTag tagobj= Poster.GenerateTagObj(null, "");
        PostObjTag tag = new PostObjTag();
        tag.setDeviceid("");
        tag.setProductkey("");
        tag.setTags("");
        boolean d=false;
        if(tag.getDeviceid().equals(tagobj.getDeviceid())&&tag.getTags().equals(tagobj.getTags())&&tag.getProductkey().equals(tagobj.getProductkey())){
            d=true;
        }
        assertEquals(d, true);
    }
    public void testGTO3(){
        PostObjTag tagobj= Poster.GenerateTagObj(null, null);
        PostObjTag tag = new PostObjTag();
        tag.setDeviceid("");
        tag.setProductkey("");
        tag.setTags(null);
        boolean d=false;
        if(tag.getDeviceid().equals(tagobj.getDeviceid())&&tag.getTags()==tagobj.getTags()&&tag.getProductkey().equals(tagobj.getProductkey())){
            d=true;
        }
        assertEquals(d, true);
    }
    
}
