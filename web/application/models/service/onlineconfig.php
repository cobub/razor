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
class OnlineConfig extends CI_Model
{
	function OnlineConfig()
	{
		parent::__construct();
		$this->load->database();
	}	
	
    function getProductid($key)
	{
		$query = $this->db->query("select product_id from ".$this->db->dbprefix('channel_product')." where productkey = '$key'");
		if($query!=null && $query->num_rows()>0)
		{
			$productid = $query->first_row()->product_id;			
		    return $productid;			
		}
		return null;
	}
	function  getConfigMessage($productid)
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('config')." where product_id = '$productid'");
		if ($query!=null && $query->num_rows()>0)
		{
			return $query->first_row();
			
		}
		return null;
	}
}