<?php
class Pageviews extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->Model ( 'common' );
		$this->load->model ( 'realtime/onlineusermodel', 'onlineusermodel' );
		$this->load->model( 'realtime/pageviewmodel','pageviewmodel');
		$this->common->requireLogin ();
	}
	
	function index()
	{
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$data['productId'] = $productId;
		$data['reportTitle'] = array(
				'title' => lang ( 'v_rpt_realtime_pageviews_title' ),
				'subtitle' => lang ( 'v_rpt_realtime_pageviews_subtitle' )
				);
		$this->common->loadHeader(lang ( 'v_rpt_realtime_pageviews_title' ));
		$this->load->view('realtime/pageviews',$data);
	}
	
	function getActivityByMinutes($productId)
	{
		echo $this->pageviewmodel->getActivityByMinutes($productId);
	}
	
	function getActivities($productId)
	{
		echo $this->pageviewmodel->getActivities($productId);
	}
}