<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class Event extends CI_Model
{
	function Event()
	{
		parent::__construct();
		$this->load->database();
	}	
	
    function getProductid($key)
	{
		$query = $this->db->query("select product_id from ".$this->db->dbprefix('channel_product')." where productkey = '$key'");

		if($query!=null&& $query->num_rows()>0)
		{
			return $query->first_row()->product_id;			
		}
		return null;
	}
	function isEventidAvailale($product_id,$event_identifier)
	{
		$query = $this->db->query("select event_id from ".$this->db->dbprefix('event_defination')." where event_identifier = '$event_identifier' and product_id = '$product_id'");
	 if($query!=null&& $query->num_rows()>0)
		{
			return $query->first_row()->event_id;			
		}
	else 
	{
		return null;
	}
}
	function getActivebyEventid($getEventid,$product_id)
	{
		$query = $this->db->query("select active from ".$this->db->dbprefix('event_defination')." where event_id = '$getEventid' and product_id = '$product_id'");
	 	if($query!=null&& $query->num_rows()>0)
		{
			return $query->first_row()->active;			
		}
		else 
		{
			return 0;
		}
	}
	function addEvent($event)
	{
		$key=$event->appkey;
		$product_id=$this->getProductid($key);
		$event_identifier=$event->event_identifier;
		$getEventid=$this->isEventidAvailale($product_id,$event_identifier);
		$active = $this->getActivebyEventid($getEventid,$product_id);
		if ($active== 0 ||$getEventid==null)
		{
			return NULL;
		}
		else 
		{
		$data = array(
			'productkey' => $event->appkey,
			'event_id'=> $getEventid,
			'label'=>isset($event->label)?$event->label:'',
			'clientdate' => $event->time,
			'num'=> isset($event->acc)?$event->acc:1,
			'event'=>$event->activity,
		    'version'=>isset($event->version)?$event->version:''
		);
		
		$this->db->insert('eventdata',$data);
		return $getEventid;
		}
	}
}
?>