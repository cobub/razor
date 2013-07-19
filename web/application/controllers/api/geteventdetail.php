<?php
class geteventdetail extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/geteventdetailmodel','eventdetail');
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
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate','eventid'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		if(isset($content->limit))
		{
			$ret=$this->eventdetail->geteventdetaildata($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->eventid,$content->version);
		}
		else
		{
			$ret=$this->eventdetail->geteventdetaildata($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->eventid,$version=null);
		}
		echo json_encode($ret);
	}
}