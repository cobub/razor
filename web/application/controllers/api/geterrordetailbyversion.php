<?php
class geterrordetailbyversion extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/geterrordetailbyversionmodel','errordetailbyversion');
	}

	function index()
	{
		if (! isset ( $_POST ["content"] ))
		{

			$ret = array (
					'flag' => -1,
					'msg' => 'Invalid content.'
			);
			echo json_encode ( $ret );
			return;
		}
		$activeinfo = $_POST ["content"];
		log_message ( "debug", $activeinfo );
		$content = json_decode($activeinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		$ret=$this->errordetailbyversion->geterrordetailbyversion($content->sessionkey,$content->productid,$content->startdate,$content->enddate);
		echo json_encode($ret);
	}
}