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
	function getdbprefixtable($name) {
		$tablename = $this->db->dbprefix ( $name );
		return $tablename;
	}
	function getMaxY($count) {
		if ($count <= 5) {
			return 5;
		} else {
			return $count + $count / 10;
		}
	}
	function getTimeTick($days) {
		if ($days <= 7) {
			return 1;
		}
		
		if ($days > 7 && $days <= 30) {
			return 2;
		}
		
		if ($days > 30 && $days <= 90) {
			return 10;
		}
		
		if ($days > 90 && $days <= 270) {
			return 30;
		}
		
		if ($days > 270 && $days <= 720) {
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
	function loadCompareHeader($viewname = "", $showDate = TRUE){
		$this->load->helper ( 'cookie' );
		if (! $this->common->isUserLogin ()) {
			$dataheader ['login'] = false;
			$this->load->view ( 'layout/compareHeader', $dataheader );
			// $this->load->view ( 'chinanetdemo', $dataheader );
		} else {
			$dataheader ['user_id'] = $this->getUserId ();
			$dataheader ['pageTitle'] = lang("c_".$this->router->fetch_class ());
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
			$products=$this->getCompareProducts();
			$dataheader['products']=$products;
			log_message ( "error", "Load Header 123" );
			$dataheader ['language'] = $this->config->item ( 'language' );
			$dataheader ['viewname'] = $viewname;
			if ($showDate) {
				$dataheader ["showDate"] = true;
			}
			$this->load->view ( 'layout/compareHeader', $dataheader );
			// $this->load->view ( 'chinanetdemo', $dataheader );
		}
	}
	
	function checkCompareProduct(){
		$products=$this->common->getCompareProducts();
		if(isset($_GET['type'])&&'compare'==$_GET['type']){
			if(empty($products)||count($products)<2||count($products)>4){
				redirect(base_url());
			}
		}
	}
	function loadHeaderWithDateControl($viewname = "") {
		$this->loadHeader ( $viewname, TRUE );
	}
	function loadHeader($viewname = "", $showDate = FALSE) {
		$this->load->helper ( 'cookie' );
		if (! $this->common->isUserLogin ()) {
			$dataheader ['login'] = false;
			$this->load->view ( 'layout/header', $dataheader );
			// $this->load->view ( 'chinanetdemo', $dataheader );
		} else {
			$dataheader ['user_id'] = $this->getUserId ();
			$dataheader ['pageTitle'] = lang("c_".$this->router->fetch_class ());
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
			$dataheader ['language'] = $this->config->item ( 'language' );
			$dataheader ['viewname'] = $viewname;
			if ($showDate) {
				$dataheader ["showDate"] = true;
			}
			$this->load->view ( 'layout/header', $dataheader );
			// $this->load->view ( 'chinanetdemo', $dataheader );
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
	
	function requireProduct()
	{
		$product = $this->getCurrentProduct();
		if(empty($product))
		{
			redirect(site_url());
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
	
	// private functiosn
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
		return $currentProduct;
	}
	function getCurrentProductIfExist() {
		$currentProduct = $this->session->userdata ( "currentProduct" );
		if (isset ( $currentProduct )) {
			return $currentProduct;
		} else {
			return false;
		}
	}
	function cleanCurrentProduct() {
		$this->session->unset_userdata ( "currentProduct" );
	}
	function setCurrentProduct($productId) {
		$currentProduct = $this->product->getProductById ( $productId );
		$this->session->set_userdata ( "currentProduct", $currentProduct );
	}
	
	function setCompareProducts($productIds=array()){
		$this->session->set_userdata('compareProducts',$productIds);
	}
	
	function getCompareProducts(){
		$pids=  $this->session->userdata ( "compareProducts" );
		$products=array();
		for($i=0;$i<count($pids);$i++){
			$product=$this->product->getProductById ( $pids[$i] );
			$products[$i]=$product;
		}
		return $products;
	}
	function changeTimeSegment($pase, $from, $to) {
		$toTime = date ( 'Y-m-d', time () );
		switch ($pase) {
			case "7day" :
				{
					$fromTime = date ( "Y-m-d", strtotime ( "-6 day" ) );
				}
				break;
			case "1month" :
				{
					$fromTime = date ( "Y-m-d", strtotime ( "-31 day" ) );
				}
				break;
			case "3month" :
				{
					$fromTime = date ( "Y-m-d", strtotime ( "-92 day" ) );
				}
				break;
			case "all" :
				{
					$currentProduct = $this->getCurrentProductIfExist ();
					if ($currentProduct) {
						$fromTime = $this->product->getReportStartDate ( $currentProduct, '1970-02-01' );
						$fromTime = date ( "Y-m-d", strtotime ( $fromTime ) );
					} else {
						$fromTime = $this->product->getUserStartDate ( $this->getUserId (), '1970-01-01' );
						$fromTime = date ( "Y-m-d", strtotime ( $fromTime ) );
					}
				}
				break;
			case "any" :
				{
					$fromTime = $from;
					$toTime = $to;
				}
				break;
			default :
				{
					$fromTime = date ( "Y-m-d", strtotime ( "-6 day" ) );
				}
				break;
		}
		if($fromTime>$toTime){
			$tmp=$toTime;
			$toTime=$fromTime;
			$fromTime=$tmp;	
		}
		$this->session->set_userdata ( 'fromTime', $fromTime );
		$this->session->set_userdata ( 'toTime', $toTime );
	}
	function getFromTime() {
		$fromTime = $this->session->userdata ( "fromTime" );
		if (isset ( $fromTime ) && $fromTime != null && $fromTime != "") {
			return $fromTime;
		}
		$fromTime = date ( "Y-m-d", strtotime ( "-6 day" ) );
		return $fromTime;
	}
	
	function getPredictiveValurFromTime(){
		$time = $this->getFromTime();
		$fromTime = date ( "Y-m-d", strtotime ( "$time -5 day" ) );
		return $fromTime;
	}
	
	function getToTime() {
		$toTime = $this->session->userdata ( "toTime" );
		if (isset ( $toTime ) && $toTime != null && $toTime != "") {
			return $toTime;
		}
		$toTime = date ( 'Y-m-d', time () );
		return $toTime;
	}
	function getDateList($from,$to){
		$defdate=array();
		for ($i=strtotime($from);$i<=strtotime($to);$i+=86400){
			if(!in_array(date('Y-m-d',$i), $defdate))
				array_push($defdate, date('Y-m-d',$i));
		}
		return $defdate;
	}
	function export($from, $to, $data) {
		$productId = $this->getCurrentProduct ()->id;
		$productName = $this->getCurrentProduct ()->name;
		
		$export = new Export ();
		// 设定文件名
		$export->setFileName ( $productName . '_' . $from . '_' . $to . '.xls' );
		// 输出列名
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}
		$export->setTitle ( $fields );
		// 输出内容
		foreach ( $data->result () as $row )
			$export->addRow ( $row );
		$export->export ();
		die ();
	}
}