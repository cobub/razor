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
 * Pageviewmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Pageviewmodel extends CI_Model
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
     * Get activity 
     * GetActivities function 
     * 
     * @param string $productId productid 
     * 
     * @return json
     */
    function getActivities($productId)
    {
        $timezonestimestamp = gmt_to_local(local_to_gmt(), $this -> config -> item('timezones'));
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $allKeys = $this -> redis -> keys("razor_r_ac_p_" . $productId . "*");
        $ret = array();
        $allActivitiesArray = array();

        if ($allKeys && count($allKeys) > 0) {
            for ($i = 0; $i < count($allKeys); $i++) {
                $key = $allKeys[$i];
                $activities = $this -> redis -> hkeys($key);
                if ($activities && count($activities) > 0) {
                    for ($j = 0; $j < count($activities); $j++) {
                        $activityName = $activities[$j];
                        if (isset($allActivitiesArray["$activityName"])) {
                            $allActivitiesArray["$activityName"] = (int)$allActivitiesArray["$activityName"] + 1;
                        } else {
                            $allActivitiesArray["$activityName"] = 1;
                        }
                    }
                }
            }
        }

        $allChildrens = array();
        if ($allActivitiesArray && count($allActivitiesArray) > 0) {
            foreach ($allActivitiesArray as $key => $value) {
                $ac = array('name' => $key, 'size' => $value);
                array_push($allChildrens, $ac);
            }
        }
        $ret["total"] = count($allActivitiesArray);
        $ret["rows"] = $allChildrens;
        echo json_encode($ret);
    }
    
    /** 
     * Get activity by minute 
     * GetActivityByMinutes function 
     * 
     * @param string $productId productid 
     * 
     * @return json 
     */
    function getActivityByMinutes($productId)
    {
        $timezonestimestamp = gmt_to_local(local_to_gmt(), $this -> config -> item('timezones'));
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $all_ret = array();
        $version = "all";

        for ($i = 29; $i >= 0; $i--) {
            $all_size = 0;
            $dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
            $allAcs = $this -> redis -> keys("razor_r_ac_p_" . $productId . "_" . $dataStr . "*");
            if ($allAcs && count($allAcs) > 0) {
                for ($k = 0; $k < count($allAcs); $k++) {
                    $key = $allAcs[$k];
                    $len = $this -> redis -> hlen($key);
                    $all_size += $len;
                }
            }

            if ($i == 0) {
                $onlinedata = array('minutes' => lang("v_rpt_realtime_now"), 'size' => $all_size);
            } else {
                $onlinedata = array('minutes' => "- " . $i . lang("v_rpt_realtime_minutes"), 'size' => $all_size);
            }
            array_push($all_ret, $onlinedata);
        }
        return json_encode($all_ret);
    }

}
?>