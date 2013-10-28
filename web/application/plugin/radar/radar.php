<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/plugin/pluginInterface.php";
class Radar extends CI_Model implements pluginInterface {
	
	function __construct() {
        
        $this->load->model('common');
        $this->load->language('plugin_radar');
	}
	
	function getPluginInfo() {
		return array (
				'identifier' => 'radar',
				'name' => lang('ls_title'),
				'level' => 2,
				'description' => lang('ls_des'),
				'version' => lang('ls_version'),
				'date' => lang('ls_date'),
				'provider' => lang('ls_provider'),
				'detail'=> 'http://dev.cobub.com/users/index.php?/help/radar',
				'menus' => $this->getMenus () 
		);
	}
	
	function getMenus() {
        $menus = array();    
        $menuPush = array(
        	'name'=> lang('m_sub_1'),
        	'link'=>'/plugin/radar/checkradarinfo',
        	'level1'=>false,
        	'level2'=>$this->getIos()
    	);
        array_push($menus,$menuPush);

        $menuRet = array(
        	'title' => lang('m_main'),
        	'menus' => $menus
     	);

		return $menuRet;
	}

    function getIos() {
        $currentProduct = $this->common->getCurrentProduct();

        if (empty($currentProduct)) {
            return false;
        } else {
            $platform = $currentProduct->product_platform;

            return ($platform == 2);
        }        
    }

}

?>
