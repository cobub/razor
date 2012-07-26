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
		$this->load->model('product/productmodel','product');
		$this->load->model('product/versionmodel','versionmodel');	  
		
	}
	
	function index()
	{		
		$this->common->loadHeader();
		$product = $this->common->getCurrentProduct();
     	$productId = $product->id;
		$data['productId'] = $productId;
		$date = date('Y-m-d',strtotime("-1 day"));
		$ret = $this->versionmodel->getBasicVersionInfo($productId,$date);
		$this->data['versionList'] = $ret;
		$this->load->view('report/versioncontrast',$this->data);
	}
 	
	//获取版本数据
	function getVersionData($type = 'new', $timePhase, $start = '', $end = '') {
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$retdata = $this->versionmodel->getVersionData ( $timePhase, $start, $end, $productId );
		echo json_encode($retdata);
	}
	
	function getVersionContrast($from1, $to1, $from2, $to2) {
		$currentProduct = $this->common->getCurrentProduct ();
		$productId = $currentProduct->id;
		//获取总数
		$total1 = $this->versionmodel->getNewAndActiveAllCount ( $productId, $from1, $to1 );
		$total2 = $this->versionmodel->getNewAndActiveAllCount ( $productId, $from2, $to2 );
		$query1 = $this->versionmodel->getVersionContrast ( $productId, $from1, $to1 );
		$query2 = $this->versionmodel->getVersionContrast ( $productId, $from2, $to2 );
		$result1 = array ();
		$result2 = array ();
		$sum1 = $total1[0]['newusers'];
		$sum2 = $total2[0]['startusers'];
		foreach ($query1->result_array() as $row)
		{
		    $row['newuserpercent']=percent($row['newusers'],$sum1);
		    $row['startuserpercent']=percent($row['startusers'],$sum2);
			array_push($result1,$row);
		}
		foreach ($query2->result_array() as $row)
		{
			$row['newuserpercent']=percent($row['newusers'],$sum1);
		    $row['startuserpercent']=percent($row['startusers'],$sum2);
			array_push($result2,$row);
		}		
		
		$result = array ($result1, $result2 );
		echo json_encode ( $result );
	}
	
	

}

?>