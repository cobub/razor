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
 * Activitylog Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Activitylog extends CI_Model
{




    /** 
     * Activity log load
     * Activitylog function 
     * 
     * @return void 
     */
    function Activitylog()
    {
        parent::__construct();
        $this -> load -> library('redis');
    }
    
    /** 
     * Add activity log 
     * AddActivitylog function 
     * 
     * @param string $activitylog activitylog 
     * 
     * @return void 
     */
    function addActivitylog($activitylog)
    {
        $data = array('appkey' => $activitylog -> appkey, 'session_id' => $activitylog -> session_id, 'start_millis' => $activitylog -> start_millis, 'end_millis' => $activitylog -> end_millis, 'activities' => $activitylog -> activities, 'duration' => $activitylog -> duration, 'version' => isset($activitylog -> version) ? $activitylog -> version : '');
        $this -> redis -> lpush("razor_clientusinglogs", serialize($data));

        $productId = $this -> utility -> getProductIdByKey($activitylog -> appkey);
        $key = "razor_r_ac_p_" . $productId . "_" . date('Y-m-d-H-i-s', time());
        $this -> redis -> hset($key, array("$activitylog->activities" => 1));
        $this -> redis -> expire($key, 30 * 60);
        $this -> processor -> process();
    }

}
?>
