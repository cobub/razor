<?php 
class getregion extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->model('api/getregionmodel','getregiondata');
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
// 		$basicinfo="{\"sessionkey\":\"26920d8b8fdfdf708438ce14c346c1a0\",\"productid\": \"1\", \"startdate\": \"2013-04-25\",\"enddate\": \"2013-04-26\",\"country\":\"\",\"limit\":\"5\" }
// 		";
		log_message ( "debug", $basicinfo );
		$content = json_decode($basicinfo);
		$verify=$this->common->verifParameter($content,$info=array('sessionkey','productid','startdate','enddate','country'));
		if($verify['flag']<=0)
		{
			echo json_encode($verify);
			return;
		}
		if(isset($content->limit)){
			$ret=$this->getregiondata->getdataofPro($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->country,$content->limit);
		}else{
			$ret=$this->getregiondata->getdataofPro($content->sessionkey,$content->productid,$content->startdate,$content->enddate,$content->country,null);
		}
		echo json_encode($ret);
	}
}

?>