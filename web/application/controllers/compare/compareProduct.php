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
 * Postnews Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class CompareProduct extends CI_Controller
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('common');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('tank_auth');
        $this->load->library('ums_acl');
        $this->load->model('product/productmodel', 'product');
        $this->load->library('export');
        $this->load->database();
    }

    /**
     * Index function
     *
     * @return json encode
     */
    public function index()
    {
        $pids = $_POST['pids'];
        $this->common->setCompareProducts($pids);
        $this->common->cleanCurrentProduct();
        echo json_encode('ok');
    }

    /**
     * Compareconsole function,load view userbehavorview
     *
     * @return void
     */
    public function compareConsole()
    {
        $this->common->loadCompareHeader(lang('m_rpt_dashboard'), true);
        $this->load->view('compare/userbehavorview');
    }
}