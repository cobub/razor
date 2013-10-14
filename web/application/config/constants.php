<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('CHART_BG_COLOR', '#FFFFFF');
define('CHART_LINE_1','#3D5C56');
define('CHART_LINE_2','#be6128');
define('CHART_LINE_3','#285fec');
define('CHART_LINE_4','#9850ec');
define('CHART_LINE_5','#678782');

define('CHART_LABEL_COLOR','#757a8a');
define('REPORT_TOP_TEN',10);

//柱状图颜色及高度，最大长度
define('BLOCK_COLOR','rgb(116, 119, 213)');
define('BLOCK_HEIGHT','15px');
define('BLOCK_MAX_LENGTH', '550');

//获取记录最大数目
define('RECORD_NUM','10000');

//每页显示记录条目
define('PAGE_NUMS', '10');


/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//acl controller const
define('VIEW',1);
define('UMS_USER',2);
/* End of file constants.php */
/* Location: ./application/config/constants.php */
// define('SERVER_BASE_URL','http://192.168.1.4/ucenter');
define('SERVER_BASE_URL','http://dev.cobub.com/users');
