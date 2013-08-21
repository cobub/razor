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
 *
 */
class Pluginlist extends CI_Controller {
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
			///all use plugins
			$json = $this->pluginlistmodel->getAllPlugins ();
			$this->data ['allplugins'] = json_decode ( $json );
			///my plug_ins
			$this->data ['myPlugins'] = $this->pluginlistmodel->getMyPlugins ( $userId );
			if ($this->data ['myPlugins'] && count ( $this->data ['myPlugins'] ) > 0) {
				foreach ( $this->data ['myPlugins'] as $plugin ) {
					$plugin ['status'] = $this->pluginlistmodel->getPluginStatus ( $userId, $plugin ['identifier'] );
					
					foreach ( $this->data ['allplugins'] as $allplugin )
					{
						if($allplugin->plugin_name ==  $plugin['name'])
						{
							$myver=preg_replace('/[^\d]/','',$plugin ['version']);
							$allver=preg_replace('/[^\d]/','',$allplugin->plugin_version);
							if($myver < $allver)
							{
							 	$plugin ['new_version'] =$allplugin->plugin_version;
							}
						}
						
					}
					array_push ( $plugins, $plugin );
				}
			}
			
		} else {
			$this->data ['msg'] = lang ( 'plg_get_keysecret' );
		}
		
		////my plugins
		$this->data ['myPlugins'] = $plugins;
		
		$this->common->loadHeader ( lang ( 'plg_plugin_manage' ) );
		$this->load->view ( 'manage/pluginsview', $this->data );
	}
	
	/*
	 * active plugin
	 */
	function activePlug($identifier) {
		$userId = $this->common->getUserId ();
		$this->pluginlistmodel->activePlugin ( $userId, $identifier );
		redirect ( site_url () . "/manage/pluginlist" );
	}
	
	/*
	 * fobidden plugin
	 */
	function disablePlug($identifier) {
		$userId = $this->common->getUserId ();
		$this->pluginlistmodel->disablePlugin ( $userId, $identifier );
		redirect ( site_url () . "/manage/pluginlist" );
	}
}


