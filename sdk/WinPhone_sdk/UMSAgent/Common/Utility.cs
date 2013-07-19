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
using System.Threading;
using System.Diagnostics;
using System.Collections.Generic;
using Microsoft.Phone.Info;
using System.Globalization;
using System.Reflection;
using Microsoft.Phone.Controls;
using System.Windows;
using System.Xml;
using System.Net.NetworkInformation;
using UMSAgent.Model;
using System.IO.IsolatedStorage;
using UMSAgent.MyObject;
using UMSAgent.CallBcak;
using System.Device.Location;
using System.Xml.Linq;



namespace UMSAgent.Common
{
    internal class Utility
    {
        public static string session_id = "";
        public IsolatedStorageSettings setting = IsolatedStorageSettings.ApplicationSettings;
        //get current app version
        public static string getApplicationVersion()
        {
            string version = "";
            try
            {
                version = XDocument.Load("WMAppManifest.xml").Root.Element("App").Attribute("Version").Value;
            }
            catch (Exception e)
            {
                DebugTool.Log(e);
            }
             
            return version;
        }

        //check network is connected
        public static bool isNetWorkConnected()
        {
            return NetworkInterface.GetIsNetworkAvailable();

        }
        //check network type
        public static string GetNetStates()
        {
            var info = Microsoft.Phone.Net.NetworkInformation.NetworkInterface.NetworkInterfaceType;
            switch (info)
            {  
                case Microsoft.Phone.Net.NetworkInformation.NetworkInterfaceType.MobileBroadbandCdma:
                    return "CDMA";
                case Microsoft.Phone.Net.NetworkInformation.NetworkInterfaceType.MobileBroadbandGsm:
                    return "CSM";
                case Microsoft.Phone.Net.NetworkInformation.NetworkInterfaceType.Wireless80211:
                    return "WiFi";
                case Microsoft.Phone.Net.NetworkInformation.NetworkInterfaceType.Ethernet:
                    return "Ethernet";
                case Microsoft.Phone.Net.NetworkInformation.NetworkInterfaceType.None:
                    return "None";
                default:
                    return "Other";
            }
        }
        
        //get device id
        public static string getDeviceId()
        {
            string strDeviceUniqueID = "";
            try
            {
                byte[] byteArray = DeviceExtendedProperties.GetValue("DeviceUniqueId") as byte[];
                string strTemp = "";

                foreach (byte b in byteArray)
                {
                    strTemp = b.ToString();
                    if (1 == strTemp.Length)
                    {
                        strTemp = "00" + strTemp;
                    }
                    else if (2 == strTemp.Length)
                    {
                        strTemp = "0" + strTemp;
                    }
                    strDeviceUniqueID += strTemp;
                }

            }
            catch (Exception e)
            {
                DebugTool.Log(e);
            }
            return strDeviceUniqueID;
        }
        //get lati and longi
        public static double[] GetLocationProperty()
        {
            double[] latLong = new double[2];
            try
            {
                GeoCoordinateWatcher watcher = new GeoCoordinateWatcher();
                watcher.TryStart(false, TimeSpan.FromMilliseconds(1000));
                GeoCoordinate coord = watcher.Position.Location;

                if (coord.IsUnknown != true)
                {
                    latLong[0] = coord.Latitude;
                    latLong[1] = coord.Longitude;
                }
            }
            catch (Exception e)
            {
                DebugTool.Log(e);
            }
            
            return latLong;
        }

       
        
        //get current time
        public static string getTime()
        {
            
            return DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss");
        }

        // get page name
        public static string getCurrentPageName()
        {
           // var currentPage = ((App)Application.Current).RootFrame.Content as PhoneApplicationPage;

            string name = "";
            var executingAssembly = System.Reflection.Assembly.GetExecutingAssembly();
            var customAttributes = executingAssembly.GetCustomAttributes(typeof(System.Reflection.AssemblyTitleAttribute), false);
            if (customAttributes != null)
            {
                var assemblyName = customAttributes[0] as System.Reflection.AssemblyTitleAttribute;
                name = assemblyName.Title;
            }
            return name;

        }

        //get os version
        public static string getOsVersion()
        {
            //OperatingSystem os = Environment.OSVersion;
            //return  os.Platform + os.Version.ToString();
            string version = "";
            try
            {
                version = System.Environment.OSVersion.Version.ToString();
            }
            catch(Exception e)
            {
                DebugTool.Log(e);
            }
            return "windows phone " +version;
        }

        //get device resolution
        public static string getResolution()
        {
            try
            {
                double w = System.Windows.Application.Current.Host.Content.ActualWidth;
                double h = System.Windows.Application.Current.Host.Content.ActualHeight;
                return w.ToString() + "*" + h.ToString();
            }
            catch (Exception e)
            {
                DebugTool.Log(e.Message.ToString());
            }
            return "";
        }
        //get device name
        public static string getDeviceName()
        {
            string devicename = "";
            try
            {
                devicename = DeviceExtendedProperties.GetValue("DeviceName").ToString();
            }
            catch(Exception e)
            {
                DebugTool.Log(e);
            }
            return devicename;
        }

        public  static bool isLegal(object o)
        {
            if (o == null)
            {
                return false;
            }
            if ((o is string) && string.IsNullOrEmpty(o as string))
            {
                return false;
            }
            return true;
        }

        //check is exist crash log
        public static bool isExistCrashLog()
        {
            try
            {
                string err_str = CrashListener.CheckForPreviousException();
                ErrorInfo o = UmsJson.Deserialize<ErrorInfo>(err_str);

               
                if (o != null)
                {
                    return true;

                }
            }
            catch (Exception e)
            {
                DebugTool.Log( e.Message);
                return false;
            }
            return false;
        }

    }
}
