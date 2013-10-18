<?php
class Event extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->Model ( 'common' );
		$this->load->model ( 'realtime/eventModel', 'eventemodel' );
		$this->common->requireLogin ();
	}
	
	function index()
	{
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$data['productId'] = $productId;
		$data['reportTitle'] = array(
				'title' => lang("v_rpt_realtime_event_report_title"),
				'subtitle' => lang("v_rpt_realtime_event_in_minute")
				);
		$data['event_identifier']= "writeblog";
		$this->common->loadHeader( lang("v_rpt_realtime_event_report_title"));
		$this->load->view('realtime/eventview',$data);
	}
	
	function getEventNum($productId)
	{
		$ret = $this->eventemodel->getEventNumByEvent($productId);
		echo json_encode($ret);
	}
	
	function getEventNumByTime($productId)
	{
		$ret = $this->eventemodel->getEventNumByTime($productId);
		echo json_encode($ret);
	}
}