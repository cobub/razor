<?php 
class geterrordetaillistbydevice extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/geterrordetaillistbydevicemodel','geterrordetaillistbydevice');
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
	    $basicinfo = $_POST ["content"];
		log_message ( "debug", $basicinfo );
		$content = json_decode($basicinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate','erroridentifier'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
			$ret=$this->geterrordetaillistbydevice->getdata($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->erroridentifier);
		echo json_encode($ret);
	}
}

?>