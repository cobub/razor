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
class UserEvent extends CI_Model {
	function __construct() {
		$this->load->database ();
	}
	
	function getEventListByProductIdAndProductVersion($productId,$version)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
	   if ($version=='unknown')
		  $version='';
	   if ($version=='all')
           $sql = "select 
e.event_sk,
e.eventidentifier,
e.eventname,
count(f.eventid) count
from ".$dwdb->dbprefix('dim_product')."   p,  ".$dwdb->dbprefix('fact_event')."  f,  ".$dwdb->dbprefix('dim_event')."  e  where  p.product_id=$productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.product_sk = p.product_sk and f.event_sk = e.event_sk 
group by  e.event_sk,e.eventidentifier,e.eventname

           
           ";
	   else {
	   	if ($version=='unknown')
	   	$version='';
        $sql = "select p.version_name,
e.event_sk,
e.eventidentifier,
e.eventname,
count(f.eventid) count
from  ".$dwdb->dbprefix('dim_product')."   p,  ".$dwdb->dbprefix('fact_event')."  f, ".$dwdb->dbprefix('dim_event')."   e  where  p.product_id=$productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.product_sk = p.product_sk and f.event_sk = e.event_sk and p.version_name='$version'
group by p.version_name, e.event_sk,e.eventidentifier,e.eventname

        
        ";
	   }
       
	  
	   $query = $dwdb->query ( $sql );
	   return $query;
	}
	
	function getProductVersions($productid)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
	   $sql = "select distinct version_name from  ".$dwdb->dbprefix('dim_product')."  where 
	   product_active=1 and channel_active=1 and version_active=1 and product_id=$productid order by version_name desc";     
	   $query = $dwdb->query ( $sql );
	   return $query;
	}
		
	//get all chart data
	function getAllEventChartData($productid,$event_sk,$version,$from,$to)
	{
		 $dwdb = $this->load->database ( 'dw', TRUE );
		if ($version=='all')
		{
			 $sql = "select   dd.datevalue, ifnull(ff.count,0) count, ifnull(ff.count/(select sum(startusers) from 
			  ".$dwdb->dbprefix('sum_basic_all')."   s, ".$dwdb->dbprefix('dim_product')."   p where s.date_sk = ff.date_sk 
			  and s.product_sk = p.product_sk and p.product_id=$productid ),0) userper,
              ifnull(ff.count/(select sum(sessions) from  ".$dwdb->dbprefix('sum_basic_all')."   s,
               ".$dwdb->dbprefix('dim_product')."   p where s.date_sk = ff.date_sk and s.product_sk = p.product_sk 
               and p.product_id=$productid),0) sessionper from     (select date_sk,datevalue
          from  ".$dwdb->dbprefix('dim_date')."  where  datevalue between '$from' and '$to') dd
         left join (select   d.date_sk,d.datevalue,                            count(* ) count
                    from   ".$dwdb->dbprefix('fact_event')."     f,
                           ".$dwdb->dbprefix('dim_product')."     p,
                           ".$dwdb->dbprefix('dim_event')."     e,
                           ".$dwdb->dbprefix('dim_date')."     d
                    where    e.event_sk = $event_sk
                             and f.product_sk = p.product_sk
                             and p.product_id = e.product_id
                            
                             and f.event_sk = e.event_sk
                             and f.date_sk = d.date_sk
                             and d.datevalue between '$from' and '$to'
                    group by d.datevalue) ff   on dd.date_sk = ff.date_sk order by dd.date_sk;";
		}
		else 
		{
	    $sql = "select   dd.datevalue, ifnull(ff.count,0) count,ifnull(ff.count/(select sum(startusers) from  
	    ".$dwdb->dbprefix('sum_basic_all')."  s, ".$dwdb->dbprefix('dim_product')." p 
	    where s.date_sk = ff.date_sk and s.product_sk = p.product_sk and p.product_id=$productid 
	    and p.version_name='$version'),0) userper,ifnull(ff.count/(select sum(sessions) from   
	    ".$dwdb->dbprefix('sum_basic_all')."  s, ".$dwdb->dbprefix('dim_product')."   p where s.date_sk = ff.date_sk 
	    and s.product_sk = p.product_sk and p.product_id=$productid and p.version_name='$version'),0) sessionper
        from     (select date_sk,datevalue from   ".$dwdb->dbprefix('dim_date')."  
          where  datevalue between '$from' and '$to') dd  left join (select   d.date_sk,d.datevalue,
                count(* ) count
                    from   ".$dwdb->dbprefix('fact_event')."     f,
                           ".$dwdb->dbprefix('dim_product')."     p,
                            ".$dwdb->dbprefix('dim_event')."    e,
                            ".$dwdb->dbprefix('dim_date')."    d
                    where    e.event_sk = $event_sk
                             and f.product_sk = p.product_sk
                             and p.product_id = e.product_id
                             and p.version_name = '$version'
                             and f.event_sk = e.event_sk
                             and f.date_sk = d.date_sk
                             and d.datevalue between '$from' and '$to'
                    group by d.datevalue) ff
           on dd.date_sk = ff.date_sk order by dd.date_sk; ";
		}    
	  
	   $query = $dwdb->query ( $sql );
	  return $query;
	}
	
	

	function getProductEventByProuctId($productId)
	{
		$sql = "select d.event_id eventid,d.productkey,d.event_identifier,d.event_name eventName,d.active,e.productkey,e.event_id, sum(e.num) eventnum from ".$this->db->dbprefix('event_defination')."  as d  left join ".$this->db->dbprefix('eventdata')."  as e on  d.event_id = e.event_id where d.product_id=".$productId." group by d.event_id";
		$result = $this->db->query($sql);
	   return $result;
	}
	
	function isUnique($productId,$event_id){
		$this->db->from('event_defination');
		$this->db->where('product_id',$productId);
		$this->db->where('event_identifier',$event_id);
		$this->db->where('active','1');
		$r = $this->db->get();
		return $r->result();
		
	}
	
	function addEvent($event_id,$event_name)
	{
	   $userId = $this->common->getUserId();
	   $product = $this->common->getCurrentProduct();	 
	   $data = array('event_identifier' => $event_id,'productkey' => $product->product_key,'event_name'=>$event_name,'channel_id'=>1,'product_id'=>$product->id,'user_id'=>$userId);
	   $this->db->insert('event_defination',$data);
	}
	//Through eventid get event information
	function geteventbyid($eventid)
	{
		$sql = "select event_id ,event_identifier,event_name from  ".$this->db->dbprefix('event_defination')."   where event_id =$eventid and active=1";
	   $result = $this->db->query($sql);
	    if($result!=null&&$result->num_rows()>0)
			 {
				      return $result->row_array();
			 }		    
		  return null;   
	}
	function modifyEvent($id,$eventId,$eventName)
	{
		$data = array ('event_identifier' => $eventId ,'event_name'=>$eventName);
	    $this->db->where('event_id',$id);
	    $this->db->update('event_defination',$data);
	}
	
	function stopEvent($id)
	{
		$this->db->where('event_id',$id);
		$data = array ('active' => 0);
	    $this->db->update('event_defination',$data);
	}
	
	function startEvent($id)
	{
		$this->db->where('event_id',$id);
		$data = array ('active' => 1);
	    $this->db->update('event_defination',$data);
	}
	
	function resetEvent($id)
	{
		$sql="delete  from ".$this->db->dbprefix('eventdata')."   where event_id=".$id;
	    $this->db->query($sql);
	}
}