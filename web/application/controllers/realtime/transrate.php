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
 * Transrate Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Transrate extends CI_Controller
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->model('realtime/transRateModel', 'transratemodel');
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view transrateview
     *
     * @return void
     */
    function index()
    {
        $productId = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $productId=$productId->id;
        $data['productId'] = $productId;
        $data['reportTitle'] = array('title' => lang('v_rpt_realtime_transrate_title'),'subtitle' => lang('v_rpt_realtime_transtrte_subtitle'));
        $this->common->loadHeader(lang('v_rpt_realtime_transrate_title'));
        $this->load->view('realtime/transrateview', $data);
    }

    /**
     * GetTransrate funciton, get transrate
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getTransrate($productId)
    {
        $ret = $this->transratemodel->getTransRate($productId);
        echo json_encode($ret);
    }

    /**
     * GetTransrateByTime funciton, get transrate
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getTransrateByTime($productId)
    {
        $ret = $this->transratemodel->getTransRateByTime($productId);
        echo json_encode($ret);
    }
}