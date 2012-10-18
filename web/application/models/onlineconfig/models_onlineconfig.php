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
class Models_Onlineconfig extends CI_Model {
	function __construct() {
		$this->load->database ();
	}
	
	function getOnlineConfigByProuctId($id)
	{
	   $sql = "select * from ".$this->db->dbprefix('config')."  where product_id=$id";
	   $query = $this->db->query($sql);
	   return $query->first_row();
	}	
	function modifyonlineconfig($id,$autogetlocation,$updateonlywifi,$sessionmillis,$reportpolicy)
	{
		$data = array (
		                'autogetlocation' => $autogetlocation ,
						'updateonlywifi'=>$updateonlywifi,
						'sessionmillis'=>$sessionmillis,
						'reportpolicy'=>$reportpolicy);
	    $this->db->where('product_id',$id);
	    $this->db->update('config',$data);
	}
}
