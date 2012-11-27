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
		$this->load->model ('dashboard/dashboardmodel','dashboard');
		$this->load->model ('analysis/trendandforecastmodel','gettrend');
		$this->load->library ('export');
		$this->common->checkCompareProduct();
	}
	
	function view($productId = 0) {
		//if compare then load compare page
		if(isset($_GET['type'])&&'compare'==$_GET['type']){
			$products=$this->common->getCompareProducts();
			$this->common->loadCompareHeader(lang('m_rpt_dashboard'));
			$this->load->view('compare/userbehavorview');
			return;
		}
		$this->common->setCompareProducts(null);
		$currentProduct = $this->common->getCurrentProduct();
		if($currentProduct!=null)
		{
			if(!empty($productId)){
				$this->common->cleanCurrentProduct();
				$this->common->setCurrentProduct ( $productId );
				$this->data ['productId'] = $currentProduct->id;
			}else{
				$this->common->requireProduct();
			}
		}
		else
		{
			if(empty($productId)){
				$this->common->requireProduct();
			}else{
				$this->data ['productId'] = $productId;
				$this->common->setCurrentProduct ( $productId );
				$currentProduct = $this->common->getCurrentProduct();
			}
		}
		$productId=$currentProduct->id;
		$this->common->loadHeaderWithDateControl ();
		$toTime = date ( 'Y-m-d', time () );
		$yestodayTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
		$this->data['today1']  = $this->productanalyze->getTodayInfo($productId,$toTime);
		$this->data['yestoday'] = $this->productanalyze->getTodayInfo($productId,$yestodayTime);
		$this->data['overall'] = $this->productanalyze->getOverallInfo($productId); 		
 		$fromTime = $this->common->getFromTime ();
 		$toreTime = $this->common->getToTime (); 		 	
 		$this->data ['dashboardDetailData'] = $this->newusermodel->getDetailUserDataByDay($fromTime,$toTime);  		
 		$this->loadaddreport($productId);
		$this->load->view( 'overview/productview', $this->data );
		
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
		$ret=array();
		//load compare data
		if(empty($currentProduct)){
			$products=$this->common->getCompareProducts();
			if(count($products)==0){echo 'noproducts';return;}
			for($i=0;$i<count($products);$i++){
				$result=$this->product->getStarterUserCountByTime($fromTime,$toTime,$products[$i]->id);
				$ret["content"][$i]['data']=$result->result_array();
				$ret["content"][$i]['name']=$products[$i]->name;
			}
			$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
			$ret["type"]=array('name'=>'compare');
			echo json_encode($ret);
			return;
		}
		//load other data
		$query = $this->product->getStarterUserCountByTime ( $fromTime, $toTime,$currentProduct->id);
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	/*load phaseusetime report*/	
	function addphaseusetimereport($delete=null,$type=null)
	{
		$productId = $this->common->getCurrentProduct();
		if($delete==null)
		{
			$this->data['add']="add";
		}
		if($delete=="del")
		{
			$this->data['delete']="delete";
		}
		if($type!=null)
		{
			$this->data['type']=$type;
		}
		$this->load->view ( 'layout/reportheader');
		$this->load->view('widgets/phaseusetime',$this->data);
	}
	
	function phaseusetime(){
		if(isset($_GET['type'])&&$_GET['type']=='compare'){
			$this->common->loadCompareHeader(lang('m_rpt_timeTrendOfUsers'),FALSE);
			$data=array();
			$data['type']='compare';
			$this->load->view('usage/phaseusetimeview',$data);
			return;
		}else{
		$this->common->loadHeader();
		$this->load->view('usage/phaseusetimeview');
		}
	}
	/*load user behavor  report*/
	function adduserbehavorviewreport()
	{
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
		/***/
		if(isset($_GET['type'])&&'compare'==$_GET['type']){
			$this->data['common']=array('show_thrend'=>0,'show_markevent'=>0);
		}
		/**/
		
		$this->load->view ( 'layout/reportheader');
		$this->load->view('widgets/userbehavorview', $this->data );
	}
	
	function loadaddreport($productid)
	{	
		$userid = $this->common->getUserId();
		$addreport=$this->dashboard->getaddreport($productid,$userid);
		if($addreport)
		{
			$this->data['addreport']=$addreport;		
		}
	}
	
	//All of the data to obtain a basic overview of user behavior	                      
	function getUsersDataByTime(){
		$currentProduct = $this->common->getCurrentProduct();	
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();	
		$ret=array();
		if($currentProduct==null){
			$products=$this->common->getCompareProducts();
			if(count($products)<1){
				echo json_encode('redirecthome');
				return;
			}
			for($i=0;$i<count($products);$i++){
				$query = $this->newusermodel->getallUserDataByPid($fromTime,$toTime,$products[$i]->id);
				$ret[$i]['name']=$products[$i]->name;
				$ret[$i]['content']=$query->result_array();
			}
			echo json_encode($ret);
			return;
		} 
		$query = $this->newusermodel->getallUserData($fromTime,$toTime);
		$ret["content"] = $query->result_array();
		
		$trendresult = $this->newusermodel->getallUserData($this->common->getPredictiveValurFromTime(),$toTime);
		$result = $this->gettrend->getPredictiveValur($trendresult->result_array());
		$ret["trendcontent"]=$result;
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		//load markevent
		$this->load->model('point_mark','pointmark');
		$markevnets=$this->pointmark->listPointviewtochart($this->common->getUserId(),$currentProduct->id,$fromTime,$toTime)->result_array();
		$marklist=$this->pointmark->listPointviewtochart($this->common->getUserId(),$currentProduct->id,$fromTime,$toTime,'listcount');
		$ret['marklist']=$marklist;
		$ret['markevents']=$markevnets;
		$ret['defdate']=$this->common->getDateList($fromTime,$toTime);
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
   function exportComparedata(){
	   	$fromTime = $this->common->getFromTime ();
	   	$toTime = $this->common->getToTime ();
	   	$products = $this->common->getCompareProducts();
	   	if(empty($products)){
	   		$this->common->requireProduct();
	   		return;
	   	}
	   	$this->load->library ( 'export' );
	   	$export = new Export ();
	   	$titlename=getExportReportTitle("Compare",lang("v_rpt_pb_userDataDetail"),$fromTime, $toTime);
	   	$titlename=iconv("UTF-8", "GBK", $titlename);
	   	$export->setFileName ($titlename);
	   	$maxlength=0;
	   	$labels=array(lang('t_newUsers'),lang('t_accumulatedUsers'),
	   			lang('t_activeUsers'),lang('t_sessions'),lang('t_averageUsageDuration'));
	   	$label=array('newusers','allusers','startusers','sessions','usingtime');
	   	for($i=0;$i<5;$i++){
	   		if($i==0){
	   			$title[0]=iconv("UTF-8", "GBK", $labels[$i]);
	   			$title[1]=iconv("UTF-8", "GBK", lang('g_date'));
	   			for($j=0;$j<count($products);$j++){
	   				$detailData[$j]=$this->newusermodel->getallUserDataByPid($fromTime,$toTime,$products[$j]->id)->result_array();
	   				if(count($detailData[$j])>$maxlength){
	   					$maxlength=count($detailData[$j]);
	   				}
	   				$title[$j+2]=iconv("UTF-8", "GBK",$products[$j]->name);
	   			}
	   		//	print_r($detailData[0][2]);
	   			$export->setTitle ($title);
	   		}else{
	   			$title[0]=$labels[$i];
	   			$title[1]=lang('g_date');
	   			for($m=0;$m<count($products);$m++){
	   				$title[$m+2]=$products[$m]->name;
	   			}
	   			$export->addRow ( $title );
	   		}
	   		$this->getExportRowData($export,$maxlength,$detailData,$products,$label[$i]);
	   	}
	   	$export->export ();
	   	die ();
   }
   
   function getExportRowData($export,$length,$userData,$products,$label){
   	    $k=0;
   	    for($i=0;$i<$length;$i++){
   	    	$result[$k++]=' ';
   	    	for($j=0;$j<count($products);$j++){
   	    		$obj=$userData[$j];
   	    		if($j==0){
   	    			$result[$k++]=substr($obj[$i]['datevalue'],0,10);
   	    		}
   	    		$currentdata=$obj[$i][$label];
   	    		if($label=="usingtime"){
   	    			if($obj[$i]['sessions']!=0){
   	    				$currentdata=($currentdata/$obj[$i]['sessions'])/1000;
   	    				$currentdata=round($currentdata,2);
   	    			}
   	    			$currentdata=$currentdata.lang('g_s');
   	    		}
   	    		$result[$k++]=$currentdata;
   	    	}
   	    	$export->addRow ( $result );
   	    	$k=0;
   		}
   	}
   
   //export one product db
   function timephaseexport($timePhase,$fromDate='',$toDate='')
   {   	
    	$currentProduct = $this->common->getCurrentProduct ();
    	$time=$this->changeDate($timePhase,$fromDate,$toDate); 
		$fromTime=$time['fromTime'];
		$toTime=$time['toTime'];
    	$query = $this->product->getStarterUserCountByTime ( $fromTime, $toTime,$currentProduct->id);
   	$detailcontent = $query->result_array();    
   	if ($detailcontent != null && count($detailcontent)>0) {
   		
   		$titlename=getExportReportTitle($currentProduct->name, lang("v_rpt_pb_timeTrendOfUsers"),$fromTime, $toTime);
   		$title=iconv("UTF-8", "GBK", $titlename);
   		$this->export->setFileName ($title);
   		//Set the column headings
   		$excel_title = array (  iconv("UTF-8", "GBK", ""),
   				iconv("UTF-8", "GBK", lang('t_activeUsers')),
   				iconv("UTF-8", "GBK", lang('t_newUsers'))  				
   		);
   		$this->export->setTitle ($excel_title );
   		//output content
   		for($i=0;$i<count($detailcontent);$i++)
   		{
   			
   		 $row['hour'] = $detailcontent[$i]['hour'].":00";
   		 $row['startusers'] = $detailcontent[$i]['startusers'];
   		 $row['newusers'] = $detailcontent[$i]['newusers'];
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
   
 function exportComparePhaseusetime($timePhase,$fromDate='',$toDate=''){
 	   $time=$this->changeDate($timePhase,$fromDate,$toDate);
 	   $fromTime=$time['fromTime'];
 	   $toTime=$time['toTime'];
   		$products = $this->common->getCompareProducts();
   		if(empty($products)){
   			$this->common->requireProduct();
   			return;
   		}
   		$this->load->library ( 'export' );
   		$export = new Export ();
   		$titlename=getExportReportTitle("Compare",lang("v_rpt_pb_timeTrendOfUsers_detail"),$fromTime, $toTime);
   		$titlename=iconv("UTF-8", "GBK", $titlename);
   		$export->setFileName ($titlename);
   		$j=0;
   		$mk=0;
   		$maxlength=0;
   		$title[$j++]=iconv("UTF-8", "GBK",'');
   		$space[$mk++]=lang('t_date_part');
   		for($i=0;$i<count($products);$i++){
   			$detailData[$i]=$this->product->getStarterUserCountByTime ( $fromTime, $toTime,$products[$i]->id)->result_array();
   			$maxlength=count($detailData[$i]);
   			$title[$j++]=iconv("UTF-8", "GBK",$products[$i]->name);
   			$title[$j++]=iconv("UTF-8", "GBK",'');
   			$space[$mk++]=lang('t_activeUsers');
   			$space[$mk++]=lang('t_newUsers');
   		}
   		$export->setTitle ($title);
   		$export->addRow($space);
   		$k=0;
   		$j=0;
   		for($m=0;$m<$maxlength;$m++){
   			$detailcontent=array();
   			for($j=0;$j<count($products);$j++){
   				$obj=$detailData[$j];
   				if($j==0){
   					array_push($detailcontent,$obj[$m]['hour'].":00");
   				}
   				array_push($detailcontent,$obj[$m]['startusers']);
   				array_push($detailcontent,$obj[$m]['newusers']);
   			}
   			$export->addRow($detailcontent);
   		}
   		$export->export ();
   		die ();
   	}
   //
   function changeDate($timePhase,$fromDate='',$toDate=''){
	   	$toTime = date ( 'Y-m-d', time ());
	   	if ($timePhase == "today")
	   	{
	   		$fromTime = date ( 'Y-m-d', time () );
	   		$toTime = date ( 'Y-m-d', time () );
	   	}
	   	if ($timePhase == "yestoday")
	   	{
	   		$fromTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
	   		$toTime = date ( 'Y-m-d', strtotime ( "-1 day" ) );
	   	}
	   	
	   	if ($timePhase == "last7days")
	   	{
	   		$fromTime = date ( "Y-m-d", strtotime ( "-6 day" ) );
	   	}
	   	if ($timePhase == "last30days")
	   	{
	   		$fromTime = date ( "Y-m-d", strtotime ( "-31 day" ) );
	   	}
	   	if($timePhase == "any")
	   	{
	   		$fromTime = $fromDate;
	   		$toTime = $toDate;
	   	}
	   	return array('fromTime'=>$fromTime,'toTime'=>$toTime);
   }
}
?>