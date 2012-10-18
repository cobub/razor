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
class Update extends CI_Model
{
	function Update()
	{
		parent::__construct();
		$this->load->database();
	}	
	
    function haveNewVersion($key,$version_code)
	{
		$query = $this->db->query("select version from ".$this->db->dbprefix('channel_product')." where productkey = '$key'");
		if($query!=null&& $query->num_rows()>0)
		{
			$version = $query->first_row()->version;
			if(strcmp($version,$version_code)>0)
			{
				return true;
			}
		}
		return false;
	}
	
	function getProductUpdate($key)
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix('channel_product')." where productkey = '$key'");
		if($query!=null && $query->num_rows()>0)
		{
			return $query->first_row();
		}
		return null;
	}
}