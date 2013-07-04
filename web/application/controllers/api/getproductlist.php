<?php
class getproductlist extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/getproductlistmodel','productlist');
		$this->load->model('api/common','common');
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
		$getinfo = $_POST ["content"];       
		log_message ( "debug", $getinfo );
		$content = json_decode($getinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		$ret=$this->productlist->getproductinfo($content->sessionkey);
		echo json_encode($ret);
	}
}