<?php
class Transrate extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->Model ( 'common' );
		$this->load->model ( 'realtime/transRateModel', 'transratemodel' );
		$this->common->requireLogin ();
	}
	
	function index()
	{
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$data['productId'] = $productId;
		$data['reportTitle'] = array(
				'title' => lang('v_rpt_realtime_transrate_title'),
				'subtitle' => lang('v_rpt_realtime_transtrte_subtitle')
				);
		$this->common->loadHeader(lang('v_rpt_realtime_transrate_title'));
		$this->load->view('realtime/transrateview',$data);
	}
	
	function getTransrate($productId)
	{
		$ret = $this->transratemodel->getTransRate($productId);
		echo json_encode($ret);
	}
	
	function getTransrateByTime($productId)
	{
		$ret = $this->transratemodel->getTransRateByTime($productId);
		echo json_encode($ret);
	}
}