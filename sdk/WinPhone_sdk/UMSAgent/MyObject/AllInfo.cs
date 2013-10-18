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
using System.Collections.Generic;

namespace UMSAgent.MyObject
{
    public class AllInfo
    {
        private List<ClientData> clientDataInfo = new List<ClientData>();
        private List<ErrorInfo> errorList = new List<ErrorInfo>();
        private List<Event> eventList = new List<Event>();
        private List<PageInfo> pageInfoList=  new List<PageInfo>();
        private List<Tag> tagList = new List<Tag>();
        private string app_key;
        public string appkey
        {
            set { app_key = value; }
            get { return app_key; }
        
        }
        public List< ClientData> clientData
        {
            get { return clientDataInfo; }
            set { clientDataInfo = value; }
        }
        public List<ErrorInfo> errorInfo
        {
            get { return errorList; }
            set { errorList = value; }
        }
        public List<Event> eventInfo
        {
            get { return eventList; }
            set { eventList = value; }
        }
        public List<PageInfo> activityInfo
        {
            get { return pageInfoList; }
            set { pageInfoList = value; }
        }
        public List<Tag> tagListInfo
        {
            get { return tagList; }
            set { tagList = value; }
        }




    }
}
