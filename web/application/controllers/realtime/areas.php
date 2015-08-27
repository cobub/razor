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
 * Areas Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Areas extends CI_Controller
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
        $this->load->model('realtime/areamodel', 'areamodel');
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view areas
     *
     * @return void
     */
    function index()
    {
        $productId = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $productId=$productId->id;
        $data['productId'] = $productId;
        $data['reportTitle'] = array('title'=>lang('v_rpt_realtime_onlineuser_title'),'subtitle'=>lang('v_rpt_realtime_onlineuser_subtitle'));
        $this->common->loadHeader(lang('v_rpt_realtime_onlineuser_title'));
        $this->load->view('realtime/areas', $data);
    }

    /**
     * GetOnlineUsers funciton, get online user
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

    /**
     * GetAreasData funciton, get areas data
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getAreasData($productId)
    {
        $ret = $this->areamodel->getAreasData($productId);
        echo json_encode($ret);
    }

    /**
     * GetBubbleAreasData funciton, get bubble areas data
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getBubbleAreasData($productId)
    {
        $ret = $this->areamodel->getBubbleAreasData($productId);
        echo json_encode($ret);
    }

    /**
     * GetAreaDataForGrid funciton, get areas data
     *
     *@param int $productId produiuct id
     *
     * @return encode json
     */
    function getAreaDataForGrid($productId)
    {
        $ret = $this->areamodel->getAreaDataForGrid($productId);
        echo json_encode($ret);
    }

    /**
     * GetDetailRegionsInfo funciton, get detail regions information
     *
     *@param int    $productId   produiuct id
     *@param string $countryName country name
     *
     * @return void
     */
    function getDetailRegionsInfo($productId,$countryName)
    {
        $data["regions"] = $this->areamodel->getRegionsByCountry($productId, $countryName);
        $this->load->view("realtime/regiondetail", $data);
    }
}