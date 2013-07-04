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
    public class ConfigRet:CommonRet
    {
        private string Reportpolicy;
        private string Autolocation;
        private string Updateonlywifi;
        private string Sessionmillis;

        public string reportpolicy
        {
            set { Reportpolicy = value; }
            get { return Reportpolicy; }
        }

        public string autogetlocation
        {
            set { Autolocation = value; }
            get { return Autolocation; }
        }

        public string updateonlywifi
        {
            set { Updateonlywifi = value; }
            get { return Updateonlywifi; }
        }

        public string sessionmillis
        {
            set { Sessionmillis = value; }
            get { return Sessionmillis; }
        }
    }
}
