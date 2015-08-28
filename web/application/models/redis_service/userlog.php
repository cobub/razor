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
 * Userlog Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Userlog extends CI_Model
{



    /** 
     * Userlog load 
     * Userlog function 
     * 
     * @return void 
     */
    function Userlog()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> library('redis');
    }
    
    /** 
     * Add userlog to redis 
     * AddUserlog function 
     * 
     * @param string $userlog userlog 
     * 
     * @return void 
     */
    function addUserlog($userlog)
    {
        $strArr = explode("\n", $userlog -> stacktrace);
        if (count($strArr) >= 3) {
            $title = $strArr[0] . "\n" . $strArr[1] . "\n" . $strArr[2];
        } else {
            $title = $strArr[0];
        }

        $data = array('appkey' => $userlog -> appkey, 'title' => $title, 'stacktrace' => $userlog -> stacktrace, 'os_version' => $userlog -> os_version, 'time' => $userlog -> time, 'device' => $userlog -> deviceid, 'activity' => $userlog -> activity, 'version' => isset($userlog -> version) ? $userlog -> version : '');
        $this -> redis -> lpush("razor_errors", serialize($data));
        $this -> processor -> process();
    }

}
?>
