<?php
class Os extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->model ( 'product/osmodel', 'os' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->model ( 'product/newusermodel', 'newusermodel' );
		$this->common->requireLogin ();
		
	}
	
	function index() {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		$this->data ['timetype'] = '7day';
		$this->load->view ( 'terminalandnet/osview', $this->data );
	}
	
	function getOsData($timePhase,$isfirst, $start = '', $end = '') {
		
		$productId = $this->common->getCurrentProduct ()->id;
		
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		
		if ($timePhase == "7day") {
			
			$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		
		}
		
		if ($timePhase == "1month") {
			
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
		
		}
		
		if ($timePhase == "3month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
		
		}
		if ($timePhase == "all") {
			
			$fromTime = 'all';
		
		}
		
		if ($timePhase == 'any') {
			
			$fromTime = $start;
			$toTime = $end;
			
		
		}
		$ret['datas'] = $this->os->getActiUsersPercentByOS ( $fromTime, $toTime, $productId )->result_array();
		
		$ret['datan'] = $this->os->getNewUserPercentByOS ( $fromTime, $toTime, $productId )->result_array();
		
	if($isfirst=='true'){
	$ret['totaldata'] = $this->os->getTotalUserPercentByOS  ($productId )->result_array();}
		
	
	    echo json_encode($ret);
	
	}
	
	function export($from, $to) {
		$this->load->library ( 'export' );
		
		$productId = $this->common->getCurrentProduct ()->id;
		$productName = $this->common->getCurrentProduct ()->name;
		$data = $this->os->getTotalUserPercentByOS ($productId);
		$export = new Export ();
		//设定文件名
		$export->setFileName($productName.'.csv' );
//		//输出列名第一种方法
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}
		$export->setTitle ( $fields );
     //输出列名第二种方法
//        $excel_title = array (iconv("UTF-8", "GBK", "操作系统版本"),iconv("UTF-8", "GBK", "用户比例") );
//			$export->setTitle ($excel_title );
		//输出内容
		foreach ( $data->result () as $row )
			$export->addRow ( $row );
		$export->export ();
		die ();
	}

}