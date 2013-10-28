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
class CheckRadarInfo extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->language('plugin_radar');
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->load->Model ( 'plugin/radar/checkradarinfomodel', 'checkradarinfomodel' );
	
	}
	
	function index() {
        $currProduct = $this->common->getCurrentProduct();
		$this->data ['appName'] = $currProduct->name;
		$userId = $this->common->getUserId ();	
		$productId = $this->checkradarinfomodel->getProductId($currProduct->name,$userId);
		$row = $this->checkradarinfomodel->getRadarAppId($userId,$productId);
	
		if($row)
		{
			$appid = $row->app_id;
			$this->responseArray = $this->activateData($appid);
			$this->responseArray ['appName'] = $currProduct->name; 
			$this->common->loadHeader (lang ('m_main'));
			$this->load->view ( 'plugin/radar/checkradarinfoview', $this->responseArray );
		}else{

		    $this->common->loadHeader (lang ('m_main'));	
		    $this->load->view ( 'plugin/radar/checkradarinfoview', $this->data );
		}
		
	}

	function activateData($appid){
			$this->data ['appid'] = $appid;
			$url_active="localhost/ucenter/index.php?/getiosdata";
			$response = $this->common->curl_post ( $url_active, $this->data );
			$obj = json_decode ( $response, true );
			$verification = $obj ['verification'];
			$hasdata = $obj['hasdata'];
			if($verification&&$hasdata){
				
				$name = $obj['name'];
				$size = $obj['size'];
				$pl = $obj['pl'];
				$c = $obj['c'];
				$py = $obj['py'];
				$ctr = $obj['ctr'];
				$url = $obj['url'];
				$ranks = $obj['ranks'];	
			

				$this->responseArray ['verification'] = $verification;
				$this->responseArray ['name'] = $name;
				$this->responseArray ['size'] = $size;
				$this->responseArray ['pl'] = $pl;
				$this->responseArray ['c'] = $c;
				$this->responseArray ['hasdata'] = $hasdata;
				$this->responseArray ['py'] = $py;
				$this->responseArray ['ctr'] = $ctr;
				$this->responseArray ['url'] = $url;
				$this->responseArray ['ranks'] = $ranks;
				$this->responseArray ['appid'] = $appid;
				
			
				return $this->responseArray;
			}else{
				$this->responseArray['hasdata'] = $hasdata;
				$this->responseArray['verification']=$verification;
				return $this->responseArray;
			}
		}
	
	function activateApp() {
		$userId = $this->common->getUserId ();
		$this->form_validation->set_rules ( 'appid', 'APPID', 'trim|required|xss_clean' );
		$currProduct = $this->common->getCurrentProduct();
		$appName = $currProduct->name;
		$this->responseArray ['appName'] = $appName; 

		
		if ($this->form_validation->run ()) {
			$appid = $this->input->post ( "appid" );
			$this->responseArray = $this->activateData($appid);
			if($this->responseArray['hasdata']&&$this->responseArray['verification']){

				$this ->data['userid'] = $userId;
				$this ->data['productId'] = $this->checkradarinfomodel->getProductId($appName,$userId);
				
				if ($this->checkradarinfomodel->saveUsersInfo ( $this->data )) {
					
					$this->common->loadHeader ( lang ('m_main') );
					$this->load->view ( 'plugin/radar/checkradarinfoview', $this->responseArray );
				}
			
			}else{

				$this->warning ['appName']=$appName;
				$this->warning ['msg'] = lang ('m_checkFailureWarning');
				$this->common->loadHeader (lang ('m_main'));
				$this->load->view ( 'plugin/radar/checkradarinfoview', $this->warning );
			}

		
		} else{

			$this->warning ['appName']=$appName;
			$this->responseArray ['msg'] = lang ('m_fillBlankWarning');
			$this->common->loadHeader ( lang ('m_main') );
			$this->load->view ( 'plugin/radar/checkradarinfoview', $this->warning );
		}
	}

    function getRanks()
    {
        $appleid = $_GET['appleid'];
		$url="localhost/ucenter/index.php?/getiosdata";
        $this->data['appid'] = $appleid;
        
        $response = $this->common->curl_post ( $url, $this->data );
        $obj = json_decode($response);
        $ranks = $obj->ranks;
      
        $h = array();
        $r = array();
        $ret = array();
        foreach ($ranks as $row) {
            array_push($r, $row->r);
            array_push($h, $row->h.'h');
        }
        
        array_push($ret, $h);
        array_push($ret, $r);
        echo json_encode($ret);
    }
}
