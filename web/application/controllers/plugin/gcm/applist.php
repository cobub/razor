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
class Applist extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->language('plugin_gcm');
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
		$this->load->Model ( 'plugin/gcm/checkgcminfomodel', 'infomodel' );
		$this->load->Model ( 'plugin/gcm/gcmactivatemodel', 'activatemodel' );
	
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
		
		return $flag;
	}

	function pushInfo() {
		
		////check appkey
		//$appkey = $this->input->post ( "appkey" );
		//$userId = $this->common->getUserId ();
		//$HasAppkey = $this->activatemodel->CheckAppkey( $userId, $appkey );
		$userId = $this->common->getUserId ();
		$HasAppkey = $this->activatemodel->getappkey( $userId);
		if (! $HasAppkey) {
			$this->data ['flag'] = 1;
			$this->data ['msg'] = lang ( 'gcm_enter_appkey' );
			$this->data ['isAuth'] = 1;
			$arr = $this->infomodel->getProductInfo ();
			$this->data ["applist"] = $this->infomodel->assoc_unique ( $arr, 'androidlist' );
			$this->common->loadHeader ( lang ( 'gcm_m_homepage' ) );
			redirect ( site_url () . "/plugin/gcm/gcmhome/pusherror");
			return;
		}
		
		////check user center userkey
		$userKeys = $this->activatemodel->getUserKeys ( $userId );
		if(!$userKeys)
		{
			$this->data ['flag'] = 1;
			$this->data ['msg'] = lang ( 'gcm_regist_userkey' );
			$this->data ['isAuth'] = 1;
			$arr = $this->infomodel->getProductInfo ();
			$this->data ["applist"] = $this->infomodel->assoc_unique ( $arr, 'androidlist' );
			$this->common->loadHeader ( lang ( 'gcm_m_homepage' ) );
			redirect ( site_url () . "/plugin/gcm/gcmhome/pusherror");
			return;
		}
		
		$appId = $_GET ['appId'];
		redirect ( site_url () . "/tag/tags?product_id=" . $appId. "&url=" .site_url () . "/plugin/gcm/push");

	}

}

