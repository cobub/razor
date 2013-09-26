<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class IOSActivate extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->library ( 'form_validation' );
		$this->load->language('plugin_ios');
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
		$this->load->Model ( 'plugin/iospush/iosactivatemodel', 'iosactivatemodel' );
	
	}
	
	function index() {
		$appName = $_GET ['appname'];	
		$this->data ['appname'] = $appName;
		$this->common->loadHeader ( lang ( 'm_ios_register' ) );	
		$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );

	}


	function activateApp() {
		$userId = $this->common->getUserId ();
		$userKeys = $this->iosactivatemodel->getUserKeys ( $userId );
		$this->data ['userkey'] = $userKeys->user_key;
		$this->data ['usersecret'] = $userKeys->user_secret;
		$this->form_validation->set_rules ( 'bundleid', 'BundleId', 'trim|required|xss_clean' );
		$appName = $this->input->post ( "appname" );
		$this->data ['appname'] = $appName;
		
		if ($this->form_validation->run ()) {
			$bundleId = $this->input->post ( "bundleid" );
			$this->data ['bundleid'] = $bundleId;
			$register_id_this = md5($this->data['userkey'].$this->data['usersecret'].md5($bundleId));
	
			$url_active = SERVER_BASE_URL."/index.php?/api/apns/register";	
			$response = $this->common->curl_post ( $url_active, $this->data );	
			$obj = json_decode ( $response, true );
			$flag = $obj['flag'];
			
			if($flag==1){
				$register_id = $obj ['register_id'];
				$this->responseArray ['flag'] = $flag;
				$this->responseArray ['register_id'] = $register_id;
				$this->responseArray ['appname'] = $appName;
				$this->responseArray ['userId'] = $userId;
				$this->responseArray ['bundleid'] = $bundleId;
		
				
				$product_id = $this->iosactivatemodel->getProductId ( $appName );
				
				$this->responseArray ['productId'] = $product_id;
				if($register_id_this==$register_id&&$this->iosactivatemodel->saveUsersInfo ( $this->responseArray ))
				{	
					$this->data ['msg'] = lang ( 'm_ios_register_successed' );
					$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
					$this->load->view ( 'plugin/iospush/iosactivateview', $this->responseArray );
				}else{
					$this->data ['msg'] = lang ( 'm_ios_warning1' );
					$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
					$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
				}
			}else{
				$this->data ['msg'] = lang ( 'm_ios_warning2' );
				$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
				$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
			}
		}else{
				$this->data ['msg'] = lang ( 'm_ios_warning3' );
				$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
				$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
		}
	}	
	function checkInfo() {
		$appName = $_GET ['appname'];
		$this->data = $this->iosactivatemodel->checkInfo ( $appName );
		$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
		$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
	
	}
}
