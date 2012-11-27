<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
$lang["installview_installheader"] = "Cobub Razor Setup Wizard";
$lang["installview_logotitle"] = "Mobile Application Analytics System";
$lang["installview_welcomestep"] = "Welcome";

$lang["installview_checkheader"] = "System checks";
$lang["installview_databaseheader"] = "Create Database";
$lang["installview_websiteheader"] = "Create site and superuser";
$lang["installview_finshheader"] = "Complete";

$lang["installview_licensetitle"] = "License Agreement";
$lang["installview_licensecontent1"] = "Please read the license agreement before installing Cobub Razor.";
$lang["installview_licensecontent3"] = "<p>The Cobub Razor package contains HighCharts products. The HighCharts products are not open source products,<br>but under certain conditions are free to use, please look into <a href='http://shop.highsoft.com/highcharts.html' target='_blank'>http://shop.highsoft.com/highcharts.html</a>.</p>";
$lang["installview_licenselink"] = "Read the license agreement";

$lang["installview_checkstep"] = "1、System checks";
$lang["installview_databasestep"] = "2、Create Database";
$lang["installview_websitestep"] = "3、Create site and superuser";
$lang["installview_finshstep"] = "4、Complete";
$lang["installview_nextstep"] = "Next";
$lang["installview_installselectlanguage"] = "Enter the installation";
$lang["installview_installstep"] = "Install";
$lang["installview_acceptcontent"] = "I accept the license attached to Cobub Razor";
$lang["installview_versionerror"] = "Your PHP version is too low, please upgrade your version of PHP";
$lang["installview_mysqlierror"] = "Your mysqli no open";
$lang["installview_writeerror"]="Your file does not have write permissions, please add the file permissions";
$lang["installview_companyname"] = "DEV.COBUB.COM";
//welcome info 
$lang["installview_welcome"] = "Welcome to Cobub Razor!";
$lang["installview_welcomeintro"] = " is a dedicated data analysis software for mobile Apps.";
$lang["installview_welcomedemand"] = "Please follow the instruction to install and deploy your own Cobub Razor System.";
//check info 
$lang["installview_check"] = "System checks";
$lang["installview_checkversion"] = "PHP Version：";
$lang["installview_checkexpand"] = "MySqli Support：";
$lang["installview_checkpermission"] = "Write permissions to the directory:";
//database info 
$lang["installview_datawarn"] = "Cobub Razor highly recommend you create two databases for the performance consideration. One for production, and the other for data warehouse.";
$lang["installview_datawarninfo"] = "You should provide same configurations for production db and data warehouse if you don't have two databases.";
$lang["installview_dataset"] = "Database Settings";
$lang["installview_dataserve"] = "Database server:";
$lang["installview_dataaccount"] = "Database account:";
$lang["installview_datapassword"] = "Database password:";
$lang["installview_dataname"] = "Database name";
$lang["installview_datatablehead"] = "Database table prefix:";
$lang["installview_datadepotset"] = "Data warehouse settings";
$lang["installview_datadepotserve"] = "Data warehouse server:";
$lang["installview_datadepotaccount"] = "Data warehouse account:";
$lang["installview_datadepotpwd"] = "Data warehouse password:";
$lang["installview_datadepotname"] = "Data warehouse name:";
$lang["installview_datadepottablehead"] = "Data warehouse table prefix:";
//user info
$lang["installview_userinfo"] = "Set site and create a administrator";
$lang["installview_userurl"] = "Server URL:";
$lang["installview_userurlreminder"] ="eg: http://example.com/razor";
$lang["installview_userlanguage"] = "Language:";
$lang["installview_userchinese"] = "Chinese";
$lang["installview_userenglish"] = "English";
$lang["installview_timezones"] = "Select Timezones:";
$lang["installview_usersupperaccount"] = "Admin Username:";
$lang["installview_userpwd"] = "Password:";
$lang["installview_userconfirmpwd"]="Confirm Password:";
$lang["installview_useremail"]="E-mail:";
//finsh info 
$lang["installview_finshinform"] = "Installation Complete!";
$lang["installview_finshinfo"] = "You have finished Cobub Razor installation, please click on the link login your site.";
$lang["installview_finshlogin"] = "Log in";
// formverfication
$lang["installview_verficationip"] = "Database server address ";
$lang["installview_verficationdbname"] = "Database name ";
$lang["installview_verficationusername"] = "Database account ";
$lang["installview_verficationpassword"] = "Database password";
$lang["installview_verficationtablehead"] = "Database table prefix";
$lang["installview_verficationconnecterror"] = "Can't connect to the database ";
$lang["installview_verficationdepotip"] = "Data warehouse server address";
$lang["installview_verficationdepotdbname"] = "Data warehouse name ";
$lang["installview_verficationdepotusername"] = "Data warehouse account ";
$lang["installview_verficationdepotpassword"] = "Data warehouse password ";
$lang["installview_verficationdepottablehead"] = "Data warehouse table prefix";
$lang["installview_verficationdepotconnecterror"] = "Can't connect to the data warehouse ";
$lang["installview_verficationsiteurl"] = "Web site address";
$lang["installview_verficationsuperuser"] = "Super-user account";
$lang["installview_verficationpwd"] = "Password";
$lang["installview_verficationverifypwd"] = "Confirm Password";
$lang["installview_verficationemail"] = "E-mail";
$lang["installview_verficationcreatefailed"] = "Database creation failed!";

$lang["installview_innodberror"] = "Please upgrade the database version!";
$lang["installview_innodberrordw"] = "Please upgrade data warehouses version!";
$lang["installview_innodbclose"] = "Please modify database configuration file to start InnoDB!";
$lang["installview_innodbclosedw"] = "Please modify the data warehouse configuration file to start InnoDB!";

$lang["installview_noexistdata"]="You enter the database does not exist, use the database already exists";
$lang["installview_noexistdatadw"]="You enter the data warehouse does not exist, the use of already existing data warehouse";

$lang["installview_finshviewtip"]="Tip: Please look into <a href='http://dev.cobub.com/docs/cobub-razor/auto-archiving/' target='_blank'>http://dev.cobub.com/docs/cobub-razor/auto-archiving/</a> to set the Scheduled Tasks.";
