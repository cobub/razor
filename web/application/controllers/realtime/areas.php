<?php
class Areas extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->Model ( 'common' );
		$this->load->model ( 'realtime/onlineusermodel', 'onlineusermodel' );
		$this->load->model ( 'realtime/areamodel','areamodel');
		$this->common->requireLogin ();
	}
	
	function index()
	{
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$data['productId'] = $productId;
		$data['reportTitle'] = array(
				'title' => lang ( 'v_rpt_realtime_onlineuser_title' ),
				'subtitle' => lang ( 'v_rpt_realtime_onlineuser_subtitle' )
				);
		$this->common->loadHeader(lang ( 'v_rpt_realtime_onlineuser_title' ));
		$this->load->view('realtime/areas',$data);
	}
	
	function getOnlineUsers($productId)
	{
		$ret = $this->onlineusermodel->getOnlineUsers($productId);
		echo json_encode($ret);
	}
	
	function getAreasData($productId)
	{
		$ret = $this->areamodel->getAreasData($productId);
		echo json_encode($ret);
	}
	
	function getBubbleAreasData($productId) {
		$ret = $this->areamodel->getBubbleAreasData($productId);
		echo json_encode($ret);

	}
	
	function getAreaDataForGrid($productId) {
		$ret = $this->areamodel->getAreaDataForGrid($productId);
		echo json_encode($ret);
	}
	
	function getDetailRegionsInfo($productId,$countryName) {
		$data["regions"] = $this->areamodel->getRegionsByCountry($productId,$countryName);
		$this->load->view("realtime/regiondetail",$data);
	}
}