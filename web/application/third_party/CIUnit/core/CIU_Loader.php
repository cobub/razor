<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* fooStack, CIUnit for CodeIgniter
* Copyright (c) 2008-2009 Clemens Gruenberger
* Released under the MIT license, see:
* http://www.opensource.org/licenses/mit-license.php
*/

/*
* CodeIgniter source modified for fooStack / CIUnit
*
* If you use MY_Loader, change the paraent class.
*/

class CIU_Loader extends CI_Loader
{

    /**
     * Load class
     *
     * This function loads the requested class.
     *
     * @param    string    the item that is being loaded
     * @param    mixed    any additional parameters
     * @param    string    an optional object name
     * @return    void
     */
    protected function _ci_load_class($class, $params = NULL, $object_name = NULL)
    {
        // Get the class name, and while we're at it trim any slashes.
        // The directory path can be included as part of the class name,
        // but we don't want a leading slash
        $class = str_replace('.php', '', trim($class, '/'));

        // Was the path included with the class name?
        // We look for a slash to determine this
        $subdir = '';
        if (($last_slash = strrpos($class, '/')) !== FALSE) {
            // Extract the path
            $subdir = substr($class, 0, $last_slash + 1);

            // Get the filename from the path
            $class = substr($class, $last_slash + 1);
        }

        // We'll test for both lowercase and capitalized versions of the file name
        foreach (array(ucfirst($class), strtolower($class)) as $class) {
            $subclass = APPPATH . 'libraries/' . $subdir . config_item('subclass_prefix') . $class . '.php';
            $ciu_subclass = CIUPATH . 'libraries/' . $subdir . config_item('ciu_subclass_prefix') . $class . '.php';

            // Is this a class extension request?
            if (file_exists($ciu_subclass)) {
                $baseclass = BASEPATH . 'libraries/' . ucfirst($class) . '.php';

                if (!file_exists($baseclass)) {
                    log_message('error', "Unable to load the requested class: " . $class);
                    show_error("Unable to load the requested class: " . $class);
                }

                // Safety:  Was the class already loaded by a previous call?
                if (in_array($ciu_subclass, $this->_ci_loaded_files)) {
                    // Before we deem this to be a duplicate request, let's see
                    // if a custom object name is being supplied.  If so, we'll
                    // return a new instance of the object
                    if (!is_null($object_name)) {
                        $CI =& get_instance();
                        if (!isset($CI->$object_name)) {
                            return $this->_ci_init_class($class, config_item('ciu_subclass_prefix'), $params, $object_name);
                        }
                    }

                    $is_duplicate = TRUE;
                    log_message('debug', $class . " class already loaded. Second attempt ignored.");
                    return;
                }

                include_once($baseclass);

                if (file_exists($subclass)) {
                    include_once($subclass);
                }

                include_once($ciu_subclass);
                $this->_ci_loaded_files[] = $ciu_subclass;

                return $this->_ci_init_class($class, config_item('ciu_subclass_prefix'), $params, $object_name);
            }

            // Is this a class extension request?
            if (file_exists($subclass)) {
                $baseclass = BASEPATH . 'libraries/' . ucfirst($class) . '.php';

                if (!file_exists($baseclass)) {
                    log_message('error', "Unable to load the requested class: " . $class);
                    show_error("Unable to load the requested class: " . $class);
                }

                // Safety:  Was the class already loaded by a previous call?
                if (in_array($subclass, $this->_ci_loaded_files)) {
                    // Before we deem this to be a duplicate request, let's see
                    // if a custom object name is being supplied.  If so, we'll
                    // return a new instance of the object
                    if (!is_null($object_name)) {
                        $CI =& get_instance();
                        if (!isset($CI->$object_name)) {
                            return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
                        }
                    }

                    $is_duplicate = TRUE;
                    log_message('debug', $class . " class already loaded. Second attempt ignored.");
                    return;
                }

                include_once($baseclass);
                include_once($subclass);
                $this->_ci_loaded_files[] = $subclass;

                return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
            }

            // Lets search for the requested library file and load it.
            $is_duplicate = FALSE;
            foreach ($this->_ci_library_paths as $path) {
                $filepath = $path . 'libraries/' . $subdir . $class . '.php';

                // Does the file exist?  No?  Bummer...
                if (!file_exists($filepath)) {
                    continue;
                }

                // Safety:  Was the class already loaded by a previous call?
                if (in_array($filepath, $this->_ci_loaded_files)) {
                    // Before we deem this to be a duplicate request, let's see
                    // if a custom object name is being supplied.  If so, we'll
                    // return a new instance of the object
                    if (!is_null($object_name)) {
                        $CI =& get_instance();
                        if (!isset($CI->$object_name)) {
                            return $this->_ci_init_class($class, '', $params, $object_name);
                        }
                    }

                    $is_duplicate = TRUE;
                    log_message('debug', $class . " class already loaded. Second attempt ignored.");
                    return;
                }

                include_once($filepath);
                $this->_ci_loaded_files[] = $filepath;
                return $this->_ci_init_class($class, '', $params, $object_name);
            }

        } // END FOREACH

        // One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir == '') {
            $path = strtolower($class) . '/' . $class;
            return $this->_ci_load_class($path, $params);
        }

