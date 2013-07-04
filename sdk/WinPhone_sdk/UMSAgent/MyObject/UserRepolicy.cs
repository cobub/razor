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
    public class UserRepolicy
    {

        private string autoLocation;
        private string updateOnlyWifi;
        private string sessionTime;
        private string repolicy;

        public string getAutoLocation()
        {
            return autoLocation;
        }
        public void setAutoLocation(string s)
        {
            this.autoLocation = s;
        }

        public string getUpdateOnlyWifi()
        {
            return updateOnlyWifi;
        }
        public void setUpdateOnlyWifi(string s)
        {
            this.updateOnlyWifi = s;
        }

        public string getSessionTime()
        {
            return sessionTime;
        }
        public void setSessionTime(string s)
        {
            this.sessionTime = s;
        }

        public string getRepolicy()
        {
            return repolicy;
        }
        public void setRepolicy(string s)
        {
            this.repolicy = s;
        }

    }
}
