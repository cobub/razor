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
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Event extends CI_Model
{

    /** 
     * Event load 
     * Event function 
     * 
     * @return void 
     */
    function Event()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> model("redis_service/processor");
        $this -> load -> library('redis');
    }
    
    /** 
     * Get productid 
     * GetProductid 
     * 
     * @param string $key key 
     * 
     * @return string 
     */
    function getProductid($key)
    {
    	//check
		$key = addslashes($key);
    	
        $query = $this -> db -> query("select product_id from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");

        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> product_id;
        }
        return null;
    }
    
    /** 
     * Is eventid available 
     * IsEventidAvailale function 
     * 
     * @param string $product_id       productid 
     * @param string $event_identifier eventidentifier 
     * 
     * @return string 
     */
    function isEventidAvailale($product_id, $event_identifier)
    {
        $query = $this -> db -> query("select event_id from " . $this -> db -> dbprefix('event_defination') . " where event_identifier = '$event_identifier' and product_id = '$product_id'");
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> event_id;
        } else {
            return null;
        }
    }
    
    /** 
     * Get active by eventid 
     * GetActivebyEventid function 
     * 
     * @param string $getEventid getEventid 
     * @param string $product_id productid 
     * 
     * @return int 
     */
    function getActivebyEventid($getEventid, $product_id)
    {
        $query = $this -> db -> query("select active from " . $this -> db -> dbprefix('event_defination') . " where event_id = '$getEventid' and product_id = '$product_id'");
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> active;
        } else {
            return 0;
        }
    }
    
    /** 
     * Add event 
     * AddEvent function 
     * 
     * @param string $event event 
     * 
     * @return bool 
     */
    function addEvent($content)
    {
    	//parse
    	$this->load->model('servicepublicclass/eventpublic', 'eventpublic');
        $event = new eventpublic();
        $event->loadevent($content);
    	
        $key = $event -> appkey;
        $product_id = $this -> getProductid($key);
		if(!$product_id) {
			return null;
		}
        $event_identifier = $event -> event_identifier;
        $getEventid = $this -> isEventidAvailale($product_id, $event_identifier);
        $active = $this -> getActivebyEventid($getEventid, $product_id);
        if ($active == 0 && $getEventid != null) {
            return null;
        } 
		
		if( $getEventid == null) {
        	$eventdata = array(
                'event_identifier' => $event_identifier, 
                'productkey' => $key, 
                'event_name' => $event_identifier, 
                'channel_id' => 1, 
                'product_id' => $product_id, 
                'user_id' => 1 );
				
			if($this->db->insert('event_defination', $eventdata)){
				$getEventid = $this->db->insert_id();
			}
            
			////check
			if($getEventid == null || $getEventid < 1) {
        		return null;
			}
    	} 
        	
		$insertdate = date('Y-m-d H:i:s');
        $data = array(
            	'productkey' => $key,
                'event_id' => $getEventid,
                'label' => $event->label,
                'clientdate' => $event->time,
                'num' => $event->acc,
                'event' => $event->activity,
                'version' => $event->version,
                'attachment' => $event->attachment,
                'deviceid' => $event->deviceid,
                'useridentifier' => $event->useridentifier,
                'session_id' => $event->session_id,
            	'lib_version' => $event->lib_version,
                'insertdate' => $insertdate);
			
            $this -> redis -> lpush("razor_events", serialize($data));
			
            $key = "razor_r_p_e_" . $product_id . "_" . $event_identifier . "_" . date('Y-m-d-H-i', time());
            $value = $this -> redis -> get($key);
            $value++;

            $this -> redis -> set($key, $value);
            $this -> redis -> expire($key, 30 * 60);
			
            $this -> processor -> process();
            return $getEventid;

    }
    
    /** 
     * Add order 
     * AddOrder function 
     * 
     * @param string $event event 
     * 
     * @return bool 
     */
    function addOrder($event)
    {
        $key = $event -> appkey;
        $product_id = $this -> getProductid($key);
        $event_identifier = $event -> event_identifier;
        $getEventid = $this -> isEventidAvailale($product_id, $event_identifier);
        $active = $this -> getActivebyEventid($getEventid, $product_id);
        if ($active == 0 || $getEventid == null) {
            return false;
        } else {
            $data = array(
            'productkey' => $event -> appkey, 
            'event_id' => $getEventid, 
            'label' => $event -> event_identifier, //order_id
            'attachment' => isset($event -> label) ? $event -> label : '', //productinfo
            'clientdate' => $event -> time, 'num' => isset($event -> acc) ? $event -> acc : 1, 'event' => $event -> activity, 'type' => 1, //1  order   0 event
            'version' => isset($event -> version) ? $event -> version : '');
            $this -> redis -> lpush("razor_events", serialize($data));

            $this -> processor -> process();
            return $getEventid;
        }
    }

}
