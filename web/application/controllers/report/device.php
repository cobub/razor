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

class Device extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->model ( 'product/devicemodel', 'device' );
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
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_de_top10") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_de_top10") , $fromTime, $toTime),
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		    );
			$this->load->view ( 'compare/devicetype', $this->data );
		}else{
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data ['deviceDetails'] = $this->device->getDeviceTypeDetail ($productId,$fromTime,$toTime);		
		$this->load->view ( 'terminalandnet/deviceview', $this->data );
		}
	}
	/*load device report*/
	function adddevicetypereport($delete=null,$type=null)
	{
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_de_top10") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_de_top10") , $fromTime, $toTime),
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
		$this->load->view('widgets/devicetype',$this->data);
	}
	
	/*
	 * Get Device Data by time phase
	 */
	function getDeviceReportData($timePhase = "all") {
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
				$activedata=$this->device->getActiveUsersPercentByDevice ( $fromTime, $toTime, $products[$i]->id );
				$newdata=$this->device->getNewUserPercentByDevice ( $fromTime, $toTime, $products[$i]->id );
				$ret["activeUserData".$products[$i]->name] = $this->change2StandardPrecent($activedata);
				$ret["newUserData".$products[$i]->name] = $this->change2StandardPrecent($newdata);
			}
		}else{
		$this->common->requireProduct();
		$activeUserData = $this->device->getActiveUsersPercentByDevice ( $fromTime, $toTime, $productId->id );
		$newUserData = $this->device->getNewUserPercentByDevice ( $fromTime, $toTime, $productId->id );
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
			$userDataObj ["devicebrand_name"] = $row->devicebrand_name;
			$percent = round ( $row->percentage * 100, 1 );
			$totalPercent += $percent;
			$userDataObj ["percentage"] = $percent;
			array_push ( $userDataArray, $userDataObj );
		}
	
		if ($totalPercent < 100.0) {
			$remainPercent = round ( 100 - $totalPercent, 2 );
			$userDataObj ["devicebrand_name"] = lang('g_others');
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
		$titlename=getExportReportTitle("Compare",lang("v_rpt_de_top10"),$fromTime, $toTime);
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
			$activedata=$this->device->getActiveUsersPercentByDevice ( $fromTime, $toTime, $products[$m]->id );
			$newdata=$this->device->getNewUserPercentByDevice ( $fromTime, $toTime, $products[$m]->id );
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
					if($obj[$i]['devicebrand_name']==''){
						$result[$k++]='unknow';
					}else{
						$result[$k++]=$obj[$i]['devicebrand_name'];
					}
					$result[$k++]=$obj[$i]['percentage']."%";
				}
			}
			$export->addRow ( $result );
			$k=0;
		}
	}
	/*
	 * Export data to CSV by time phase
	 */
	function export() {
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$productName = $this->common->getCurrentProduct ()->name;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		
		$data = $this->device->getDeviceTypeDetail ( $productId, $fromTime, $toTime );
		
		$this->load->library ( 'export' );
		$export = new Export ();
		// set file name
		$titlename=getExportReportTitle($productName, lang("v_rpt_de_details"),$fromTime, $toTime);
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