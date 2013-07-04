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
using System.Diagnostics;


namespace UMSAgent.Common
{
    internal class DebugTool
    {
        public static void Log(string log)
        {
            if (log.Length <= 600)
            {
                UMSLog(log);
            }
            else
            { 
                UMSLog(log.Substring(0,600));
                UMSLog("......");
            }
        }

        public static void Log(Exception e)
        {
            if (e != null)
            {
                Log(e.StackTrace);
            }
        }
        public static void Log(String info,Exception e)
        {
            if (e != null)
            {
                Log(info+e.StackTrace);
            }
        }

        private static void UMSLog(string info)
        {
            if (!string.IsNullOrEmpty(info) && Constants.isDebugMode)
            {
                try
                {
                   // Debug.WriteLine("debug---"+info);
                   typeof(Debug).GetMethod("WriteLine", new Type[] { typeof(string) }).Invoke(null, new object[] { string.Format("debug--->{0}", info) });
                }
                catch (Exception)
                {
                    
                }
            }
        }
    }
}
