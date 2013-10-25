<?php 
class IOSApplistModel extends CI_Model
{
	function __construct() 
	{
		$this -> load -> database();
		$this -> load -> model('common');
	}
	
	//remove array repeated key or value
	function assoc_unique($arr, $key)
	{
		$tmp_arr = array();
		foreach($arr as $k => $v)
		{
			if(in_array($v[$key], $tmp_arr))
			{
				unset($arr[$k]);
			}
			else {
				$tmp_arr[] = $v[$key];
			}
		}
		sort($arr); 
		return $arr;
	}
	
	function getProductInfo () 
	{
		$userId = $this->common->getUserId ();
		$sql_product = "select * from ". $this->db->dbprefix("product")." where product_platform = 2 and user_id= ".$userId;
		$applist=array();
		$query_product = $this -> db -> query($sql_product);
		$rows_product = $query_product -> num_rows();
		if($query_product != null && $rows_product > 0) 
		{
			for($i=0;$i<$rows_product;$i++)
			{
				$product_name = $query_product ->row($i) -> name;
				$product_id = $query_product ->row($i) -> id;
				$product_active = $query_product ->row($i) -> active;
				if($product_active == 0)
				{
					 //$sql_ios = "delete from " . $this -> db -> dbprefix('ios_product') . " where product_id =".$product_id." and user_id=".$userId;
      				 //$this -> db -> query($sql_ios);
      				 
      			}else{

					$sql_ios =  "select * from ". $this->db->dbprefix("ios_product")." where product_id =".$product_id." and user_id=".$userId;
					$query_ios = $this -> db -> query($sql_ios);
					$rows_ios = $query_ios -> num_rows();
					if($query_ios != null && $rows_ios > 0) 
					{
						$isActive = $query_ios -> row() -> is_active;
				
					}else{
						$isActive = 0;
					}
				
					$applist[$i] = array('androidlist'=>$product_name,'product_id'=>$product_id,'isActive'=>$isActive);
				}
			}
			
			return $applist;
		}
				
	}

function getRegisterId($productId,$userId){
	$sql = "select * from ".$this->db->dbprefix('ios_product')." where user_id=".$userId." and product_id=".$productId;
	$res = $this->db->query($sql);
	if($res!=null&&$res->num_rows()>0){
		return $res->first_row();
	}
		return false;
}

function getApplist(){
		$sql ="select * from ".$this->db->dbprefix('ios_product')." ;";
		$result = $this->db->query($sql);
		$re = array();
		if($result!=null && $result->num_rows()>0){
			$resultarr = $result->result_array();
			// print_r($resultarr);
			for($i=0;$i<count($resultarr);$i++){
				$productid=$resultarr[$i]['product_id'];
				$sqltoapplist="select * from ".$this->db->dbprefix('product')." where id=".$productid;
				$res = $this->db->query($sqltoapplist);
				if($res!=null&& $res->num_rows()>0){
					$apparr = $res->result_array();
					array_push($re, $apparr);
				}
			}
		}
		// print_r($re);
		return $re;
	}

	function getappinfo($productid){
		$sql ="select * from ".$this->db->dbprefix('ios_product')." where product_id=".$productid;
		$res = $this->db->query($sql);
		if($res!=null&& $res->num_rows()>0){
			return $res->result_array();
		}
	}
	function getUserinfo($uid){
		$sql = "select * from ".$this->db->dbprefix('userkeys')." where user_id=".$uid;
		$res = $this->db->query($sql);
		if($res!=null&& $res->num_rows()>0){
			return $res->result_array();
		}
	}

	function getProductName($productid){
		$slq ="select * from ".$this->db->dbprefix('product')." where id=".$productid;
		$res = $this -> db ->query($slq);
		if($res!=null&& $res->num_rows()>0){
			$arr= $res->result_array();
			return $arr[0]['name'];
		}
	}
}


	




