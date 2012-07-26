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
class Common extends CI_Model {
	function __construct() {
		parent::__construct ();				
		$this->load->library ( 'session' );
		$this->load->helper ( 'url' );
		$this->load->library ( 'tank_auth' );
		$this->load->library ( 'ums_acl' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->library ( 'export' );		
		$this->load->database ();

	}
	
	function getdbprefixtable($name)
	{
	   $tablename=$this->db->dbprefix($name);	  
	   return $tablename;
	}
	
	function getMaxY($count) {
		if ($count <= 5) {
			return 5;
		} else {
			return $count + $count / 10;
		}
	}
	
	
	function getTimeTick($days)
	{
		if($days<=7)
		{
			return 1;
		}
		
		if($days >7 && $days<=30)
		{
			return 2;
		}
		
		if($days >30 && $days<=90)
		{
			return 10;
		}
		
		if($days >90 && $days<= 270)
		{
			return 30;
		}
		
		if($days>270 && $days<=720)
		{
			return 70;
		}
		return 1;
	}
	
	function getStepY($count) {
		if ($count <= 5) {
			return 1;
		} else {
			return round ( $count / 5, 0 );
		}
	}
	
	function loadHeader() {
		if (! $this->common->isUserLogin ()) {
			$dataheader ['login'] = false;
			$this->load->view ( 'layout/header', $dataheader );
			//$this->load->view ( 'chinanetdemo', $dataheader );
		} else {
			$dataheader ['user_id'] = $this->getUserId ();
			$dataheader ['pageTitle'] = $this->getPageTitle ( $this->router->fetch_class () );
			if ($this->isAdmin ()) {
				$dataheader ['admin'] = true;
			}
			$dataheader ['login'] = true;
			$dataheader ['username'] = $this->getUserName ();
			$product = $this->getCurrentProduct ();
			if (isset ( $product ) && $product != null) {
				$dataheader ['product'] = $product;
				log_message ( "error", "HAS Product" );
			}
			
			$productList = $this->product->getAllProducts ( $dataheader ['user_id'] );
			if ($productList != null && $productList->num_rows () > 0) {
				$dataheader ["productList"] = $productList;
			}
			log_message ( "error", "Load Header 123" );
		$this->load->view ( 'layout/header', $dataheader );
		//$this->load->view ( 'chinanetdemo', $dataheader );
		}
	}
	
	function show_message($message) {
		$this->session->set_userdata ( 'message', $message );
		redirect ( '/auth/' );
	}
	
	function requireLogin() {
		if (! $this->tank_auth->is_logged_in ()) {
			redirect ( '/auth/login/' );
		}
	}
	
	function isAdmin() {
		$userid = $this->tank_auth->get_user_id ();
		$role = $this->getUserRoleById ( $userid );
		if ($role == 3) {
			return true;
		}
		return false;
	}
	
	function getUserId() {
		return $this->tank_auth->get_user_id ();
	}
	
	function getUserName() {
		return $this->tank_auth->get_username ();
	}
	
	function isUserLogin() {
		return $this->tank_auth->is_logged_in ();
	}
	
	function canRead($controllerName) {
		$id = $this->getResourceIdByControllerName ( $controllerName );
		if ($id) {
			$acl = new Ums_acl ();
			$userid = $this->tank_auth->get_user_id ();
			$role = $this->getUserRoleById ( $userid );
			
			if ($acl->can_read ( $role, $id )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getPageTitle($controllerName) {
		$this->db->where ( 'name', $controllerName );
		$query = $this->db->get ( 'user_resources' );
		if ($query != null && $query->num_rows () > 0) {
			return $query->first_row ()->description;
		}
		return "";
	}
	
	//private functiosn
	function getResourceIdByControllerName($controllerName) {
		$this->db->where ( 'name', $controllerName );
		$query = $this->db->get ( 'user_resources' );
		if ($query != null && $query->num_rows () > 0) {
			return $query->first_row ()->id;
		}
		return null;
	}
	
	function getUserRoleById($id) {
		if ($id == '')
			return '2';
		$this->db->select ( 'roleid' );
		$this->db->where ( 'userid', $id );
		$query = $this->db->get ( 'user2role' );
		$row = $query->first_row ();
		if ($query->num_rows > 0) {
			return $row->roleid;
		} else
			return '2';
	}
	
	function getCurrentProduct() {
		$currentProduct = $this->session->userdata ( "currentProduct" );
		if (isset ( $currentProduct )) {
			return $currentProduct;
		} else {
			redirect ( '/auth/login/' );
		}
	}
	
	function cleanCurrentProduct() {
		$this->session->unset_userdata ( "currentProduct" );
	}
	
	function setCurrentProduct($productId) {
		$currentProduct = $this->product->getProductById ( $productId );
		$this->session->set_userdata ( "currentProduct", $currentProduct );
	}
	
	function export($from, $to,$data) {
		$productId = $this->getCurrentProduct ()->id;
		$productName = $this->getCurrentProduct ()->name;
		
		$export = new Export ();
		//设定文件名
		$export->setFileName ( $productName . '_' . $from . '_' . $to . '.xls' );
		//输出列名
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}
		$export->setTitle ( $fields );
		//输出内容
		foreach ( $data->result () as $row )
			$export->addRow ( $row );
		$export->export ();
		die ();
	}

}