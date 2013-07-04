<?php 
class getconversiondetail extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getconversiondetailmodel','getconversiondetail');
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
// 		$basicinfo="{\"sessionkey\":\"26920d8b8fdfdf708438ce14c346c1a0\",\"productid\": \"1\", \"startdate\": \"2013-04-25\",\"enddate\": \"2013-04-26\",\"version\":\"3.0\",\"targetid\":\"1\" }
// 		";
		log_message ( "debug", $basicinfo );
		$content = json_decode($basicinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate','targetid'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		if(isset($content->version)){
			$ret=$this->getconversiondetail->getdataofconversiondetail($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->targetid,$content->version);
		}else{
			$ret=$this->getconversiondetail->getdataofconversiondetail($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->targetid,"all");
		}
			
		echo json_encode($ret);
	}
}

?>