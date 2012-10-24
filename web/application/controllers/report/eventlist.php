<?php
class Eventlist extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'event/userEvent', 'event' );
		$this->load->model ( 'product/versionmodel', 'versionmodel' );
		$this->common->requireLogin ();
		$this->common->requireProduct();
		$this->load->model('product/productmodel','product');
		
	}
	
	function index() {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ();	
		$productId=$productId->id;
		$this->data ['event'] = $this->event->getEventListByProductIdAndProductVersion ( $productId, 'all' );
		$this->data ['versions'] = $this->event->getProductVersions ( $productId );
		$this->data ['current_version'] = 'all';
		$this->load->view ( 'events/eventlistview', $this->data );
	}
	
	function getEventListData($version = '') {
		$this->common->loadHeader ();
		$productId = $this->common->getCurrentProduct ();	
		$productId=$productId->id;
		$this->data ['event'] = $this->event->getEventListByProductIdAndProductVersion ( $productId, $version );
		    
		$this->data ['versions'] = $this->event->getProductVersions ( $productId );
		$this->data ['current_version'] = $version;
		$this->load->view ( 'events/eventlistview', $this->data );
	}
	
	
	
	function getEventDeatil($event_sk,$version,$event_name)
	{
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$productId=$productId->id;
		$this->data['event_sk'] = $event_sk;
		$this->data['event_version'] = $version;
		$this->data['event_name'] = $event_name;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
				'timePase' => getTimePhaseStr($fromTime, $toTime),
				'eventMsgNum'=>lang("v_rpt_el_eventNum"),
				'eventMsgNumActive'=>lang("v_rpt_el_eventNumA"),				
				'eventMsgNumSession'=>lang("v_rpt_el_eventNumS")
		);
		$this->load->view ( 'events/eventchartdetailview', $this->data );
	}
	

	function getChartDataAll($event_sk,$version)
	{    
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;		
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		//$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$ret = array();
	    $data = $this->event->getAllEventChartData($productId,$event_sk,$version,$fromTime,$toTime);
	    $result = json_encode($data->result());
	    echo $result;
	
	}
    

	
	
}