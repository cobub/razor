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
 * TransRateModel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class TransRateModel extends CI_Model
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
     * Get transrate 
     * GetTransRate function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getTransRate($productId)
    {
        $r = $this -> getTargetListByProductId($productId);
        $num = $r -> num_rows();
        $ret = array();
        $nret = array();
        foreach ($r->result() as $row) {
            $transEvents = $this -> getTransEventsIdByTargetId($row -> tid);
            $event_from = $transEvents["from"];
            $event_to = $transEvents["to"];
           
            $timezonestimestamp = time();
            $timezonestime = date('Y-m-d H:i:s', $timezonestimestamp);
            $from_count = 0;
            $to_count = 0;
            for ($i = 30; $i >= 0; $i--) {
                $dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
                $from_size = $this -> redis -> get("razor_r_p_e_" . $productId . "_" . $event_from . "_" . $dataStr);
                $from_count += $from_size;
                $to_size = $this -> redis -> get("razor_r_p_e_" . $productId . "_" . $event_to . "_" . $dataStr);
                $to_count += $to_size;

                if ($from_size == 0)
                    $rate = 0;
                else
                    $rate = $to_size / $from_size;
                if ($i == 0) {
                    $r = array('name' => $row -> targetname, 'time' => lang("v_rpt_realtime_now"), 'from_count' => $from_count, 'to_count' => $to_count, 'rate' => $rate, 'event_to' => $event_to);
                } else {
                    $r = array('name' => $row -> targetname, 'time' => '-' . $i . lang("v_rpt_realtime_minutes"), 'from_count' => $from_count, 'to_count' => $to_count, 'rate' => $rate, 'event_to' => $event_to);
                }

                array_push($ret, $r);
            }
            array_push($nret, $ret);
        }
        return $nret;
    }
    
    /** 
     * Get transrate 
     * GetTransRateByTime function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getTransRateByTime($productId)
    {
        $r = $this -> getTargetListByProductId($productId);
        $num = $r -> num_rows();
        $ret = array();
        $nret = array();
        foreach ($r->result() as $row) {
            $transEvents = $this -> getTransEventsIdByTargetId($row -> tid);
            $event_from =  $transEvents["from"];
            $event_to = $transEvents["to"];
            $timezonestimestamp = time();
            $timezonestime = date('Y-m-d H:i:s', $timezonestimestamp);
            $from_count = 0;
            $to_count = 0;
            for ($i = 29; $i >= 0; $i--) {
                $dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
                $from_size = $this -> redis -> get("razor_r_p_e_" . $productId . "_" . $event_from . "_" . $dataStr);
                $from_count += $from_size;
                $to_size = $this -> redis -> get("razor_r_p_e_" . $productId . "_" . $event_to . "_" . $dataStr);
                $to_count += $to_size;

                if ($from_count == 0)
                    $rate = 0;
                else
                    $rate = $to_count / $from_count;

            }
            $event_toName = $this->getEventnameByEventIdent($event_to);
            $r = array('name' => $row -> targetname,
             'time' => '-' . $i . lang("v_rpt_realtime_minutes"),
             'from_count' => $from_count, 
             'to_count' => $to_count, 
             'rate' => $rate, 
             'event_to' => $event_toName);
            if ($to_count == 0) {
                continue;
            }
            array_push($ret, $r);
        }
        return $ret;
    }
    /** 
     * Get target list 
     * GetTargetListByProductId function 
     * 
     * @param string $productId productId 
     * 
     * @return query 
     */
    function getTargetListByProductId($productId)
    {
        $sql = 'select * from ' . $this -> db -> dbprefix('target') . ' where productid=' . $productId;

        $r = $this -> db -> query($sql);
        return $r;
    }
    
    /** 
     * Get transeventsid by target 
     * GetTransEventsIdByTargetId function 
     * 
     * @param string $targetId targetid 
     * 
     * @return array 
     */
    function getTransEventsIdByTargetId($targetId)
    {
        $sql = 'select t.*,e.event_identifier from ' .
         $this -> db -> dbprefix('targetevent') . ' t, '.
         $this -> db -> dbprefix('event_defination') .
         ' e where t.eventid=e.event_id and t.targetid=' . $targetId;

        $r = $this -> db -> query($sql);
        $num = $r -> num_rows();
        $tmp = array();
        foreach ($r->result() as $row) {
            array_push($tmp, $row -> event_identifier);
        }
        $ret = array("from" => $tmp[0], "to" => $tmp[$num - 1]);
        return $ret;
    }
    
    /** 
     * Get event name 
     * GetTransEventsIdByTargetId function 
     * 
     * @param string $targetId targetid 
     * 
     * @return string 
     */
    function getEventnameByEventIdent($eventIdentifier)
    {
        $sql = "select event_name from " .
         $this -> db -> dbprefix('event_defination') .
         " where event_identifier='$eventIdentifier' ";

        $r = $this -> db -> query($sql);
        if($r && $r->num_rows()==1) {
            return $r->first_row()->event_name;
        }
    }

}
?>