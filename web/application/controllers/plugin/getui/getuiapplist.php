<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Getuipplist Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class getuiapplist extends CI_Controller
{
    private $data = array();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->language('plugin_getui');
        $this->load->Model('common');
        $this->load->Model('plugin/getui/applistmodel', 'plugins');
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view pluginapp
     *
     * @return void
     */
    function index()
    {
        $this->common->cleanCurrentProduct();
        $this->common->loadHeaderWithDateControl(lang('getui_report'));
        $applist = $this->plugins->getApplist();
        if (count($applist)>=1) {
            if (count($applist[0])>=1) {
                $productid=$applist[0][0]['id'];
            } else {
                  $productid='';
            }
        } else {
                $productid='';
        }
        $appid = $this->plugins->getAppid($productid);
        $this->data ['appid'] = $appid;
        $this->data ['arr'] = $applist;
        $this->load->view('plugin/getui/pluginapp', $this->data);
    }
}
?>