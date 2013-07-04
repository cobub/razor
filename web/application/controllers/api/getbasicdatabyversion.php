<?php
class getbasicdatabyversion extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getbasicversion','basicvesion');
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
		$versioninfo = $_POST ["content"];
		log_message ( "debug", $versioninfo );
		$content = json_decode($versioninfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		$ret=$this->basicvesion->getversiondata($content->sessionkey,$content->productid,$content->startdate,$content->enddate);
		echo json_encode($ret);
	}
}