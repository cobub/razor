<?php
class getchannellist extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getchannelinfo','channelinfo');
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
		$channelinfo = $_POST ["content"];
		log_message ( "debug", $channelinfo );
		$content = json_decode($channelinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		$ret=$this->channelinfo->getchannel($content->sessionkey,$content->productid);
		echo json_encode($ret);
	}
}