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
class Product extends CI_Model {
	function __construct() {
		$this->load->database ();
		$this->load->model ( 'common' );
	}
	
	// //get one platform all product
	function getProductListByPlatform($platformId) {
		
		$getIDsql = "select p.id,p.name,f.name platform from " . $this->db->dbprefix ( 'product' ) . "  p,
		" . $this->db->dbprefix ( 'platform' ) . "  f
		where p.product_platform = f.id  and p.active = 1 and  p.product_platform = $platformId 
		order by p.id desc;";
		
		$getIDsqlResult = $this->db->query ( $getIDsql );
		
		if ($getIDsqlResult != null && $getIDsqlResult->num_rows () > 0) {
			return $getIDsqlResult->result_array();
		}
		
		return null;
	}
	
	// //get application all version
	function getversionbyproductid($productid)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct version_name
		from " . $dwdb->dbprefix ( 'sum_basic_product_version' ) . "
		where product_id='$productid'";
		$query = $dwdb->query ( $sql );
		if($query!=null&&$query->num_rows()>0)
		{
			$query=$query->result_array();
			return $query;
		}

		return null;
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