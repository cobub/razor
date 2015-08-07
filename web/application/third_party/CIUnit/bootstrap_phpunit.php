<?php
date_default_timezone_set("Asia/Shanghai");
/*
echo '<pre>';
var_dump($GLOBALS);
echo '</pre>';
exit;
*/

/*
 * ------------------------------------------------------
 *  CIUnit Version
 * ------------------------------------------------------
 */
define('CIUnit_Version', '0.18-dev_for_CI2.0.3');

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
define('ENVIRONMENT', 'testing');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * By default CI runs with error reporting set to -1.
 *
 */

error_reporting(-1);

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 * 
 * NO TRAILING SLASH!
 * 
 * The test should be run from inside the tests folder.  The assumption
 * is that the tests folder is in the same directory path as system.  If
 * it is not, update the paths appropriately.
 */
$system_path = dirname(__FILE__) . '/../../../system';
//$system_path = 'D:/wamp/www/CI_Unit/test/system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 * 
 * The tests should be run from inside the tests folder.  The assumption
 * is that the tests folder is in the same directory as the application
 * folder.  If it is not, update the path accordingly.
 */
$application_folder = dirname(__FILE__) . '/../..';

/*
 *---------------------------------------------------------------
 * VIEW FOLDER NAME
 *---------------------------------------------------------------
 * 
 * If you want to move the view folder out of the application 
 * folder set the path to the folder here. The folder can be renamed
 * and relocated anywhere on your server. If blank, it will default 
 * to the standard location inside your application folder.  If you 
 * do move this, use the full server path to this folder 
 *
 * NO TRAILING SLASH!
 *
 */
$view_folder = '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';


/**
 * --------------------------------------------------------------
 * CIUNIT FOLDER NAME
 * --------------------------------------------------------------
 *
 * Typically this folder will be within the application's third-party
 * folder.  However, you can place the folder in any directory.  Just
 * be sure to update this path.
 *
 * NO TRAILING SLASH!
 *
 */
$ciunit_folder = dirname(__FILE__);

/**
 * --------------------------------------------------------------
 * UNIT TESTS FOLDER NAME
 * --------------------------------------------------------------
 *
 * This is the path to the tests folder.
 */
$tests_folder = dirname(__FILE__) . "/../../../tests";


// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

/* This chdir() causes error when run tests by folder.
	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}
*/

if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path) . '/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/') . '/';

// Is the system path correct?
if (!is_dir($system_path)) {
    exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: " . pathinfo(__FILE__, PATHINFO_BASENAME));
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


// The path to the "application" folder
if (is_dir($application_folder)) {
    define('APPPATH', realpath($application_folder) . '/');
} else {
    if (!is_dir(BASEPATH . $application_folder . '/')) {
        exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: " . SELF);
    }

    define('APPPATH', realpath(BASEPATH . $application_folder) . '/');
}

// The path to the "views" folder
if (is_dir($view_folder)) {
    define ('VIEWPATH', $view_folder . '/');
} else {
    if (!is_dir(APPPATH . 'views/')) {
        exit("Your view folder path does not appear to be set correctly. Please open the following file and correct this: " . SELF);
    }

    define ('VIEWPATH', APPPATH . 'views/');
}

// The path to CIUnit
if (is_dir($ciunit_folder)) {
    define('CIUPATH', $ciunit_folder . '/');
} else {
    if (!is_dir(APPPATH . 'third_party/' . $ciunit_folder)) {
        exit("Your CIUnit folder path does not appear to be set correctly. Please open the following file and correct this: " . SELF);
    }

    define ('CIUPATH', APPPATH . 'third_party/' . $ciunit_folder);
}


// The path to the Tests folder
define('TESTSPATH', realpath($tests_folder) . '/');

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */

// Load the CIUnit CodeIgniter Core
require_once CIUPATH . 'core/CodeIgniter.php';

// Autoload the PHPUnit Framework
require_once('PHPUnit/Autoload.php');

// Load the CIUnit Framework
require_once CIUPATH . 'libraries/CIUnit.php';

//=== and off we go ===
$CI =& set_controller('CIU_Controller', CIUPATH . 'core/');
$CI->load->add_package_path(CIUPATH);

require_once(CIUPATH . 'libraries/spyc/spyc.php');

CIUnit::$spyc = new Spyc();

require_once(CIUPATH . 'libraries/Fixture.php');

$CI->fixture = new Fixture();
CIUnit::$fixture =& $CI->fixture;

/* End of file bootstrap_phpunit.php */
/* Location: ./application/third_party/CIUnit/bootstrap_phpunit.php */

require_once(TESTSPATH . 'beforeTest.php');
$CI->beforeTest = new beforeTest();
