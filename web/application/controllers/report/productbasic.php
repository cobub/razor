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
		$this->common->loadHeader ();
		$toTime = date ( 'Y-m-d', time () );
		$yestodayTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
		$this->data['today1']  = $this->productanalyze->getTodayInfo($productId,$toTime);
		$this->data['yestoday'] = $this->productanalyze->getTodayInfo($productId,$yestodayTime);
		$this->data['overall'] = $this->productanalyze->getOverallInfo($productId);
		$this->data['pagenum']=count($this->newusermodel->getexportdetaildatas($currentProduct)) ;
		$this->load->view ( 'product/productview', $this->data );
	}
	                           
	function getTypeAnalyzeData($timePhase,$fromDate='',$toDate='') {
		$currentProduct = $this->common->getCurrentProduct ();
		$toTime = date ( 'Y-m-d', time () );
		if ($timePhase == "today") {
			$fromTime = date ( 'Y-m-d', time () );
		}
		if ($timePhase == "yestoday") {
			$fromTime = date ( "Y-m-d", strtotime ( "-1 day" ) );
			$toTime = date ( 'Y-m-d', strtotime ( "-1 day" ) );
		}
		
		if ($timePhase == "last7days") {
			$fromTime = date ( "Y-m-d", strtotime ( "-7 day" ) );
		}
		if ($timePhase == "last30days") {
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
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
	
	//获得用户行为基本概况的所有数据                         
	function getUsersDataByTime($timePhase,$fromDate='',$toDate=''){
		$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
		
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
		}
		if($timePhase == "1month")
		{
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
		
		if($timePhase == "3month")
		{
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
		
		if($timePhase == "all")
		{
			$fromTime = $currentProduct->date;
		}
		
		if($timePhase == "any")
		{
			$fromTime = $fromDate;
			$toTime = $toDate;
		}		
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);   
		$query = $this->newusermodel->getallUserData($fromTime,$toTime,$currentProduct->id);
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
		
	}
	
	//获得明细数据
	function getDetailData($pageIndex=0)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$rowArray = $this->newusermodel->getDetailUserDataByDay($currentProduct,$pageIndex);
		$htmlText = "";
		if($rowArray!=null && count($rowArray)>0)
		{
			for($i=0;$i<count($rowArray);$i++)
			{
				$row = $rowArray[$i];
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td>".$row['date']."</td>";
				$htmlText = $htmlText."<td>".$row['new']."</td>";
				$htmlText = $htmlText."<td>".$row['total']."</td>";
				$htmlText = $htmlText."<td>".$row['active']."</td>";
				$htmlText = $htmlText."<td>".$row['start']."</td>";
				$htmlText = $htmlText."<td>".$row['aver']."</td>";
				$htmlText = $htmlText."</tr>";
			}
		}
		echo $htmlText;
	}

   //导出明细数据
   function exportdetaildata()
   {
   	    $toTime = date('Y-m-d',time());
        $currentProduct = $this->common->getCurrentProduct();
		$detaildata = $this->newusermodel->getexportdetaildatas($currentProduct);
		if ($detaildata != null && count($detaildata)>0) {
			$data = $detaildata;
			$this->export->setFileName ($toTime.'_userdetaildata.csv');		
           //设置列标题		
			$excel_title = array (iconv("UTF-8", "GBK", lang('allview_exportdate')),iconv("UTF-8", "GBK", lang('allview_exportnewuser')),iconv("UTF-8", "GBK", lang('allview_exportaccumulative')),iconv("UTF-8", "GBK", lang('allview_exportactive')),iconv("UTF-8", "GBK", lang('allview_exportsession')),iconv("UTF-8", "GBK",lang('allview_exportavgtime')));
			$this->export->setTitle ($excel_title );
		
			//输出内容
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
			$this->load->view("region/nodataview");
		}
   }
}
?>