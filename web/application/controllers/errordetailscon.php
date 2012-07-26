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
class errordetailscon extends CI_Controller{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->Model('common');
		$this->common->requireLogin();  

	}
	
	function index()
	{		
		$this->common->loadHeader();
		$this->load->view('report/errordetails',$this->data);
	}
	function getStackTrace($errorid)
	{
		
	}
	function getErrorDetail()
	{
		
	}
	function getDeviDceistribution()
	{
		
	}
	function getOSDistribution()
	{
		
	}
	

}

?>