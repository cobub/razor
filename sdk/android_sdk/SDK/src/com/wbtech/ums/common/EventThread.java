/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */
package com.wbtech.ums.common;

import com.wbtech.ums.UmsAgent;

import android.content.Context;
/**
 * event 
 * @author Administrator
 *
 */
public class EventThread extends Thread{
	private static final Object eventObject = new Object();
	  private Context paramContext;
	  private String eventID;
	  private String label;
	  private String umsAppkey;
	  private int acc;
	public  EventThread(Context paramContext, String appkey,String event_id, String label, int acc)
	  {
	    this.paramContext = paramContext;
	    this.eventID = event_id;
	    this.label = label;
	    this.umsAppkey = appkey;
	    this.acc = acc;
	  }
	@Override
	public void run() {
		try {
			synchronized (eventObject){
				UmsAgent.saveEvent(UmsAgent.getUmsAgent(),paramContext, umsAppkey, eventID, label, acc);				
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
