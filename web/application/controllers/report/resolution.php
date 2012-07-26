<?php
class Resolution extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->model ( 'product/orientationmodel', 'orientationmodel' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->model ( 'product/newusermodel', 'newusermodel' );
		$this->common->requireLogin ();
		
	}
	
	function index() {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		$this->data ['activeuser'] = $this->orientationmodel->getActiveUsersPercentByOrientation ( $fromTime, $toTime, $productId );
		$this->data ['newuser'] = $this->orientationmodel->getNewUsersPercentByOrientation ( $fromTime, $toTime, $productId );
		$this->data ['operator'] = $this->orientationmodel->getpageresolution( $fromTime, $toTime, $productId );
		$resultnum=$this->orientationmodel->getTotalUsersPercentByResolution ( $fromTime, $toTime, $productId );
		$this->data ['num'] = $resultnum->num_rows();
		$this->data ['timetype'] = '7day';
		$this->load->view ( 'terminalandnet/resolutionview', $this->data );
	}
	//获得分页的分辨率信息
	function genresolution($fromTime, $toTime,$pageindex)
	{    $percent=100;
		$pagenum=$pageindex*REPORT_TOP_TEN;
		$productId = $this->common->getCurrentProduct ()->id;
		$query=$this->orientationmodel->getpageresolution($fromTime, $toTime, $productId,$pagenum);
		$htmlText = "";
		if($query!= null &&$query->num_rows () > 0)
		{
			foreach ($query->result_array()as $row)
			{		
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td>".$row['deviceresolution_name']."</td>";
				$htmlText = $htmlText."<td>".round($percent*$row['percentage'],2)."%</td>";				
				$htmlText = $htmlText."</tr>";
			}		
		echo $htmlText;
	}
	}
	
	function getResolutionData($timePhase, $start = '', $end = '') {
		
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
			$this->data ['from'] = $start;
			$this->data ['to'] = $end;
		
		}
		$this->data ['timetype'] = $timePhase;
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->data ['activeuser'] = $this->orientationmodel->getActiveUsersPercentByOrientation ( $fromTime, $toTime, $productId );
		$this->data ['newuser'] = $this->orientationmodel->getNewUsersPercentByOrientation ( $fromTime, $toTime, $productId );
		$this->data ['operator'] = $this->orientationmodel->getTotalUsersPercentByResolution ( $fromTime, $toTime, $productId );
		
		$this->load->view ( 'terminalandnet/resolutionview', $this->data );
	
	}
	
	function export($from, $to) {
		$this->load->library ( 'export' );
		
		$productId = $this->common->getCurrentProduct ()->id;
		$productName = $this->common->getCurrentProduct ()->name;
		$data = $this->orientationmodel->getTotalUsersPercentByResolution( $from, $to, $productId );
		$export = new Export ();
		//设定文件名
		$export->setFileName ( $productName . '_' . $from . '_' . $to . '.csv' );
		//输出列名
//			//输出列名第一种方法
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}		
		$export->setTitle ( $fields );
   //输出列名第二种方法
   //  $excel_title = array (iconv("UTF-8", "GBK", "分辨率"),iconv("UTF-8", "GBK", "用户比例") );
  //			$export->setTitle ($excel_title );
		//输出内容
		foreach ( $data->result () as $row )
			$export->addRow ( $row );
		$export->export ();
		die ();
	}

}