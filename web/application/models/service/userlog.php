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
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Userlog extends CI_Model
{
    /**
     * Userlog function,to pre_load database configration
     *
     * @return void
     */
    function Userlog()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * AddUserlog function
     *
     * @param int $content content
     *
     * @return bool
     */
    function addUserlog($content)
    {
        $this->load->model('servicepublicclass/errorlogpublic', 'errorlogpublic');
        $userlog = new errorlogpublic();
        $userlog->loaderrorlog($content);
          $nowtime = date('Y-m-d H:i:s');
        if (isset($userlog->time)) {
            $nowtime = $userlog->time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') ||
                strtotime($nowtime) == ''
            ) {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $strArr = explode("\n", $userlog->stacktrace);
        if (count($strArr) >= 3) {
            $title = $strArr[0] . "\n" . $strArr[1] . "\n" . $strArr[2];
        } else {
            $title = $strArr[0];
        }
		
      
        $insertdate = date('Y-m-d H:i:s');
        $data = array(
            'appkey' => $userlog->appkey,
            'device' => $userlog->devicename,
            'os_version' => $userlog->os_version,
            'activity' => $userlog->activity,
            'time' => $nowtime,
            'title' => $title,
            'stacktrace' => $userlog->stacktrace,
            'version' => isset($userlog->version) ? $userlog->version : '',
            'error_type' => isset($userlog->error_type) ? $userlog->error_type : 0,
            'session_id' => isset($userlog->session_id) ? $userlog->session_id : '',
            'useridentifier' => isset($userlog->useridentifier) ? $userlog->useridentifier : '',
            'lib_version' => isset($userlog->lib_version) ? $userlog->lib_version : '',
            'deviceid' => isset($userlog->deviceid) ? $userlog->deviceid : '',
            'dsymid' => isset($userlog->dsymid) ? $userlog->dsymid : '',
            'cpt' => isset($userlog->cpt) ? $userlog->cpt : '',
            'bim' => isset($userlog->bim) ? $userlog->bim : '',
            'insertdate' => $insertdate
        );
		
		$data = $this->db->escape_str($data);
		
        $this->db->insert('errorlog', $data);
    }
}
?>