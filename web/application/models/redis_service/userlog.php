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
        $this -> load -> model("redis_service/processor");
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

		
        $insertdate = date('Y-m-d H:i:s');
        $data = array(
        'appkey' => $userlog -> appkey, 
            'device' => $userlog->devicename,
            'os_version' => $userlog->os_version,
            'activity' => $userlog->activity,
            'time' => $userlog->time,
            'title' => $title,
            'stacktrace' => $userlog->stacktrace,
            'version' => isset($userlog->version) ? $userlog->version : '',
            'error_type' => isset($userlog->error_type) ? $userlog->error_type : 0,
            'session_id' => isset($userlog->session_id) ? $userlog->session_id : '',
            'useridentifier' => isset($userlog->useridentifier) ? $userlog->useridentifier : '',
            'lib_version' => isset($userlog->lib_version) ? $userlog->lib_version : '',
            'deviceid' => isset($userlog->deviceid) ? $userlog->deviceid : '',
            'insertdate' => $insertdate
        );
        $this -> redis -> lpush("razor_errors", serialize($data));
        $this -> processor -> process();
    }

}
?>
