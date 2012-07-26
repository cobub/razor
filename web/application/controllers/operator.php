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
class Operator extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();		
		$this->load->Model('common');
		$this->load->model('channelmodel','channel');
		$this->load->model('product/operatormodel','operator');
		$this->load->model('product/productmodel','product');
		$this->load->model('product/newusermodel','newusermodel');
		$this->common->requireLogin();
	}
	
	function index()
	{
		$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct()->id;
		$toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));	
		$this->data['activeuser'] = $this->operator->getActiveUsersPercentByOperator($fromTime,$toTime,$productId);
		$this->data['newuser'] = $this->operator->getNewUsersPercentByOperator($fromTime,$toTime,$productId);
        $this->data['operator'] = $this->operator->getTotalUsersPercentByOperator($fromTime,$toTime,$productId);
		$this->data['timetype'] = '7day';
		$this->load->view('terminalandnet/operatorview', $this->data);
	}
	
	function getOperatorData($timePhase,$start='',$end='')
	{
	
		$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct()->id;
		
	    $toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));
		
		if($timePhase == "7day")
		{
			
			$fromTime = date('Y-m-d',strtotime("-7 day"));	
			$this->data['timetype'] = '7day';	
			
		}
		
		if($timePhase == "1month")
		{
			
			$fromTime = date("Y-m-d",strtotime("-30 day"));
			$this->data['timetype'] = '1month';
			
		}
		
		if($timePhase == "3month")
		{
			$fromTime = date("Y-m-d",strtotime("-90 day"));
			$this->data['timetype'] = '3month';
			
		}
		if($timePhase == "all")
		{
			
			$fromTime = 'all';
			$this->data['timetype'] = 'all';
			
		}
		
		if($timePhase == 'any')
		{
			
			$fromTime = $start;
			$toTime = $end;
			$this->data['timetype'] = 'any';
			$this->data['from'] = $start;
			$this->data['to'] = $end;
			
	    }
		$this->data['activeuser'] = $this->operator->getActiveUsersPercentByOperator($fromTime,$toTime,$productId);
		$this->data['newuser'] = $this->operator->getNewUsersPercentByOperator($fromTime,$toTime,$productId);
        $this->data['operator'] = $this->operator->getTotalUsersPercentByOperator($fromTime,$toTime,$productId);
		
		$this->load->view('terminalandnet/operatorview', $this->data);
	
	}
	
}