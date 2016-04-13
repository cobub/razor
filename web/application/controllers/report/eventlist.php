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
 * Eventlist Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Eventlist extends CI_Controller
{

    /**
     * Data array $data
     */
    private $data = array();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->model('event/userEvent', 'event');
        $this->load->model('product/versionmodel', 'versionmodel');
        $this->load->model('analysis/trendandforecastmodel', 'trend');
        $this->common->requireLogin();
        $this->common->requireProduct();
        $this->load->model('product/productmodel', 'product');
    }

    /**
     * Index fuction
     *
     * @return void
     */
    function index()
    {
        $this->common->loadHeaderWithDateControl();
        $productId = $this->common->getCurrentProduct();
        $productId = $productId->id;
        $this->data['versions'] = $this->event->getProductVersions($productId);
        $this->load->view('events/eventlistview', $this->data);
    }

    /**
     * GetEventListData function
     * Get eventList data
     *
     * @param int $version $version = ''
     *
     * @return void
     */
    function getEventListData($version = '')
    {
        $productId = $this->common->getCurrentProduct();
        $productId = $productId->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $result['event'] = $this->event->getEventListInfo($productId, $version, $fromTime, $toTime);
        echo json_encode($result);
    }

    /**
     * GetEventDeatil function
     * Get event deatil
     *
     * @param int    $event_sk   event_sk
     * @param int    $version    version
     * @param string $event_name event name
     * @param string $event_identifier event_identifier
     *
     * @return void
     */
    function getEventDeatil($event_sk, $version, $event_name,$event_identifier) {
        $this -> common -> loadHeaderWithDateControl();
        $productId = $this -> common -> getCurrentProduct();
        $productId = $productId -> id;
        $this -> data['event_sk'] = $event_sk;
        $this -> data['event_version'] = $version;
        $this -> data['event_name'] = urldecode($event_name);
        $this -> data['event_identifier'] = $event_identifier;
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $this -> data['reportTitle'] = array('timePase' => getTimePhaseStr($fromTime, $toTime), 'eventMsgNum' => lang("v_rpt_el_eventNum"), 'eventMsgNumActive' => lang("v_rpt_el_eventNumA"), 'eventMsgNumSession' => lang("v_rpt_el_eventNumS"));
        $this -> load -> view('events/eventchartdetailview', $this -> data);
    }

    /**
     * GetChartDataAll function
     * Get all chartData
     *
     * @param int $event_sk event_sk
     * @param int $version  version
     *
     * @return void
     */
    function getChartDataAll($event_sk, $version)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId = $currentProduct->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        // $fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
        $result = array();
        $data = $this->event->getAllEventChartData($productId, $event_sk, $version, $fromTime, $toTime);
        $trendFromtime = $this->common->getPredictiveValurFromTime();
        $dataoftrend = $this->event->getAllEventChartData($productId, $event_sk, $version, $trendFromtime, $toTime);
        $da = $dataoftrend;
        $trendresult = $this->trend->geteventtrenddata($da->result_array());
        // print_r($trendresult);
        // load markevents
        $mark = array();
        $currentProduct = $this->common->getCurrentProduct();
        $this->load->model('point_mark', 'pointmark');
        $markevnets = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime)->result_array();
        $marklist = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime, 'listcount');
        $result['marklist'] = $marklist;
        $result['markevents'] = $markevnets;
        $result['defdate'] = $this->common->getDateList($fromTime, $toTime);
        // end load markevents
        $result['dataList'] = $data->result();
        $result['trend'] = $trendresult;
        echo json_encode($result);
    }

	/*
    * Export resolution data to excel
    */
    function exportEvent($version = 'all',$eventname='') {

        $this -> load -> library('export');
		
		$productId = $this -> common -> getCurrentProduct();
        $productId = $productId -> id;
		$productName = $this -> common -> getCurrentProduct() -> name;
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $eventname = urldecode($eventname);
        if($eventname==''){
            $data = $this->event->getEventListInfo($productId, $version, $fromTime, $toTime);
        }
        else{
            $data = $this -> event -> getSearchEventInfo($productId, $version, $eventname,$fromTime,$toTime);
        }

        if($data != null && count($data) > 0 )
        {
            $export = new Export();
            ////set file name
            $titlename = getExportReportTitle($productName, lang('m_rpt_eventlist'), $fromTime, $toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $export -> setFileName($title);
            ////set title name
            $excel_title = array (iconv("UTF-8", "GBK", lang("v_rpt_el_eventID")),
            iconv("UTF-8", "GBK", lang("v_rpt_el_eventName")),
            iconv("UTF-8", "GBK", lang("v_rpt_el_messages")) );
            $export->setTitle ($excel_title );
            ////set content

            foreach ($data as $row){
            	$rowadd['eventidentifier'] = $row['eventidentifier'];
                $rowadd['eventname'] = $row['eventname'];
				$rowadd['count'] = $row['count'];
                $export->addRow ( $rowadd );
            }
            $export -> export();
            die();
        }
        else{
            $this->load->view("usage/nodataview");
        }
    }

   /*
    * search event by ID or Name
    */
    function getSearchEventData($version = '',$eventname='') {
        $productId = $this -> common -> getCurrentProduct();
        $productId = $productId -> id;
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $eventname = urldecode($eventname);
        if($eventname==''){
            $result['event'] = $this->event->getEventListInfo($productId, $version, $fromTime, $toTime);
        }
        else{
            $result['event'] = $this -> event -> getSearchEventInfo($productId, $version, $eventname,$fromTime,$toTime);
        }
        echo json_encode($result);
    }
}
 