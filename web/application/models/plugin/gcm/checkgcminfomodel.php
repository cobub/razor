<?php
class CheckGcmInfoModel extends CI_Model {
	
	function __construct() {
		$this->load->database ();
		$this->load->model ( 'common' );
	
	}
	
	// remove array repeated key or value
	function assoc_unique($arr, $key) {
		$tmp_arr = array ();
		foreach ( $arr as $k => $v ) {
			if (in_array ( $v [$key], $tmp_arr )) {
				unset ( $arr [$k] );
			} else {
				$tmp_arr [] = $v [$key];
			}
		}
		sort ( $arr );
		return $arr;
	}
	
	function getProductInfo() {
		$sql_product = "select * from " . $this->db->dbprefix ( "product" ) . " where product_platform = 1";
		$applist = array ();
		$query_product = $this->db->query ( $sql_product );
		$rows_product = $query_product->num_rows ();
		if ($query_product != null && $rows_product > 0) {
			for($i = 0; $i < $rows_product; $i ++) {
				$product_name = $query_product->row ( $i )->name;
				$product_id = $query_product->row ( $i )->id;
				$applist [$i] = array (
						'androidlist' => $product_name,
						'appId' => $product_id 
				);
			}
		}
		
		return $applist;
	}
	

	function getApplist(){
		$sql ="select * from ".$this->db->dbprefix('product')." ;";
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
	
	function getAppid($product_id){
		if(!$product_id)
			return '';
	
		$sql = "select * from ".$this->db->dbprefix('product')." where id=".$product_id;
		$res = $this->db->query($sql);
		if($res!=null&& $res->num_rows()>0){
			$arr = $res->result_array();
			return $arr[0]['id'];
		}
		return '';
	}
	
	function getproductid($app_id){
		$sql = "select * from ".$this->db->dbprefix('product')." where id='".$app_id."';";
		$res = $this->db->query($sql);
		if($res!=null&& $res->num_rows()>0){
			$arr = $res->result_array();
			return $arr[0]['product_id'];
		}
		return '';
	
	}
	
	function getappinfo($productid){
		$sql ="select * from ".$this->db->dbprefix('product')." where id=".$productid;
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

	function getappkey( $uid){
		$slq ="select * from ".$this->db->dbprefix('gcmappkeys')." where user_id=".$uid;
		$res = $this -> db ->query($slq);
		if($res!=null&& $res->num_rows()>0){
			$arr= $res->result_array();
			return $arr[0]['appkey'];
		}
	}

	function getumsappkey($productid){
		$slq ="select * from ".$this->db->dbprefix('product')." where id=".$productid;
		$res = $this -> db ->query($slq);
		if($res!=null&& $res->num_rows()>0){
			$arr= $res->result_array();
			return $arr[0]['product_key'];
		}
	}
	

}
