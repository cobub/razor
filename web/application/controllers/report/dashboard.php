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
 * Dashboard Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Dashboard extends CI_Controller
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
        $this->load->model('common');
        $this->load->model('dashboard/dashboardmodel', 'dashboard');
        $this->common->requireLogin();
    }

    /**
     * Addshowreport
     *
     * @return void
     */
    function addshowreport()
    {
        $product = $this->common->getCurrentProduct();
        $productid = $product->id;
        $reportname = $_POST['reportname'];
        $controller = $_POST['controller'];
        $height = $_POST['height'];
        $type = $_POST['type'];
        $userid = $this->common->getUserId();
        $src = "/report/" . $controller . "/add" . $reportname . "report";
        $ret = $this->dashboard->addreport($productid, $userid, $reportname, $controller, $src, $height, $type);
        $html = "";
        if ($ret == 1) {
            $html = $html . "<iframe src='" . site_url() . $src . "/del' id='" .$reportname .
                     "' frameborder='0' scrolling='no'style='width:100%;height:" .
                     $height . "px;margin: 10px 3% 0 0.3%;'></iframe>";
        }
        if ($ret >= 8) {
            $html = $ret;
        }
        echo $html;
    }

    /**
     * Deleteshowreport
     *
     * @return void
     */
    function deleteshowreport()
    {
        $product = $this->common->getCurrentProduct();
        $productid = $product->id;
        $reportname = $_POST['reportname'];
        $type = $_POST['type'];
        $userid = $this->common->getUserId();
        $ret = $this->dashboard->deletereport($productid, $userid, $reportname, $type);
        if ($ret) {
            echo true;
        } else {
            echo false;
        }
    }

    /**
     * Loadwidgetslist
     *
     * @return void
     */
    function loadwidgetslist()
    {
        $product = $this->common->getCurrentProduct();
        $productid = $product->id;
        $userid = $this->common->getUserId();
        $addreport = $this->dashboard->getaddreport($productid, $userid);
        $num = $this->dashboard->getreportnum($productid, $userid);
        $this->data['num'] = $num;
        if ($addreport) {
            $this->data['addreport'] = $addreport;
        }
        $this->load->view('widgets/widgetslistview', $this->data);
    }

    /**
     * Savereportlocation
     *
     * @return void
     */
    function savereportlocation()
    {
        $product = $this->common->getCurrentProduct();
        $productid = $product->id;
        $userid = $this->common->getUserId();
        $reportname = $_POST['reportname'];
        $type = $_POST['type'];
        $location = $_POST['location'];
        $ret = $this->dashboard->updatereport($productid, $userid, $reportname, $type, $location);
        if ($ret) {
            echo true;
        } else {
            echo false;
        }
    }
}
