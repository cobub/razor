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
class Usetime extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();		
		$this->load->Model('common');
		$this->load->model('product/usinganalyzemodel','analyze');
		$this->common->requireLogin();
		
	}
	
	function index()
	{
		$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct()->id;
		$this->data['data'] = $this->analyze->getUsingTimeByProduct($productId);
		$this->load->view('useranalyze/usetimeview', $this->data);
	}
	
}