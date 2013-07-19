<?php
class geterrordetaillistbyversion extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/geterrordetaillistbyversionmodel','errordetaillist');
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
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate','erroridentifier','versionname'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		$ret=$this->errordetaillist->geterrordetaillist($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->erroridentifier,$content->versionname);
		echo json_encode($ret);
	}
}