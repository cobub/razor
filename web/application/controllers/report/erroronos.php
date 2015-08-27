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
 * Erroronos Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Erroronos extends CI_Controller
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
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->Model('common');
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/versionmodel', 'versionmodel');
        $this->load->model('product/errormodel', 'errormodel');
        $this->common->requireLogin();
        $this->common->requireProduct();
        $this->load->Model('common');
    }

    /**
     * Index fuction
     *
     * @return void
     */
    function index()
    {
        // add header
        $this->common->loadHeaderWithDateControl();
        // add chart
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->load->view('errors/errorlogonosview');
        // add error list
        $product = $this->common->getCurrentProduct();
        $productid = $product->id;
        $query = $this->errormodel->getErrorlistOnOs($productid, $fromTime, $toTime);
        $fixed_error = array();
        $unfixed_error = array();
        if ($query != null && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $item = array();
                $item['title'] = $row->title;
                $item['title_sk'] = $row->title_sk;
                $item['deviceos_name'] = $row->deviceos_name;
                $item['time'] = $row->time;
                $item['isfix'] = $row->isfix;
                $item['errorcount'] = $row->errorcount;
                $item['deviceos_sk'] = $row->deviceos_sk;
                if ($row->isfix == '1') {
                    array_push($fixed_error, $item);
                } else {
                    array_push($unfixed_error, $item);
                }
            }
        }
        $this->data['fixed_error'] = $fixed_error;
        $this->data['unfixed_error'] = $unfixed_error;
        $this->load->view('errors/erroronoslistview', $this->data);
    }
    
    /**
     * Adderrorosreport function
     * Load errorlogonos report
     *
     * @param string $delete $delete = null
     * @param string $type   $type = null
     *
     * @return void
     */
    function adderrorosreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['reportTitle'] = array('errorCount' => getReportTitle(lang("v_rpt_err_errorNums"), $fromTime, $toTime),'errorCountPerSession' => getReportTitle(lang("v_rpt_err_errorNumsInSessions"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime));
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
        $this->load->view('widgets/errorlogonos', $this->data);
    }
    
    /**
     * Geterroralldata function
     * Error all data report
     *
     * @return void
     */
    function geterroralldata()
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $result = $this->errormodel->getErrorAllDataOnOs($productId, $fromTime, $toTime);
        echo json_encode($result);
    }
    
    /**
     * ChangeErrorStatus function
     * Mark as repair or unrepair
     *
     * @return void
     */
    function changeErrorStatus()
    {
        $titlesk = $_POST['title_sk'];
        
        $fix = $_POST['fix'];
        
        $this->errormodel->markfixerrorinfo($productid, $product_version, $titlesk, $titles, $product_sk, $fix);
        
        $product = $this->common->getCurrentProduct();
        $productkey = $product->id;
        $query = $this->errormodel->geterrorlist($productid);
        if ($query != null && $query->num_rows() > 0) {
            $this->data['isfix'] = 0;
            $this->data['errorlistnofix'] = $query;
            $this->data['nonum'] = $query->num_rows();
        }
        $this->load->view('errors/errorlistview', $this->data);
    }
    
    /**
     * Detailstacktrace function
     * Error detail on os
     *
     * @param int $title_sk    title_sk
     * @param int $deviceos_sk deviceos_sk
     *
     * @return void
     */
    function detailstacktrace($title_sk, $deviceos_sk)
    {
        $this->common->loadHeader();
        $product_id = $this->common->getCurrentProduct();
        $product_id = $product_id->id;
        $from = $this->common->getFromTime();
        $to = $this->common->getToTime();
        $query = $this->errormodel->getErrorDetailOnOs($title_sk, $deviceos_sk, $product_id, $from, $to);
        if ($query != null && $query->num_rows() > 0) {
            $this->data['errordetail'] = $query;
            $this->data['num'] = $query->num_rows();
            $this->data['stacktrace'] = $query->first_row()->stacktrace;
            $this->data['isfix'] = $query->first_row()->isfix;
        }
        
        $this->data['reportTitle'] = array('errorOnDevice' => getReportTitle(lang("v_rpt_err_deviceDistributionComment"), $from, $to),'errorOnOs' => getReportTitle(lang("v_rpt_err_appVersionD"), $from, $to),'timePhase' => getTimePhaseStr($from, $to));
        $this->data['titlesk'] = $title_sk;
        $this->data['deviceos_sk'] = $deviceos_sk;
        $this->load->view('errors/erroronosdetails', $this->data);
    }
    
    /**
     * GetDeviceInfoOnOs function
     * Device distriution pie report on os
     *
     * @param int $titlesk     titlesk
     * @param int $deviceos_sk deviceos_sk
     *
     * @return void
     */
    function getDeviceInfoOnOs($titlesk, $deviceos_sk)
    {
        $from = $this->common->getFromTime();
        $to = $this->common->getToTime();
        $productid = $this->common->getCurrentProduct()->id;
        $data = $this->errormodel->getDeviceInfoOnOs($titlesk, $productid, $deviceos_sk, $from, $to);
        echo json_encode($data);
    }
    
    /**
     * GetAppVersionOnOs function
     * Version distribution pie report on os
     *
     * @param int $titlesk     titlesk
     * @param int $deviceos_sk deviceos_sk
     *
     * @return void
     */
    function getAppVersionOnOs($titlesk, $deviceos_sk)
    {
        $from = $this->common->getFromTime();
        $to = $this->common->getToTime();
        $productid = $this->common->getCurrentProduct()->id;
        $data = $this->errormodel->getAppVersionOnOs($titlesk, $productid, $deviceos_sk, $from, $to);
        echo json_encode($data);
    }
}
?>