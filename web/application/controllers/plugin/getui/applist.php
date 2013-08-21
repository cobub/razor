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
		$this->load->library ( 'form_validation' );
		
		$this->load->Model ( 'common' );
		$this->load->Model ( 'plugin/getui/applistmodel', 'applistmodel' );
		$this->load->Model ( 'plugin/getui/activatemodel', 'activatemodel' );
	
	}
	
	function index() {
		
		$this->common->loadHeader ( lang ( 'getuiHomePage' ) );
		$arr = $this->applistmodel->getProductInfo ();
		$userId = $this->common->getUserId ();
		$userKeys = $this->activatemodel->getUserKeys ( $userId );
		if($userKeys){
			$this ->data ["applist"] = $this->applistmodel->assoc_unique ( $arr, 'androidlist' );
			$this ->data['isAuth'] = 1;
			$this ->data ['flag'] = $this -> getUserStatus();
			$this->load->view ( 'plugin/getui/applistview', $this ->data );
		}else{
			$this-> data['isAuth'] = 0;
			$this->data ['msgw'] = lang ( 'plg_get_keysecret' );
			$this ->data ['flag'] = $this -> getUserStatus();
			$arr = $this->applistmodel->getProductInfo ();
			$this->data ["applist"] = $this->applistmodel->assoc_unique ( $arr, 'androidlist' );
			$this->load->view ( 'plugin/getui/applistview', $this->data );
		}
	}
	
	function getUserStatus(){
		$userId = $this->common->getUserId ();
		$userKeys = $this->activatemodel->getUserKeys ( $userId );
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

	function pushInfo() {
		$appName = $_GET ['appName'];
		$data = $this->activatemodel->checkInfo ( $appName );
		$flag = $this -> getUserStatus();
		$type = $_GET['type'];

		if ($data) {
			$userId = $this->common->getUserId ();
			$userKeys = $this->activatemodel->getUserKeys ( $userId );
			$data ['userKey'] = $userKeys->user_key;
			$data ['userSecret'] = $userKeys->user_secret;
			$data['isAuth']=1;

			if($type)	redirect ( site_url () .  "/tag/tags?product_id=" . $data ['productId']. "&url=" .site_url () . "/plugin/getui/getuicl/transmission");
				else  redirect ( site_url () . "/tag/tags?product_id=" . $data ['productId']. "&url=" .site_url () . "/plugin/getui/getuicl");
		} else {
				
			$data['flag'] = $flag;
			$data ['msg'] = lang ( 'v_warningInfo' );
			$data['isAuth']=1;
			$arr = $this->applistmodel->getProductInfo ();
			$data ["applist"] = $this->applistmodel->assoc_unique ( $arr, 'androidlist' );
			$this->common->loadHeader ( lang ( 'getuiHomePage' ) );
			$this->load->view ( 'plugin/getui/applistview', $data );
		}

	}

}
