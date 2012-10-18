<?php

class version extends CI_Controller{
	private $data = array();
	private $allversions;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->Model('common');
		$this->common->requireLogin();
		$this->common->requireProduct();
		$this->load->model('product/productmodel','product');
		$this->load->model('product/versionmodel','versionmodel');	  
		
	}
	
	function index()
	{		
		$this->common->loadHeaderWithDateControl ();
		$product = $this->common->getCurrentProduct();
     	$productId = $product->id;		
		$date = date('Y-m-d',strtotime("-1 day"));
		$ret = $this->versionmodel->getBasicVersionInfo($productId,$date);
		$this->data['versionList'] = $ret;		
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();		
		$this->data['reportTitle'] = array(
				'timePase' => getTimePhaseStr($fromTime, $toTime),
				'activeUser'=>lang("v_rpt_ve_trendActiveUsers"),
				'newUser'=>lang("v_rpt_ve_trendsAnalytics")			
		);
		$this->load->view('overview/versioncontrast',$this->data);
	}
 	
	//get version data info 
	function getVersionData() {
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();		
		$retdata = $this->versionmodel->getVersionData($fromTime, $toTime, $productId );
		echo json_encode($retdata);
	}
	
	function getVersionContrast($from1, $to1, $from2, $to2) {
		$currentProduct = $this->common->getCurrentProduct ();
		$productId = $currentProduct->id;
		//get sum num
		$total1 = $this->versionmodel->getNewAndActiveAllCount ( $productId, $from1, $to1 );
		$total2 = $this->versionmodel->getNewAndActiveAllCount ( $productId, $from2, $to2 );
		$query1 = $this->versionmodel->getVersionContrast ( $productId, $from1, $to1 );
		$query2 = $this->versionmodel->getVersionContrast ( $productId, $from2, $to2 );
		$result1 = array ();
		$result2 = array ();
		$sum1 = $total1[0]['newusers'];
		$sum12 = $total1[0]['startusers'];
		$sum2 = $total2[0]['startusers'];
		$sum21 = $total2[0]['newusers'];
		foreach ($query1->result_array() as $row)
		{
		    $row['newuserpercent']=percent($row['newusers'],$sum1);
		    $row['startuserpercent']=percent($row['startusers'],$sum12);
			array_push($result1,$row);
		}
		foreach ($query2->result_array() as $row)
		{
			$row['newuserpercent']=percent($row['newusers'],$sum21);
		    $row['startuserpercent']=percent($row['startusers'],$sum2);
			array_push($result2,$row);
		}		
		
		$result = array ($result1, $result2 );
		echo json_encode ( $result );
	}
	
	

}

?>