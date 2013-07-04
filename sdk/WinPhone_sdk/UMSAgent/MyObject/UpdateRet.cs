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
    public class UpdateRet:CommonRet
    {
        private string m_time;
        private string m_version;
        private string m_description;
        private string m_fileurl;
        public string time
        {
            set { m_time = value; }
            get { return m_time; }
        }
        public string version
        {
            set { m_version = value; }
            get { return m_version; }
        }
        public string description
        {
            set { m_description = value; }
            get { return m_description; }
        }
        public string fileurl
        {
            set { m_fileurl = value; }
            get { return m_fileurl; }
        }

    }
}
