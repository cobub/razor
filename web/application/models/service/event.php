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
 * Event Model
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Event extends CI_Model
{
    /**
     * Event function,to pre_load database configration
     *
     * @return void
     */
    function Event()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * GetProductid function,get product id
     *
     * @param int $key key
     *
     * @return product id
     */
    function getProductid($key)
    {
        $query = $this->db->query("select product_id from " . $this->db->dbprefix('channel_product') . " where productkey = '$key'");
        
        if ($query != null && $query->num_rows() > 0) {
            return $query->first_row()->product_id;
        }
        return null;
    }

    /**
     * IsEventidAvailale function
     *
     * @param int    $product_id       product id
     * @param string $event_identifier event identifier
     *
     * @return event id
     */
    function isEventidAvailale($product_id, $event_identifier)
    {
        $query = $this->db->query("select event_id from " . $this->db->dbprefix('event_defination') . " where event_identifier = '$event_identifier' and product_id = '$product_id'");
        if ($query != null && $query->num_rows() > 0) {
            return $query->first_row()->event_id;
        } else {
            return null;
        }
    }

    /**
     * GetActivebyEventid function
     *
     * @param string $getEventid roduct id
     * @param string $product_id event id
     * 
     * @return avtive
     */
    function getActivebyEventid($getEventid, $product_id)
    {
        $query = $this->db->query("select active from " . $this->db->dbprefix('event_defination') . " where event_id = '$getEventid' and product_id = '$product_id'");
        if ($query != null && $query->num_rows() > 0) {
            return $query->first_row()->active;
        } else {
            return 0;
        }
    }

    /**
     * AddEvent function
     *
     * @param string $content content
     *
     * @return avtive
     */
    function addEvent($content)
    {
        $this->load->model('servicepublicclass/eventpublic', 'eventpublic');
        $event = new eventpublic();
        $event->loadevent($content);
        $key = $event->appkey;
        $product_id = $this->getProductid($key);
        $event_identifier = $event->event_identifier;
        $getEventid = $this->isEventidAvailale($product_id, $event_identifier);
        $active = $this->getActivebyEventid($getEventid, $product_id);
        if ($active == 0 || $getEventid == null) {
            return null;
        } else {
            $nowtime = date('Y-m-d H:i:s');
            if (isset($event->time)) {
                $nowtime = $event->time;
                if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                    $nowtime = date('Y-m-d H:i:s');
                }
            }
            $data = array('productkey' => $event->appkey,'event_id' => $getEventid,'label' => isset($event->label) ? $event->label : '','clientdate' => $nowtime,'num' => isset($event->acc) ? $event->acc : 1,'event' => $event->activity,'version' => isset($event->version) ? $event->version : ''
            );
            
            $this->db->insert('eventdata', $data);
            return $getEventid;
        }
    }
}
?>