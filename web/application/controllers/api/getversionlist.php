<?php
class getversionlist extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getversioninfo','versioninfo');
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
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		$ret=$this->versioninfo->getversion($content->sessionkey,$content->productid);
		echo json_encode($ret);
	}
}
