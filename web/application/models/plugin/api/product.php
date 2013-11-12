<?php
class Product extends CI_Model {
	function __construct() {
		$this->load->database ();
		$this->load->model ( 'common' );
	}
	
	// //get one platform all product
	function getProductListByPlatform($platformId) {
		
		$getIDsql = "select p.id,p.name,f.name
		from " . $this->db->dbprefix ( 'product' ) . "  p,
		" . $this->db->dbprefix ( 'platform' ) . "  f
		where p.product_platform = f.id  and p.active = 1 and  p.product_platform = $platformId 
		order by p.id desc;";
		
		$getIDsqlResult = $this->db->query ( $getIDsql );
		
		if ($getIDsqlResult != null && $getIDsqlResult->num_rows () > 0) {
			return $getIDsqlResult->result ();
		}
		
		return null;
	}
	
	// //platform:1=>Android,2=>iOS,3=>windows Phone;parameter:$plat=(1,2,3)
	function getApplistByPlatform($plat) {
		$data = array (
				'active' => 1,
				'product_platform' => $plat 
		);
		
		$this->db->from ( $this->db->dbprefix ( 'product' ) );
		$this->db->where ( $data );
		$query = $this->db->get ();
		
		if ($query->num_rows () > 0) {
			return $query->result ();
		} else {
			return null;
		}
	}
	
	// //get application all version
	function getProductAllVersionsById($productid) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct version_name
		from " . $dwdb->dbprefix ( 'sum_basic_product_version' ) . "
		where product_id='$productid'";
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0) {
			return $query->result_array ();
		} else {
			return false;
		}
	}
	
	//get application create time
	function getProductCreateTimeById($productid) {
		$data = array (
				'id' => $productid
		);
		
		$this->db->from ( $this->db->dbprefix ( 'product' ) );
		$this->db->where ( $data );
		$query = $this->db->get ();
		
		if ($query->num_rows () > 0) {
			return $query->first_row ()->date;
		} else {
			return null;
		}
	
	}
	
	//set current product
	function setCurrentProduct($productId) {
		$this->common->setCurrentProduct ( $productId );
	}
	
	//get current selected app
	function getCurrentProduct() {
		$this->common->getCurrentProduct ();
	}
	
	//clean current product
	function cleanCurrentProduct() {
		$this->common->cleanCurrentProduct ();
	}

}