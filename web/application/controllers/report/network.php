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

class Network extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();		
		$this->load->Model('common');
		$this->load->model('product/networkmodel','network');
		$this->load->model('product/productmodel','product');
		$this->common->requireLogin();
		$this->common->checkCompareProduct();
	}
	
	function index()
	{
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		if(isset($_GET['type'])&&$_GET['type']=='compare'){
			$this->common->loadCompareHeader ();
			$this->data['reportTitle'] = array(
					'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_nw_top10") , $fromTime, $toTime),
					'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_nw_top10") , $fromTime, $toTime),
					'timePhase'=>getTimePhaseStr($fromTime, $toTime)
			);
			$this->load->view ( 'compare/networkview', $this->data );
		}else{
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$this->data['details'] = $this->network->getALlNetWorkData($productId,$fromTime,$toTime);	
		$this->load->view('terminalandnet/networkview', $this->data);
		}
	}
	/*load network report*/
	function addnetworkreport($delete=null,$type=null)
	{
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_nw_top10") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_nw_top10") , $fromTime, $toTime),
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
		$this->load->view('widgets/network',$this->data);
	}
	
	/*
	 * Get network data
	 */
	function getNetWorkData()
	{
		$productId = $this->common->getCurrentProduct();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		
		if(empty($productId)){
			$products = $this->common->getCompareProducts();
			if(empty($products)){
				$this->common->requireProduct();
				return;
			}
			for($i=0;$i<count($products);$i++){
				$activedata=$this->network->getActiveUserNetWorkType( $fromTime, $toTime, $products[$i]->id );
				$newdata=$this->network->getNewUserNetWorkType( $fromTime, $toTime, $products[$i]->id );
				$ret["activeUserData".$products[$i]->name] = $this->change2StandardPrecent($activedata);
				$ret["newUserData".$products[$i]->name] = $this->change2StandardPrecent($newdata);
			}
		}else{
		$this->common->requireProduct();
		$activeUserData = $this->network->getActiveUserNetWorkType($fromTime,$toTime,$productId->id);
		$newUserData = $this->network->getNewUserNetWorkType($fromTime,$toTime,$productId->id);		
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
			$userDataObj ["networkname"] = $row->networkname;
			$percent = round ( $row->percentage * 100, 1 );
			$totalPercent += $percent;
			$userDataObj ["percentage"] = $percent;
			array_push ( $userDataArray, $userDataObj );
		}
	
		if ($totalPercent < 100.0) {
			$remainPercent = round ( 100 - $totalPercent, 2 );
			$userDataObj ["networkname"] = lang('g_others');
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
		$titlename=getExportReportTitle("Compare",lang("v_rpt_nw_top10"),$fromTime, $toTime);
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
			$activedata=$this->network->getActiveUserNetWorkType( $fromTime, $toTime, $products[$m]->id );
			$newdata=$this->network->getNewUserNetWorkType( $fromTime, $toTime, $products[$m]->id );
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
					if($obj[$i]['networkname']==''){
						$result[$k++]='unknow';
					}else{
						$result[$k++]=$obj[$i]['networkname'];
					}
					$result[$k++]=$obj[$i]['percentage']."%";
				}
			}
			$export->addRow ( $result );
			$k=0;
		}
	}
	
	/*
	 * Export to excel
	*/
	function export() {
		$this->load->library ( 'export' );
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$productName = $this->common->getCurrentProduct ()->name;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$data = $this->network->getALlNetWorkData( $productId, $fromTime, $toTime );
		$export = new Export ();
		// set file name
		$titlename=getExportReportTitle($productName,lang('v_rpt_nw_details'),$fromTime, $toTime);
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