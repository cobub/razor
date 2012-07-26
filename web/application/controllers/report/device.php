<?php
class Device extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->model ( 'product/devicemodel', 'device' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->model ( 'product/newusermodel', 'newusermodel' );
		$this->common->requireLogin ();
		
	}
	
	function index() {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		$this->data ['activeuser'] = $this->device->getActiveUsersPercentByDevice ( $fromTime, $toTime, $productId );
		$this->data ['newuser'] = $this->device->getNewUserPercentByDevice ( $fromTime, $toTime, $productId );
		$this->data ['operator'] = $this->device->getDeviceTypeDetail($productId);
		$this->data ['timetype'] = '7day';
		//$this->data ['num'] = $this->device->getTotalUsersnum ( $fromTime, $toTime, $productId );
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->load->view ( 'terminalandnet/deviceview', $this->data );
	}
	
	function getDeviceData($timePhase, $start = '', $end = '') {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;
		
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		
		if ($timePhase == "7day") {
			
			$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
			$this->data ['timetype'] = '7day';
		
		}
		
		if ($timePhase == "1month") {
			
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
			$this->data ['timetype'] = '1month';
		
		}
		
		if ($timePhase == "3month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
			$this->data ['timetype'] = '3month';
		
		}
		if ($timePhase == "all") {
			
			$fromTime = 'all';
			$this->data ['timetype'] = 'all';
		
		}
		
		if ($timePhase == 'any') {
			
			$fromTime = $start;
			$toTime = $end;
			$this->data ['timetype'] = 'any';
		
		}
		
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->data ['activeuser'] = $this->device->getActiveUsersPercentByDevice ( $fromTime, $toTime, $productId );
		$this->data ['newuser'] = $this->device->getNewUserPercentByDevice ( $fromTime, $toTime, $productId );
		$this->data ['operator'] = $this->device->getDeviceTypeDetail (  $productId );
		//$this->data ['num'] = $this->device->getTotalUsersnum ( $fromTime, $toTime, $productId );		
		$this->load->view ( 'terminalandnet/deviceview', $this->data );
	
	}
	
	function export($from, $to) {
		$productId = $this->common->getCurrentProduct ()->id;
		$productName = $this->common->getCurrentProduct ()->name;
		$data = $this->device->getDeviceTypeDetail (  $productId );
		
		$this->load->library ( 'export' );
		$export = new Export ();
		//设定文件名
		$export->setFileName ( $productName . '_' . $from . '_' . $to . '.csv' );
		//输出列名第一种方法
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}		
		$export->setTitle ( $fields );
       //输出列名第二种方法
//        $excel_title = array (iconv("UTF-8", "GBK", "设备型号"),iconv("UTF-8", "GBK", "总数"),iconv("UTF-8", "GBK", "用户比例") );
//			$export->setTitle ($excel_title );
		//输出内容
		foreach ( $data->result () as $row )
			$export->addRow ( $row );
		$export->export ();
		die ();
	}
	//设置分页
//	function devicepage( $pagenum) {
//		$percent = 100;
//		$currentProduct = $this->common->getCurrentProduct ();
//		$productid = $currentProduct->id;
//		$pagenum = $pagenum * PAGE_NUMS;
//		//$devicepage = $this->device->getDeviceTypeDetail($productid, $pagenum, PAGE_NUMS );
//		$htmlText = "";
//		$array = $this->data['operator']->result();
//	    for ( $i=$pagenum;$i<$pagenum+PAGE_NUMS && $i<count($array);$i++) {
//		        $row = $array[$i];
//				$htmlText = $htmlText . "<tr>";
//				$htmlText = $htmlText . "<td>" . $row ['devicebrand_name'] . "</td>";
//				$htmlText = $htmlText . "<td>" . $percent * $row ['percentage'] . "%</td>";
//				$htmlText = $htmlText . "</tr>";
//			}
//		
//			echo $htmlText;
//		}
	

}