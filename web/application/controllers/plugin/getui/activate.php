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
class Activate extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->language('plugin_getui');
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
		$this->load->Model ( 'plugin/getui/activatemodel', 'activatemodel' );
	
	}
	
	function index() {
		$appName = $_GET ['appName'];	
		$this->data ['appName'] = $appName;
		$this->common->loadHeader ( lang ( 'v_activateApp' ) );	
		$this->load->view ( 'plugin/getui/activateview', $this->data );
	}
	
	function activateApp() {
		$userId = $this->common->getUserId ();
		$userKeys = $this->activatemodel->getUserKeys ( $userId );
		$this->data ['userKey'] = $userKeys->user_key;
		$this->data ['userSecret'] = $userKeys->user_secret;
		
		$this->form_validation->set_rules ( 'packagename', 'PackageName', 'trim|required|xss_clean' );
		$appName = $this->input->post ( "appname" );
		$this->data ['appName'] = $appName;
		
		if ($this->form_validation->run ()) {
			$app_identifier = $this->input->post ( "packagename" );
			$this->data ['app_identifier'] = $app_identifier;
		
		
			$url_active = SERVER_BASE_URL."/index.php?/api/igetui/register";
			$response = $this->common->curl_post ( $url_active, $this->data );
			$obj = json_decode ( $response, true );
			$flag = $obj ['flag'];

			// response infomation can not be null, or activate failure
			if($flag == -1){
				$this->data ['msg'] = lang ( 'v_warning1' );
				$this->common->loadHeader ( lang ( 'v_activateApp' ) );
				$this->load->view ( 'plugin/getui/activateview', $this->data );
			}else if($flag == -2){
				$this->data ['msg'] = lang ( 'v_warning2' );
				$this->common->loadHeader ( lang ( 'v_activateApp' ) );
				$this->load->view ( 'plugin/getui/activateview', $this->data );
			}else if($flag == -3){
				$this->data ['msg'] = lang ( 'v_warning3' );
				$this->common->loadHeader ( lang ( 'v_activateApp' ) );
				$this->load->view ( 'plugin/getui/activateview', $this->data );
			}else if (1 == $flag ) {
			
				$appId = $obj ['appid'];
				$appKey = $obj ['appkey'];
				$appSecret = $obj ['appsecret'];
				$masterSecret = $obj ['mastersecret'];
				$this->responseArray ['flag'] = $flag;
				$this->responseArray ['appId'] = $appId;
				$this->responseArray ['appKey'] = $appKey;
				$this->responseArray ['appSecret'] = $appSecret;
				$this->responseArray ['masterSecret'] = $masterSecret;
				$this->responseArray ['appName'] = $appName;
				$this->responseArray ['app_identifier'] = $app_identifier;
				$this->responseArray ['userId'] = $userId;
				$this->responseArray ['activateDate'] = $obj['createtime'];
				
				$product_id = $this->activatemodel->getProductId ( $appName );
				
				$this->responseArray ['productId'] = $product_id;
				
				if ($this->activatemodel->saveUsersInfo ( $this->responseArray )) {
					$this->common->loadHeader ( lang ( 'v_keysInfo' ) );
					$this->load->view ( 'plugin/getui/activateview', $this->responseArray );
				
				} else {
					
					$this->data ['msg'] = lang ( 'v_warningInfo1' );
					$this->common->loadHeader ( lang ( 'v_activateApp' ) );
					$this->load->view ( 'plugin/getui/activateview', $this->data );
				}
			
			} 
		
		} else {
			$this->data ['msg'] = lang ( 'v_warningInfo4' );
			$this->common->loadHeader ( lang ( 'v_activateApp' ) );
			$this->load->view ( 'plugin/getui/activateview', $this->data );
		}
	}
	
	function checkInfo() {
		$appName = $_GET ['appName'];
		$this->data = $this->activatemodel->checkInfo ( $appName );
		$this->common->loadHeader ( lang ( 'v_keysInfo' ) );
		$this->load->view ( 'plugin/getui/activateview', $this->data );
	
	}
}
