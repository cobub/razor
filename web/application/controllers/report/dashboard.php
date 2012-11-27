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
class Dashboard extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array ('form','url') );	
		$this->load->model ('common');
		$this->load->model ('dashboard/dashboardmodel','dashboard');
		$this->common->requireLogin ();
	}
	
	function addshowreport()
	{
		$product = $this->common->getCurrentProduct();
		$productid=$product->id;
		$reportname = $_POST['reportname'];		
		$controller = $_POST['controller'];
		$height     = $_POST['height'];
		$type       = $_POST['type'];		   
		$userid = $this->common->getUserId();
		$src=site_url()."/report/".$controller."/add".$reportname."report";
		$ret=$this->dashboard->addreport($productid,$userid,$reportname,$controller,$src,$height,$type);				
		$html="";
		if($ret==1)
		{		
			$html=$html."<iframe src='".$src."/del' id='".$reportname."' frameborder='0' scrolling='no'style='width:100%;height:".$height."px;margin: 10px 3% 0 0.3%;'></iframe>";
		}	
		if($ret>=8)
		{
			$html= $ret;
		}		
		echo $html;		
	}	
	
	function deleteshowreport()
	{
		$product = $this->common->getCurrentProduct();
		$productid=$product->id;
		$reportname=$_POST['reportname'];
		$type=$_POST['type'];
		$userid = $this->common->getUserId();
		$ret=$this->dashboard->deletereport($productid,$userid,$reportname,$type);
		if($ret)
		{
			echo true;
		}
		else
		{
			echo false;
		}
		
	}
	function loadwidgetslist()
	{		
		$product = $this->common->getCurrentProduct();
		$productid=$product->id;
		$userid = $this->common->getUserId();
		$addreport=$this->dashboard->getaddreport($productid,$userid);
		$num=$this->dashboard->getreportnum($productid,$userid);
		$this->data['num']=$num;		
		if($addreport)
		{
			$this->data['addreport']=$addreport;
		}	
		$this->load->view('widgets/widgetslistview', $this->data);
	}

	function savereportlocation()
	{
		$product = $this->common->getCurrentProduct();
		$productid=$product->id;
		$userid = $this->common->getUserId();
		$reportname=$_POST['reportname'];
		$type=$_POST['type'];
		$location=$_POST['location'];
		$ret=$this->dashboard->updatereport($productid,$userid,$reportname,$type,$location);
		if($ret)
		{
			echo true;
		}
		else
		{
			echo false;
		}
		
	}
}
