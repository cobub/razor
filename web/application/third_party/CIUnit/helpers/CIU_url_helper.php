<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * CodeIgniter URL Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access    public
 * @param    string    the URL
 * @param    string    the method: location or redirect
 * @return    string
 */
if (!function_exists('redirect')) {
    function redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        if (!preg_match('#^https?://#i', $uri)) {
            $uri = site_url($uri);
        }

        switch ($method) {
            case 'refresh'    :
                $GLOBALS['OUT']->set_header("Refresh:0;url=" . $uri);
                break;
            default        :
                $GLOBALS['OUT']->set_header("Location: " . $uri, TRUE, $http_response_code);
                break;
        }
    }
}

/* End of file CIU_url_helper.php */
/* Location: ./application/third_party/CIUnit/helpers/CIU_url_helper.php */