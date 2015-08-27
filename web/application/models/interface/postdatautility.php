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

/**
 * Postdatautility Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Postdatautility extends CI_Model
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
    }

    /**
     * post function
     * post data
     *
     * @param string $url      url
     * @param string $postdata postdata
     *
     * @return query result
     */
    function post($url, $postdata)
    {
        $postdata = http_build_query($postdata);
        log_message("debug", $url);
        $opts = array(
                'http' => array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                )
        );
        
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        log_message("debug", $result);
        return $result;
    }
}