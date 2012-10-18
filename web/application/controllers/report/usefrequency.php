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
 * @filesource
 */
class Usefrequency extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();		
		$this->load->Model('common');
		$this->load->model('product/usinganalyzemodel','analyze');
		$this->common->requireLogin();
		$this->common->requireProduct();
		
	}
	
	function index()
	{
		$this->common->loadHeaderWithDateControl ();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
				'reportName'=> lang("v_rpt_uf_distribution"),
				'timePase' => getTimePhaseStr($fromTime, $toTime)
				);
		$this->load->view('usage/usefrequencyview', $this->data);
	}
	
	/*
	 * Get User Frequency data
	 */
	function getUserFrequencyData()
	{
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$productId = $this->common->getCurrentProduct();
		$productId=$productId->id;
		$ret["userFrequencyData"] = $this->analyze->getUsingFrequenceByProduct($productId,$fromTime,$toTime)->result_array();
		echo json_encode($ret);
	}
	
}