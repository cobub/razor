<?php
class Operator extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();		
		$this->load->Model('common');
		$this->load->model('product/operatormodel','operator');
		$this->load->model('product/productmodel','product');
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
		$dataofresult=$this->operator->getTotalUsersPercentByOperatorJSON($fromTime,$toTime,$productId);
		if(empty($dataofresult)){
			$this->data['jsonoperator']=json_encode($dataofresult);
		}else{
			$this->data['jsonoperator']="{}";
		}
		
		$this->load->view('terminalandnet/operatorview', $this->data);
		
		
	}
	
	function getOperatorData($timePhase,$type,$start='',$end='')
	{
	
		//$this->common->loadHeader();
		$productId = $this->common->getCurrentProduct()->id;
		
	    $toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));
		
		if($timePhase == "7day")
		{			
			$fromTime = date('Y-m-d',strtotime("-7 day"));			
		}
		
		if($timePhase == "1month")
		{			
			$fromTime = date("Y-m-d",strtotime("-30 day"));			
		}
		
		if($timePhase == "3month")
		{
			$fromTime = date("Y-m-d",strtotime("-90 day"));
			
		}
		if($timePhase == "all")
		{			
			$fromTime = 'all';			
		}
		
		if($timePhase == 'any')
		{			
			$fromTime = $start;
			$toTime = $end;			
	    }
	    $this->data['timetype'] = $timePhase;
	    $this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$result=array();
		if($type=='activeusers'){
			$result['datas'] = $this->operator->getActiveUsersPercentByOperatorJSON($fromTime,$toTime,$productId);
	
		}
		if($type=="newusers"){
			$result['datas'] = $this->operator->getNewUsersPercentByOperatorJSON($fromTime,$toTime,$productId);
    
		}
		   $result['operator'] = $this->operator->getTotalUsersPercentByOperatorJSON($fromTime,$toTime,$productId);
		$result['type']=$type;
		echo json_encode($result);
	     
	//	$this->load->view('terminalandnet/operatorview', $this->data);
	
	}
	
	
	function export($from,$to)
	{
		$this->load->library('export');
		
		$productId = $this->common->getCurrentProduct()->id;
		$productName = $this->common->getCurrentProduct()->name;
		$data = $this->operator->getTotalUsersPercentByOperator($from,$to,$productId);
		$export = new Export();
		//设定文件名
		$export->setFileName($productName.'_'.$from.'_'.$to.'.csv');
		//输出列名第一种方法
		$fields = array ();
		foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
		}
      $export->setTitle($fields);
      //输出列名第二种方法
//      $excel_title = array (iconv("UTF-8", "GBK", "运营商"),iconv("UTF-8", "GBK", "用户比例") );
//	  $export->setTitle ($excel_title );	
		//输出内容
		foreach ($data->result() as $row)
		    $export->addRow($row);
        $export->export();
        die();
	}
}