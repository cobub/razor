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
		$this->load->library ( 'export' );
		$this->load->model('event/userevent','userevent');
		$this->common->checkCompareProduct();		
	}
	
	function index() {
		if(isset($_GET['type'])&&$_GET['type']=='compare'){
			$this->common->loadCompareHeader(lang('m_rpt_userRetention'));
			$this->load->view('compare/userremain');
			return;
		}
		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl ();		
		$this->load->view('usage/userremainview',$this->data);	
	}
	
	//load userremain report
	
	function adduserremainreport($delete=null,$type=null)
	{
			if(isset($_GET['type'])&&$_GET['type']=='compare'){
			$products=$this->common->getCompareProducts();
			if(count($products)==0){echo 'redirecthome';return;}
			$this->load->view ( 'layout/reportheader');
			$this->data['show_version']=0;
			$this->load->view('widgets/userremain',$this->data);
		}else{
		$productId = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$productId=$productId->id;
		$procuctversion=$this->userevent->getProductVersions($productId);
		if ($procuctversion != null && $procuctversion->num_rows > 0)
		{
			$this->data['productversion']=$procuctversion;
		}
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
		$this->load->view('widgets/userremain',$this->data);
		}
	}
	
	function getUserRemainweekMonthData($version="all")
	{
			$data=array();
		    $productId = $this->common->getCurrentProduct ();
			$from = $this->common->getFromTime ();
			$to = $this->common->getToTime();
		    $products=$this->common->getCompareProducts();
			if(count($products)>=2){
			for($i=0;$i<count($products);$i++){
				$data['userremainweek'][$i]['data']=$this->userremain->getUserRemainCountByWeek($version,$products[$i]->id,$from,$to)->result_array();
				$data['userremainweek'][$i]['name']=$products[$i]->name;
				$data['userremainmonth'][$i]['data']=$this->userremain->getUserRemainCountByMonth($version,$products[$i]->id,$from,$to)->result_array();
				$data['userremainmonth'][$i]['name']=$products[$i]->name;
			}
				echo json_encode($data);
		    }else if(!empty($productId)){
			$productId=$productId->id;
			$this->common->requireProduct();
			$procuctversion=$this->userevent->getProductVersions($productId);			
			$userremain_w= $this->userremain->getUserRemainCountByWeek($version,$productId,$from,$to);
			$userremain_m= $this->userremain->getUserRemainCountByMonth($version,$productId,$from,$to);			
			$data['userremainweek'] = $userremain_w->result();
			$data['userremainmonth'] = $userremain_m->result();
			echo json_encode ($data);
		    }else{
		    	echo json_encode($data);
		    }
	}
}