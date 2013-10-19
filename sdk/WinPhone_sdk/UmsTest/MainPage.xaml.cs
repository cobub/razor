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
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Shapes;
using Microsoft.Phone.Controls;


namespace UmsTest
{
    public partial class MainPage : PhoneApplicationPage
    {
        // Constructor
        public MainPage()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, RoutedEventArgs e)
        {
            UMSAgent.UMSApi.onEvent("mycontact", "中文测试");
           // UMSAgent.UMSApi.postTag("有志青年");
        }

        protected override void OnNavigatedFrom(System.Windows.Navigation.NavigationEventArgs e)
        {
            base.OnNavigatedFrom(e);
            UMSAgent.UMSApi.onPageEnd("main page");
        }
        
        protected override void OnNavigatedTo(System.Windows.Navigation.NavigationEventArgs e)
        {
            base.OnNavigatedTo(e);
            UMSAgent.UMSApi.onPageBegin("main page");
        }

        private void button2_Click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/Page2.xaml", UriKind.Relative));
            
        }

        private void button3_Click(object sender, RoutedEventArgs e)
        {
            int i = 0;
            int j = 8 / i;
        }

        private void button4_Click(object sender, RoutedEventArgs e)
        {
            UMSAgent.UMSApi.onEvent("mycontact", "MainPage", "some lable");
        }

        private void button5_Click(object sender, RoutedEventArgs e)
        {
            UMSAgent.UMSApi.onEvent("mycontact", "MainPage", "acc label", 10);
        }

        private void button6_Click(object sender, RoutedEventArgs e)
        {
            CrashObj obj = null;
            this.button6.Content = obj.name;
        }
        public class CrashObj
        {
            public string name;
            public string Name
            {
                get { return name; }
                set { this.name = value; }
            }
        
        }

        private void button7_Click(object sender, RoutedEventArgs e)
        {
            UMSAgent.UMSApi.postTag("购物达人");
        }


    }
}