        // If we got this far we were unable to find the requested class.
        // We do not issue errors if the load call failed due to a duplicate request
        if ($is_duplicate == FALSE) {
            log_message('error', "Unable to load the requested class: " . $class);
            show_error("Unable to load the requested class: " . $class);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Instantiates a class
     *
     * @param    string
     * @param    string
     * @param    string    an optional object name
     * @return    null
     */
    protected function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL)
    {
        // Is there an associated config file for this class? Note: these should always be lowercase
        if ($config === NULL) {
            // Fetch the config paths containing any package paths
            $config_component = $this->_ci_get_component('config');

            if (is_array($config_component->_config_paths)) {
                // Break on the first found file, thus package files
                // are not overridden by default paths
                foreach ($config_component->_config_paths as $path) {
                    // We test for both uppercase and lowercase, for servers that
                    // are case-sensitive with regard to file names. Check for environment
                    // first, global next
                    if (defined('ENVIRONMENT') AND file_exists($path . 'config/' . ENVIRONMENT . '/' . strtolower($class) . '.php')) {
                        include_once($path . 'config/' . ENVIRONMENT . '/' . strtolower($class) . '.php');
                        break;
                    } elseif (defined('ENVIRONMENT') AND file_exists($path . 'config/' . ENVIRONMENT . '/' . ucfirst(strtolower($class)) . '.php')) {
                        include_once($path . 'config/' . ENVIRONMENT . '/' . ucfirst(strtolower($class)) . '.php');
                        break;
                    } elseif (file_exists($path . 'config/' . strtolower($class) . '.php')) {
                        include_once($path . 'config/' . strtolower($class) . '.php');
                        break;
                    } elseif (file_exists($path . 'config/' . ucfirst(strtolower($class)) . '.php')) {
                        include_once($path . 'config/' . ucfirst(strtolower($class)) . '.php');
                        break;
                    }
                }
            }
        }

        if ($prefix == '') {
            if (class_exists('CI_' . $class)) {
                $name = 'CI_' . $class;
            } elseif (class_exists(config_item('subclass_prefix') . $class)) {
                $name = config_item('subclass_prefix') . $class;
            } else {
                $name = $class;
            }
        } else {
            $name = $prefix . $class;
        }

        // Is the class name valid?
        if (!class_exists($name)) {
            log_message('error', "Non-existent class: " . $name);
            show_error("Non-existent class: " . $class);
        }

        // Set the variable name we will assign the class to
        // Was a custom class name supplied? If so we'll use it
        $class = strtolower($class);

        if (is_null($object_name)) {
            $classvar = (!isset($this->_ci_varmap[$class])) ? $class : $this->_ci_varmap[$class];
        } else {
            $classvar = $object_name;
        }

        // Save the class name and object name
        $this->_ci_classes[$class] = $classvar;
        // Instantiate the class
        $CI =& get_instance();
        if ($config !== NULL) {
            if (!defined('CIUnit_Version')) {
                $CI->$classvar = new $name($config);
            } elseif (!isset($CI->$classvar)) {
                //redesignme: check if we have got one already..
                $CI->$classvar = new $name($config);
            }
        } else {
            if (!defined('CIUnit_Version')) {
                $CI->$classvar = new $name;
            } elseif (!isset($CI->$classvar)) {
                //redesignme: check if we have got one already..
                $CI->$classvar = new $name;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Autoloader
     *
     * The config/autoload.php file contains an array that permits sub-systems,
     * libraries, and helpers to be loaded automatically.
     *
     * This function is public, as it's used in the CI_Controller class.
     * However, there is no reason you should ever needs to use it.
     *
     * @param    array
     * @return    void
     */
    public function ci_autoloader()
    {
        if (defined('ENVIRONMENT') AND file_exists(APPPATH . 'config/' . ENVIRONMENT . '/autoload.php')) {
            // enable multiple autoload during tests
            include(APPPATH . 'config/' . ENVIRONMENT . '/autoload.php');
        } else {
            // enable multiple autoload during tests
            include(APPPATH . 'config/autoload.php');
        }


        if (!isset($autoload)) {
            return FALSE;
        }

        // Autoload packages
        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) {
                $this->add_package_path($package_path);
            }
        }

        // Load any custom config file
        if (count($autoload['config']) > 0) {
            $CI =& get_instance();
            foreach ($autoload['config'] as $key => $val) {
                $CI->config->load($val);
            }
        }

        // Autoload helpers and languages
        foreach (array('helper', 'language') as $type) {
            if (isset($autoload[$type]) AND count($autoload[$type]) > 0) {
                $this->$type($autoload[$type]);
            }
        }

        // A little tweak to remain backward compatible
        // The $autoload['core'] item was deprecated
        if (!isset($autoload['libraries']) AND isset($autoload['core'])) {
            $autoload['libraries'] = $autoload['core'];
        }

        // Load libraries
        if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0) {
            // Load the database driver.
            if (in_array('database', $autoload['libraries'])) {
                $this->database();
                $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
            }

            // Load all other libraries
            foreach ($autoload['libraries'] as $item) {
                $this->library($item);
            }
        }

        // Autoload models
        if (isset($autoload['model'])) {
            $this->model($autoload['model']);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Load View
     *
     * This function is used to load a "view" file.  It has three parameters:
     *
     * 1. The name of the "view" file to be included.
     * 2. An associative array of data to be extracted for use in the view.
     * 3. TRUE/FALSE - whether to return the data or load it.  In
     * some cases it's advantageous to be able to return data so that
     * a developer can process it in some way.
     *
     * @param    string
     * @param    array
     * @param    bool
     * @return    void
     */
    public function view($view, $vars = array(), $return = FALSE)
    {
        if ($return === TRUE) {
            return parent::view($view, $vars, $return);
        }

        $output = $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => TRUE));
        $CI =& get_instance();
        $CI->output->add_output($output);
    }

