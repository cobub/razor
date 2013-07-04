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
	
	function isUnique($exceptionlab,$condition,$emails){
		$product = $this->common->getCurrentProduct();
		$id = $product->id;
		$sql ="select * from ".$this->db->dbprefix('alert')."  a where a.productid=$id and a.emails='".$emails."'  and a.label='".$exceptionlab."' and a.condition='".$condition."' ;  ";
		$result = $this->db->query($sql);
		return $result;
		
	}
	
	function addlab($exceptionlab,$condition,$emailstr)
	{
	   $userId = $this->common->getUserId();
	   $product = $this->common->getCurrentProduct();	 
	   $data = array('label' => $exceptionlab,'condition'=>$condition,'productid'=>$product->id,'userid'=>$userId,'emails'=>$emailstr);
	    $this->db->insert('alert',$data);
	}
	function getalertbyid($id,$condition)
	{
		$sql ="SELECT * 
FROM ".$this->db->dbprefix('alert')."    
WHERE id =  '".$id."'
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
	
	function delalert($id,$condition)
	{
		
	    $sql = "delete from  ".$this->db->dbprefix('alert')." where id='".$id."' and `condition`=$condition ";
	    $this->db->query($sql);
	    
	}
	
	
	function resetalert($Id,$label,$condition,$emails)
	{
		$sql ="UPDATE ".$this->db->dbprefix('alert')." SET `condition`=$condition,`label`='$label',`emails`='$emails' WHERE `id`=$Id";
// 	$sql="	update ".$this->db->dbprefix('alert')." set `condition`=$condition,`label`=$label,`emails`=$emails where `id`=".$productId."";
// 	echo $sql;  
	$this->db->query($sql);
	}
}