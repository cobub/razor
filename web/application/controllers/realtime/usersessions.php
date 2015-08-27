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
 * Usersessions Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Usersessions extends CI_Controller
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
        $this->load->model('realtime/onlineusermodel', 'onlineusermodel');
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view usersessionsview
     *
     * @return void
     */
    function index()
    {
        $productId = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $productId=$productId->id;
        $data['productId'] = $productId;
        $data['reportTitle'] = array('title' => lang('v_rpt_realtime_onlineuser_title'),'subtitle' => lang('v_rpt_realtime_onlineuser_subtitle'));
        $this->common->loadHeader(lang('v_rpt_realtime_onlineuser_title'));
        $this->load->view('realtime/usersessionsview', $data);
    }

    /**
     * GetOnlineUsers funciton, get online users
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getOnlineUsers($productId)
    {
        $ret = $this->onlineusermodel->getOnlineUsers($productId);
        echo json_encode($ret);
    }
}