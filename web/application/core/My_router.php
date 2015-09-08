<?php

/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 自定义路由类
 * 
 * 让CI控制器支持多级目录 
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
   
class MY_Router extends CI_Router  
{  
    /** 
     * Set_directory function 
     * Set the directory name 
     * 
     * @param  string $dir dir 
     * @return void 
     */  
    function set_directory($dir)  
    {  
        $this->directory = $dir.'/';  
    }  
   
    /** 
     * Validates the supplied segments.  Attempts to determine the path to 
     * the controller. 
     * 
     * @param  array $segments segments
     * @return array 
     */  
   
    function _validate_request($segments)  
    {  
        if (count($segments) == 0) { 
            return $segments;  
        }  
   
        // Does the requested controller exist in the root folder?  
        if (file_exists(APPPATH.'controllers/'.$segments[0].'.php')) {  
            return $segments;  
        }  
   
        // Is the controller in a sub-folder?  
        if (is_dir(APPPATH.'controllers/'.$segments[0])) { 
            $temp = array('dir' => array(), 'path' => APPPATH.'controllers/');  
   
            foreach ($segments as $k => $v) { 
                $temp['path'] .= $v.'/';  
   
                if (is_dir($temp['path'])) { 
                    $temp['dir'][] = $v;  
                    unset($segments[$k]);  
                }  
            }  
   
            $this->set_directory(implode('/', $temp['dir']));  
            $segments = array_values($segments);  
            unset($temp);  
   
            if (count($segments) > 0) { 
                // Does the requested controller exist in the sub-folder?  
                if ( !file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'.php')) { 
                    if (!empty($this->routes['404_override'])) { 
                        $x = explode('/', $this->routes['404_override']);  
   
                        $this->set_directory('');  
                        $this->set_class($x[0]);  
                        $this->set_method(isset($x[1]) ? $x[1] : 'index');  
   
                        return $x;  
                    } else { 
                        show_404($this->fetch_directory().$segments[0]);  
                    }  
                }  
            } else { 
                // Is the method being specified in the route?  
                if (strpos($this->default_controller, '/') !== false) { 
                    $x = explode('/', $this->default_controller);  
   
                    $this->set_class($x[0]);  
                    $this->set_method($x[1]);  
                } else { 
                    $this->set_class($this->default_controller);  
                    $this->set_method('index');  
                }  
   
                // Does the default controller exist in the sub-folder?  
                if (!file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.'.php')) { 
                    $this->directory = '';  
                    return array();  
                }  
   
            }  
   
            return $segments;  
        }  
   
   
        // If we've gotten this far it means that the URI does not correlate to a valid  
        // controller class.  We will now see if there is an override  
        if (!empty($this->routes['404_override'])) { 
            $x = explode('/', $this->routes['404_override']);  
   
            $this->set_class($x[0]);  
            $this->set_method(isset($x[1]) ? $x[1] : 'index');  
   
            return $x;  
        }  
   
   
        // Nothing else to do at this point but show a 404  
        show_404($segments[0]);  
    }  
}  
// END MY_Router Class  
   