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
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Ink;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Shapes;
using System.Collections.Generic;
using UMSAgent.Common;
using System.Reflection;
using Microsoft.Phone.Info;
using System.Globalization;
using UMSAgent.MyObject;
using System.IO.IsolatedStorage;
using UMSAgent.UMS;


namespace UMSAgent.Model
{
    public class AllModel
    {
        public string key;
        public string eventid;
       // public ApplicationUnhandledExceptionEventArgs e;
        
        public AllModel(string appkey)
        {
            this.key = appkey;
        }

       //get user event info
        public  Event getEventInfo(string eventid,string pagename,string label="",int acc =1)
        {
            Event e = new Event();
            e.event_identifier = eventid;
            e.activity =HttpUtility.UrlEncode( pagename);
            e.time = Utility.getTime();
            e.appkey = key;
            e.label = HttpUtility.UrlEncode(label);
            e.version = Utility.getApplicationVersion();
            e.acc = acc;
            return e;
        }

        

        ////get online config info
        public OnLineConfig getOnlineConfig()
        {
            OnLineConfig obj = new OnLineConfig();
            obj.appkey = UmsManager.appkey;
            return obj;
        }

        public UpdatePreference getUpdatePreference(string version)
        { 
            UpdatePreference obj = new UpdatePreference();
            obj.appkey = UmsManager.appkey;
            obj.version_code = version;
            return obj;
        
        }

        //get Tag data
        public Tag getTagData(string tags)
        {
            Tag tag = new Tag();
            tag.productkey = UmsManager.appkey;
            tag.tags = HttpUtility.UrlEncode(tags);
            tag.deviceid = Utility.getDeviceId();
            return tag;
        }
      
        //get client data
        public ClientData getClientData()
        {
            ClientData clientdata = new ClientData();
            clientdata.platform = "windows phone";
            clientdata.os_version = Utility.getOsVersion();
            clientdata.language =HttpUtility.UrlEncode( CultureInfo.CurrentCulture.DisplayName);
            clientdata.resolution = UMSApi.device_resolution;
            clientdata.deviceid = Utility.getDeviceId();
            clientdata.devicename = DeviceExtendedProperties.GetValue("DeviceName").ToString();
            clientdata.version = Utility.getApplicationVersion();
            clientdata.appkey = key;
            clientdata.time = Utility.getTime();
            IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;
            clientdata.userid = settings["UserIdentifier"].ToString();
            if (settings["autolocation"].ToString().Equals("1"))
            {
                double[] location = Utility.GetLocationProperty();
                if (location.Length == 2)
                    clientdata.latitude = location[0].ToString() == null ? "" : location[0].ToString();
                else
                    clientdata.latitude = "";
                if (location.Length == 2)
                    clientdata.longitude = location[1].ToString() == null ? "" : location[1].ToString();
                else
                    clientdata.longitude = "";
            }
            else
            {
                clientdata.latitude = "";
                clientdata.longitude = "";
            }
            clientdata.isMobileDevice = true;

            clientdata.network = Utility.GetNetStates();
            clientdata.defaultbrowser = "";
            
            
            return clientdata;
        }

       

        public string getUrl(int type)
        {
            string url = "";
            switch (type)
            {
                case 0://get client data
                    url = Constants.BASEURL + Constants.postClientDataUrl;
                    break;
                case 1://get online config data
                    url = Constants.BASEURL + Constants.getOnlineConfigUrl;
                    break;
                case 2://check new version data
                    url = Constants.BASEURL + Constants.checkNewVersionUrl;
                    break;
                case 3://post user event data
                    url = Constants.BASEURL + Constants.postEventUrl;
                    break;
                case 4:// all data
                    url = Constants.BASEURL + Constants.allDataUrl;
                    break;
                case 5:// error data
                    url = Constants.BASEURL + Constants.errorDataUrl;
                    break;
                case 6:// page info data
                    url = Constants.BASEURL + Constants.postActivityLog;
                    break;
                case 7://tag data
                    url = Constants.BASEURL + Constants.postTag;
                    break;

                default:
                    break;

            }
            return url;

        }
    }
}