    // --------------------------------------------------------------------

    /**
     * Load Helper
     *
     * This function loads the specified helper file.
     *
     * @param    mixed
     * @return    void
     */
    public function helper($helpers = array())
    {
        foreach ($this->_ci_prep_filename($helpers, '_helper') as $helper) {
            if (isset($this->_ci_helpers[$helper])) {
                continue;
            }

            $ciu_helper = CIUPATH . 'helpers/' . config_item('ciu_subclass_prefix') . $helper . '.php';

            if (file_exists($ciu_helper)) {
                include_once($ciu_helper);
            }

            $ext_helper = APPPATH . 'helpers/' . config_item('subclass_prefix') . $helper . '.php';

            // Is this a helper extension request?
            if (file_exists($ext_helper)) {
                $base_helper = BASEPATH . 'helpers/' . $helper . '.php';

                if (!file_exists($base_helper)) {
                    show_error('Unable to load the requested file: helpers/' . $helper . '.php');
                }

                include_once($ext_helper);
                include_once($base_helper);

                $this->_ci_helpers[$helper] = TRUE;
                log_message('debug', 'Helper loaded: ' . $helper);
                continue;
            }

            // Try to load the helper
            foreach ($this->_ci_helper_paths as $path) {
                if (file_exists($path . 'helpers/' . $helper . '.php')) {
                    include_once($path . 'helpers/' . $helper . '.php');

                    $this->_ci_helpers[$helper] = TRUE;
                    log_message('debug', 'Helper loaded: ' . $helper);
                    break;
                }
            }

            // unable to load the helper
            if (!isset($this->_ci_helpers[$helper])) {
                show_error('Unable to load the requested file: helpers/' . $helper . '.php');
            }
        }
    }

    // --------------------------------------------------------------------

    /*
    * Can load a view file from an absolute path and
    * relative to the CodeIgniter index.php file
    * Handy if you have views outside the usual CI views dir
    */
    function viewfile($viewfile, $vars = array(), $return = FALSE)
    {
        return $this->_ci_load(
            array('_ci_path' => $viewfile,
                '_ci_vars' => $this->_ci_object_to_array($vars),
                '_ci_return' => $return)
        );
    }

    // --------------------------------------------------------------------

    function reset()
    {
        $this->_ci_cached_vars = array();
        $this->_ci_classes = array();
        $this->_ci_loaded_files = array();
        $this->_ci_models = array();
        $this->_ci_helpers = array();
    }
}

/* End of file CIU_Loader.php */
/* Location: ./application/third_party/CIUnit/core/CIU_Loader.php */