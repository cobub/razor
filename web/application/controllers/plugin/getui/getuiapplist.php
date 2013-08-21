<?php
class getuiapplist extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->Model ( 'plugin/getui/applistmodel', 'plugins' );
		
		 // $this ->load-> model('pluginlistmodel','plugin');
		$this->common->requireLogin ();
	
	}
	
	function index() {
		$this->common->cleanCurrentProduct ();
		// 获取userkey userSecret appid appname
		// $userKey=$_POST['userKey'];
		// $userSecret=$_POST['userSecret'];
		// $appid = $_POST['appid'];
		// $appname=$_POST['appname'];
		
		// $this->data['appname']=$userKey;
		// $this->data['appid']=$userSecret;
		// $this->data['userSecret']=$appid;
		// $this->data['userKey']=$appname;
		
		// $this->common->loadHeader();
		$this->common->loadHeaderWithDateControl  ( lang ( 'getui_report' ) );
		
		$applist = $this->plugins->getApplist ();
		// print_r($applist);
		if(count($applist)>=1){
			if(count($applist[0])>=1){
				$productid=$applist[0][0]['id'];
			}else{
				$productid='';
			}
		}else{
				$productid='';
			}

		
		$appid = $this->plugins->getAppid ( $productid );
		$this->data ['appid'] = $appid;
		$this->data ['arr'] = $applist;
		$this->load->view ( 'plugin/getui/pluginapp', $this->data );
	}

}

?>