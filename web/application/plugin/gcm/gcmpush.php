<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/plugin/pluginInterface.php";
class Gcmpush  extends CI_Model implements pluginInterface {

	function __construct() {
		$this->load->language('plugin_gcm');

	}

	function getPluginInfo() {
		return array (
				'identifier' => 'GCM',
				'name' => 'GCM',
				'level' => 1,
				'description' => lang('gcm_descraption'),
				'version' => '0.1',
				'date' => '2013-09-02',
				'provider' => lang('gcm_provider'),
				'detail'=> lang('gcm_detail'),
				'menus' => $this->getMenus ()
		);
	}

	function getMenus() {
		$menus = array();

		$menuHome = array(
				'name'=> lang('gcm_m_homepage'),
				'link'=>'/plugin/gcm/gcmhome',
				'level1'=>true,
				'level2'=>false
		);

		array_push($menus,$menuHome);

		$menuRet = array(
				'title' => 'GCM',
				'menus' => $menus
		);

		return $menuRet;
	}

}

?>