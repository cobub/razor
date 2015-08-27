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
 * Getuireport Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class getuireport extends CI_Controller
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
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view pushnote
     *
     * @return void
     */
    function index()
    {
        $this->data ['appname'] = "testappname";
        $this->data ['appid'] = "testappname";
        $this->data ['userSecret'] = "testappname";
        $this->data ['userKey'] = "testappname";
        $this->common->loadHeader();
        $this->load->view('plugin/getui/pushnote', $this->data);
    }
}

?>