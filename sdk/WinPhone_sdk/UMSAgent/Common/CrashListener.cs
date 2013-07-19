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
using System.IO;
using System.IO.IsolatedStorage;
using UMSAgent.MyObject;
using System.Collections.Generic;
using Microsoft.Phone.Info;
using UMSAgent.UMS;
namespace UMSAgent.Common
{
    public class CrashListener
    {

        const string filename = "ums_error_log.txt";

        //internal static void ReportException(Exception ex, string extra)
        //{

        //    try
        //    {
        //        using (var store = IsolatedStorageFile.GetUserStoreForApplication())
        //        {
        //            SafeDeleteFile(store);

        //            using (TextWriter output = new StreamWriter(store.CreateFile(filename)))
        //            {
        //                ErrorInfo error = new ErrorInfo();
                        
        //                error.appkey = UmsManager.appkey;
        //                //error.stacktrace = ex.Message+"\r\n"+ex.StackTrace;
        //                error.stacktrace = ex.StackTrace == null ? "" : ex.StackTrace;
        //                error.time = Utility.getTime();
                        
        //                error.version = Utility.getApplicationVersion();
        //                error.activity = Utility.getCurrentPageName();
        //                error.deviceid = Utility.getDeviceName();
        //                error.os_version = Utility.getOsVersion();
                        
        //                string str =UmsJson.Serialize(error);
        //                output.WriteLine(str);
        //            }

        //        }

        //    }

        //    catch (Exception)
        //    {

        //    }

        //}

        internal static void ReportException(String error, string extra)
        {

            try
            {

                using (var store = IsolatedStorageFile.GetUserStoreForApplication())
                {

                    SafeDeleteFile(store);

                    using (TextWriter output = new StreamWriter(store.CreateFile(filename)))
                    {
                        output.WriteLine(error);
                    }

                }

            }

            catch (Exception)
            {

            }

        }



        internal static string CheckForPreviousException()
        {
            string contents = null;
            try
            {
                

                using (var store = IsolatedStorageFile.GetUserStoreForApplication())
                {

                    if (store.FileExists(filename))
                    {

                        using (TextReader reader = new StreamReader(store.OpenFile(filename, FileMode.Open, FileAccess.Read, FileShare.None)))
                        {

                            contents = reader.ReadToEnd();

                        }

                       // SafeDeleteFile(store);

                    }

                }

                if (contents != null)
                {

                    //handle crash msg
                    
                }

            }

            catch (Exception)
            {

            }

            finally
            {

               // SafeDeleteFile(IsolatedStorageFile.GetUserStoreForApplication());

            }
            return contents;

        }



        public static void SafeDeleteFile(IsolatedStorageFile store)
        {

            try
            {

                store.DeleteFile(filename);

                IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;
                if (settings.Contains("errordata"))
                {
                    settings.Remove("erroedata");
                }

            }

            catch (Exception)
            {

            }

        }

    }

}
