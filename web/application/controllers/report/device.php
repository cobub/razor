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
 * Device Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Device extends CI_Controller
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
        $this->load->model('channelmodel', 'channel');
        $this->load->model('product/devicemodel', 'device');
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/newusermodel', 'newusermodel');
        $this->common->requireLogin();
        $this->common->checkCompareProduct();
    }

    /**
     * Index
     *
     * @return void
     */
    function index()
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        if (isset($_GET['type']) && $_GET['type'] == 'compare') {
            $this->common->loadCompareHeader();
            $this->_data['reportTitle'] = array('activeUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_de_top10"), $fromTime, $toTime), 'newUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_de_top10"), $fromTime, $toTime), 'timePhase' => getTimePhaseStr($fromTime, $toTime));
            $this->load->view('compare/devicetype', $this->_data);
        } else {
            $this->common->loadHeaderWithDateControl();
            $productId = $this->common->getCurrentProduct();
            $this->common->requireProduct();
            $productId = $productId->id;
            $fromTime = $this->common->getFromTime();
            $toTime = $this->common->getToTime();
            
            $Total = $this->device->getDeviceSessionTotal($productId, $fromTime, $toTime);
            if ($Total) {
                $this->_data['sessions'] = $Total->first_row()->sessions;
                $this->_data['newusers'] = $Total->first_row()->newusers;
            } else {
                $this->_data['sessions'] = 0;
                $this->_data['newusers'] = 0;
            }
            
            $this->_data['deviceDetails'] = $this->device->getDeviceTypeDetail($productId, $fromTime, $toTime);
            $this->load->view('terminalandnet/deviceview', $this->_data);
        }
    }

    /**
     * Adddevicetypereport
     *
     * @param string $delete delete
     * @param string $type   type
     *            
     * @return void
     */
    function adddevicetypereport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->_data['reportTitle'] = array('activeUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_de_top10"), $fromTime, $toTime),'newUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_de_top10"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime));
        if ($delete == null) {
            $this->_data['add'] = "add";
        }
        if ($delete == "del") {
            $this->_data['delete'] = "delete";
        }
        if ($type != null) {
            $this->_data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/devicetype', $this->_data);
    }

    /**
     * GetDeviceReportData
     *
     * @param string $timePhase timePhase
     *            
     * @return json
     */
    function getDeviceReportData($timePhase = "all")
    {
        $productId = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        
        if (empty($productId)) {
            $products = $this->common->getCompareProducts();
            if (empty($products)) {
                $this->common->requireProduct();
                return;
            }
            for ($i = 0; $i < count($products); $i ++) {
                $activedata = $this->device->getSessionByDevicetop($fromTime, $toTime, $products[$i]->id);
                $newdata = $this->device->getNewuserByDevicetop($fromTime, $toTime, $products[$i]->id);
                $ret["activeUserData" . $products[$i]->name] = $this->change2StandardPrecent($activedata, 1);
                $ret["newUserData" . $products[$i]->name] = $this->change2StandardPrecent($newdata, 2);
            }
        } else {
            $this->common->requireProduct();
            $activeUserData = $this->device->getSessionByDevicetop($fromTime, $toTime, $productId->id);
            $newUserData = $this->device->getNewuserByDevicetop($fromTime, $toTime, $productId->id);
            $ret["activeUserData"] = $this->change2StandardPrecent($activeUserData, 1);
            $ret["newUserData"] = $this->change2StandardPrecent($newUserData, 2);
        }
        echo json_encode($ret);
    }

    /**
     * Change2StandardPrecent
     *
     * @param array $userData userdata
     * @param int   $type     type
     *             
     * @return array
     */
    function change2StandardPrecent($userData, $type)
    {
        $userDataArray = array();
        $totalPercent = 0;
        $numTotal = 0;
        
        foreach ($userData->result() as $row) {
            if ($type == 1) {
                $numTotal += $row->sessions;
            }
            if ($type == 2) {
                $numTotal += $row->newusers;
            }
        }
        
        foreach ($userData->result() as $row) {
            if (count($userData) > 10) {
                break;
            }
            $userDataObj = array();
            
            if (empty($row->devicebrand_name)) {
                $row->devicebrand_name = "unknown";
            }
            $userDataObj["devicebrand_name"] = $row->devicebrand_name;
            
            if ($type == 1) {
                $userDataObj["sessions"] = $row->sessions / 1;
                $percent = round($row->sessions / $numTotal * 100, 1);
                $totalPercent += $percent;
                $userDataObj["percentage"] = $percent;
            }
            
            if ($type == 2) {
                $userDataObj["newusers"] = $row->newusers / 1;
                $percent = round($row->newusers / $numTotal * 100, 1);
                $totalPercent += $percent;
                $userDataObj["percentage"] = $percent;
            }
            
            array_push($userDataArray, $userDataObj);
        }
        
        if ($totalPercent < 100.0) {
            $remainPercent = round(100 - $totalPercent, 2);
            $userDataObj["devicebrand_name"] = lang('g_others');
            $userDataObj["percentage"] = $remainPercent;
            if ($type == 1) {
                $userDataObj["sessions"] = 0;
            }
            
            if ($type == 2) {
                $userDataObj["newusers"] = 0;
            }
            array_push($userDataArray, $userDataObj);
        }
        
        return $userDataArray;
    }

    /**
     * ExportCSV
     *
     * @return array
     */
    function exportCSV()
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $products = $this->common->getCompareProducts();
        if (empty($products)) {
            $this->common->requireProduct();
            return;
        }
        $this->load->library('export');
        $export = new Export();
        $titlename = getExportReportTitle("Compare", lang("v_rpt_de_top10"), $fromTime, $toTime);
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $export->setFileName($titlename);
        $j = 0;
        $mk = 0;
        $title[$j ++] = iconv("UTF-8", "GBK", lang('t_sessions'));
        $space[$mk ++] = ' ';
        for ($i = 0; $i < count($products); $i ++) {
            $title[$j ++] = iconv("UTF-8", "GBK", $products[$i]->name);
            $title[$j ++] = iconv("UTF-8", "GBK", lang('v_rpt_re_count'));
            $title[$j ++] = iconv("UTF-8", "GBK", lang('g_percent'));
            $space[$mk ++] = ' ';
            $space[$mk ++] = ' ';
            $space[$mk ++] = ' ';
        }
        $export->setTitle($title);
        $k = 0;
        $maxlength = 0;
        $maxlength2 = 0;
        $j = 0;
        $nextlabel[$j ++] = lang('t_newUsers');
        for ($m = 0; $m < count($products); $m ++) {
            $activedata = $this->device->getSessionByDevicetop($fromTime, $toTime, $products[$m]->id);
            $newdata = $this->device->getNewuserByDevicetop($fromTime, $toTime, $products[$m]->id);
            $detailData[$m] = $this->change2StandardPrecent($activedata, 1);
            $detailNewData[$m] = $this->change2StandardPrecent($newdata, 2);
            
            if (count($detailData[$m]) > $maxlength) {
                $maxlength = count($detailData[$m]);
            }
            if (count($detailNewData[$m]) > $maxlength2) {
                $maxlength2 = count($detailNewData[$m]);
            }
            $nextlabel[$j ++] = $products[$m]->name;
            $nextlabel[$j ++] = ' ';
            $nextlabel[$j ++] = ' ';
        }
        $this->getExportRowData($export, $maxlength, $detailData, $products, 1);
        $export->addRow($space);
        $export->addRow($nextlabel);
        $this->getExportRowData($export, $maxlength2, $detailNewData, $products, 2);
        $export->export();
        die();
    }

    /**
     * GetExportRowData
     *
     * @param string $export   export
     * @param string $length   length
     * @param string $userData userData
     * @param string $products products
     * @param int    $type     type
     *            
     * @return void
     */
    function getExportRowData($export, $length, $userData, $products, $type)
    {
        $k = 0;
        for ($i = 0; $i < $length; $i ++) {
            $result[$k ++] = $i + 1;
            for ($j = 0; $j < count($products); $j ++) {
                $obj = $userData[$j];
                if ($i >= count($obj)) {
                    $result[$k ++] = '';
                    $result[$k ++] = '';
                    $result[$k ++] = '';
                } else {
                    if ($obj[$i]['devicebrand_name'] == '') {
                        $result[$k ++] = 'unknow';
                    } else {
                        $result[$k ++] = $obj[$i]['devicebrand_name'];
                    }
                    if ($type == 1) {
                        $result[$k ++] = $obj[$i]['sessions'];
                    }
                    if ($type == 2) {
                        $result[$k ++] = $obj[$i]['newusers'];
                    }
                    $result[$k ++] = $obj[$i]['percentage'] . "%";
                }
            }
            
            $export->addRow($result);
            $k = 0;
        }
    }

    /**
     * Export
     *
     * @return void
     */
    function export()
    {
        $productId = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $productId = $productId->id;
        $productName = $this->common->getCurrentProduct()->name;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        
        $data = $this->device->getDeviceTypeDetail($productId, $fromTime, $toTime);
        if ($data != null && $data->num_rows() > 0) {
            $this->load->library('export');
            
            $export = new Export();
            // set file name
            $titlename = getExportReportTitle($productName, lang("v_rpt_de_details"), $fromTime, $toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $export->setFileName($title);
            // set title name
            $excel_title = array(
                    iconv("UTF-8", "GBK", lang("v_rpt_de_type")),
                    iconv("UTF-8", "GBK", lang("t_sessions")),
                    iconv("UTF-8", "GBK", lang("t_sessionsP")),
                    iconv("UTF-8", "GBK", lang("t_newUsers")),
                    iconv("UTF-8", "GBK", lang("t_newUsersP"))
            );
            $export->setTitle($excel_title);
            // //percent
            $Total = $this->device->getDeviceSessionTotal($productId, $fromTime, $toTime);
            if ($Total) {
                $sessions = $Total->first_row()->sessions;
                $newusers = $Total->first_row()->newusers;
            } else {
                $sessions = 0;
                $newusers = 0;
            }
            
            // set content
            foreach ($data->result() as $row) {
                if (! $row->devicebrand_name)
                $row->devicebrand_name = 'unknown';
                $rowadd['devicebrand_name'] = $row->devicebrand_name;
                $rowadd['sessions'] = $row->sessions;
                $rowadd['sessions_p'] = ($sessions > 0) ? round(100 * $row->sessions / $sessions, 1) . '%' : '0%';
                $rowadd['newusers'] = $row->newusers;
                $rowadd['newusers_p'] = ($newusers > 0) ? round(100 * $row->newusers / $newusers, 1) . '%' : '0%';
                $export->addRow($rowadd);
            }
            
            $export->export();
            die();
        } else {
            $this->load->view("usage/nodataview");
        }
    }
}
