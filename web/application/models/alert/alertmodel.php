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
class Alertmodel extends CI_Model {
	function __construct() {
		$this->load->database ();
	}
	
	

	function getProductAlertByProuctId($productId)
	{
		$sql = "select * from ".$this->db->dbprefix('alert')."  as d   where d.productid=".$productId." group by d.id";
		$result = $this->db->query($sql);
	   return $result;
	}
	
	function isUnique($exceptionlab,$condition){
		$product = $this->common->getCurrentProduct();
		$id = $product->id;
		$sql ="select * from ".$this->db->dbprefix('alert')."  a where a.productid=$id  and a.label='".$exceptionlab."' and a.condition='".$condition."';  ";
		$result = $this->db->query($sql);
		return $result;
		
	}
	
	function addlab($exceptionlab,$condition,$emailstr)
	{
	   $userId = $this->common->getUserId();
	   $product = $this->common->getCurrentProduct();	 
	   $data = array('label' => $exceptionlab,'condition'=>$condition,'productid'=>$product->id,'userid'=>$userId,'emails'=>$emailstr);
	echo    $this->db->insert('alert',$data);
	}
	function getalertbyid($lab,$condition)
	{
		$sql ="SELECT * 
FROM ".$this->db->dbprefix('alert')."    
WHERE label =  '".$lab."'
AND active =1
AND  abs(`condition` -".$condition.")<0.001";
// 		$sql = "select *  from ".$this->db->dbprefix('alert')."   where label ='".$lab."' and active=1 and condition =".$condition.";";
	   $result = $this->db->query($sql);
	    if($result!=null&&$result->num_rows()>0)
			 {
				      return $result->row_array();
			 }		    
		  return null;   
	}
	
	function delalert($label)
	{
		
	    $sql = "delete from  ".$this->db->dbprefix('alert')." where label='".$label."'";
	    $this->db->query($sql);
	    
	}
	
	
	function resetalert($label,$condition)
	{
	$sql="	update ".$this->db->dbprefix('alert')." set `condition`=$condition where `label`='".$label."'";
	echo $sql;  
	$this->db->query($sql);
	}
}