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
class Accountauth extends CI_Controller {
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'security' );
		$this->load->library ( 'tank_auth' );
		$this->lang->load ( 'tank_auth' );
		$this->load->library ( 'session' );
		$this->load->model ( 'common' );
		$this->load->model ( 'pluginlistmodel' );
	}
	
	function index() {
		$userId = $this->common->getUserId ();
		
		$userKeys = $this->pluginlistmodel->getUserKeys ( $userId );
		$plugins = array ();
		if ($userKeys) {
			$this->data ['puserkey'] = $userKeys->user_key;
			$this->data ['pusersecret'] = $userKeys->user_secret;
			$this->data ['succesmsg'] = lang ( 'plg_keysecret_error' );
			$this->common->loadHeader(lang('v_plugins_account'));
			$this->load->view ( 'manage/accountauthview', $this->data );
		}
		else {
			$this->common->loadHeader(lang('v_plugins_account'));
			$this->load->view ( 'manage/accountauthview');
		}
		
	}
	
	// //save user's key&secret in razor_ table
	function saveUserKeys() {
		$this->form_validation->set_rules ( 'userkey', 'UserKey', 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'usersecret', 'UserSecret', 'trim|required|xss_clean' );
		if ($this->form_validation->run ()) {
			$userKey = $this->input->post ( "userkey" );
			$userSecret = $this->input->post ( "usersecret" );
			if ($this->pluginlistmodel->verifyUserKeys ( $userKey, $userSecret )) {
				
				$userId = $this->common->getUserId ();
				$this->pluginlistmodel->saveUserKeys ( $userId, $userKey, $userSecret );
				//redirect ( site_url () . "/manage/accountauth",$this->data );
				$this->data ['puserkey'] = $userKey;
				$this->data ['pusersecret'] = $userSecret;
				$this->data ['successmsg'] = lang ( 'plg_keysecret_success' );
				$this->common->loadHeader(lang('v_plugins_account'));
				$this->load->view ( 'manage/accountauthview', $this->data );
				
			} else {
				$this->data ['msg'] = lang ( 'plg_keysecret_error' );
				$this->common->loadHeader ( lang ( 'v_plugins_account' ) );
				$this->load->view ( 'manage/accountauthview', $this->data );
			}
		} else {
			$this->data ['msg'] = lang ( 'plg_keysecret_error' );
			$this->common->loadHeader ( lang ( 'v_plugins_account' ) );
			$this->load->view ( 'manage/accountauthview', $this->data );
		}
	}

}



