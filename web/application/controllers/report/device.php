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
		$this->common->requireProduct();
	}
	
	function index() {
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$productId=$productId->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data ['deviceDetails'] = $this->device->getDeviceTypeDetail ($productId,$fromTime,$toTime);
		$this->data['reportTitle'] = array(
			'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_de_top10") , $fromTime, $toTime),
			'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_de_top10") , $fromTime, $toTime),
		    'timePhase'=>getTimePhaseStr($fromTime, $toTime)
				);
		$this->load->view ( 'terminalandnet/deviceview', $this->data );
	}
	
	/*
	 * Get Device Data by time phase
	 */
	function getDeviceReportData($timePhase = "all") {
		$productId = $this->common->getCurrentProduct ()->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		
		$activeUserData = $this->device->getActiveUsersPercentByDevice ( $fromTime, $toTime, $productId );
		$newUserData = $this->device->getNewUserPercentByDevice ( $fromTime, $toTime, $productId );
		
		$activeUserDataArray = array ();
		$totalPercent = 0;
		foreach ( $activeUserData->result () as $row ) {
			if (count ( $activeUserData ) > 10) {
				break;
			}
			$activeUserDataObj = array ();
			$activeUserDataObj ["devicebrand_name"] = $row->devicebrand_name;
			$percent = round ( $row->percentage * 100, 1 );
			$totalPercent += $percent;
			$activeUserDataObj ["percentage"] = $percent;
			array_push ( $activeUserDataArray, $activeUserDataObj );
		}
		
		if ($totalPercent < 100.0) {
			$remainPercent = round ( 100 - $totalPercent, 2 );
			$activeUserDataObj ["devicebrand_name"] = lang('g_others');
			$activeUserDataObj ["percentage"] = $remainPercent;
			array_push ( $activeUserDataArray, $activeUserDataObj );
		}
		
		$newUserDataArray = array ();
		$totalPercent = 0;
		foreach ( $newUserData->result () as $row ) {
			if (count ( $newUserDataArray ) > 10) {
				break;
			}
			$newDataObj = array ();
			$newDataObj ["devicebrand_name"] = $row->devicebrand_name;
			$percent = round ( $row->percentage * 100, 1 );
			$totalPercent += $percent;
			$newDataObj ["percentage"] = $percent;
			array_push ( $newUserDataArray, $newDataObj );
		}
		
		if ($totalPercent < 100.0) {
			$remainPercent = round ( 100 - $totalPercent, 2 );
			$newDataObj ["devicebrand_name"] = lang('g_others');
			$newDataObj ["percentage"] = $remainPercent;
			array_push ( $newUserDataArray, $newDataObj );
		}
		
		$ret ["activeUserData"] = $activeUserDataArray;
		$ret ["newUserData"] = $newUserDataArray;
		
		echo json_encode ( $ret );
	}
	
	/*
	 * Export data to CSV by time phase
	 */
	function export() {
		$productId = $this->common->getCurrentProduct ();
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