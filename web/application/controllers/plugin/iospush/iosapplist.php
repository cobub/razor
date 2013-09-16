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
class IOSApplist extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->language('plugin_ios');
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
		$this->load->Model ( 'plugin/iospush/iosapplistmodel', 'iosapplistmodel' );
		$this->load->Model ('plugin/iospush/iosactivatemodel','iosactivatemodel');
	}
	
	function index() {
		
		$this->common->loadHeader ( lang ( 'm_IOSpush' ) );
		$arr = $this->iosapplistmodel->getProductInfo ();
	
		$userId = $this->common->getUserId ();
		$userKeys = $this->iosactivatemodel->getUserKeys ( $userId );
		if($userKeys){
			$this ->data ["applist"] = $this->iosapplistmodel->assoc_unique ( $arr, 'androidlist' );
			$this ->data ['flag'] = $this -> getUserStatus();
			$this->load->view ( 'plugin/iospush/iosapplistview', $this ->data );
		}else{
			$this->data ['msgw'] = lang ( 'plg_get_keysecret' );
			$this->data ['flag'] = $this -> getUserStatus();
			$arr = $this->iosapplistmodel->getProductInfo ();
			$this->data ["applist"] = $this->iosapplistmodel->assoc_unique ( $arr, 'androidlist' );
			$this->load->view ( 'plugin/iospush/iosapplistview', $this->data );
		}
	}

	

	function getUserStatus(){
		$userId = $this->common->getUserId ();
		$userKeys = $this->iosactivatemodel->getUserKeys ( $userId );
		if($userKeys){
			$data=array(
			'userKey'=>$userKeys->user_key,
			'userSecret'=>$userKeys->user_secret
			);
		$url = SERVER_BASE_URL."/index.php?/api/igetui/getUserActive";
		$response = $this->common->curl_post ( $url, $data );
		$obj = json_decode ( $response, true );

		$flag = $obj['flag'];
		}else $flag = 0;
		
		return $flag;
	}

	function isAcUsed(){

		$userId = $this->common->getUserId ();	
		$userKeys = $this->iosactivatemodel->getUserKeys ( $userId );
		$this->data ['userkey'] = $userKeys->user_key;
		$this->data ['usersecret'] = $userKeys->user_secret;
		$url_active = SERVER_BASE_URL."/index.php?/api/apns/isAcUsed";
		$response = $this->common->curl_post ( $url_active, $this->data );
		$obj = json_decode ( $response, true );
		$flag = $obj['flag'];
		return $flag;
	}
	
	function isCerActived($registerId){
		$this->data['register_id']=$registerId;
		$url = SERVER_BASE_URL."/index.php?/api/apns/isAppActived";	
		$response = $this->common->curl_post ( $url, $this->data );		
		$obj = json_decode ( $response, true );
		$flag = $obj['flag'];
		return $flag;
	}

	function pushInfo() {

		$appName = $_GET ['appname'];	
		$productId=$this->iosactivatemodel->getProductId($appName);	
		$userId = $this->common->getUserId ();
		$ios_row = $this->iosapplistmodel->getRegisterId($productId,$userId);
		$flag = $this -> getUserStatus();
		if($ios_row){
			$registerId = $ios_row->register_id;
			$isActive = $ios_row->is_active;
			$isCerActive = $this->isCerActived($registerId);	
			$isAcUsed = $this->isAcUsed();
			if($isAcUsed){
				if($isCerActive){
					redirect ( site_url () . "/tag/tags?product_id=" . $productId. "&url=" .site_url () . "/plugin/iospush/ioscl");
				}else{
						$data['flag'] = $flag;
						$data ['msg'] = lang ( 'm_cer_warning' ).lang('m_cer_warning1');
						$arr = $this->iosapplistmodel->getProductInfo ();
						$data ["applist"] = $this->iosapplistmodel->assoc_unique ( $arr, 'androidlist' );
						$this->common->loadHeader ( lang ( 'm_IOSpush' ) );
						$this->load->view ( 'plugin/iospush/iosapplistview', $data );
					}
			
			}else{
				$data['flag'] = $flag;
				$data ['msg'] = lang ( 'm_acc_warning' );
				$arr = $this->iosapplistmodel->getProductInfo ();
				$data ["applist"] = $this->iosapplistmodel->assoc_unique ( $arr, 'androidlist' );
				$this->common->loadHeader ( lang ( 'm_IOSpush' ) );
				$this->load->view ( 'plugin/iospush/iosapplistview', $data );
			}

		}else{
			$data['flag'] = $flag;
			$data ['msg'] = lang ( 'm_reg_warning' );
			$arr = $this->iosapplistmodel->getProductInfo ();
			$data ["applist"] = $this->iosapplistmodel->assoc_unique ( $arr, 'androidlist' );
			$this->common->loadHeader ( lang ( 'm_IOSpush' ) );
			$this->load->view ( 'plugin/iospush/iosapplistview', $data );
		}

	}
}