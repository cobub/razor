<?php

/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource  device.php
 */

class Resolution extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->model ( 'product/resolutionmodel', 'orientationmodel' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->model ( 'product/newusermodel', 'newusermodel' );
		$this->common->requireLogin ();
		$this->common->checkCompareProduct();
	}
	
	function index() {
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		if(isset($_GET['type'])&&$_GET['type']=='compare'){
			$this->common->loadCompareHeader ();
			$this->data['reportTitle'] = array(
					'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10") , $fromTime, $toTime),
					'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10") , $fromTime, $toTime),
					'timePhase'=>getTimePhaseStr($fromTime, $toTime)
			);
			$this->load->view ( 'compare/resolutionview', $this->data );
		}else{
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data ['details'] = $this->orientationmodel->getTotalUsersPercentByResolution( $fromTime, $toTime, $productId );		
		$this->load->view ( 'terminalandnet/resolutionview', $this->data );
		}
	}
	/*load resolution report*/
	function addresolutioninforeport($delete=null,$type=null)
	{
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10") , $fromTime, $toTime),
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
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
		$this->load->view('widgets/resolutioninfo',$this->data);
	}
	
	/*
	 * Get resolution data called by ajax
	 */
	function getResolutionData() {
		$productId = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		
		if(empty($productId)){
			$products = $this->common->getCompareProducts();
			if(empty($products)){
				$this->common->requireProduct();
				return;
			}
			for($i=0;$i<count($products);$i++){
				$activedata=$this->orientationmodel->getActiveUsersPercentByOrientation( $fromTime, $toTime, $products[$i]->id );
				$newdata=$this->orientationmodel->getNewUsersPercentByOrientation( $fromTime, $toTime, $products[$i]->id );
				$ret["activeUserData".$products[$i]->name] = $this->change2StandardPrecent($activedata);
				$ret["newUserData".$products[$i]->name] = $this->change2StandardPrecent($newdata);
			}
		}else{
		$this->common->requireProduct();
		$activeUserData = $this->orientationmodel->getActiveUsersPercentByOrientation ( $fromTime, $toTime, $productId->id);
		$newUserData = $this->orientationmodel->getNewUsersPercentByOrientation ( $fromTime, $toTime, $productId->id );
		$ret ["activeUserData"] = $this->change2StandardPrecent($activeUserData);
		$ret ["newUserData"] = $this->change2StandardPrecent($newUserData);
		}
		
		echo json_encode ( $ret );
	}
	
	function change2StandardPrecent($userData){
		$userDataArray = array ();
		$totalPercent = 0;
		foreach ( $userData->result () as $row ) {
			if (count ( $userData ) > 10) {
				break;
			}
			$userDataObj = array ();
			$userDataObj ["deviceresolution_name"] = $row->deviceresolution_name;
			$percent = round ( $row->percentage * 100, 1 );
			$totalPercent += $percent;
			$userDataObj ["percentage"] = $percent;
			array_push ( $userDataArray, $userDataObj );
		}
	
		if ($totalPercent < 100.0) {
			$remainPercent = round ( 100 - $totalPercent, 2 );
			$userDataObj ["deviceresolution_name"] = lang('g_others');
			$userDataObj ["percentage"] = $remainPercent;
			array_push ( $userDataArray, $userDataObj );
		}
		return $userDataArray;
		//	print_r($userDataArray);
	}

	function exportCSV(){
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$products = $this->common->getCompareProducts();
		if(empty($products)){
			$this->common->requireProduct();
			return;
		}
		$this->load->library ( 'export' );
		$export = new Export ();
		$titlename=getExportReportTitle("Compare",lang("v_rpt_re_top10"),$fromTime, $toTime);
		$titlename=iconv("UTF-8", "GBK", $titlename);
		$export->setFileName ($titlename);
		$j=0;
		$mk=0;
		$title[$j++]=iconv("UTF-8", "GBK", lang('t_activeUsers'));
		$space[$mk++]=' ';
		for($i=0;$i<count($products);$i++){
 			$title[$j++]=iconv("UTF-8", "GBK",$products[$i]->name);
 			$title[$j++]='';
 			$space[$mk++]=' ';
 			$space[$mk++]=' ';
		}
		$export->setTitle ($title);
		$k=0;
		$maxlength=0;
		$maxlength2=0;
		$j=0;
		$nextlabel[$j++]=lang('t_newUsers');
		for($m=0;$m<count($products);$m++){
			$activedata=$this->orientationmodel->getActiveUsersPercentByOrientation( $fromTime, $toTime, $products[$m]->id );
			$newdata=$this->orientationmodel->getNewUsersPercentByOrientation( $fromTime, $toTime, $products[$m]->id );
			$detailData[$m] = $this->change2StandardPrecent($activedata);
			$detailNewData[$m] = $this->change2StandardPrecent($newdata);
			if(count($detailData[$m])>$maxlength){
				$maxlength=count($detailData[$m]);
			}
			if(count($detailNewData[$m])>$maxlength2){
				$maxlength2=count($detailNewData[$m]);
			}
			$nextlabel[$j++]=$products[$m]->name;
			$nextlabel[$j++]=' ';
		}
		$this->getExportRowData($export,$maxlength,$detailData,$products);
		$export->addRow ( $space );
		$export->addRow ( $nextlabel );
		$this->getExportRowData($export,$maxlength2,$detailNewData,$products);
		$export->export ();
		die ();
	}
	
	function getExportRowData($export,$length,$userData,$products){
		$k=0;
		for($i=0;$i<$length;$i++){
			$result[$k++]=$i+1;
			for($j=0;$j<count($products);$j++){
				$obj=$userData[$j];
				if($i>=count($obj)){
					$result[$k++]='';
					$result[$k++]='';
				}else{
					if($obj[$i]['deviceresolution_name']==''){
						$result[$k++]='unknow';
					}else{
						$result[$k++]=$obj[$i]['deviceresolution_name'];
					}
					$result[$k++]=$obj[$i]['percentage']."%";
				}
			}
			$export->addRow ( $result );
			$k=0;
		}
	}
	
	/*
	 * Export resolution data to excel
	 */
	function export() {
		$this->load->library ( 'export' );
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$productName = $this->common->getCurrentProduct ()->name;
		$data = $this->orientationmodel->getTotalUsersPercentByResolution( $fromTime, $toTime, $productId );
		$export = new Export ();
		$titlename=getExportReportTitle($productName, lang('v_rpt_re_details'),$fromTime, $toTime);
		$title=iconv("UTF-8", "GBK", $titlename);
		$export->setFileName ($title);			
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}		
		$export->setTitle ( $fields );
		foreach ( $data->result () as $row )
			$export->addRow ( $row );
		$export->export ();
		die ();
	}

}