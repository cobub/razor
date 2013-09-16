<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/plugin/pluginInterface.php";
class Ipush  extends CI_Model implements pluginInterface {

	function __construct() {
		//$this->load->language('plugin_getui');

	}
	
	function getPluginInfo() {
		return array (
				'identifier' => 'ipush',
				'name' => 'IOS 推送',
				'level' => 1,
				'description' => 'ios push',
				'version' => '0.1',
				'date' => '2013-08-30',
				'provider' => '南京西桥科技',
				'detail'=> 'http://dev.cobub.com/users/index.php?/help',
				'menus' => $this->getMenus () 
		);
	}
	
	function getMenus() {
		$menus = array();
		$menuPush = array(
				'name'=> 'IOS首页',
				'link'=>'/plugin/iospush/iosapplist',
				'level1'=>true,
				'level2'=>false
		);
		array_push($menus,$menuPush);
		
		$menuRet = array(
				'title' => 'ios 推送',
				'menus' => $menus
		);
		
		return $menuRet;
	}
	}
?>