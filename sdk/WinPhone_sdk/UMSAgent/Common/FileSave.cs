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
using UMSAgent.MyObject;
using System.IO.IsolatedStorage;
using System.Collections.Generic;

namespace UMSAgent.Common
{
    public class FileSave
    {
       
        public static void saveFile(int type, object obj)
        {
            IsolatedStorageSettings settings = IsolatedStorageSettings.ApplicationSettings;
            switch (type)
            {
                case (int)UMSApi.DataType.CLIENTDATA:// client data
                    List<ClientData> list_clientdata = new List<ClientData>();
                    ClientData c = (ClientData)obj;

                    if (settings.Contains("clientdata"))
                    {
                        list_clientdata = (List<ClientData>)settings["clientdata"];
                        list_clientdata.Add(c);
                        settings["clientdata"] = list_clientdata;
                    }
                    else
                    {
                        list_clientdata.Add(c);
                        settings.Add("clientdata", list_clientdata);
                    }
                    settings.Save();
                   // DebugTool.Log("client data list size:" + list_clientdata.Count);
                    break;
                case (int)UMSApi.DataType.EVENTDATA://event data
                    List<Event> list_event = new List<Event>();
                    Event e = (Event)obj;

                    if (settings.Contains("eventdata"))
                    {
                        list_event = (List<Event>)settings["eventdata"];
                        list_event.Add(e);
                        settings["eventdata"] = list_event;
                    }
                    else
                    {
                        list_event.Add(e);
                        settings.Add("eventdata", list_event);

                    }
                    settings.Save();
                    DebugTool.Log("event list size:" + list_event.Count);

                    break;


                case (int)UMSApi.DataType.TAGDATA://tag data
                    List<Tag> list_tag = new List<Tag>();
                    Tag tag = (Tag)obj;

                    if (settings.Contains("tagdata"))
                    {
                        list_tag = (List<Tag>)settings["tagdata"];
                        list_tag.Add(tag);
                        settings["tagdata"] = list_tag;
                    }
                    else
                    {
                        list_tag.Add(tag);
                        settings.Add("tagdata", list_tag);

                    }
                    settings.Save();
                    DebugTool.Log("tag list size:" + list_tag.Count);
                    break;
                case (int)UMSApi.DataType.ERRORDATA://error data
                    
                    break;
                case (int)UMSApi.DataType.PAGEINFODATA://page info data
                    PageInfo pageinfo = (PageInfo)obj;
                    List<PageInfo> list_pageinfo = new List<PageInfo>();
                    if (settings.Contains("pageinfo"))
                    {
                        list_pageinfo = (List<PageInfo>)settings["pageinfo"];
                        list_pageinfo.Add(pageinfo);
                        settings["pageinfo"] = list_pageinfo;
                    }
                    else
                    {
                        list_pageinfo.Add(pageinfo);
                        settings.Add("pageinfo", list_pageinfo);

                    }
                    settings.Save();

                    DebugTool.Log("pageinfo list size:" + list_pageinfo.Count);
                    break;

                default:
                    break;
            }

            if (settings.Contains("hasDateToSend"))
            {
                settings["hasDateToSend"] = "1";
            }
            else
            {
                settings.Add("hasDateToSend", "1");
            }
        
        
        }
    }
}
