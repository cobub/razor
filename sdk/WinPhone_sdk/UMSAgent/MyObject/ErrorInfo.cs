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

namespace UMSAgent.MyObject
{
    public class ErrorInfo
    {
        private string errorOsVersion;
        private string errorStackTrace;
        private string errorTime;
        private string errorVersion;
        private string errorDeviceId;
        private string errorPage;
        private string errorAppkey;

        public string appkey
        {
            get { return errorAppkey; }
            set { errorAppkey = value; }
        }
        public string version
        {
            get { return errorVersion; }
            set { errorVersion = value; }
        }
        public string deviceid
        {
            get { return errorDeviceId; }
            set { errorDeviceId = value; }
        }
        public string stacktrace
        {
            get { return errorStackTrace; }
            set { errorStackTrace = value; }
        }
        public string time
        {
            get { return errorTime; }
            set { errorTime = value; }
        }
        public string activity
        {
            get { return errorPage; }
            set { errorPage = value; }
        }
        public string os_version
        {
            get { return errorOsVersion; }
            set { errorOsVersion = value; }
        }

        
    }
}
