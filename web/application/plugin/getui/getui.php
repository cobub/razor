<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/plugin/pluginInterface.php";
class getui  extends CI_Model implements pluginInterface {

	function __construct() {
		$this->load->language('plugin_getui');
		
	}
	
	function getPluginInfo() {
		return array (
				'identifier' => 'igetui',
				'name' => lang('getui'),
				'level' => 1,
				'description' => lang('getui_description'),
				'version' => '0.1',
				'date' => '2013-08-30',
				'provider' => lang('getui_provider'),
				'provider_url' => lang('getui_provider_url'),
				'detail'=> lang('getui_detail_url'),
				'menus' => $this->getMenus () 
		);
	}
	// '个推插件 是Cobub Razor官方基于个推开发的一款推送插件.
	//它集成了个推的推送功能与Cobub Razor提供的Tag功能，更加方便，快捷，精准的进行推送。',
	function getMenus() {
		// $sideBars = "<h3>个推</h3>
  //           <ul class='toggle'>
  //               <li class='icn_my_application'>

  //                   <a href='" . site_url () . "/plugin/getui/applist' class='colorMediumBlue bold spanHover'>个推首页</a>

  //               </li>
               
  //               <li class='icn_add_apps'>
  //              <a href='" . site_url () . "/plugin/getui/getuiapplist' class='colorMediumBlue bold spanHover'> 个推报表</a></li>

               
  //           </ul>";

        $menus = array();    

        // $menus = array(
        // 	'title'=>'个推',
        // 	'个推首页'=>'/plugin/getui/applist',
        // 	'个推报表'=>'/plugin/getui/getuiapplist'
        // 	);
        $menuHome = array(
        	'name'=>lang('getuiHomePage'),
        	'link'=>'/plugin/getui/applist',
        	'level1'=>true,
        	'level2'=>false
        	);

        array_push($menus,$menuHome);

         $menuPush = array(
        	'name'=>lang('getui_report'),
        	'link'=>'/plugin/getui/getuiapplist',
        	'level1'=>true,
        	'level2'=>false
        	);
        array_push($menus,$menuPush);
        $menuRet = array(
        	'title' => lang('getui'),
        	'menus' => $menus
        	);

		return $menuRet;
	}

}

?>