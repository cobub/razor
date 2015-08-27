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
 * Onlineusermodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Onlineusermodel extends CI_Model
{




    /** 
     * Construct load 
     * Construct function 
     * 
     * @return void 
     */
    function __construct()
    {
        parent::__construct();
        $this -> load -> helper('date');
        $this -> load -> library('redis');
    }
    
    /** 
     * Get online users 
     * GetOnlineUsers function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getOnlineUsers($productId)
    {
        $timezonestimestamp = gmt_to_local(local_to_gmt(), $this -> config -> item('timezones'));
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $ret = array();
        for ($i = 30; $i >= 0; $i--) {
            $dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
            $size = $this -> redis -> hlen("razor_r_u_p_" . $productId . "_" . $dataStr);
            if ($i == 0) {
                $onlinedata = array('minutes' => lang("v_rpt_realtime_now"), 'size' => $size);
            } else {
                $onlinedata = array('minutes' => "- " . $i . lang("v_rpt_realtime_minutes"), 'size' => $size);
            }
            array_push($ret, $onlinedata);
        }
        return $ret;
    }

}
?>