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
 * Version Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class version extends CI_Controller
{
    private $data = array();
    private $allversions;

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->Model('common');
        $this->common->requireLogin();
        $this->common->requireProduct();
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/versionmodel', 'versionmodel');
    }

    /**
     * Index function , load view versioncontrast
     *
     * @return void
     */
    function index()
    {
        $this->common->loadHeaderWithDateControl();
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $date = date('Y-m-d', time());
        $ret = $this->versionmodel->getBasicVersionInfo($productId, $date);
        $this->data['versionList'] = $ret;
        $this->load->view('overview/versioncontrast', $this->data);
    }

    /**
     * Addversionviewreport function , add version view report
     *
     * @param string $delete delete
     * @param string $type   type
     *            
     * @return void
     */
    function addversionviewreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['reportTitle'] = array('timePase' => getTimePhaseStr($fromTime, $toTime), 'activeUser' => lang("v_rpt_ve_trendActiveUsers"), 'newUser' => lang("v_rpt_ve_trendsAnalytics"));
        $currentProduct = $this->common->getCurrentProduct();
        $this->load->model('point_mark', 'pointmark');
        $markevnets = $this->pointmark->listPointviewtochart($this->common->getUserId(), $currentProduct->id, $fromTime, $toTime)->result_array();
        $marklist = $this->pointmark->listPointviewtochart($this->common->getUserId(), $currentProduct->id, $fromTime, $toTime, 'listcount');
        $this->data['marklist'] = $marklist;
        $this->data['markevents'] = $markevnets;
        $this->data['defdate'] = array();
        $j = 0;
        for ($i = strtotime($fromTime); $i <= strtotime($toTime); $i += 86400) {
            $this->data['defdate'][$j] = date('Y-m-d', $i);
            $j ++;
        }
        // end load markevent
        if ($delete == null) {
            $this->data['add'] = "add";
        }
        if ($delete == "del") {
            $this->data['delete'] = "delete";
        }
        if ($type != null) {
            $this->data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/versionview', $this->data);
    }

    /**
     * GetVersionData function , get version data info
     *
     * @return encode json
     */
    function getVersionData()
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $retdata = $this->versionmodel->getVersionData($fromTime, $toTime, $productId);
        // load markevent
        $this->load->model('point_mark', 'pointmark');
        $markevnets = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime)->result_array();
        $marklist = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime, 'listcount');
        $retdata['marklist'] = $marklist;
        $retdata['markevents'] = $markevnets;
        $retdata['defdate'] = $this->common->getDateList($fromTime, $toTime);
        // end load markevent
        echo json_encode($retdata);
    }

    /**
     * GetVersionContrast function , get version contrast info
     *
     * @param string $from1   form1
     * @param string $to1     to1
     * @param string $from2   form2
     * @param string $to2     to2
     * @param string $version version
     * 
     * @return encode json
     */
    function getVersionContrast($from1, $to1, $from2, $to2, $version)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId = $currentProduct->id;
        // get sum num
        $total1 = $this->versionmodel->getNewAndActiveAllCount($productId, $from1, $to1);
        $total2 = $this->versionmodel->getNewAndActiveAllCount($productId, $from2, $to2);
        $query1 = $this->versionmodel->getVersionContrast($productId, $from1, $to1, $version);
        $query2 = $this->versionmodel->getVersionContrast($productId, $from2, $to2, $version);
        $result1 = array();
        $result2 = array();
        $sum1 = $total1[0]['newusers'];
        $sum12 = $total1[0]['startusers'];
        $sum2 = $total2[0]['startusers'];
        $sum21 = $total2[0]['newusers'];
        foreach ($query1->result_array() as $row) {
            $row['newuserpercent'] = percent($row['newusers'], $sum1);
            $row['startuserpercent'] = percent($row['startusers'], $sum12);
            array_push($result1, $row);
        }
        foreach ($query2->result_array() as $row) {
            $row['newuserpercent'] = percent($row['newusers'], $sum21);
            $row['startuserpercent'] = percent($row['startusers'], $sum2);
            array_push($result2, $row);
        }
        
        $result = array($result1,$result2
        );
        echo json_encode($result);
    }
}
?>