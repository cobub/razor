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
using System.Windows;
using UMSAgent.MyObject;
using UMSAgent.Common;

using System.IO.IsolatedStorage;
using UMSAgent.Model;

namespace UMSAgent.UMS
{
    public class UmsManager
    {
        public static string appkey = null;
        Session app_session;
        public static string session_id = "";
        //public string msg;
        public bool readOnlineConfig = false;
        public IsolatedStorageSettings setting = IsolatedStorageSettings.ApplicationSettings;
        //public string repolicy;
        public UserRepolicy userRepolicy;
        AllModel model;
        //AsyncCallBackPro callback;


        public void init()
        {
            model = new AllModel(appkey);
            
            //Session session = Session.initSessionWithOldData();
            //if (this.shouldStartNewSession(session))
            //{

                this.app_session = new Session();
                this.app_session.initNewSession();
                session_id = this.app_session.UMS_SESSION_ID;
                // new Thread(new ParameterizedThreadStart(this.startNewSession)).Start(session);
                initUserRepolicy();
                initUserSetting();
               
            //}
            //else
            //{
            //    this.app_session = session;
            //}

        }

        //init user report repolicy
        private void initUserRepolicy()
        {
            if (userRepolicy == null)
                userRepolicy = new UserRepolicy();
            userRepolicy.setAutoLocation("0");
            userRepolicy.setRepolicy("0");
            userRepolicy.setSessionTime("30");
            userRepolicy.setUpdateOnlyWifi("1");
        }

        //user config read and save 
        private void initUserSetting()
        {
            if (!setting.Contains("UserIdentifier"))
            {
                setting.Add("UserIdentifier", "");

            }
            if (!setting.Contains("hasDateToSend"))
            {
                setting.Add("hasDateToSend", "0");

            }
            
            if (!setting.Contains("repolicy"))
            {
                setting.Add("repolicy", userRepolicy.getRepolicy());
            }
            else
            {
                userRepolicy.setRepolicy((string)setting["repolicy"]);
            }
            if (!setting.Contains("autolocation"))
            {
                setting.Add("autolocation", userRepolicy.getAutoLocation());
            }
            else
            {
                userRepolicy.setAutoLocation((string)setting["autolocation"]);
            }

            if (!setting.Contains("sessiontime"))
            {
                setting.Add("sessiontime", userRepolicy.getSessionTime());
            }
            else
            {
                userRepolicy.setSessionTime((string)setting["sessiontime"]);
            }

            if (!setting.Contains("updateonlywifi"))
            {
                setting.Add("updateonlywifi", userRepolicy.getUpdateOnlyWifi());
            }
            else
            {
                userRepolicy.setUpdateOnlyWifi((string)setting["updateonlywifi"]);
            }
            setting.Save();
        }

      

        private bool shouldStartNewSession(Session session)
        {
            if (session == null)
            {
                //DebugTool.Log("session is null,new a session;");
                
                return true;
            }
            //if (DateTime.Now.Subtract(session.endtime).CompareTo(Constants.sessionTime) > 0)
            //{
            //    DebugTool.Log("session is time out ,new a session;");
            //    return true;
            //}
            //DebugTool.Log("session is useful;");
            //UMSApi.isNewSession = false;
            return false;
        }

        public void addPageStart(string pagename)
        {

            if (this.app_session != null)
            {
                this.app_session.onPageStart(pagename);
            }

        }

        public void addPageEnd(string pagename)
        {

            if (this.app_session != null)
            {
                this.app_session.onPageEnd(pagename);
            }

        }

        //public void onClosing()
        //{
        //    IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;
        //    if (settings.Contains("closeTime"))
        //    {
        //        settings["closeTime"] = DateTime.Now;

        //    }
        //    else
        //    {
        //        settings.Add("closeTime",DateTime.Now);
        //    }
        
        //}
    }
}
