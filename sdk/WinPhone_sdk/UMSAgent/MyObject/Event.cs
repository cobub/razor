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
	public class Event
	{
        private string eventid;
        private string event_time;
        private string event_activity;
        private string event_label;
        private string event_key;
        private string event_version;
        private int event_acc;

        public int acc
        {
            get { return event_acc; }
            set { event_acc = value; }
        }
        public string version
        {
            get { return event_version; }
            set { event_version = value; }
        }
        public string event_identifier
        {
            get { return eventid; }
            set { eventid = value; }
        }

        public string time
        {
            get { return event_time; }
            set { event_time = value; }
        }

        public string activity
        {
            get { return event_activity; }
            set { event_activity = value; }
        }

       

        public string label
        {
            get { return event_label; }
            set { event_label = value; }
        }

        public string appkey
        {
            get { return event_key; }
            set { event_key = value; }
        }

	
	}
}
