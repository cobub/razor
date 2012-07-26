<?php

class Pagevisit extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'common' );		
		$this->common->requireLogin ();
		$this->load->model('product/productmodel','product');
		$this->load->model('product/pagemodel','page');
		
	}
	function index()
	{
		$this->common->loadHeader();	
		$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
		$fromTime = $currentProduct->date;
		$this->data['version']=$this->page->getallVersionBasicData($fromTime,$toTime,$currentProduct->id);
		$this->load->view('pagevisit/pageview',$this->data);
	}
	
	function getPageInfo($timePhase,$version="all",$pageIndex=0,$fromDate='',$toDate='')
	{
		$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
		
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
		}
		if($timePhase == "1month")
		{
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
		
		if($timePhase == "3month")
		{
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
		
		if($timePhase == "all")
		{
			$fromTime = $currentProduct->date;
		}
		
		if($timePhase == "any")
		{
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
		
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$rowArray = $this->page->getVersionBasicData($fromTime,$toTime,$currentProduct->id);
		//print_r($rowArray);
		echo json_encode($rowArray);
	}
}