<?php
class getuireport extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		$this->load->language('plugin_getui');
		$this->load->Model ( 'common' );
		
		$this->common->requireLogin ();
	
	}
	
	function index() {
		
		// 获取userkey userSecret appid appname
		// $userKey=$_POST['userKey'];
		// $userSecret=$_POST['userSecret'];
		// $appid = $_POST['appid'];
		// $appname=$_POST['appname'];
		
		$this->data ['appname'] = "testappname";
		$this->data ['appid'] = "testappname";
		$this->data ['userSecret'] = "testappname";
		$this->data ['userKey'] = "testappname";
		$this->common->loadHeader ();
		$this->load->view ( 'plugin/getui/pushnote', $this->data );
	}

}

?>