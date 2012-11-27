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
class DashboardModel extends CI_Model
{
	function __construct()
	{
		$this->load->database();		
			
	}
	function addreport($productid,$userid,$reportname,$controller,$src,$height,$type)
	{	
		$ret=$this->isreportname($productid,$userid, $reportname,$type);		
		$num=$this->getreportnum($productid,$userid);
		$maxlocation=$this->getmaxlocation($productid,$userid);	
		if($maxlocation==0)	
		{
			$location=0;
		}
		else
		{
			$location=$maxlocation+1;
		}		
		if($ret && ($num<8))
		{
			$date=date('Y-m-d H:i:s');
			$data = array(
					'productid'  => $productid,
					'userid'     => $userid,
					'controller' => $controller,
					'reportname' => $reportname,
					'src'        => $src,
					'createtime' => $date,
					'height'     => $height,
					'type'       => $type,
					'location'	 => $location				
			);				
			
			$this->db->insert('reportlayout', $data);
			return 1;
		}
		else 
		{
			if($num>=8)
			{
				return $num;
			}	
			else 
			{
				return 0;
			}
			
		}		
	}
	function getmaxlocation($productid,$userid)
	{
		$this->db->select_max('location');
		$this->db->where('productid', $productid);
		$this->db->where('userid', $userid);
		$maxlocation = $this->db->get('reportlayout');
		if($maxlocation!=null)
		{
			$row = $maxlocation->row();
			return $row->location;			
		}
		else
		{
			return 0;
		}
	}
	
	function isreportname($productid,$userid, $reportname,$type)
	{
		$this->db->where('productid', $productid);
		$this->db->where('userid', $userid);
		$this->db->where('reportname', $reportname);
		$this->db->where('type', $type);
		$ret=$this->db->get('reportlayout');
		if($ret!=null && $ret->num_rows()>0)
		{			
			return false;
		}
		else 
		{
			return true;
		}
		
	}
	
	function getaddreport($productid,$userid,$type=null)
	{	   
		$this->db->where('productid', $productid);
		$this->db->where('userid', $userid);
		if($type!=null)
		{
			$this->db->where('type', $type);
		}
		$this->db->order_by("location", "asc");
		$ret=$this->db->get('reportlayout');
		if($ret!=null && $ret->num_rows()>0)
		{
		  return  $ret;
		}
		return false;
		
	}
	
	 function getreportnum($productid,$userid)
	{	   
		$this->db->where('productid', $productid);
		$this->db->where('userid', $userid);				
		$ret=$this->db->get('reportlayout');
		if($ret!=null && $ret->num_rows()>0)
		{
			$ret=$ret->num_rows();		 
		}
		else 
		{
			$ret=0;
		}
		return $ret;
		
	}
	
	function deletereport($productid,$userid,$reportname,$type)
	{
		$this->db->where('productid', $productid);
		$this->db->where('reportname', $reportname);
		$this->db->where('userid', $userid);
		$this->db->where('type', $type);
		$this->db->delete('reportlayout');
	}
	
	function updatereport($productid,$userid,$reportname,$type,$location)
	{
		$data = array(
				'location' => $location
						);
		
		$this->db->where('productid', $productid);
		$this->db->where('reportname', $reportname);
		$this->db->where('userid', $userid);
		$this->db->where('type', $type);		
		$this->db->update('reportlayout', $data);
	}
}