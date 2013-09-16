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
class Gcmhome extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->language ( 'plugin_gcm' );
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
		$this->load->Model ( 'plugin/gcm/checkgcminfomodel', 'checkgcmmodel' );
		$this->load->Model('plugin/gcm/gcmactivatemodel', 'activatemodel' );
	}
	
	function index() {
		
		$this->common->loadHeader ( lang ( 'gcm_m_homepage' ) );
		$arr = $this->checkgcmmodel->getProductInfo ();
		$this->data ["applist"] = $this->checkgcmmodel->assoc_unique ( $arr, 'androidlist' );
		$userId = $this->common->getUserId ();
		$this->data ['isAuth'] = $this->activatemodel->getappkey($userId);
		$this->data ['flag'] = $this->getUserStatus ();
		$userId = $this->common->getUserId ();
		$this->data ['appkey'] = $this->activatemodel->getappkey($userId);
		$this->load->view ( 'plugin/gcm/gcmhomeview', $this->data );
	}
	
	function pusherror() {
	
		$this->common->loadHeader ( lang ( 'gcm_m_homepage' ) );
		$arr = $this->checkgcmmodel->getProductInfo ();
		$this->data ["applist"] = $this->checkgcmmodel->assoc_unique ( $arr, 'androidlist' );
		$userId = $this->common->getUserId ();
		$this->data ['isAuth'] = 0;
		$this->data ['flag'] = $this->getUserStatus ();
		$userId = $this->common->getUserId ();
		$this->data ['appkey'] = "";
		$this->data ['msg'] = lang('gcm_enter_appkey');
		$this->load->view ( 'plugin/gcm/gcmhomeview', $this->data );
	}
	
	function saveAppkey() {
		$this->common->loadHeader ( lang ( 'gcm_m_homepage' ) );
		$arr = $this->checkgcmmodel->getProductInfo ();
		$this->data ["applist"] = $this->checkgcmmodel->assoc_unique ( $arr, 'androidlist' );
		
		$appkey = $this->input->post ( "appkey" );
		$userId = $this->common->getUserId ();
		$this->activatemodel->saveappkeys ( $userId, $appkey );
		$this->data ['appkey'] = $appkey;
		
		$this->data ['isAuth'] = 1;
		$this->data ['flag'] = $this->getUserStatus ();
		
		$this->load->view ( 'plugin/gcm/gcmhomeview', $this->data );
		
	}
	
	function getUserStatus() {
		$userId = $this->common->getUserId ();
		$userKeys = $this->activatemodel->getUserKeys ( $userId );
		if ($userKeys) {
			$data = array (
					'userKey' => $userKeys->user_key,
					'userSecret' => $userKeys->user_secret 
			);
			$url = SERVER_BASE_URL . "/index.php?/api/igetui/getUserActive";
			$response = $this->common->curl_post ( $url, $data );
			$obj = json_decode ( $response, true );
			
			$flag = $obj ['flag'];
		} else {
			$flag = 0;
		}
		////test code
		return 1;
		////
		return $flag;
	}

}

