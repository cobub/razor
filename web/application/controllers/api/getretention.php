<?php 
class getretention extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getretentionmodel','getretention');
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
// 		$basicinfo="{\"sessionkey\":\"26920d8b8fdfdf708438ce14c346c1a0\",\"productid\": \"1\", \"startdate\": \"2013-01-25\",\"enddate\": \"2013-04-26\",\"type\":\"month\" }
// 		";
		log_message ( "debug", $basicinfo );
		$content = json_decode($basicinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate','type'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
			$ret=$this->getretention->getretentiondata($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->type);
		echo json_encode($ret);
	}
}

?>