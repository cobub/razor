<?php 
class CheckRadarInfoModel extends CI_Model 
{
	
	function __construct()
	{
		$this -> load -> database();
		$this -> load -> model('common');
		
	
	}
	
	function getUserKeys($userId)
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix("userkeys")." where user_id = $userId");
		if($query && $query->num_rows()>0)
		{
			return $query->first_row();
		}
		return false;
	}
	
	function getProductId($appName,$userId)
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix("product")." where name = '".$appName."' and user_id= $userId");
		if($query && $query->num_rows()>0)
		{
			return $query->first_row()-> id;
		}
		return false;

	}
	
	function saveUsersInfo($obj)
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix("radar")." where user_id = ".$obj['userid']." and product_id = ".$obj['productId']);
		if($query && $query->num_rows()>0)
		{
			$query_update = $this->db->query("update ".$this->db->dbprefix("radar")." set app_id= ".$obj['appid']." where user_id = ".$obj['userid']." and product_id = ".$obj['productId']);
			if($query_update){
				return true;
			}
			return false;
		}else{
			
			$radar_sql = "insert into ". $this->db->dbprefix("radar")."(`user_id`,`app_id`,`product_id`) 
			values ('".$obj['userid']."','".$obj['appid']."','".$obj['productId']."');";
			$query = $this -> db -> query($radar_sql);
			if($query){
				return true;
			}
			return false;
		}

		
	}

	function getRadarAppId($userId,$productId)
	{
		$query = $this->db->query("select * from ".$this->db->dbprefix("radar")." where user_id = $userId and product_id = $productId");
		if($query && $query->num_rows()>0)
		{
			return $query->first_row();
		}
		return false;

	}
}

