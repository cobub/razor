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
    public partial class Page2 : PhoneApplicationPage
    {
        public Page2()
        {
            InitializeComponent();
            
        }

        private void button1_Click(object sender, RoutedEventArgs e)
        {
           // NavigationService.Navigate(new Uri("/MainPage.xaml",UriKind.Relative));
            NavigationService.GoBack();
        }
        protected override void OnNavigatedFrom(System.Windows.Navigation.NavigationEventArgs e)
        {
            base.OnNavigatedFrom(e);
            UMSAgent.UMSApi.onPageEnd("second page");
        }

        protected override void OnNavigatedTo(System.Windows.Navigation.NavigationEventArgs e)
        {
            base.OnNavigatedTo(e);
            UMSAgent.UMSApi.onPageBegin("second page");
        }
        
        private void button2_Click(object sender, RoutedEventArgs e)
        {
            
            UMSAgent.UMSApi.getNewVersion();
            //UMSAgent.UMSApi.UpdateEvent += new UMSAgent.UMSApi.UpdateEventHandler(showMsg);
           
            ////this.progressBar1.Visibility = Visibility.Visible;
            ////this.button2.Content = "checking...";
            
        }

       
       

        
    }
}