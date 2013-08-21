<?php
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . "/plugin/pluginInterface.php";
class getui implements pluginInterface {
	
	function __construct() {
	}
	
	function getPluginInfo() {
		return array (
				'identifier' => 'igetui',
				'name' => '个推',
				'level' => 1,
				'description' => '个推插件 是Cobub Razor官方基于个推开发的一款推送插件.它集成了个推的推送功能与Cobub Razor提供的Tag功能，更加方便，快捷，精准的进行推送。',
				'version' => '0.1',
				'date' => '2013-08-30',
				'provider' => '南京西桥科技',
				'detail'=> 'http://dev.cobub.com/users/index.php?/help/getui',
				'menus' => $this->getMenus () 
		);
	}
	
	function getMenus() {
		$sideBars = "<h3>个推</h3>
            <ul class='toggle'>
                <li class='icn_my_application'>

                    <a href='" . site_url () . "/plugin/getui/applist' class='colorMediumBlue bold spanHover'>个推首页</a>

                </li>
               
                <li class='icn_add_apps'>
               <a href='" . site_url () . "/plugin/getui/getuiapplist' class='colorMediumBlue bold spanHover'> 个推报表</a></li>

               
            </ul>";

        $menus = array();    

        // $menus = array(
        // 	'title'=>'个推',
        // 	'个推首页'=>'/plugin/getui/applist',
        // 	'个推报表'=>'/plugin/getui/getuiapplist'
        // 	);
        $menuHome = array(
        	'name'=>'个推首页',
        	'link'=>'/plugin/getui/applist',
        	'level1'=>true,
        	'level2'=>false
        	);

        array_push($menus,$menuHome);

         $menuPush = array(
        	'name'=>'个推报表',
        	'link'=>'/plugin/getui/getuiapplist',
        	'level1'=>true,
        	'level2'=>false
        	);
        array_push($menus,$menuPush);
        $menuRet = array(
        	'title' => '个推',
        	'menus' => $menus
        	);

		return $menuRet;
	}

}

?>