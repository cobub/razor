<?php 
class GcmActivatemodel extends CI_Model 
{
	
	function __construct()
	{
		$this -> load -> database();
		$this -> load -> model('common');
		
	}
	
	// //save user's userkey and usersecret to razor_userkeys's table
	function getappkey($userId) {
		$this->db->from ( $this->db->dbprefix ( 'gcmappkeys' ) );
		$this->db->where ( 'user_id', $userId );
		$this->db->where ( 'status', 1 );
		$query = $this->db->get ();
		if ($query && $query->num_rows () > 0) {
			return $query->first_row ()->appkey;
		} 
		return false;
	}
	
	function CheckAppkey( $userId, $appkey )
	{		
		$this->db->from ( $this->db->dbprefix ( 'gcmappkeys' ) );
		$this->db->where ( 'user_id', $userId );
		$this->db->where ( 'appkey', $appkey);
		$this->db->where ( 'status', 1 );
		$query = $this->db->get ();
		if ($query && $query->num_rows () > 0) {
			return true;
		}
		
		return false;
	}
	
	// //save user's userkey and usersecret to razor_userkeys's table
	function saveappkeys($userId, $appkey) {
		
		////first update status 0;
		$this->db->from ( $this->db->dbprefix ( 'gcmappkeys' ) );
		$this->db->where ( 'user_id', $userId );
		$queryupdate = $this->db->get ();
		if ($queryupdate && $queryupdate->num_rows () > 0)
		{
			foreach ($queryupdate->result() as $row)
			{
				$this->db->where('user_id', $userId);
				$this->db->where('appkey', $row->appkey);
				$data = array (
						'status' => 0
				);
				$this->db->update ( $this->db->dbprefix ( 'gcmappkeys' ), $data );
			}
		}
		
		////Second Yes or No  input appkey
		$this->db->from ( $this->db->dbprefix ( 'gcmappkeys' ) );
		$this->db->where ( 'user_id', $userId );
		$this->db->where ( 'appkey', $appkey );
		$query = $this->db->get ();
		if ($query && $query->num_rows () > 0) {
			$this->db->where('user_id', $userId);
			$this->db->where('appkey',$appkey);
			$data = array ('status' => 1);
			$this->db->update ( $this->db->dbprefix ( 'gcmappkeys' ), $data );
		} else {
			$data = array (
					'user_id' => $userId,
					'appkey' => $appkey,
					'status' => 1
			);
			$this->db->insert ( $this->db->dbprefix ( 'gcmappkeys' ), $data );
		}
	
	}

	////get userkeys
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
	
	function checkInfo($appName)
	{
		$data['appName'] = $appName;
		$product_id = $this -> activatemodel ->  getProductId($appName);
	
		$data['productId'] = $product_id;
		$sql = "select * from ". $this->db->dbprefix("product")." where product_id =".$product_id;
	
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


