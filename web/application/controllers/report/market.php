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
 * Market Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Market extends CI_Controller
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
        $this->load->model('channelmodel', 'channel');
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/newusermodel', 'newusermodel');
        $this->common->requireLogin();
        $this->common->requireProduct();
    }

    /**
     * ViewMarket function
     * View market
     * 
     * @return void
     */
    function viewMarket()
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $data['productId'] = $productId;
        $today = date('Y-m-d', time());
        $count7 = date("w") + 7;
        $yestodayTime = date("Y-m-d", strtotime("-1 day"));
        $seven_day = date("Y-m-d", strtotime("-" . $count7 . " day"));
        $thirty_day = date("Y-m-d", strtotime("-1 month"));
        $thirty_day = substr($thirty_day, 0, 8) . '01';
        $sevendayactive = $this->product->getActiveDays($seven_day, 0, $productId);
        $data['sevendayactive'] = $sevendayactive;
        $thirty_day_active = $this->product->getActiveDays($thirty_day, 1, $productId);
        $data['thirty_day_active'] = $thirty_day_active;
        $todayData = $this->product->getAnalyzeDataByDateAndProductID($today, $productId);
        $yestodayData = $this->product->getAnalyzeDataByDateAndProductID($yestodayTime, $productId);
        $data['count'] = $todayData->num_rows();
        $data['todayData'] = $todayData;
        $data['yestodayData'] = $yestodayData;
		$data['channel'] = $this->product->getChannelData($productId);

        $this->common->loadHeaderWithDateControl();
        $this->load->view('overview/productmarket', $data);
    }

	function getchanneldata() {
		$channel =  $_POST['channel'];
		$fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
		$marketdata = $this->product->getMarketDataBychannelid($channel,$fromTime, $toTime);
		echo json_encode($marketdata);
	}
    
    /**
     * Addchannelmarketreport function
     * Load channel market report
     * 
     * @param string $delete $delete = null
     * @param string $type   $type = null
     * 
     * @return void
     */
    function addchannelmarketreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $data['reportTitle'] = array('timePase' => getTimePhaseStr($fromTime, $toTime),'newUser' => getReportTitle(lang("v_rpt_mk_newUserStatistics") . " " . null, $fromTime, $toTime),'activeUser' => getReportTitle(lang("v_rpt_mk_activeuserS") . " " . null, $fromTime, $toTime),'Session' => getReportTitle(lang("v_rpt_mk_sessionS") . " " . null, $fromTime, $toTime),'avgUsageDuration' => getReportTitle(lang("t_averageUsageDuration") . " " . null, $fromTime, $toTime),'activeWeekly' => getReportTitle(lang("t_activeRateW") . " " . null, $fromTime, $toTime),'activeMonthly' => getReportTitle(lang("t_activeRateM") . " " . null, $fromTime, $toTime));
        if ($delete == null) {
            $data['add'] = "add";
        }
        if ($delete == "del") {
            $data['delete'] = "delete";
        }
        if ($type != null) {
            $data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/channelmarket', $data);
    }

    /**
     * GetMarketData function
     * Get market data
     * 
     * @param string $type $type = ''
     * 
     * @return json
     */
    function getMarketData($type = '')
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $markets = $this->product->getProductChanelById($productId);
        
        $ret = array();
        if ($markets != null && $markets->num_rows() > 0) {
           // foreach ($markets->result() as $row) {
                if ($type == "monthrate") {
                    $data = $this->product->getActiveNumbers($productId, $fromTime, $toTime, 1);
                } else if ($type == "weekrate") {
                        $data = $this->product->getActiveNumbers($productId, $fromTime, $toTime, 0);
                } else {
                        $data = $this->product->getAllMarketData(0, $fromTime, $toTime);
                }
           // }
            if ($type == "monthrate" || $type == "weekrate") {
                if ($data == null || count($data) == 0) {
                    $content_arr['VersionIsNullActiveRate'] = array();
                    $tmp = array();
                    $tmp['percent'] = 0;
                    $tmp['datevalue'] = "0000-00-00 00:00:00";
                    array_push($content_arr['VersionIsNullActiveRate'], $tmp);
                    $ret['content'] = $content_arr;
                }
            }
        } else {
            $data = "";
        }
        $result = array();
        $result['dataList'] = $data;
        // load markevents
        $mark = array();
        $currentProduct = $this->common->getCurrentProduct();
        $this->load->model('point_mark', 'pointmark');
        $markevnets = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime)->result_array();
        $marklist = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime, 'listcount');
        
        $result['markevents'] = $markevnets;
        if ($type == "weekrate") {
            
            $result['marklist'] = $this->product->getRateVersion($productId, $fromTime, $toTime, 0);
            $result['defdate'] = $this->product->getRatedate($productId, $fromTime, $toTime, 0);
        } else if ($type == "monthrate") {
                $result['marklist'] = $this->product->getRateVersion($productId, $fromTime, $toTime, 1);
                $result['defdate'] = $this->product->getRatedate($productId, $fromTime, $toTime, 1);
        } else {
                $result['marklist'] = $marklist;
                $result['defdate'] = $this->common->getDateList($fromTime, $toTime);
        }
        // end load markevents
        echo json_encode($result);
    }
	
		/*
    * Export page data to excel
    */
	function exportPage($channel) {
		
		$channel = urldecode($channel);
		$this -> load -> library('export');
		
		$productId = $this -> common -> getCurrentProduct();
        $productId = $productId -> id;
		$productName = $this -> common -> getCurrentProduct() -> name;
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
		$productName=str_replace(" ", "_", $productName);

		$alldata = $this->product->getMarketDataBychannelid($channel,$fromTime, $toTime);
		$data = $alldata['content'];
        if($data != null && count($data) > 0 )
        {
            $export = new Export();
            ////set file name
            $titlename = getExportReportTitle($productName, lang('v_rpt_mk_channelList'), $fromTime, $toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $export -> setFileName($title);
            ////set title name
            $excel_title = array (
            iconv("UTF-8", "GBK", lang("v_man_au_channelName")),
            iconv("UTF-8", "GBK", lang("g_date")),
            iconv("UTF-8", "GBK", lang("t_newUsers")),
            iconv("UTF-8", "GBK", lang("t_activeUsers")),
			iconv("UTF-8", "GBK", lang("t_sessions")),
			iconv("UTF-8", "GBK", lang("t_averageUsageDuration")),
			iconv("UTF-8", "GBK", lang("t_accumulatedUsers"))
             );
            $export->setTitle ($excel_title );
            ////set content

            foreach ($data as $row){
            	$rowadd['channel_name'] = $row['channel_name'];
				$rowadd['datevalue'] = $row['datevalue'];
				$rowadd['newusers'] = $row['newusers'];
				$rowadd['startusers'] = $row['startusers'];
				$rowadd['sessions'] = $row['sessions'];
				if($row['sessions']){
					$rowadd['usingtime'] = round(floatval($row['usingtime']*1.0/$row['sessions'])/1000,2).lang('g_s');
				}
				else {
					$rowadd['usingtime'] = 0;
				}
				$rowadd['allusers'] = $row['allusers'];
                $export->addRow ( $rowadd );
            }
            $export -> export();
            die();
        }
        else{
            $this->load->view("usage/nodataview");
        }
	}


}

?>