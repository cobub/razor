<?php
class Network extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();		
		$this->load->Model('common');
		$this->load->model('product/networkmodel','network');
		$this->load->model('product/productmodel','product');
		$this->common->requireLogin();
		 
	}
	
	function index()
	{
		
		$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct()->id;
		$toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));	
		$this->data['activeUsernetworktype'] = $this->network->getActiveUserNetWorkType($fromTime,$toTime,$productId);
		$this->data['newUsernetworktype'] = $this->network->getNewUserNetWorkType($fromTime,$toTime,$productId);
		$this->data['totalnetworktype'] = $this->network->getALlNetWorkData($productId);
		
		$this->data['timetype'] = '7day';
		
		$this->load->view('terminalandnet/networkview', $this->data);
		
		
	}
	
	function getNetWorkData($timePhase,$start='',$end='')
	{
	
		$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct()->id;
		
	    $toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));
		
		if($timePhase == "7day")
		{			
			$fromTime = date('Y-m-d',strtotime("-7 day"));			
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
			$fromTime = 'all';			
		}
		
		if($timePhase == 'any')
		{			
			$fromTime = $start;
			$toTime = $end;			
	    }
	    $this->data['timetype'] = $timePhase;
	    $this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		
		$this->data['activeUsernetworktype'] = $this->network->getActiveUserNetWorkType($fromTime,$toTime,$productId);
		
		$this->data['newUsernetworktype'] = $this->network->getNewUserNetWorkType($fromTime,$toTime,$productId);
		$this->data['totalnetworktype'] = $this->network->getALlNetWorkData($productId);
		
		
	     
		$this->load->view('terminalandnet/networkview', $this->data);
	
	}
}