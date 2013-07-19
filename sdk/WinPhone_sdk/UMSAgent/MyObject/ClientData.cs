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
using System.Runtime.Serialization;

namespace UMSAgent.MyObject
{
    
    public class ClientData
    {
        private string plat_form;
        private string osversion;
        private string lang;
       // private string appkey;
        private string Resolution;
        private string Deviceid;
        private bool IsMobileDevice;
        private string Devicename;
        private string Defaultbrowser;
        private string Network;
        private string Version;
        private string Time;
        private string Appkey;
        private string Latitude;
        private string Longitude;
        private string Userid;

        public string userid
        {
            get { return Userid; }
            set { Userid = value; }
        }
        
        
        public string platform
        {
            get { return plat_form; }
            set { plat_form = value; }
        }
        public string os_version
        {
            get { return osversion; }
            set { osversion = value; }
        }
        public string language
        {
            get { return lang; }
            set { lang = value; }
        }
        public string resolution
        {
            get { return Resolution; }
            set { Resolution = value; }
        }
        public string deviceid
        {
            get { return Deviceid; }
            set { Deviceid = value; }
        }
        public bool isMobileDevice
        {
            get { return IsMobileDevice; }
            set { IsMobileDevice = value; }
        }
        public string devicename
        {
            get { return Devicename; }
            set { Devicename = value; }
        }
        public string defaultbrowser
        {
            get { return Defaultbrowser; }
            set { Defaultbrowser = value; }
        }
        public string network
        {
            get { return Network; }
            set { Network = value; }
        }
        public string version
        {
            get { return Version; }
            set { Version = value; }
        }
        public string time
        {
            get { return Time; }
            set { Time = value; }
        }
        public string appkey
        {
            get { return Appkey; }
            set { Appkey = value; }
        }
        public string latitude
        {
            get { return Latitude; }
            set { Latitude = value; }
        }
        public string longitude
        {
            get { return Longitude; }
            set { Longitude = value; }
        }

    }
}
