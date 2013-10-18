<?php 
class IOSActivatemodel extends CI_Model 
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
	
	function getProductId($product_name)
	{	
		$sql_productId = "select * from ". $this->db->dbprefix("product")." where name ="."'$product_name'";
	
		$query_productId = $this -> db -> query($sql_productId);
		
		if($query_productId != null && $query_productId->num_rows() > 0){
			$product_id =  $query_productId ->row() -> id;
			return $product_id;
		
		}else{
			return false;
		}
	}
	
	function saveUsersInfo($obj)
	{
	
		if($this -> isAppActivated($obj['productId'])){
			return false;
		}else{
			$ios_sql = "insert into ". $this->db->dbprefix("ios_product")."(`product_id`,`is_active`,`user_id`,`register_id`,`bundle_id`) 
			values ('".$obj['productId']."','1','".$obj['userId']."','".$obj['register_id']."','".$obj['bundleid']."');";
			$query = $this -> db -> query($ios_sql);
			return true;
		}
	}
	
	function isAppActivated($productId)
	{
		$sql = "select * from ". $this->db->dbprefix("ios_product")." where product_id = $productId";
		$query = $this->db->query($sql);
		if($query && $query->num_rows()>0)
		{
			return true;
		}
		return false;
	}
	
	function checkInfo($appName)
	{
		$data['appname'] = $appName;
		//$product_id = $this ->iosactivatemodel ->getProductId($appName);
		$product_id = $this->getProductId($appName);
		if($product_id)
		{
			$data['productId'] = $product_id;
			$sql = "select * from ". $this->db->dbprefix("ios_product")." where product_id =".$product_id;
			
			$query = $this->db->query($sql);
			if($query && $query->num_rows()>0)
			{
				$data['register_id']= $query->row()->register_id;
				$data['bundleid']= $query->row()->bundle_id;
				$data['flag']=1;
			}else{
			
				return false;
			}
			
			return $data;
		}
		
		return false;
		
	}

}

