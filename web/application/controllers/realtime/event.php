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
 * Event Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Event extends CI_Controller
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
        $this->load->model('realtime/eventModel', 'eventemodel');
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view eventview
     *
     * @return void
     */
    function index()
    {
        $productId = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $productId=$productId->id;
        $data['productId'] = $productId;
        $data['reportTitle'] = array('title' => lang("v_rpt_realtime_event_report_title"),'subtitle'=>lang("v_rpt_realtime_event_in_minute"));
        $data['event_identifier']= "writeblog";
        $this->common->loadHeader(lang("v_rpt_realtime_event_report_title"));
        $this->load->view('realtime/eventview', $data);
    }

    /**
     * GetEventNum funciton, get event number
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getEventNum($productId)
    {
        $ret = $this->eventemodel->getEventNumByEvent($productId);
        echo json_encode($ret);
    }

    /**
     * GetEventNumByTime funciton, get event number
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getEventNumByTime($productId)
    {
        $ret = $this->eventemodel->getEventNumByTime($productId);
        echo json_encode($ret);
    }
}