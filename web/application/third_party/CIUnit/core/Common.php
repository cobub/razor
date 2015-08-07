<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Changed some things in here for CIUnit
 */


/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package        CodeIgniter
 * @subpackage    codeigniter
 * @category    Common Functions
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/
 */

// ------------------------------------------------------------------------


/**
 * Class registry
 *
 * This function acts as a singleton. If the requested class does not
 * exist it is instantiated and set to a static variable. If it has
 * previously been instantiated the variable is returned.
 *
 * @access    public
 * @param    string    the class name being requested
 * @param    string    the directory where the class should be found
 * @param    string    the class name prefix
 * @return    object
 */
if (!function_exists('load_class')) {
    function &load_class($class, $directory = 'libraries', $prefix = 'CI_')
    {
        static $_classes = array();

        // Does the class exist? If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        // Look for the class first in the native system/libraries folder
        // thenin the local application/libraries folder
        // then in the ciunit/core folder
        foreach (array(BASEPATH, APPPATH, CIUPATH) as $path) {
            if (file_exists($path . $directory . '/' . $class . '.php')) {
                $name = $prefix . $class;

                if (class_exists($name) === FALSE) {
                    require($path . $directory . '/' . $class . '.php');
                }

                break;
            }
        }

        // Is the request a class extension? If so we load it too
        if (file_exists(APPPATH . $directory . '/' . config_item('subclass_prefix') . $class . '.php')) {
            $name = config_item('subclass_prefix') . $class;

            if (class_exists($name) === FALSE) {
                require(APPPATH . $directory . '/' . config_item('subclass_prefix') . $class . '.php');
            }
        }

        // Does the class have a CIU class extension?
        if (file_exists(CIUPATH . '/core/' . config_item('ciu_subclass_prefix') . $class . '.php')) {
            $name = config_item('ciu_subclass_prefix') . $class;

            if (class_exists($name) === FALSE) {
                require(CIUPATH . '/core/' . config_item('ciu_subclass_prefix') . $class . '.php');
            }
        }

        // Did we find the class?
        if ($name === FALSE) {
            // Note: We use exit() rather then show_error() in order to avoid a
            // self-referencing loop with the Excptions class
            exit('Unable to locate the specified class: ' . $class . '.php');
        }

        // Keep track of what we just loaded
        is_loaded($class);

        $_classes[$class] = new $name();
        return $_classes[$class];
    }
}

// --------------------------------------------------------------------

/**
 * Loads the main config.php file
 *
 * This function lets us grab the config file even if the Config class
 * hasn't been instantiated yet
 *
 * @access    private
 * @return    array
 */
if (!function_exists('get_config')) {
    function &get_config($replace = array())
    {
        static $_config;

        if (isset($_config)) {
            return $_config[0];
        }

        // Is the config file in the environment folder?
        if (!defined('ENVIRONMENT') OR !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/config.php')) {
            $file_path = APPPATH . 'config/config.php';
        }

        // Fetch the config file
        if (!file_exists($file_path)) {
            exit('The configuration file does not exist.');
        }

        require($file_path);

        // Fetch the CIU config file
        if (!file_exists(CIUPATH . 'config/config.php')) {
            exit('The configuration file does not exist.');
        }

        require(CIUPATH . 'config/config.php');

        // Does the $config array exist in the file?
        if (!isset($config) OR !is_array($config)) {
            exit('Your config file does not appear to be formatted correctly.');
        }

        // Are any values being dynamically replaced?
        if (count($replace) > 0) {
            foreach ($replace as $key => $val) {
                if (isset($config[$key])) {
                    $config[$key] = $val;
                }
            }
        }

        return $_config[0] =& $config;
    }
}

/* End of file Common.php */
/* Location: ./application/third_party/CIUnit/core/Common.php */