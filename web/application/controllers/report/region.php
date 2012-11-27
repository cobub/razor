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
		$this->common->checkCompareProduct();
			
	}
	function index() {
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
		$this->data['reportTitle'] = array(
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10Nations") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10Nations") , $fromTime, $toTime),
				'regionActiveUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10Provinces") , $fromTime, $toTime),
				'regionNewUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10Provinces") , $fromTime, $toTime),
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
		if(isset($_GET['type'])&&$_GET['type']=='compare'){
			$this->common->loadCompareHeader ();
			$this->load->view ( 'compare/regionview', $this->data );
		}else{
		$this->common->loadHeaderWithDateControl ();
		$currentProduct = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$country = "中国";
		
		$this->data['counum'] = $this->region->gettotalacbycountry($fromTime, $toTime, $currentProduct->id );			
		$activepagecoun = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS );
		$this->data ['activepagecoun'] = $activepagecoun;
		
		$this->data['pronum'] = $this->region->gettotalactivebypro ( $fromTime, $toTime, $currentProduct->id, $country );		
		$activepagepro = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id, $country, 0,PAGE_NUMS );
		$this->data ['activepagepro'] = $activepagepro;
		$this->data ['from'] = $fromTime;
		$this->data ['to'] = $toTime;
		$this->load->view ( 'usage/regionview', $this->data );
		}
	}
	/*load region report*/
	function addregionprovincereport($delete=null,$type=null)
	{
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
		$this->data['reportTitle'] = array(				
				'regionActiveUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10Provinces") , $fromTime, $toTime),
				'regionNewUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10Provinces") , $fromTime, $toTime),
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
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
		$this->load->view('widgets/regionprovince',$this->data);
	}
	/*load country report*/
	function addregioncountryreport($delete=null,$type=null)
	{
		$fromTime = $this->common->getFromTime();
		$toTime =   $this->common->getToTime();
		$this->data['reportTitle'] = array(
				'activeUserReport'=> getReportTitle(lang("t_activeUsers")." ".lang("v_rpt_re_top10Nations") , $fromTime, $toTime),
				'newUserReport'=>  getReportTitle(lang("t_newUsers")." ".lang("v_rpt_re_top10Nations") , $fromTime, $toTime),				
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
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
		$this->load->view('widgets/regioncountry',$this->data);
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
		if(empty($currentProduct)){
			$products = $this->common->getCompareProducts();
			if(empty($products)){
				$this->common->requireProduct();
				return;
			}
			for($i=0;$i<count($products);$i++){
				$activedata=$this->region->getactivebypro($fromTime, $toTime,$products[$i]->id,$country);
				$newdata=$this->region->getnewbypro($fromTime, $toTime,$products[$i]->id,$country);
				$ret["regionActiveUserData".$products[$i]->name] = $this->change2StandardPrecent($activedata,"region");
				$ret["regionNewUserData".$products[$i]->name] = $this->change2StandardPrecent($newdata,"region");
			}
		}else{
		//new user National distribution
		$this->common->requireProduct();
		$activeUserData = $this->region->getactivebypro ($fromTime, $toTime, $currentProduct->id,$country);
		$newUserData = $this->region->getnewbypro ( $fromTime, $toTime, $currentProduct->id,$country);

		$ret ["regionActiveUserData"] = $activeUserData->result_array();
		$ret ["regionNewUserData"] = $newUserData->result_array();
		}
		
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
		if(empty($currentProduct)){
			$products = $this->common->getCompareProducts();
			if(empty($products)){
				$this->common->requireProduct();
				return;
			}
			for($i=0;$i<count($products);$i++){
				$activedata=$this->region->getactivebycountry($fromTime, $toTime,$products[$i]->id);
				$newdata=$this->region->getnewbycountry($fromTime, $toTime,$products[$i]->id);
				$ret["activeUserData".$products[$i]->name] = $this->change2StandardPrecent($activedata,"country");
				$ret["newUserData".$products[$i]->name] = $this->change2StandardPrecent($newdata,"country");
			}
		}else{
		//new user National distribution
		$this->common->requireProduct();
		$newUserData = $this->region->getnewbycountry ($fromTime, $toTime, $currentProduct->id );
		$activeUserData = $this->region->getactivebycountry ( $fromTime, $toTime, $currentProduct->id );	
		$ret ["activeUserData"] = $activeUserData->result_array();
		$ret ["newUserData"] = $newUserData->result_array();
		}
	
		echo json_encode($ret);
	}
	
	function change2StandardPrecent($userData,$type){
		$userDataArray = array ();
		$totalPercent = 0;
		foreach ( $userData->result () as $row ) {
			if (count ( $userData ) > 10) {
				break;
			}
			$userDataObj = array ();
			if($type=="country"){
				$userDataObj ["country"] = $row->country;
			}
			else{
				$userDataObj ["region"] = $row->region;
			}
			$percent = round ( $row->percentage * 100, 1 );
			$totalPercent += $percent;
			$userDataObj ["percentage"] = $percent;
			array_push ( $userDataArray, $userDataObj );
		}
		
		if ($totalPercent < 100.0) {
			$remainPercent = round ( 100 - $totalPercent, 2 );
			$userDataObj [$type] = lang('g_others');
			$userDataObj ["percentage"] = $remainPercent;
			array_push ( $userDataArray, $userDataObj );
		}
		return $userDataArray;
	//	print_r($userDataArray);
	}
	
	
	//get data info by time
	function regioninfo($timePhase, $fromDate = '', $toDate = '') {
		$this->common->loadHeader ();
		$country = "中国";		
		$currentProduct = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
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
		$this->common->requireProduct();
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
		$this->common->requireProduct();
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
	
function exportCSV($label){
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$products = $this->common->getCompareProducts();
		if(empty($products)){
			$this->common->requireProduct();
			return;
		}
		$this->load->library ( 'export' );
		$export = new Export ();
		if($label=="country"){
		$titlename=getExportReportTitle("Compare",lang("v_rpt_re_top10Nations"),$fromTime, $toTime);
		}
		else{
			$titlename=getExportReportTitle("Compare",lang("v_rpt_re_top10Provinces"),$fromTime, $toTime);
		}
		$titlename=iconv("UTF-8", "GBK", $titlename);
		$export->setFileName ($titlename);
		$j=0;
		$mk=0;
		$title[$j++]=iconv("UTF-8", "GBK", lang('t_activeUsers'));
		$space[$mk++]=' ';
		for($i=0;$i<count($products);$i++){
 			$title[$j++]=iconv("UTF-8", "GBK",$products[$i]->name);
 			$title[$j++]='';
 			$space[$mk++]=' ';
 			$space[$mk++]=' ';
		}
		$export->setTitle ($title);
		$k=0;
		$maxlength=0;
		$maxlength2=0;
		$j=0;
		$nextlabel[$j++]=lang('t_newUsers');
		for($m=0;$m<count($products);$m++){
			if($label=="country"){
				$activedata=$this->region->getactivebycountry($fromTime, $toTime,$products[$m]->id);
				$newdata=$this->region->getnewbycountry($fromTime, $toTime,$products[$m]->id);		
			}else{
				$country = '中国';
				$activedata=$this->region->getactivebypro($fromTime, $toTime,$products[$m]->id,$country);
				$newdata=$this->region->getnewbypro($fromTime, $toTime,$products[$m]->id,$country);	
			}
			$detailData[$m] = $this->change2StandardPrecent($activedata,$label);
			$detailNewData[$m] = $this->change2StandardPrecent($newdata,$label);
			if(count($detailData[$m])>$maxlength){
				$maxlength=count($detailData[$m]);
			}
			if(count($detailNewData[$m])>$maxlength2){
				$maxlength2=count($detailNewData[$m]);
			}
			$nextlabel[$j++]=$products[$m]->name;
			$nextlabel[$j++]=' ';
		}
		$this->getExportRowData($export,$maxlength,$detailData,$products,$label);
		$export->addRow ( $space );
		$export->addRow ( $nextlabel );
		$this->getExportRowData($export,$maxlength2,$detailNewData,$products,$label);
		$export->export ();
		die ();
	}
	
	function getExportRowData($export,$length,$userData,$products,$label){
		$k=0;
		for($i=0;$i<$length;$i++){
			$result[$k++]=$i+1;
			for($j=0;$j<count($products);$j++){
				$obj=$userData[$j];
				if($i>=count($obj)){
					$result[$k++]='';
					$result[$k++]='';
				}else{
					if($obj[$i][$label]==''){
						$result[$k++]='unknow';
					}else{
						$result[$k++]=$obj[$i][$label];
					}
					$result[$k++]=$obj[$i]['percentage']."%";
				}
			}
			$export->addRow ( $result );
			$k=0;
		}
	}
	
	/*
	 * export country info
	 */
	function exportcountry() {
		$currentProduct = $this->common->getCurrentProduct ();
		$this->common->requireProduct();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();		
		$activecountry = $this->region->getcountryexport ( $fromTime, $toTime, $currentProduct->id );
		if ($activecountry != null && $activecountry->num_rows () > 0) {
			
			$data = $activecountry;			
			$titlename=getExportReportTitle($currentProduct->name, lang("v_rpt_re_detailsOfNation"),$fromTime, $toTime);
			$titlename=iconv("UTF-8", "GBK", $titlename);
			$this->export->setFileName ($titlename );
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
		$this->common->requireProduct();
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		$activepro = $this->region->getproexport ( $fromTime, $toTime, $currentProduct->id, $country );
		if ($activepro != null && $activepro->num_rows () > 0) {
			$data = $activepro;
			$titlename=getExportReportTitle($currentProduct->name, lang("v_rpt_re_detailsOfProvince"),$fromTime, $toTime);
			$titlename=iconv("UTF-8", "GBK", $titlename);
			$this->export->setFileName ($titlename );
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