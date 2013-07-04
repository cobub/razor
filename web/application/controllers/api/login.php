<?php
class login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/loginmodel','login');	
		$this->load->model('service/utility','utility');
		$this->load->model('api/common','common');	
	}
	
	function index()
	{	
		
		if (! isset ($_POST ["content"]))
		{
		
			$ret = array (
					'flag' => -6,
					'msg' => 'Invalid content.'
			);
			echo json_encode ( $ret );
			return;
		}
		$userinfo = $_POST ["content"];       
		log_message ( "debug", $userinfo );
		$content = json_decode($userinfo);			
		$verify=$this->common->verifParameter($content,$info=array('username','password'));	
		if($verify['flag'] <= 0)
		{
			echo json_encode($verify);
			return;
		}
		$onlineip = $this->utility->getOnlineIP();		
		$ret=$this->login->veriflogin($content->username,$content->password,$onlineip);
		echo json_encode($ret);
	}
	
}
