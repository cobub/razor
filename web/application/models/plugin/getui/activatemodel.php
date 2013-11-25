<?php 
class Activatemodel extends CI_Model 
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
			$getui_sql = "insert into ". $this->db->dbprefix("getui_product")."(`product_id`,`is_active`,`app_id`,`user_id`,`app_key`,`app_secret`,`app_mastersecret`,`app_identifier`,`activate_date`) 
			values ('".$obj['productId']."','1','".$obj['appId']."','".$obj['userId']."','".$obj['appKey']."','".$obj['appSecret']."','".$obj['masterSecret']."','".$obj['app_identifier']."','".$obj['activateDate']."');";
			$query = $this -> db -> query($getui_sql);
			return true;
		}
	}
	
	function isAppActivated($productId)
	{
		$sql = "select * from ". $this->db->dbprefix("getui_product")." where product_id = $productId";
		$query = $this->db->query($sql);
		if($query && $query->num_rows()>0)
		{
			return true;
		}
		return false;
	}
	
	function checkInfo($appName,$appid)
	{
		$data['appName'] = $appName;
		$data['appid']=$appid;
		$product_id = $this -> activatemodel ->  getProductId($appName);
		
		$data['productId'] = $product_id;
		$sql = "select * from ". $this->db->dbprefix("getui_product")." where product_id =".$appid;

		$query = $this->db->query($sql);
		if($query && $query->num_rows()>0)
		{
			$data['appId']= $query->row()-> app_id;
			$data['appKey'] = $query -> row() -> app_key;
			$data['appSecret'] = $query -> row() -> app_secret;
			$data['masterSecret'] = $query -> row() -> app_mastersecret; 
			$data['app_identifier'] = $query -> row() -> app_identifier;
			$data['activateDate'] = $query -> row() -> activate_date;
			$data['flag']=1;

			
		}else{

			return false;
		}

		return $data;
	}

}

