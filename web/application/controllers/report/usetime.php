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
 * Usetime Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Usetime extends CI_Controller
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
        $this->load->Model('common');
        $this->load->model('product/usinganalyzemodel', 'analyze');
        $this->common->requireLogin();
        $this->common->checkCompareProduct();
        $this->load->library('export');
    }

    /**
     * Index function , load view usetimeview
     *
     * @return void
     */
    function index()
    {
        if (isset($_GET['type']) && $_GET['type'] == 'compare') {
            $this->common->loadCompareHeader();
            $fromTime = $this->common->getFromTime();
            $toTime = $this->common->getToTime();
            $products = $this->common->getCompareProducts();
            if (empty($products)) {
                $this->common->requireProduct();
                return;
            }
            $comparecontent = "";
            for ($i = 0; $i < count($products); $i ++) {
                $userdata = $this->analyze->getUsingTimeByProduct($products[$i]->id, $fromTime, $toTime)->result_array();
                $comparecontent = $this->changeData($products[$i]->name, $comparecontent, $userdata);
            }
            $this->data['comparecontent'] = $comparecontent;
            $this->data['comparetitlecontent'] = $userdata;
        } else {
            $this->common->requireProduct();
            $this->common->loadHeaderWithDateControl();
        }
        $this->load->view('usage/usetimeview', $this->data);
    }

    /**
     * Addusadgedurationreport function , load usage duration report
     *
     *@param string $delete delete
     *@param string $type   type
     *
     * @return void
     */
    function addusadgedurationreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['reportTitle'] = array('reportName' => lang("m_rpt_usageDuration"),'timePase' => getTimePhaseStr($fromTime, $toTime));
        $productId = $this->common->getCurrentProduct();
        if (! empty($productId) && $delete == null) {
            $this->data['add'] = "add";
        }
        if ($delete == "del") {
            $this->data['delete'] = "delete";
        }
        if ($type != null) {
            $this->data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/usageduration', $this->data);
    }

    /**
     * ChangeData function , load usage duration report
     *
     *@param string $key           key
     *@param string $detailcontent detail content
     *@param string $userdata      user data
     *
     * @return detail content
     */
    function changeData($key, $detailcontent, $userdata)
    {
        $detailcontent = $detailcontent . "<tr><td>" . $key . "</td>";
        foreach ($userdata as $row) {
            $detailcontent = $detailcontent . "<td>" . $row['numbers'];
            $detailcontent = $detailcontent . "(" . round(100 * $row['percentage'], 1) . '%)</td>';
        }
        $detailcontent = $detailcontent . "</tr>";
        return $detailcontent;
    }

    /**
     * GetUsingTimeData function , Get use time data from
     *
     * @return detail content
     */
    function getUsingTimeData()
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $productId = $this->common->getCurrentProduct();
        if (empty($productId)) {
            $products = $this->common->getCompareProducts();
            if (empty($products)) {
                $this->common->requireProduct();
                return;
            }
            for ($i = 0; $i < count($products); $i ++) {
                $ret[$products[$i]->name] = $this->analyze->getUsingTimeByProduct($products[$i]->id, $fromTime, $toTime)->result_array();
            }
        } else {
            $this->common->requireProduct();
            $productId = $productId->id;
            $ret["usingTimeData"] = $this->analyze->getUsingTimeByProduct($productId, $fromTime, $toTime)->result_array();
        }
        echo json_encode($ret);
    }

    /**
     * ExportCompareUsetime function , export compare usetime
     *
     * @return void
     */
    function exportCompareUsetime()
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $products = $this->common->getCompareProducts();
        $result = array();
        for ($i = 0; $i < count($products); $i ++) {
            $result[$i]['content'] = $this->analyze->getUsingTimeByProduct($products[$i]->id, $fromTime, $toTime)->result_array();
            $result[$i]['name'] = $products[$i]->name;
        }
        // print_r($result);
        $times = array();
        for ($i = 0; $i < count($result); $i ++) {
            $content = $result[$i]['content'];
            for ($j = 0; $j < count($content); $j ++) {
                if ($i == 0) {
                    array_push($times, $content[$j]['segment_name']);
                }
            }
        }
        $titlename = getExportReportTitle("Compare", lang("v_rpt_ud_distribution"), $fromTime, $toTime);
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $this->export->setFileName($titlename);
        $excel_title = array();
        for ($i = 0; $i < count($times); $i ++) {
            if ($i == 0) {
                $excel_title[0] = iconv('UTF-8', 'GBK', lang('v_app'));
                $excel_title[1] = iconv('UTF-8', 'GBK', $times[0]);
            } else
                array_push($excel_title, iconv('UTF-8', 'GBK', $times[$i]));
        }
        // print_r($excel_title);
        $this->export->setTitle($excel_title);
        for ($k = 0; $k < count($result); $k ++) {
            $row = array();
            $row[0] = $result[$k]['name'];
            $content = $result[$k]['content'];
            for ($j = 0; $j < count($content); $j ++) {
                $row[$j + 1] = $content[$j]['numbers'] . '(' . round($content[$j]['percentage'] * 100, 1) . '%)';
            }
            $this->export->addRow($row);
        }
        $this->export->export();
        die();
    }
}
