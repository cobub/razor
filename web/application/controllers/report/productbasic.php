<?php

class productbasic extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->model ( 'product/newusermodel', 'newusermodel' );
		$this->load->model ( 'product/productanalyzemodel','productanalyze');
		$this->common->requireLogin ();	
		$this->load->model('product/usinganalyzemodel','usinganalyzemodel');		
		$this->load->library ('export');
	}
	
	function view($productId = 0) {
		
		$currentProduct = $this->common->getCurrentProduct();
		if($currentProduct!=null)
		{
			$productId = $currentProduct->id;
			$this->data ['productId'] = $currentProduct->id;
		}
		else
		{
			$this->data ['productId'] = $productId;
			$this->common->setCurrentProduct ( $productId ); 
		}
		$currentProduct = $this->common->getCurrentProduct();
		$this->common->loadHeaderWithDateControl ();
		$toTime = date ( 'Y-m-d', time () );
		$yestodayTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
		$this->data['today1']  = $this->productanalyze->getTodayInfo($productId,$toTime);
		$this->data['yestoday'] = $this->productanalyze->getTodayInfo($productId,$yestodayTime);
		$this->data['overall'] = $this->productanalyze->getOverallInfo($productId); 		
 		$fromTime = $this->common->getFromTime ();
 		$toreTime = $this->common->getToTime (); 		
 		$this->data['reportTitle'] = array(
 				'timePase' => getTimePhaseStr($fromTime, $toreTime),
 				'newUser'=>lang("t_newUserSta"),
 				'totalUser'=>lang("t_accumulatedUserSta"),
 				'activeUser'=>lang("t_activeUserSta"),
 				'sessionNum'=>lang("t_sessionsSta"),
 				'avgUsage'=>lang("t_averageUsageDuration")
 		); 	 	
 		$this->data ['dashboardDetailData'] = $this->newusermodel->getDetailUserDataByDay($fromTime,$toTime); 		
		$this->load->view ( 'overview/productview', $this->data );
	}
	                           
	function getTypeAnalyzeData($timePhase,$fromDate='',$toDate='') {
		$currentProduct = $this->common->getCurrentProduct ();
		$toTime = date ( 'Y-m-d', time ());
		if ($timePhase == "today") {
			$fromTime = date ( 'Y-m-d', time () );
			$toTime = date ( 'Y-m-d', time () );
		}
		if ($timePhase == "yestoday") {
			$fromTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
			$toTime = date ( 'Y-m-d', strtotime ( "-1 day" ) );
		}
		
		if ($timePhase == "last7days") {
			$fromTime = date ( "Y-m-d", strtotime ( "-6 day" ) );
		}
		if ($timePhase == "last30days") {
			$fromTime = date ( "Y-m-d", strtotime ( "-31 day" ) );
		}  
		if($timePhase == "any")
		{
			$fromTime = $fromDate;
			$toTime = $toDate;
		}           
		$query = $this->product->getStarterUserCountByTime ( $fromTime, $toTime,$currentProduct->id);
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	
	//All of the data to obtain a basic overview of user behavior	                      
	function getUsersDataByTime(){
		$currentProduct = $this->common->getCurrentProduct();	
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();		
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);   
		$query = $this->newusermodel->getallUserData($fromTime,$toTime);
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
		
	}

 /*
	 * Export data to CSV by time phase
	 */
   function exportdetaildata()
   {
	   	$fromTime = $this->common->getFromTime ();
	   	$toTime = $this->common->getToTime ();
        $currentProduct = $this->common->getCurrentProduct();   
        $productName =trim($currentProduct->name);
		$detaildata = $this->newusermodel->getDetailUserDataByDay($fromTime,$toTime);		
		if ($detaildata != null && count($detaildata)>0) {
			$data = $detaildata;
			$titlename=getExportReportTitle($productName, lang("v_rpt_pb_userDataDetail"),$fromTime, $toTime);
			$title=iconv("UTF-8", "GBK", $titlename);
			$this->export->setFileName ($title);		
           //Set the column headings	
			$excel_title = array (  iconv("UTF-8", "GBK", lang('g_date')),
									iconv("UTF-8", "GBK", lang('t_newUsers')),
									iconv("UTF-8", "GBK", lang('t_accumulatedUsers')),
									iconv("UTF-8", "GBK", lang('t_activeUsers')),
									iconv("UTF-8", "GBK", lang('t_sessions')),
									iconv("UTF-8", "GBK",lang('t_averageUsageDuration'))
					            );
			$this->export->setTitle ($excel_title );		
			//output content
		    for($i=0;$i<count($data);$i++)
			{
				$row = $data[$i];				
				$this->export->addRow ( $row );
			}			
			$this->export->export ();
			die ();
		
		}
		else 
		{
			$this->load->view("usage/nodataview");
		}
   }
}
?>