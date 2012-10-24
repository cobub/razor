<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package Cobub Razor
 * @author WBTECH Dev Team
 * @copyright Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license http://www.cobub.com/products/cobub-razor/license
 * @link http://www.cobub.com/products/cobub-razor/
 * @since Version 1.0
 * @filesource
 *
 */
class Autoupdate extends CI_Controller {
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		$this->load->model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->library ( 'upload' );
		$this->load->helper ( 'cookie' );
	}
	function index($cp_id, $channel_id) {
		$this->common->loadHeader ();
		
		$max_upload = ( int ) ini_get ( 'upload_max_filesize' );
		$max_post = ( int ) ini_get ( 'post_max_size' );
		$memory_limit = ( int ) ini_get ( 'memory_limit' );
		$upload_mb = min ( $max_upload, $max_post, $memory_limit );
		$userid = $this->common->getUserId ();
		$result = $this->channel->getuapkplatform ( $channel_id );
		$this->data ['cp_id'] = $cp_id;
		$isupdate = $this->channel->judgeupdate ( $cp_id );
		if ($result ['platform'] == 1) {
			if ($isupdate) {
				$this->data ['apkinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
				$this->data ['androidinfo'] = $this->channel->getupdatehistory ( $cp_id );
				$this->load->view ( 'autoupdate/androidhistory', $this->data );
			} else {
				$this->data ['upload_mb'] = $upload_mb;
				$this->data ['upinfo'] = 1;
				$this->load->view ( 'autoupdate/updateandroid', $this->data );
			}
		}
		if ($result ['platform'] == 2) {
			if ($isupdate) {
				$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
				$this->data ['iphoneinfo'] = $this->channel->getupdatehistory ( $cp_id );
				$this->load->view ( 'autoupdate/iphonehistory', $this->data );
			} else {
				$this->data ['upinfo'] = 1;
				$this->load->view ( 'autoupdate/updateiphone', $this->data );
			}
		}
		if ($result ['platform'] == 3) {
			if ($isupdate) {
				$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
				$this->data ['iphoneinfo'] = $this->channel->getupdatehistory ( $cp_id );
				$this->load->view ( 'autoupdate/iphonehistory', $this->data );
			} else {
				$this->data ['upinfo'] = 1;
				$this->load->view ( 'autoupdate/updateiphone', $this->data );
			}
		}
	}
	// check versionid
	function versionid_check($versionid) {
		if (! preg_match ( '/\d+\.\d+/', $versionid )) {
			$this->form_validation->set_message ( 'versionid_check',lang ( 'v_man_au_versionError' ));
			return FALSE;
		} else {
			return TRUE;
		}
	}
	//check 
	function description_check($description){
		if(empty($description)){
			$this->form_validation->set_message('description_check','Please input the description info');
			return false;
		}
		return true;
	}
	// upload apk file
	function uploadapk($cp_id, $upinfo) {
		$this->common->loadHeader ();
		$max_upload = ( int ) ini_get ( 'upload_max_filesize' );
		$max_post = ( int ) ini_get ( 'post_max_size' );
		$memory_limit = ( int ) ini_get ( 'memory_limit' );
		$upload_mb = min ( $max_upload, $max_post, $memory_limit );
		$fizeinfo = get_cookie ( 'filesize' );
		if ($fizeinfo == "error") {
			$this->data ['error'] = "<p align='center'>" . lang ( 'v_man_au_maxLimit' ) . "</p>";
		}
		$config ['upload_path'] = 'assets/android/';
		$config ['allowed_types'] = 'apk';
		$config ['max_size'] = '30000000';
		$this->upload->initialize ( $config );
		$this->load->library ( 'upload', $config );
		
		$this->form_validation->set_rules ( 'description', lang ( 'v_man_au_updateLog' ), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'versionid', lang ( 'v_man_au_versionID' ), 'trim|required|xss_clean|callback_versionid_check' );
		$this->data ['upinfo'] = $upinfo;
		
		if ($this->form_validation->run () == FALSE) {
			$this->data ['cp_id'] = $cp_id;
			$this->data ['upload_mb'] = $upload_mb;
			$this->load->view ( 'autoupdate/updateandroid', $this->data );
		} else {
			if (! $this->upload->do_upload ( "userfile" )) {
				$error = $this->upload->display_errors ();
				$this->data ['error'] = $this->upload->display_errors ();
				$this->data ['cp_id'] = $cp_id;
				$this->data ['upload_mb'] = $upload_mb;
				$this->load->view ( 'autoupdate/updateandroid', $this->data );
			} else {
				$upload_data = $this->upload->data ();
				$updateurl = base_url () . 'assets/android/' . $upload_data ['file_name'];
				$userid = $this->common->getUserId ();
				$description = $this->input->post ( 'description' );
				$versionid = $this->input->post ( 'versionid' );
				
				$versioninfo = $this->channel->getversionid ( $cp_id, $versionid, $upinfo );
				if ($versioninfo) {
					$isupdate = $this->channel->updateapk ( $userid, $cp_id, $description, $updateurl, $versionid, $upinfo );
					if ($isupdate) {
						$this->data ['apkinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
						$this->load->view ( 'autoupdate/updateandrlist', $this->data );
					}
				} else {
					$this->data ['upload_mb'] = $upload_mb;
					$this->data ['errorversion'] = lang ( 'v_man_au_versionGreater' );
					$this->data ['cp_id'] = $cp_id;
					$this->load->view ( 'autoupdate/updateandroid', $this->data );
				}
			}
		}
	}
	// verify apk file size
	function verifysize() { // get php.ini min uploadfile size
		$max_upload = ( int ) ini_get ( 'upload_max_filesize' );
		$max_post = ( int ) ini_get ( 'post_max_size' );
		$memory_limit = ( int ) ini_get ( 'memory_limit' );
		$upload_mb = min ( $max_upload, $max_post, $memory_limit );
		$getfilesize = $_POST ['size'];
		$pasttime = 60 * 60;
		if ($upload_mb > $getfilesize) {
			$cookie = array (
					'name' => 'filesize',
					'value' => 'ok',
					'expire' => $pasttime,
					'secure' => false 
			);
		} else {
			$cookie = array (
					'name' => 'filesize',
					'value' => 'error',
					'expire' => $pasttime,
					'secure' => false 
			);
		}
		set_cookie ( $cookie );
	}
	// deal with iphone app and windows phone
	function uploadapp($cp_id, $upinfo) {
		$this->common->loadHeader ();
		$this->form_validation->set_rules ( 'appurl', lang ( 'v_man_au_info_appUrl' ), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'versionid', lang ( 'v_man_au_versionID' ), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'description', lang ( 'v_man_au_updateLog' ), 'trim|required|xss_clean|min_length[10]' );
		$this->data ['upinfo'] = $upinfo;
		if ($this->form_validation->run () == FALSE) {
			$error = array (
					'error' => $this->upload->display_errors () 
			);
			$this->data ['error'] = array (
					'error' => $this->upload->display_errors () 
			);
			$this->data ['cp_id'] = $cp_id;
			$this->load->view ( 'autoupdate/updateiphone', $this->data );
		} else {
			
			$updateurl = $this->input->post ( 'appurl' );
			$userid = $this->common->getUserId ();
			$description = $this->input->post ( 'description' );
			$versionid = $this->input->post ( 'versionid' );
			$versioninfo = $this->channel->getversionid ( $cp_id, $versionid, $upinfo );
			if ($versioninfo) {
				$isupdate = $this->channel->updateapp ( $userid, $cp_id, $description, $updateurl, $versionid, $upinfo );
				if ($isupdate) {
					$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
					$this->load->view ( 'autoupdate/updateipolist', $this->data );
				}
			} else {
				
				$this->data ['errorversion'] = lang ( 'v_man_au_versionGreater' );
				$this->data ['cp_id'] = $cp_id;
				$this->load->view ( 'autoupdate/updateiphone', $this->data );
			}
		}
	}
	
	// $upinfo Tag is updated or upgrade(0:update, 1:upgrade)
	// update feature in the list of Automatically update
	function updatenewinfo($channel_id, $cp_id) {
		$this->common->loadHeader ();
		$max_upload = ( int ) ini_get ( 'upload_max_filesize' );
		$max_post = ( int ) ini_get ( 'post_max_size' );
		$memory_limit = ( int ) ini_get ( 'memory_limit' );
		$upload_mb = min ( $max_upload, $max_post, $memory_limit );
		$this->data ['cp_id'] = $cp_id;
		$this->data ['upinfo'] = 0;
		$result = $this->channel->getuapkplatform ( $channel_id );
		if ($result ['platform'] == 1) {
			$this->data ['upload_mb'] = $upload_mb;
			$this->data ['updateinfo'] = $this->channel->getnewlistinfo ( $cp_id );
			$this->load->view ( 'autoupdate/updateandroid', $this->data );
		}
		if ($result ['platform'] == 2) {
			$this->data ['updateinfo'] = $this->channel->getnewlistinfo ( $cp_id );
			$this->load->view ( 'autoupdate/updateiphone', $this->data );
		}
		if ($result ['platform'] == 3) {
			$this->data ['updateinfo'] = $this->channel->getnewlistinfo ( $cp_id );
			$this->load->view ( 'autoupdate/updateiphone', $this->data );
		}
	}
	// To upgrade automatically update content
	function upgradeinfo($channel_id, $cp_id) {
		$this->common->loadHeader ();
		$max_upload = ( int ) ini_get ( 'upload_max_filesize' );
		$max_post = ( int ) ini_get ( 'post_max_size' );
		$memory_limit = ( int ) ini_get ( 'memory_limit' );
		$upload_mb = min ( $max_upload, $max_post, $memory_limit );
		$this->data ['cp_id'] = $cp_id;
		$this->data ['upinfo'] = 1;
		$result = $this->channel->getuapkplatform ( $channel_id );
		if ($result ['platform'] == 1) {
			$this->data ['upload_mb'] = $upload_mb;
			$this->load->view ( 'autoupdate/updateandroid', $this->data );
		}
		if ($result ['platform'] == 2) {
			$this->load->view ( 'autoupdate/updateiphone', $this->data );
		}
		if ($result ['platform'] == 3) {
			$this->load->view ( 'autoupdate/updateiphone', $this->data );
		}
	}
	// Delete automatically update in the history list
	function deleteupdate($channel_id, $cp_id, $vp_id) {
		$this->common->loadHeader ();
		$userid = $this->common->getUserId ();
		$this->channel->deleteupdate ( $vp_id );
		$result = $this->channel->getuapkplatform ( $channel_id );
		if ($result ['platform'] == 1) {
			
			$this->data ['apkinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
			$this->data ['androidinfo'] = $this->channel->getupdatehistory ( $cp_id );
			$this->load->view ( 'autoupdate/androidhistory', $this->data );
		}
		if ($result ['platform'] == 2) {
			$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
			$this->data ['iphoneinfo'] = $this->channel->getupdatehistory ( $cp_id );
			$this->load->view ( 'autoupdate/iphonehistory', $this->data );
		}
		if ($result ['platform'] == 3) {
			$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
			$this->data ['iphoneinfo'] = $this->channel->getupdatehistory ( $cp_id );
			$this->load->view ( 'autoupdate/iphonehistory', $this->data );
		}
	}
}