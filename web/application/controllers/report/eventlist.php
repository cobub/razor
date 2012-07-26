<?php
class Eventlist extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'event/userEvent', 'event' );
		$this->load->model ( 'product/versionmodel', 'versionmodel' );
		$this->common->requireLogin ();
		
	}
	
	function index() {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;		
		$this->data ['event'] = $this->event->getEventListByProductIdAndProductVersion ( $productId, 'all' );
		$this->data ['versions'] = $this->event->getProductVersions ( $productId );
		$this->data ['current_version'] = 'all';
		$this->load->view ( 'report/eventlistview', $this->data );
	}
	
	function getEventListData($version = '') {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;	
		
		$this->data ['event'] = $this->event->getEventListByProductIdAndProductVersion ( $productId, $version );
		    
		$this->data ['versions'] = $this->event->getProductVersions ( $productId );
		$this->data ['current_version'] = $version;
		$this->load->view ( 'report/eventlistview', $this->data );
	}
	
	
	
	function getEventDeatil($event_sk,$version,$event_name)
	{
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ()->id;
		$this->data['event_sk'] = $event_sk;
		$this->data['event_version'] = $version;
		$this->data['event_name'] = $event_name;
		$this->load->view ( 'report/eventchartdetailview', $this->data );	
	
	}
	

	function getChartDataAll($event_sk,$version,$timePhase,$from='',$to='')
	{
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
			
			$fromTime = $from;
			$toTime = $to;
			;
			
		}
		$productId = $this->common->getCurrentProduct ()->id;
		$ret = array();
	    $data = $this->event->getAllEventChartData($productId,$event_sk,$version,$fromTime,$toTime);
	    $result = json_encode($data->result());
	    echo $result;
	
	}
    

	
	
}