<?php

class market extends CI_Controller{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->Model('common');
		$this->load->model('channelmodel','channel');
		$this->load->model('product/productmodel','product');
		$this->load->model('product/newusermodel','newusermodel');
		$this->common->requireLogin();
		
	}
	
	function viewMarket($market='default',$timePhase='7day',$type='new',$start ='',$end='')
	{
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
	    if ($timePhase == "7day") {
			
			$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
			$data ['timetype'] = '7day';
		}
		
		if ($timePhase == "1month") {
			
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
			$data ['timetype'] = '1month';
		}
		
		if ($timePhase == "3month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
			$data ['timetype'] = '3month';
		}
		if ($timePhase == "all") {
			
			$fromTime = 'all';
			$data ['timetype'] = 'all';
		}
		
		if ($timePhase == 'any') {
			
			$fromTime = $start;
			$toTime = $end;
			$data ['timetype'] = 'any';
			
		}
		
		$this->load->helper('open-flash-chart');
		$product = $this->common->getCurrentProduct();
		$productId = $product->id;
		$data['productId'] = $productId;
		
		$today = date ( 'Y-m-d', time () );
		$yestodayTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
		$seven_day = date ( "Y-m-d", strtotime ( "-7 day" ) );
		$thirty_day = date ( "Y-m-d", strtotime ( "-30 day" ) );

		
		$sevendayactive=$this->product->getActiveUsersNum($seven_day,$today,$productId);
		$data['sevendayactive']=$sevendayactive;
		$thirty_day_active=$this->product->getActiveUsersNum($thirty_day,$today,$productId);
		$data['thirty_day_active']=$thirty_day_active;
		$todayData = $this->product->getAnalyzeDataByDateAndProductID($today,$productId);
	    $yestodayData = $this->product->getAnalyzeDataByDateAndProductID($yestodayTime,$productId);

	    $data['count'] = $todayData->num_rows();
		$data ['todayData'] = $todayData;
		$data['yestodayData']=$yestodayData;
		$this->common->loadHeader();
		$this->load->view('product/productmarket',$data);
		
	}
	
	function getMarketData($market,$time,$type='new',$start='',$end='')
	{
		
		
		$product = $this->common->getCurrentProduct();
		$productId = $product->id;
		$markets = $this->product->getProductChanelById($productId);
		$ret = array();
		if($markets!=null && $markets->num_rows()>0)
		{
			foreach ($markets->result() as $row)
			{
				if($type=="weekactive"||$type=="monthactive"){
					$data = $this->	product->getActiveNumber($row->channel_id,$start,$end,$time,$type);
				}else{
					$data = $this->product->getAllMarketData($row->channel_id,$start,$end,$time);
					
				}
			}
		}else{
			$data="";
		}
		
		$result = json_encode($data);
		echo  $result;
	}
	
	
}

?>