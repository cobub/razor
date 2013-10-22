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


// 	function activateApp() {
// 		$userId = $this->common->getUserId ();
// 		$userKeys = $this->iosactivatemodel->getUserKeys ( $userId );
// 		$this->data ['userkey'] = $userKeys->user_key;
// 		$this->data ['usersecret'] = $userKeys->user_secret;
// 		$this->form_validation->set_rules ( 'bundleid', 'BundleId', 'trim|required|xss_clean' );
// 		$appName = $this->input->post ( "appname" );
// 		$this->data ['appname'] = $appName;
		
// 		if ($this->form_validation->run ()) {
// 			$bundleId = $this->input->post ( "bundleid" );
// 			$this->data ['bundleid'] = $bundleId;
// 			$register_id_this = md5($this->data['userkey'].$this->data['usersecret'].md5($bundleId));
	
// 			$url_active = SERVER_BASE_URL."/index.php?/api/apns/register";	
// 			$response = $this->common->curl_post ( $url_active, $this->data );	
// 			$obj = json_decode ( $response, true );
// 			$flag = $obj['flag'];
			
// 			if($flag==1){
// 				$register_id = $obj ['register_id'];
// 				$this->responseArray ['flag'] = $flag;
// 				$this->responseArray ['register_id'] = $register_id;
// 				$this->responseArray ['appname'] = $appName;
// 				$this->responseArray ['userId'] = $userId;
// 				$this->responseArray ['bundleid'] = $bundleId;
		
				
// 				$product_id = $this->iosactivatemodel->getProductId ( $appName );
				
// 				$this->responseArray ['productId'] = $product_id;
// 				if($register_id_this==$register_id&&$this->iosactivatemodel->saveUsersInfo ( $this->responseArray ))
// 				{	
// 					$this->data ['msg'] = lang ( 'm_ios_register_successed' );
// 					$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
// 					$this->load->view ( 'plugin/iospush/iosactivateview', $this->responseArray );
// 				}else{
// 					$this->data ['msg'] = lang ( 'm_ios_warning1' );
// 					$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
// 					$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
// 				}
// 			}else{
// 				$this->data ['msg'] = lang ( 'm_ios_warning2' );
// 				$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
// 				$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
// 			}
// 		}else{
// 				$this->data ['msg'] = lang ( 'm_ios_warning3' );
// 				$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
// 				$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
// 		}
// 	}	
	
	function checkInfo() {
		
		$appName = $_GET ['appname'];
		$this->data = $this->iosactivatemodel->checkInfo ( $appName );
		$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
		$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
	
	}

	function upload($register_id,$appname)
	{
		$this->data = $this->iosactivatemodel->checkInfo ( $appname );
		if(!$this->iosactivatemodel->checkInfo ( $appname ))
		{
			$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
			$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
			
		}
		
		$this->form_validation->set_rules('crt_passwd', lang('apns_upload_crt_passwd'), 'trim|required|xss_clean');
		if ($this->form_validation->run()) {
			
			$config = array(
					'upload_path' => getcwd().'/assets/android/',
					'allowed_types' => '*'
			);
			
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload()) {
				 $this->data['msg'] = lang('v_ios_upload_filewrong');
					
			} else {
				$file = $this->upload->data();
					
				$file_name = $file['file_name'];
				$filename = getcwd().'/assets/android/'.$file_name;
				////read file
				$handle = fopen($filename, "r");
				$contents = fread($handle, filesize ($filename));
				fclose($handle);
				////delete upload file
				unlink($filename);
				
				$this->reapsen['password'] = $this->form_validation->set_value('crt_passwd');
				$this->reapsen['registerid']=$register_id;
				$this->reapsen['filename']= $file_name;
				$this->reapsen['filecontent']=base64_encode($contents);
					
				$url_active = SERVER_BASE_URL."/index.php?/api/apns/upload";
				$response = $this->common->curl_post ( $url_active, $this->reapsen );
				$obj = json_decode($response, true );
				$flag = $obj['flag'];
					
				if ($flag == 1){
					$this->data['msg'] = lang('v_ios_upload_successed');
				}
				else{
					$this->data['msg'] = lang('v_ios_upload_failed');
				}
			}
			
		}

		$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
		$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );	
		
	}
	
	function update($appName) {	
		$this->form_validation->set_rules ( 'bundleid', 'BundleId', 'trim|required|xss_clean' );
		if(isset($appName) && $this->form_validation->run ())
		{
			$this->respon['bundleID'] = $this->input->post ( "bundleid" );
			$product_id = $this->iosactivatemodel->getProductId ( $appName );
			$this->respon['productID'] = $product_id;
			if($this->iosactivatemodel->updateInfo($this->respon ))
			{
				$this->data ['msg'] = "更新成功！";
				$this->data ['update'] = 1;
			}
		}
	
		$this->data = $this->iosactivatemodel->checkInfo ( $appName );
		$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
		$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
	
	}
	

