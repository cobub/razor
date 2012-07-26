<?php
class Userremain extends CI_Controller {
	
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		
		$this->load->Model ( 'common' );
		$this->load->model ( 'product/userremainmodel', 'userremain' );
		$this->common->requireLogin ();
		$this->load->library ( 'export' );
	
	}
	
	function index() {
		$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct ()->id;
		$to = date ( 'Y-m-d', time () );
		$from = date ( 'Y-m-d', strtotime ( "-30 day" ) );
		$timePhase = '1month';
		$data['userremain'] = $this->userremain->getUserRemainCountByWeek($timePhase,$productId,$from,$to);
		$data['userremain_m'] = $this->userremain->getUserRemainCountByMonth($timePhase,$productId,$from,$to);
		$data['userremain_json'] = json_encode($data['userremain_m']->result());
		$this->load->view('user/userremainview',$data);
	}
	
	function getData($timePhase,$type,$m_from='',$m_to='') 
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
			$fromTime = $m_from;
			$toTime = $m_to;
			$data ['timetype'] = 'any';
		}
		
	//	$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct ()->id;
		$resultArray = array(); 
		if($type=='month'){
			
			$resultArray['datas'] = $this->userremain->getUserRemainCountByMonthJSON($timePhase,$productId,$fromTime,$toTime);
			
		}else{
			$resultArray['datas'] = $this->userremain->getUserRemainCountByWeekJSON($timePhase,$productId,$fromTime,$toTime);
			
		}
		$resultArray['type']=$type;
	//	$resultArray['userremain_json'] = json_encode($data['userremain_m']->result());
		//$this->load->view('user/userremainview',$data);
		
		$result= json_encode($resultArray);
		//print_r($result);	
		echo $result;
	}
}