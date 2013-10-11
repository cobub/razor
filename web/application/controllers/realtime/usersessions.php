<?php
class Usersessions extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->Model ( 'common' );
		$this->load->model ( 'realtime/onlineusermodel', 'onlineusermodel' );
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
		$this->load->view('realtime/usersessionsview',$data);
	}
	
	function getOnlineUsers($productId)
	{
		$ret = $this->onlineusermodel->getOnlineUsers($productId);
		echo json_encode($ret);
	}
}