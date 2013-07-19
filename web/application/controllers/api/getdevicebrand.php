<?php 
class getdevicebrand extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getdevicebrandmodel','getdevicebrand');
	}
	function index(){
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
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		if(isset($content->limit)){
			$ret=$this->getdevicebrand->getdataofdeviceBrand($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->limit);
		}else{
			$ret=$this->getdevicebrand->getdataofdeviceBrand($content->sessionkey,$content->productid,$content->startdate,$content->enddate,null);
		}
		echo json_encode($ret);
	}
}

?>