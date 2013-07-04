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
    public class PageInfo 
        
    {
        private string startTime;
        private string endTime;
        private string pageName;
        private string Version;
        private string Appkey;
        private string Session_id;
        private string Duration;

        public string start_millis
        {
            get { return startTime; }
            set { startTime = value; }
        }

        public string end_millis
        {
            get { return endTime; }
            set { endTime = value; }
        }

        public string activities
        {
            get { return pageName; }
            set { pageName = value; }
        }

        public string version
        {
            get { return Version; }
            set { Version = value; }
        }

        public string session_id
        {
            get { return Session_id; }
            set { Session_id = value; }
        }

        public string appkey
        {
            get { return Appkey; }
            set { Appkey = value; }
        }
        public string duration
        {
            get { return Duration; }
            set { Duration = value; }
        }
    }
}
