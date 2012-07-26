<?php

class region extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'common' );		
		$this->load->model ( 'region/regionmodel', 'region' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->library ( 'pagination' );
		$this->load->library ( 'export' );
			
	}
	function index() {
		$this->common->loadHeader ();
		$country = "中国";
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = date ( "Y-m-d", strtotime ( "-7 day" ) );
		$toTime = date ( 'Y-m-d', time () );
		//活跃用户 国家分布
		$activecountry = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id );
		if ($activecountry != null && $activecountry->num_rows () > 0) {
			$this->data ['activecountry'] = $activecountry;	
			$this->data['counum'] = $this->region->gettotalacbycountry($fromTime, $toTime, $currentProduct->id );			
			$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS );
			$this->data ['activepagecoun'] = $activepagecoun;
		
		}else{
			$this->data['counum']=0;
		}
		//新增用户 国家分布
		$newcountry = $this->region->getnewbycountry ($fromTime, $toTime, $currentProduct->id );
		if ($newcountry != null && $newcountry->num_rows () > 0) {
			$this->data ['newcountry'] = $newcountry;
		}
		//活跃用户 省市分布
		$activepro = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id, $country ); //数据从$pageForm开始筛选，选取10个
		if ($activepro != null && $activepro->num_rows () > 0) {
			$this->data ['activepro'] = $activepro;	
			$this->data['pronum'] = $this->region->gettotalactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );		
			$activepagepro = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id, $country, 0,PAGE_NUMS );
			$this->data ['activepagepro'] = $activepagepro;
		}else{
			$this->data['pronum']=0;
		}
		//新增用户 省市分布
		$newpro = $this->region->getnewbypro ($fromTime, $toTime, $currentProduct->id, $country );
		if ($newpro != null && $newpro->num_rows () > 0) {
			$this->data ['newpro'] = $newpro;
		}
		
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->load->view ( 'region/regionview', $this->data );
	}
	
	//根据时间刷选数据
	function regioninfo($timePhase, $fromDate = '', $toDate = '') {
		$this->common->loadHeader ();
		$country = "中国";		
		$currentProduct = $this->common->getCurrentProduct ();
		$toTime = date ( 'Y-m-d', time () );
		if ($timePhase == "7day") {
			$fromTime = date ( "Y-m-d", strtotime ( "-7 day" ) );
		}
		if ($timePhase == "1month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
		}
		
		if ($timePhase == "3month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
		}
		
		if ($timePhase == "all") {
			$fromTime = $currentProduct->date;
		}
		
		if ($timePhase == "any") {
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		
		//活跃用户 国家分布
		$activecountry = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id );
		if ($activecountry != null && $activecountry->num_rows () > 0) {
			
			$this->data ['activecountry'] = $activecountry;			
			$this->data['counum'] = $this->region->gettotalacbycountry ( $fromTime, $toTime, $currentProduct->id );			
			$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS );
			$this->data ['activepagecoun'] = $activepagecoun;
		}else{
			$this->data['counum']=0;
		}
		//新增用户 国家分布
		$newcountry = $this->region->getnewbycountry ( $fromTime, $toTime, $currentProduct->id );
		if ($newcountry != null && $newcountry->num_rows () > 0) {
			$this->data ['newcountry'] = $newcountry;
		}
		//活跃用户 省市分布
		$activepro = $this->region->getactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($activepro != null && $activepro->num_rows () > 0) {
			$this->data ['activepro'] = $activepro;			
			$this->data['pronum'] = $this->region->gettotalactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );			
			$activepagepro = $this->region->getactivebypro ( $fromTime, $toTime, $currentProduct->id, $country, 0,  PAGE_NUMS);
			$this->data ['activepagepro'] = $activepagepro;
		}else{
			$this->data['pronum']=0;
		}
		//新增用户 省市分布
		$newpro = $this->region->getnewbypro ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($newpro != null && $newpro->num_rows () > 0) {
			$this->data ['newpro'] = $newpro;
		}
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->load->view ( 'region/regionview', $this->data );
	}
	
	//活跃国家明细分页
	function  activecountrypage($fromTime,$toTime,$pagenum)
	{
		$percent=100;
		$currentProduct = $this->common->getCurrentProduct ();
		$pagenum=$pagenum*PAGE_NUMS;		
		$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, $pagenum, PAGE_NUMS );
		$htmlText = "";
		if($activepagecoun!= null &&$activepagecoun->num_rows () > 0)
		{
			foreach ($activepagecoun->result_array()as $row)
			{				
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td>".$row['country']."</td>";
				$htmlText = $htmlText."<td>".$percent*$row['percentage']."%</td>";				
				$htmlText = $htmlText."</tr>";
			}		
		echo $htmlText;
		}
	}
	//活跃省市明细分页
	function activepropage($fromTime,$toTime,$pagenum)
	{
		$country = "中国";
		$percent=100;
		$currentProduct = $this->common->getCurrentProduct ();
		$pagenum=$pagenum*PAGE_NUMS;
		$activepagepro = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id, $country, $pagenum,PAGE_NUMS );
	    $htmlText = "";
		if($activepagepro!= null &&$activepagepro->num_rows () > 0)
		{
			foreach ($activepagepro->result_array()as $row)
			{				
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td>".$row['region']."</td>";
				$htmlText = $htmlText."<td>".$percent*$row['percentage']."%</td>";				
				$htmlText = $htmlText."</tr>";
			}		
		echo $htmlText;
		}
	}
	//导出国家    地域信息
	function exportcountry($fromTime, $toTime) {
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		$activecountry = $this->region->getcountryexport ( $fromTime, $toTime, $currentProduct->id );
		if ($activecountry != null && $activecountry->num_rows () > 0) {
			
			$data = $activecountry;
			$this->export->setFileName ( 'regioncountry_' . $fromTime . '_' . $toTime . '.csv' );
//			//输出列名 第一种方法
			$fields = array ();
			foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
			}
           $this->export->setTitle ( $fields );
			//输出列名 第二种方法
//			$excel_title = array (iconv("UTF-8", "GBK", "国家"),iconv("UTF-8", "GBK", "比例") );
//			$this->export->setTitle ($excel_title );
			//输出内容
			foreach ( $data->result () as $row )			
				$this->export->addRow ( $row );
			$this->export->export ();
			die ();
		}
	   else 
		{
			$this->load->view("region/nodataview");
		}
		
	
	}
	//导出省市   地域信息
	function exportpro($fromTime, $toTime) {
		$country = "中国";
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		$activepro = $this->region->getproexport ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($activepro != null && $activepro->num_rows () > 0) {
			$data = $activepro;
			$this->export->setFileName ( 'regionprovince_' . $fromTime . '_' . $toTime . '.csv' );
			//输出列名
			$fields = array ();
			foreach ( $data->list_fields () as $field ) {
				array_push ( $fields, $field );
			}
			$this->export->setTitle ( $fields );
			//设置列名(第2种方法)
//			$excel_title = array (iconv("UTF-8", "GBK", "省市"),iconv("UTF-8", "GBK", "比例") );
//			$this->export->setTitle ($excel_title );
			
			//输出内容
			foreach ( $data->result () as $row )
				$this->export->addRow ( $row );
			$this->export->export ();
			die ();
		
		}
		else 
		{
			$this->load->view("region/nodataview");
		}
	
	}
}

?>