////register and upload all success,register success upload failed
	function activateApp() {
		////initialize
		$appName = $this->input->post ( "appname" );
		////register success ,update bundle id and certificate
		$this->regidata = $this->iosactivatemodel->checkInfo ( $appName );
		if($this->regidata)
		{
			$password = $this->input->post ( "password" );
			$bundleid = $this->input->post("bundleid");
			
			///file upload begin
			$config = array(
					'upload_path' => getcwd().'/assets/android/',
					'allowed_types' => '*'
			);
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload()) {
			
				$this->data = $this->iosactivatemodel->checkInfo ( $appName );
				$this->data['msg'] = lang('v_ios_waring_register_certificate_update_faile');
			
				$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
				$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
				return ;
			}
			
			$file = $this->upload->data();
				
			$file_name = $file['file_name'];
			$filename = getcwd().'/assets/android/'.$file_name;
			////read file
			$handle = fopen($filename, "r");
			$contents = fread($handle, filesize ($filename));
			fclose($handle);
			////delete upload file
			unlink($filename);
				
			$this->reapsen['password'] = $password;
			$this->reapsen['registerid']=$this->regidata['register_id'];
			$this->reapsen['filename']= $file_name;
			$this->reapsen['filecontent']=base64_encode($contents);
				
			$url_active = SERVER_BASE_URL."/index.php?/api/apns/upload";
			$response = $this->common->curl_post ( $url_active, $this->reapsen );
			$objfile = json_decode($response, true );
			$flagfile = $objfile['flag'];
			////file upload end
			
			////updata bundle_id
			$this->updataArray['appname'] = $appName;
			$this->updataArray['bundle_id'] = $bundleid;
			
			
			if(!empty($bundleid)&& $this->iosactivatemodel->updateInfo($this->updataArray)){
			    $info = lang('v_ios_waring_register_bundleupdate');
			}
			else {
				$info ="";
			}
			
			$this->data = $this->iosactivatemodel->checkInfo ( $appName );
			if($flagfile == 1){
				if(!empty($info)){
				 $this->data['msg'] = $info.",".lang('v_ios_waring_register_certificate_update_success');
				}
			    else {
			    	$this->data['msg'] = lang('v_ios_waring_register_certificate_update_success');
			    }
			}
			else 
			{
				if(!empty($info)){
					$this->data['msg'] = $info.",".lang('v_ios_waring_register_certificate_update_faile');
				}
				else {
					$this->data['msg'] = lang('v_ios_waring_register_certificate_update_faile');
				}
			}

			$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
			$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
			return ;
		}
		$userId = $this->common->getUserId ();
		$userKeys = $this->iosactivatemodel->getUserKeys ( $userId );
		$this->form_validation->set_rules ( 'bundleid', 'BundleId', 'trim|required|xss_clean' );
		$this->form_validation->set_rules('passwd', lang('apns_upload_crt_passwd'), 'trim|required');
		////upload file
		if ($this->form_validation->run()) {
			$password = $this->input->post ( "password" );
			$bundleId = $this->input->post ( "bundleid" );
			////register application
			$this->dataregist ['appname'] = $appName;
			$this->dataregist ['userkey'] = $userKeys->user_key;;
			$this->dataregist ['usersecret'] = $userKeys->user_secret;
			$this->dataregist ['bundleid'] = $bundleId;
			
			$register_id_this = md5($this->dataregist['userkey'].$this->dataregist['usersecret'].md5($bundleId));
	
			$url_active = SERVER_BASE_URL."/index.php?/api/apns/register";
			$respRegist= $this->common->curl_post ( $url_active, $this->dataregist );
			$objRegist = json_decode ( $respRegist, true );
			$flagRegist = $objRegist['flag'];
	
			if($flagRegist!=1)
			{
				$this->data['appname'] = $appName;
				$this->data ['msg'] = lang('m_ios_warning2');
			}
			else if($flagRegist==1){
				$this->data = $this->iosactivatemodel->checkInfo ( $appName );
				$register_id = $objRegist ['register_id'];
				$this->responseArray ['flag'] = 1;
				$this->responseArray ['register_id'] = $register_id;
				$this->responseArray ['appname'] = $appName;
				$this->responseArray ['userId'] = $userId;
				$this->responseArray ['bundleid'] = $bundleId;
					
				$product_id = $this->iosactivatemodel->getProductId ( $appName );
					
				$this->responseArray ['productId'] = $product_id;
				if($register_id_this==$register_id&&$this->iosactivatemodel->saveUsersInfo ( $this->responseArray ))
				{
					$config = array(
							'upload_path' => getcwd().'/assets/android/',
							'allowed_types' => '*'
					);
						
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload()) {
						$this->data = $this->iosactivatemodel->checkInfo ( $appName );
						$this->data ['msg'] = lang('v_ios_waring_register_id_file_failed');
					}
					else
					{
						$file = $this->upload->data();
							
						$file_name = $file['file_name'];
						$filename = getcwd().'/assets/android/'.$file_name;
						////read file
						$handle = fopen($filename, "r");
						$contents = fread($handle, filesize ($filename));
						fclose($handle);
						////delete upload file
						unlink($filename);
							
						$this->reapsen['password'] = $password;
						$this->reapsen['registerid']=$register_id;
						$this->reapsen['filename']= $file_name;
						$this->reapsen['filecontent']=base64_encode($contents);
							
						$url_active = SERVER_BASE_URL."/index.php?/api/apns/upload";
						$response = $this->common->curl_post ( $url_active, $this->reapsen );
						$objfile = json_decode($response, true );
						$flagfile = $objfile['flag'];
						if($flagfile == 1)
						{
							$this->data = $this->iosactivatemodel->checkInfo ( $appName );
							$this->data['msg'] = lang('v_ios_waring_register_id_file_successed');
						}
						else {
							$this->data = $this->iosactivatemodel->checkInfo ( $appName );
							$this->data['msg'] = lang('v_ios_waring_register_id_file_failed');
						}
					}
				}
			}
		}
		else{
			$this->data['appname'] = $appName;
			$this->data['msg'] = lang ( 'm_ios_warning3' );
		}
		$this->common->loadHeader ( lang ( 'm_iosinfo' ) );
		$this->load->view ( 'plugin/iospush/iosactivateview', $this->data );
	}

	
}
