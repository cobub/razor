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
 * Funnels Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Funnels extends CI_Controller
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
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->library('export');
        $this->load->model('event/userEvent', 'event');
        $this->load->model('conversion/conversionmodel', 'conversion');
        $this->common->requireLogin();
        $this->common->checkCompareProduct();
    }

    /**
     * Index fuction
     *
     * @return void
     */
    function index()
    {
        $user_id = $this->common->getUserId();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $result = array();
        // load compare product data
        if (isset($_GET['type']) && 'compare' == $_GET['type']) {
            $products = $this->common->getCompareProducts();
            for ($i = 0; $i < count($products); $i ++) {
                $data = $this->conversion->getConversionListByProductIdAndUserId($products[$i]->id, $user_id, $fromTime, $toTime);
                $targetdata = $data['targetdata'];
                $eventdata = $data['eventdata'];
                $result['result'][$i] = $this->prepareUnitAndeventCount($targetdata, $eventdata);
                $result['result'][$i]['name'] = $products[$i]->name;
            }
            $result['common'] = array('type' => 'compare');
            $this->common->loadCompareHeader(lang('m_rpt_events'));
            $this->load->view('conversionrate/funnelsview', $result);
            return;
        } // end load compare data
        $this->common->loadHeaderWithDateControl();
        $this->common->requireProduct();
        $product_id = $this->common->getCurrentProduct()->id;
        $data = $this->conversion->getConversionListByProductIdAndUserId($product_id, $user_id, $fromTime, $toTime);
        $targetdata = $data['targetdata'];
        $eventdata = $data['eventdata'];
        $data['result'] = $this->prepareConversionData($targetdata, $eventdata);
        $this->load->view('conversionrate/funnelsview', $data);
    }
    
    /**
     * PrepareConversionData function
     * Prepare conversion data
     *
     * @param array $targetdata $targetdata = array()
     * @param array $eventdata  $eventdata = array()
     *
     * @return $result
     */
    function prepareConversionData($targetdata = array(), $eventdata = array())
    {
        $result = array();
        for ($i = 0; $i < count($targetdata); $i ++) {
            $target = $targetdata[$i];
            $result['tid'][$i] = $target['tid'];
            $result['targetname'][$i] = $target['targetname'];
            $result['unitprice'][$i] = $target['unitprice'];
            $result['event1'][$i] = $target['a1'];
            $result['event2'][$i] = $target['a2'];
            if (empty($eventdata)) {
                $result['event1_c'][$i] = 0;
                $result['event2_c'][$i] = 0;
            }
            $e1_c = 0;
            $e2_c = 0;
            
            for ($j = 0; $j < count($eventdata); $j ++) {
                $event = $eventdata[$j];
                if ($target['sid'] == $event['event_id']) {
                    $result['event1_c'][$i] = $event['num'];
                    $e1_c ++;
                }
                if ($target['eid'] == $event['event_id']) {
                    $result['event2_c'][$i] = $event['num'];
                    $e2_c ++;
                }
            }
            if ($e1_c == 0) {
                $result['event1_c'][$i] = 0;
            }
            if ($e2_c == 0) {
                $result['event2_c'][$i] = 0;
            }
        }
        return $result;
    }
    
    /**
     * PrepareUnitAndeventCount function
     * Prepare compare unitprice and totalcount
     *
     * @param array $targetdata $targetdata = array()
     * @param array $eventdata  $eventdata = array()
     *
     * @return $result
     */
    function prepareUnitAndeventCount($targetdata = array(), $eventdata = array())
    {
        $result = array();
        $date = array();
        $sc_arr = array();
        $unitprices = array();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $datelist = $this->common->getDateList($fromTime, $toTime);
        // all event count
        for ($d = 0; $d < count($datelist); $d ++) {
            $count = 0;
            $scount = 0;
            $unitprice = 0;
            for ($i = 0; $i < count($targetdata); $i ++) {
                $target = $targetdata[$i];
                for ($j = 0; $j < count($eventdata); $j ++) {
                    $event = $eventdata[$j];
                    if ($target['eid'] == $event['event_id']) {
                        $date_array = explode(" ", $event['datevalue']);
                        $k = '';
                        if ($datelist[$d] == $date_array[0]) {
                            $count += $event['num'];
                            $unitprice += $event['num'] * ($target['unitprice']);
                        } else {
                            $count += 0;
                        }
                    }
                    if ($target['sid'] == $event['event_id']) {
                        $date_array = explode(" ", $event['datevalue']);
                        $k = '';
                        if ($datelist[$d] == $date_array[0]) {
                            $scount += $event['num'];
                        } else {
                            $scount += 0;
                        }
                    }
                }
            }
            $sc_arr[$datelist[$d]] = $scount;
            $date[$datelist[$d]] = $count;
            $unitprices[0][$datelist[$d]] = $unitprice;
        }
        $result['unitprice'] = $unitprices;
        $result['date'] = $date;
        $result['scount'] = $sc_arr;
        return $result;
    }
    
    /**
     * Addconversionsreport function
     * Load funnel report
     *
     * @param string $delete $delete = null
     * @param string $type   $type = null
     *
     * @return $result
     */
    function addconversionsreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $data['reportTitle'] = array('errorCount' => getReportTitle(lang("v_rpt_err_errorNums"), $fromTime, $toTime),'errorCountPerSession' => getReportTitle(lang("v_rpt_err_errorNumsInSessions"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime));
        $productId = $this->common->getCurrentProduct();
        if (! empty($productId) && $delete == null) {
            $data['add'] = "add";
        }
        if ($delete == "del") {
            $data['delete'] = "delete";
        }
        if ($type != null) {
            $data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/conversions', $data);
    }

    /**
     * AddFunnel function
     * Add funnel
     *
     * @return $result
     */
    function addFunnel()
    {
        $target_name = $_POST['funnel_name'];
        $step_events = $_POST['event_ids'];
        $step_names = $_POST['step_names'];
        $unitprice = $_POST['unitprice'];
        $user_id = $this->common->getUserId();
        $product_id = $this->common->getCurrentProduct()->id;
        $data['events'] = explode(',', $step_events);
        $data['names'] = explode(',', $step_names);
        $info = $this->conversion->addConversionrate($user_id, $product_id, $target_name, $unitprice, $data);
        echo $info;
    }

    /**
     * ViewDetail function
     * View detail
     *
     * @param int $targetid targetid
     *
     * @return $result
     */
    function viewDetail($targetid)
    {
        $this->common->loadHeaderWithDateControl();
        $productId = $this->common->getCurrentProduct();
        $productId = $productId->id;
        $fromdate = $this->common->getFromTime();
        $todate = $this->common->getToTime();
        $this->data['reportTitle'] = array('eventCount' => getReportTitle(lang("v_rpt_re_eventcount"), $fromdate, $todate),'timePhase' => getTimePhaseStr($fromdate, $todate));
        $this->data['versions'] = $this->event->getProductVersions($productId);
        $this->data['targetid'] = $targetid;
        $this->load->view('conversionrate/funneldetailview', $this->data);
    }

    /**
     * GetViewDetail function
     * Get view detail
     *
     * @param int $targetid targetid
     * @param int $version  version
     *
     * @return void
     */
    function getViewDetail($targetid, $version)
    {
        $productId = $this->common->getCurrentProduct();
        $productId = $productId->id;
        $fromdate = $this->common->getFromTime();
        $todate = $this->common->getToTime();
        $eventDetaildata = array();
        $eventDetail = array();
        $razordata = $this->conversion->detailfunnel($targetid);
        $razordwdata = $this->conversion->detailfunnel2($fromdate, $todate, $version, $productId);
        $i = 0;
        foreach ($razordata as $row) {
            $data = array();
            $i ++;
            $event_id = $row->eventid;
            $data['eventalias'] = $row->eventalias;
            $data['num'] = 0;
            foreach ($razordwdata as $dwrow) {
                $dwevent_id = $dwrow->event_id;
                if ($event_id == $dwevent_id) {
                    $data['num'] = $dwrow->num;
                    break;
                }
            }
            array_push($eventDetail, $data);
        }
        if ($eventDetail != null) {
            $eventDetaildata['content'] = $eventDetail;
        }
        echo json_encode($eventDetaildata);
    }

    /**
     * DelteFunnelEvent function
     * Delte funnel event
     *
     * @return json
     */
    function delteFunnelEvent()
    {
        $target_id = $_POST['target_id'];
        $event_id = $_POST['event_id'];
        if ($this->conversion->checkIsDeleteFunnelEvent($target_id) <= 2) {
            echo json_encode('lt2');
            // less than 2
            return;
        }
        $affrow = $this->conversion->delteFunnelEvent($target_id, $event_id);
        echo json_encode($affrow);
    }

    /**
     * EditFunnel function
     * Edit funnel
     *
     * @param int $targetid targetid
     *
     * @return void
     */
    function editFunnel($targetid)
    {
        $this->common->loadHeader();
        $user_id = $this->common->getUserId();
        $product_id = $this->common->getCurrentProduct()->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $data['eventlist'] = $this->event->getEventListByProductIdAndProductVersion($product_id, 'all', $fromTime, $toTime);
        $data['steplist'] = $this->conversion->getFunnelByTargetid($targetid);
        $this->load->view('conversionrate/modify', $data);
    }

    /**
     * ModifyFunnel function
     * Modify funnel
     *
     * @return void
     */
    function modifyFunnel()
    {
        $event_ids = $_POST['event_ids'];
        $step_names = $_POST['step_names'];
        $target_id = $_POST['target_id'];
        $target_name = $_POST['funnel_name'];
        $unitprice = $_POST['unitprice'];
        $data['event_ids'] = explode(',', $event_ids);
        $data['event_names'] = explode(',', $step_names);
        
        $aff_row = $this->conversion->modifyFunnel($target_id, $target_name, $unitprice, $data);
        echo json_encode($aff_row);
    }

    /**
     * GetChartData function
     * Get chartData
     *
     * @return json
     */
    function getChartData()
    {
        $product = $this->common->getCurrentProduct();
        $user_id = $this->common->getUserId();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $result = array();
        // load data
        if (empty($product)) {
            $products = $this->common->getCompareProducts();
            for ($i = 0; $i < count($products); $i ++) {
                $data = $this->conversion->getConversionListByProductIdAndUserId($products[$i]->id, $user_id, $fromTime, $toTime);
                $targetdata = $data['targetdata'];
                $eventdata = $data['eventdata'];
                $result['result'][$i] = $this->prepareUnitAndeventCount($targetdata, $eventdata);
                $result['result'][$i]['name'] = $products[$i]->name;
            }
            $result['common'] = array('type' => 'compare');
            echo json_encode($result);
            return;
        }
        $productId = $product->id;
        $result['dataList'] = $this->conversion->getChartData($user_id, $productId, $fromTime, $toTime);
        // load markevents
        $this->load->model('point_mark', 'pointmark');
        $markevnets = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime)->result_array();
        $marklist = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime, 'listcount');
        $result['marklist'] = $marklist;
        $result['markevents'] = $markevnets;
        $result['defdate'] = $this->common->getDateList($fromTime, $toTime);
        // end load markevents
        echo json_encode($result);
    }
    
    /**
     * ExportComparedata function
     * Export the compare data
     *
     * @return void
     */
    function exportComparedata()
    {
        $user_id = $this->common->getUserId();
        $fromTime = $this->common->getFromTime();
        $products = $this->common->getCompareProducts();
        $toTime = $this->common->getToTime();
        $titlename = getExportReportTitle('Compare', lang('v_rpt_re_funnelModel'), $fromTime, $toTime);
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $this->export->setFileName($titlename);
        $result = array();
        for ($i = 0; $i < count($products); $i ++) {
            $data = $this->conversion->getConversionListByProductIdAndUserId($products[$i]->id, $user_id, $fromTime, $toTime);
            $targetdata = $data['targetdata'];
            $eventdata = $data['eventdata'];
            $result['result'][$i] = $this->prepareUnitAndeventCount($targetdata, $eventdata);
            $result['result'][$i]['name'] = $products[$i]->name;
        }
        $excel_title = array();
        for ($i = 0; $i < count($result['result']); $i ++) {
            if ($i == 0) {
                array_push($excel_title, iconv("UTF-8", "GBK", lang('g_date')));
            }
            array_push($excel_title, iconv("UTF-8", "GBK", " "));
            array_push($excel_title, iconv("UTF-8", "GBK", $result['result'][$i]['name']));
        }
        $this->export->setTitle($excel_title);
        $rowTitle = array();
        // set firest row
        $rowTitle[0] = " ";
        for ($i = 0; $i < count($result['result']); $i ++) {
            array_push($rowTitle, lang('v_rpt_re_funneleventC'));
            array_push($rowTitle, lang('v_rpt_re_unitprice'));
        }
        $this->export->addRow($rowTitle);
        $datelist = $result['result'][0]['date'];
        foreach ($datelist as $key => $value) {
            $row = array();
            $row[0] = $key;
            for ($i = 0; $i < count($result['result']); $i ++) {
                $r = $result['result'][$i];
                $date = $r['date'];
                foreach ($date as $k => $v) {
                    if ($key == $k) {
                        array_push($row, $date[$key]);
                        if (! isset($r['unitprice'])) {
                            echo 0;
                        } else {
                            array_push($row, $r['unitprice'][0][$key]);
                        }
                    }
                }
            }
            $this->export->addRow($row);
        }
        $this->export->export();
        die();
    }
}
