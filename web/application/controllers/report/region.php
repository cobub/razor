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
		$this->common->requireProduct();
	}
	function index() {
		$this->common->loadHeaderWithDateControl ();
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
		$country = "中国";
		
		$this->data['counum'] = $this->region->gettotalacbycountry($fromTime, $toTime, $currentProduct->id );			
		$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS );
		$this->data ['activepagecoun'] = $activepagecoun;
		
		$this->data['pronum'] = $this->region->gettotalactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );		
		$activepagepro = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id, $country, 0,PAGE_NUMS );
		$this->data ['activepagepro'] = $activepagepro;
		
		
		$this->data['reportTitle'] = array(
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10Nations") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10Nations") , $fromTime, $toTime),
				'regionActiveUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10Provinces") , $fromTime, $toTime),
				'regionNewUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10Provinces") , $fromTime, $toTime),
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->load->view ( 'usage/regionview', $this->data );
	}
	
	/*
	 * Get Region data, called by ajax
	 */
	function getRegionData()
	{
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
		$country = '中国';
		//new user National distribution
		$activeUserData = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id,$country);
		$newUserData = $this->region->getnewbypro ( $fromTime, $toTime, $currentProduct->id,$country);

		$ret ["regionActiveUserData"] = $activeUserData->result_array();
		$ret ["regionNewUserData"] = $newUserData->result_array();
		
		echo json_encode($ret);
	}
	
	/*
	 * Get Country data, called by ajax
	*/
	function getCountryData()
	{
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
	
		//new user National distribution
		$newUserData = $this->region->getnewbycountry ($fromTime, $toTime, $currentProduct->id );
		$activeUserData = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id );
	
		$ret ["activeUserData"] = $activeUserData->result_array();
		$ret ["newUserData"] = $newUserData->result_array();
	
		echo json_encode($ret);
	}
	
	
	//get data info by time
	function regioninfo($timePhase, $fromDate = '', $toDate = '') {
		$this->common->loadHeader ();
		$country = "中国";		
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
		//active user national distribution
		$activecountry = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id );
		if ($activecountry != null && $activecountry->num_rows () > 0) {
			
			$this->data ['activecountry'] = $activecountry;			
			$this->data['counum'] = $this->region->gettotalacbycountry ( $fromTime, $toTime, $currentProduct->id );			
			$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS );
			$this->data ['activepagecoun'] = $activepagecoun;
		}else{
			$this->data['counum']=0;
		}
		//new user national distribution 
		$newcountry = $this->region->getnewbycountry ( $fromTime, $toTime, $currentProduct->id );
		if ($newcountry != null && $newcountry->num_rows () > 0) {
			$this->data ['newcountry'] = $newcountry;
		}
		//active user province distribution
		$activepro = $this->region->getactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($activepro != null && $activepro->num_rows () > 0) {
			$this->data ['activepro'] = $activepro;			
			$this->data['pronum'] = $this->region->gettotalactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );			
			$activepagepro = $this->region->getactivebypro ( $fromTime, $toTime, $currentProduct->id, $country, 0,  PAGE_NUMS);
			$this->data ['activepagepro'] = $activepagepro;
		}else{
			$this->data['pronum']=0;
		}
		//new user province distribution
		$newpro = $this->region->getnewbypro ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($newpro != null && $newpro->num_rows () > 0) {
			$this->data ['newpro'] = $newpro;
		}
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->load->view ( 'usage/regionview', $this->data );
	}
	
	/*
	 * active national paging detail
	 */
	function  activecountrypage($pagenum)
	{
		$percent=100;
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$pagenum=$pagenum*PAGE_NUMS;		
		$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, $pagenum, PAGE_NUMS );
		$htmlText = "";
		if($activepagecoun!= null &&$activepagecoun->num_rows () > 0)
		{
			foreach ($activepagecoun->result_array()as $row)
			{				
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td>".$row['country']."</td>";
				$htmlText = $htmlText."<td>".$row['access']."</td>";
				$htmlText = $htmlText."<td>".round($percent*$row['percentage'],1)."%</td>";				
				$htmlText = $htmlText."</tr>";
			}		
		echo $htmlText;
		}
	}
	
	/*
	 * active province paging detail
	 */
	function activepropage($pagenum)
	{
		$country = "中国";
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
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
				$htmlText = $htmlText."<td>".round($row['access'],1)."</td>";
				$htmlText = $htmlText."<td>".$percent*$row['percentage']."%</td>";				
				$htmlText = $htmlText."</tr>";
			}		
		echo $htmlText;
		}
	}
	/*
	 * export country info
	 */
	function exportcountry() {
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();		$activecountry = $this->region->getcountryexport ( $fromTime, $toTime, $currentProduct->id );
		if ($activecountry != null && $activecountry->num_rows () > 0) {
			
			$data = $activecountry;
			$this->export->setFileName ( 'regioncountry_' . $fromTime . '_' . $toTime . '.csv' );
			$fields = array ();
			foreach ( $data->list_fields () as $field ) {
			array_push ( $fields, $field );
			}
            $this->export->setTitle ( $fields );
// 			$excel_title = array (iconv("UTF-8", "GBK", "国家"),iconv("UTF-8", "GBK", "比例") );
// 			$this->export->setTitle ($excel_title );
			foreach ( $data->result () as $row )			
				$this->export->addRow ( $row );
			$this->export->export ();
			die ();
		}
	   else 
		{
			$this->load->view("usage/nodataview");
		}
		
	
	}
	/*
	 * export province info
	 */
	function exportpro() {
		$country = "中国";
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$currentProduct = $this->common->getCurrentProduct ();
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		$activepro = $this->region->getproexport ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($activepro != null && $activepro->num_rows () > 0) {
			$data = $activepro;
			$this->export->setFileName ( 'regionprovince_' . $fromTime . '_' . $toTime . '.csv' );
			$fields = array ();
			foreach ( $data->list_fields () as $field ) {
				array_push ( $fields, $field );
			}
			$this->export->setTitle ( $fields );
			foreach ( $data->result () as $row )
			$this->export->addRow ( $row );
			$this->export->export ();
			die ();
		
		}
		else 
		{
			$this->load->view("usage/nodataview");
		}
	
	}
}

?>