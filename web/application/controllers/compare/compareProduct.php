<?php
class CompareProduct extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('common');
		$this->load->library ( 'session' );
		$this->load->helper ( 'url' );
		$this->load->library ( 'tank_auth' );
		$this->load->library ( 'ums_acl' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->library ( 'export' );
		$this->load->database ();
	}
	
	public function index(){
		$pids=$_POST['pids'];
		$this->common->setCompareProducts($pids);
		$this->common->cleanCurrentProduct();
		echo json_encode('ok');
	}
	
	public function compareConsole(){
		$this->common->loadCompareHeader(lang('m_rpt_dashboard'),TRUE);
		$this->load->view('compare/userbehavorview');
	}
}