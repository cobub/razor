package com.wbtech.ums;

//import org.powermock.api.mockito.PowerMockito;

import java.lang.reflect.Field;
import java.lang.reflect.Modifier;

public class Common {

    static void setFinalStatic(Class<?> c,String varName, Object newValue) throws Exception {
//        Field field = PowerMockito.field(c, varName);
//        field.setAccessible(true);
//        // remove final modifier from field
//        Field modifiersField = Field.class.getDeclaredField("modifiers");
//        modifiersField.setAccessible(true);
//        modifiersField.setInt(field, field.getModifiers() & ~Modifier.FINAL);
//
//        field.set(null, newValue);
    }
}
