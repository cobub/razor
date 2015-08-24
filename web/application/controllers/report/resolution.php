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
 * Resolution Controller
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */

class Resolution extends CI_Controller
{
    /**
     * Data array $data
     */
    private $_data = array();
    
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this -> load -> Model('common');
        $this -> load -> model('channelmodel', 'channel');
        $this -> load -> model('product/resolutionmodel', 'orientationmodel');
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> model('product/newusermodel', 'newusermodel');
        $this -> common -> requireLogin();
        $this -> common -> checkCompareProduct();
    }
    
    /**
     * Index
     *
     * @return void
     */
    function index()
    {
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        if (isset($_GET['type']) && $_GET['type'] == 'compare') {
            $this -> common -> loadCompareHeader();
            $this -> _data['reportTitle'] = array('activeUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10"), $fromTime, $toTime), 
            'newUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10"), $fromTime, $toTime), 
            'timePhase' => getTimePhaseStr($fromTime, $toTime));
            $this -> load -> view('compare/resolutionview', $this -> data);
        } else {
            $this -> common -> loadHeaderWithDateControl();
            $productId = $this -> common -> getCurrentProduct();
            $this -> common -> requireProduct();
            $productId = $productId -> id;
            $fromTime = $this -> common -> getFromTime();
            $toTime = $this -> common -> getToTime();
            
            $Total = $this->orientationmodel->getSessionNewuserByResolution($fromTime, $toTime, $productId);
            if ($Total && $Total->num_rows()>0) {
                $this->_data['sessions'] = $Total->first_row()->sessions;
                $this->_data['newusers'] = $Total->first_row()->newusers;
            } else {
                $this->_data['sessions'] = 0;
                $this->_data['newusers'] = 0;
            }
            
            $this->_data['details'] = $this->orientationmodel->getTotalUsersPercentByResolution($fromTime, $toTime, $productId);
            $this->load->view('terminalandnet/resolutionview', $this->_data);
            
        }
    }

    /**
     * Addresolutioninforeport
     *
     * @param string $delete delete
     * @param string $type   type
     * 
     * @return void
     */
    function addresolutioninforeport($delete = null, $type = null)
    {
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $this -> data['reportTitle'] = array('activeUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10"), $fromTime, $toTime), 
        'newUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10"), $fromTime, $toTime), 
        'timePhase' => getTimePhaseStr($fromTime, $toTime));
        if ($delete == null) {
            $this -> data['add'] = "add";
        }
        if ($delete == "del") {
            $this -> data['delete'] = "delete";
        }
        if ($type != null) {
            $this -> data['type'] = $type;
        }
        $this -> load -> view('layout/reportheader');
        $this -> load -> view('widgets/resolutioninfo', $this -> data);
    }

    /**
     * GetResolutionData
     * 
     * @return void
     */
    function getResolutionData()
    {
        $productId = $this -> common -> getCurrentProduct();
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();

        if (empty($productId)) {
            $products = $this -> common -> getCompareProducts();
            if (empty($products)) {
                $this -> common -> requireProduct();
                return;
            }
            for ($i = 0; $i < count($products); $i++) {
                $activedata = $this -> orientationmodel -> getActiveUsersPercentByOrientation($fromTime, $toTime, $products[$i] -> id);
                $newdata = $this -> orientationmodel -> getNewUsersPercentByOrientation($fromTime, $toTime, $products[$i] -> id);
                $ret["activeUserData" . $products[$i] -> name] = $this -> change2StandardPrecent($activedata);
                $ret["newUserData" . $products[$i] -> name] = $this -> change2StandardPrecent($newdata);
            }
        } else {
            $this->common->requireProduct();
            $activeUserData = $this->orientationmodel->getSessionByOrientiontop($fromTime, $toTime, $productId->id);
            $newUserData = $this->orientationmodel->getNewuserByOrientiontop($fromTime, $toTime, $productId->id);
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
    function change2StandardPrecent($userData,$type)
    {
        $userDataArray = array();
        $totalPercent = 0;
        foreach ($userData->result() as $row) {
            if (count($userData) > 10) {
                break;
            }
            $userDataObj = array();
            if (empty($row->deviceresolution_name)) {
                $row->deviceresolution_name = "unknown";
            }
            $userDataObj["deviceresolution_name"] = $row->deviceresolution_name;
            if ($type == 1) {
                $userDataObj["sessions"] = $row->sessions / 1;
            }
            if ($type == 2) {
                $userDataObj["newusers"] = $row->newusers / 1;
            }
            array_push($userDataArray, $userDataObj);
        }

        return $userDataArray;
    }
    
    /**
     * ExportCSV
     * 
     * @return void
     */
    function exportCSV()
    {
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $products = $this -> common -> getCompareProducts();
        if (empty($products)) {
            $this -> common -> requireProduct();
            return;
        }
        $this -> load -> library('export');
        $export = new Export();
        $titlename = getExportReportTitle("Compare", lang("v_rpt_re_top10"), $fromTime, $toTime);
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $export -> setFileName($titlename);
        $j = 0;
        $mk = 0;
        $title[$j++] = iconv("UTF-8", "GBK", lang('t_activeUsers'));
        $space[$mk++] = ' ';
        for ($i = 0; $i < count($products); $i++) {
            $title[$j++] = iconv("UTF-8", "GBK", $products[$i] -> name);
            $title[$j++] = '';
            $space[$mk++] = ' ';
            $space[$mk++] = ' ';
        }
        $export -> setTitle($title);
        $k = 0;
        $maxlength = 0;
        $maxlength2 = 0;
        $j = 0;
        $nextlabel[$j++] = lang('t_newUsers');
        for ($m = 0; $m < count($products); $m++) {
            $activedata = $this -> orientationmodel -> getActiveUsersPercentByOrientation($fromTime, $toTime, $products[$m] -> id);
            $newdata = $this -> orientationmodel -> getNewUsersPercentByOrientation($fromTime, $toTime, $products[$m] -> id);
            $detailData[$m] = $this -> change2StandardPrecent($activedata, 1);
            $detailNewData[$m] = $this -> change2StandardPrecent($newdata, 2);
            if (count($detailData[$m]) > $maxlength) {
                $maxlength = count($detailData[$m]);
            }
            if (count($detailNewData[$m]) > $maxlength2) {
                $maxlength2 = count($detailNewData[$m]);
            }
            $nextlabel[$j++] = $products[$m] -> name;
            $nextlabel[$j++] = ' ';
        }
        $this -> getExportRowData($export, $maxlength, $detailData, $products);
        $export -> addRow($space);
        $export -> addRow($nextlabel);
        $this -> getExportRowData($export, $maxlength2, $detailNewData, $products);
        $export -> export();
        die();
    }
    
    /**
     * GetExportRowData
     * 
     * @param string $export   export
     * @param string $length   length
     * @param string $userData userdata
     * @param string $products products
     * 
     * @return void
     */
    function getExportRowData($export, $length, $userData, $products)
    {
        $k = 0;
        for ($i = 0; $i < $length; $i++) {
            $result[$k++] = $i + 1;
            for ($j = 0; $j < count($products); $j++) {
                $obj = $userData[$j];
                if ($i >= count($obj)) {
                    $result[$k++] = '';
                    $result[$k++] = '';
                } else {
                    if ($obj[$i]['deviceresolution_name'] == '') {
                        $result[$k++] = 'unknow';
                    } else {
                        $result[$k++] = $obj[$i]['deviceresolution_name'];
                    }
                    $result[$k++] = $obj[$i]['percentage'] . "%";
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
        $this->load->library('export');
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $productId = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $productId = $productId->id;
        $productName = $this->common->getCurrentProduct()->name;
        $data = $this->orientationmodel->getTotalUsersPercentByResolution($fromTime, $toTime, $productId);
        if ($data != null && $data->num_rows() > 0) {
            $export = new Export();
            ////set file name
            $titlename = getExportReportTitle($productName, lang('v_rpt_re_details'), $fromTime, $toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $export->setFileName($title);
            ////set title name
            $excel_title = array(
                iconv("UTF-8", "GBK", lang("v_rpt_re_screen")),
                iconv("UTF-8", "GBK", lang("t_sessions")),
                iconv("UTF-8", "GBK", lang("t_sessionsP")),
                iconv("UTF-8", "GBK", lang("t_newUsers")),
                iconv("UTF-8", "GBK", lang("t_newUsersP"))
            );
            $export->setTitle($excel_title);
            ////set content
            $Total = $this->orientationmodel->getSessionNewuserByResolution($fromTime, $toTime, $productId);
            if ($Total) {
                $sessions = $Total->first_row()->sessions;
                $newusers = $Total->first_row()->newusers;
            } else {
                $sessions = 0;
                $newusers = 0;
            }
            foreach ($data->result() as $row) {
                if (!$row->deviceresolution_name) {
                    $row->deviceresolution_name = 'unknown';
                }
                    
                $rowadd['deviceresolution_name'] = $row->deviceresolution_name;
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
