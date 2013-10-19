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


namespace UMSAgent.Common
{
    internal class Constants
    {
        internal static bool isDebugMode = false;
        internal static TimeSpan sessionTime = new TimeSpan(0, 0, 30);
        internal static string operateSystem = "Windows Phone";
        public static string BASEURL = "";
        internal static string postClientDataUrl = "?/ums/postClientData";
        internal static string checkNewVersionUrl = "?/ums/getApplicationUpdate";
        internal static string getOnlineConfigUrl = "?/ums/getOnlineConfiguration";
        internal static string postEventUrl = "?/ums/postEvent";
        internal static string allDataUrl = "?/ums/uploadLog";
        internal static string errorDataUrl = "?/ums/postErrorLog";
        internal static string postActivityLog = "?/ums/postActivityLog";
        internal static string postTag = "?/ums/postTag";
    }
}
