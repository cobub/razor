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
    public class Tag
    {
        private string product_key;
        private string tag;
        private string device_id;

        public string productkey
        {
            get { return product_key; }
            set { product_key = value; }
        }

        public string tags
        {
            get { return tag; }
            set { tag = value; }
        }

        public string deviceid
        {
            get { return device_id; }
            set { device_id = value; }
        }
    }
}
