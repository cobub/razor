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
 * EventModel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class EventModel extends CI_Model
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
        $this -> load -> model('event/userEvent', 'event');
    }
    
    /** 
     * Get event num data for hightcharts 
     * GetEventNumByTime function 
     * 
     * @param string $productId productId 
     * 
     * @return array 
     */
    function getEventNumByTime($productId)
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $all_ret = array();
        $version = "all";
        $eventid_array = $this -> event -> getEventIdentifierinfo($productId, $version);

        for ($i = 30; $i >= 0; $i--) {
            $all_size = 0;
            foreach ($eventid_array->result() as $row) {
                $dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
                $size = $this -> redis -> get("razor_r_p_e_" . $productId . "_" . $row -> event_identifier . "_" . $dataStr);
                $all_size += $size;
            }
            if ($i == 0) {
                $data = array('minutes' => lang("v_rpt_realtime_now"), 'size' => $all_size);
            } else {
                $data = array('minutes' => '-' . $i . lang("v_rpt_realtime_minutes"), 'size' => $all_size);
            }

            array_push($all_ret, $data);
        }
        return $all_ret;
    }
    
    /** 
     * Get event num data for table
     * GetEventNumByEvent function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getEventNumByEvent($productId)
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $all_ret = array();
        $version = "all";
        $eventid_array = $this -> event -> getEventIdentifierinfo($productId, $version);

        foreach ($eventid_array->result() as $row) {
            $all_size = 0;
            for ($i = 0; $i <= 30; $i++) {
                $dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
                $size = $this -> redis -> get("razor_r_p_e_" . $productId . "_" . $row -> event_identifier . "_" . $dataStr);
                $all_size += $size;
            }

            $data = array('event' => $row -> event_identifier, 'size' => $all_size);
            if ($all_size == 0) {
                continue;
            }
            array_push($all_ret, $data);
        }
        return $all_ret;
    }

}
?>