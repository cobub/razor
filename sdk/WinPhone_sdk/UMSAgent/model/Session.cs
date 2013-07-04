/**
 * Cobub Razor
 *
 * An open source analytics windows phone sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */

using System;
using System.Net;
using System.IO.IsolatedStorage;
using UMSAgent.Common;
using UMSAgent.Model;
using System.Collections.Generic;
using System.Text;
using UMSAgent.MyObject;
using UMSAgent.CallBcak;
using UMSAgent.UMS;

namespace UMSAgent.Model
{
    internal class Session
    {
        public Dictionary<string, object> pageDictionary;
       
        public string UMS_SESSION_ID = "session_id";
        public DateTime endtime ;

        public void initNewSession()
        {
            this.generateSessionID();
        }
        public static Session initSessionWithOldData()
        {
            IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;

            if (settings.Contains("cobub_session_id") && settings.Contains("closeTime"))
            {
                try
                {
                    Session session = new Session();
                    session.UMS_SESSION_ID = settings["cobub_session_id"].ToString();
                    session.endtime = (DateTime)settings["closeTime"];
                    //DebugTool.Log("old session,session_id is:" + session.UMS_SESSION_ID);
                    return session;
                   
                }
                catch (Exception e)
                {
                    DebugTool.Log(e);
                }
                    
            }
            return null;
        }

        private void generateSessionID()
        {
            try
            {
                string sessionid = Guid.NewGuid().ToString();
                this.UMS_SESSION_ID = sessionid;
                
                IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;

                if (settings.Contains("cobub_session_id"))
                {
                    settings["cobub_session_id"] = sessionid;
                }
                else
                {
                    settings.Add("cobub_session_id", sessionid);
                }
                if (settings.Contains("pages"))
                {
                    settings.Remove("pages");
                }
                if (settings.Contains("current_pages"))
                {
                    settings.Remove("current_pages");
                }
                if (settings.Contains("duration"))
                {
                    settings.Remove("duration");
                }
                settings.Save();
            }
            catch (Exception exception)
            {
                DebugTool.Log(exception);
            }
        }

        public void onPageStart(string pagename)
        {
            if (pageDictionary == null)
            {
                pageDictionary = new Dictionary<string, object>();
            }
            if (pageDictionary.ContainsKey(pagename))
            {
                pageDictionary.Remove(pagename);
            }
            if (pageDictionary.ContainsKey(pagename+"starttime"))
            {
                pageDictionary.Remove(pagename+"starttime");
            }
           
            pageDictionary.Add(pagename, DateTime.Now.Ticks);
            pageDictionary.Add(pagename+"starttime", DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"));
        }

        public void onPageEnd(string pagename)
        {
            if (pageDictionary != null && pageDictionary.ContainsKey(pagename))
            {
                long t1 = (long)this.pageDictionary[pagename];
                long duration = (long)Math.Ceiling((double)(((double)(DateTime.Now.Ticks - t1)) / 10000.0));

                //DebugTool.Log(pagename + " duration:" + duration);
                AllModel model = new AllModel(UmsManager.appkey);
                PageInfo pageInfo = new PageInfo();
                pageInfo.appkey = UmsManager.appkey;
                pageInfo.duration = duration.ToString();
                pageInfo.end_millis = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss");
                pageInfo.start_millis = pageDictionary[pagename+"starttime"].ToString();
                pageInfo.version = Utility.getApplicationVersion();
                pageInfo.activities = pagename;
                pageInfo.session_id = UMS_SESSION_ID;
                pageInfo.version = Utility.getApplicationVersion();
                DataManager ma = new DataManager(UmsManager.appkey);
                ma.appkey = UmsManager.appkey;
                ma.pageInfoDataProceed(pageInfo);
                
            }
        }

    }
}
