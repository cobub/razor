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

class Userremain extends CI_Controller {
	
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		
		$this->load->Model ( 'common' );
		$this->load->model ( 'product/userremainmodel', 'userremain' );
		$this->common->requireLogin ();
		$this->common->requireProduct();
		$this->load->library ( 'export' );
		$this->load->model('event/userevent','userevent');
	
	}
	
	function index() {
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$productId=$productId->id;
		$procuctversion=$this->userevent->getProductVersions($productId);		
		if ($procuctversion != null && $procuctversion->num_rows > 0)
		{
			$this->data['productversion']=$procuctversion;
		}
		$this->load->view('usage/userremainview',$this->data);	
	}
	
	function getUserRemainweekMonthData($version="all")
	{
		    $data=array();
		    $productId = $this->common->getCurrentProduct ();	
			$productId=$productId->id;
			$from = $this->common->getFromTime ();
			$to = $this->common->getToTime();
			$procuctversion=$this->userevent->getProductVersions($productId);			
			$userremain_w= $this->userremain->getUserRemainCountByWeek($version,$productId,$from,$to);
			$userremain_m= $this->userremain->getUserRemainCountByMonth($version,$productId,$from,$to);			
			$data['userremainweek'] = $userremain_w->result();
			$data['userremainmonth'] = $userremain_m->result();
			echo json_encode ($data);
	}
}