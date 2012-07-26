<?php
class errorlog extends CI_Controller {
	private $data = array ();
	
	function __construct() {
		parent::__construct ();
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->load->Model ( 'common' );
		$this->load->model ( 'product/productmodel', 'product' );
		$this->load->model ( 'product/versionmodel', 'versionmodel' );
		$this->load->model ( 'product/errormodel', 'errormodel' );
		$this->common->requireLogin ();
		
		
		
	
	}
	function index() {
		$this->common->loadHeader ();
		$this->load->view ( 'report/errorlogview', $this->data );
		$product = $this->common->getCurrentProduct ();
		$productid = $product->id;
		
		//加载错误列表信息		
		$query = $this->errormodel->geterrorlist ($productid);	
		if ($query != null && $query->num_rows () > 0) {			
			$this->data ['nonum'] = $query->num_rows ();			
		
		}		
		$this->data ['errorlistnofix'] =$this->errormodel->getpageerrorlist ($productid);
		$this->data ['isfix'] = 0;		
		$this->load->view ( 'report/errorlistview', $this->data );
	
	}
	//获得错误信息列表的分页信息
	function geterrorlistpageinfo($pageindex,$fix,$devicename="")
	{
		$pagenum=$pageindex*REPORT_TOP_TEN;
		$productId = $this->common->getCurrentProduct ()->id;
		$query=$this->errormodel->getpageerrorlist ($productId,$fix,$pagenum,$devicename="");
		$htmlText = "";
		if($query!= null &&$query->num_rows () > 0)
		{ 			
			foreach ($query->result_array()as $row)
			{		
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td width='5%'><input name='select' id='profile' type='checkbox' value='".$row['title_sk']."|</br>|".$row['product_sk']."|</br>|".$row['title']."|</br>|".$row['version_name']."'/></td>";
				$htmlText = $htmlText."<td width='55%'><font color='black'><a href=".site_url()."/report/errorlog/detailstacktrace/".$row['title_sk']."/".$row['product_sk']."/".$fix."\">" ;
				$title = "null";
				if($row ['title']=="")
				{
					$title = "nulls";
				}
				else 
				{
					$title = $row['title'];
				}
				$htmlText = $htmlText.$title."</a></font></td>";
				$htmlText = $htmlText."<td width='10%'>";
				$versionname = "null";
				if($row ['version_name']=="")
				{
					$versionname= lang('errorlistview_tbodyunknow');
				}
				else
				{
					$versionname= $row ['version_name'];
				} 		       
		        $htmlText = $htmlText.$versionname."</td>" 	;
		        $htmlText = $htmlText."<td width='15%'>".$row ['time']."</td>";
			    $htmlText = $htmlText."<td width='15%'>".$row ['errorcount']."</td>";			
				$htmlText = $htmlText."</tr>";
			}		
		echo $htmlText;
	}
	}
	
	//错误数据报表
	function geterroralldata($type, $timePhase, $start = '', $end = '') {
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$toTime = date ( 'Y-m-d', time () );
		if ($timePhase == "7day") {
			$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		}
		if ($timePhase == "1month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
		
		}
		
		if ($timePhase == "3month") {
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
		
		}	
		if ($timePhase == "all") {
			$fromTime = $product->date;
		}
		
		if ($timePhase == "any") {
			$fromTime = $start;
			$toTime = $end;
		
		} 
		$result = $this->errormodel->geterrorinfodata($productId,$fromTime,$toTime);
		echo json_encode($result);
	}
	
	//错误数据报表
	function geterrordata($type, $timePhase, $start = '', $end = '') {
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$versions = $this->errormodel->geterrorinfoversion ();
		$ret = array ();
		if ($versions != null && $versions->num_rows () > 0) {
			foreach ( $versions->result () as $row ) {
				$data = $this->getreporttype ( $productId, $row->version, $timePhase, $type, $start, $end );
				array_push ( $ret, $data );
			}
		}
		echo json_encode ( $ret );
	}
	//处理是何种数据报表
	function getreporttype($product_id, $version, $timePhase, $type, $start, $end) {
		
		if ($type == 'errorNumber') {
			return $this->geterrornumberdata ( $product_id, $version, $timePhase, $start, $end );
		}
		if ($type == 'errorAndStart') {
			return $this->geterrorstartnum ( $product_id, $version, $timePhase, $start, $end );
		}
	}
	//错误个数         数据报表数据处理
	function geterrornumberdata($product_id, $version, $timePhase, $fromDate, $toDate) {
		
		$currentProduct = $this->common->getCurrentProduct ();
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		if ($timePhase == "7day") {
			$fromTime = date ( "Y-m-d", strtotime ( "-7 day" ) );
			$title =lang('errortitle_error7days');
		}
		if ($timePhase == "1month") {
			$title = lang('errortitle_error30days');
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
		
		}
		
		if ($timePhase == "3month") {
			$title = lang('errortitle_error3month');
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
		
		}
		
		if ($timePhase == "all") {
			$title = lang('errortitle_errorall');
			$fromTime = $currentProduct->date;
		
		}
		
		if ($timePhase == "any") {
			$title = lang('errortitle_erroranytime');
			$fromTime = $fromDate;
			$toTime = $toDate;
		
		}
		
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		$query = $this->errormodel->geterrornumbyvertime ( $fromTime, $toTime, $product_id, $version );
		$ret ['version'] = $version;
		$ret ["title"] = $title;
		$ret ["content"] = $query->result_array ();
		return $ret;
	
	}
	////错误/启动 个数 数据报表数据处理
	function geterrorstartnum($product_id, $version, $timePhase, $fromDate, $toDate) {
		
		$currentProduct = $this->common->getCurrentProduct ();
		$toTime = date ( 'Y-m-d', time () );
		$fromTime = date ( 'Y-m-d', strtotime ( "-7 day" ) );
		if ($timePhase == "7day") {
			$fromTime = date ( "Y-m-d", strtotime ( "-7 day" ) );
			$title =lang('errortitle_starterror7days');
		}
		if ($timePhase == "1month") {
			$title =lang('errortitle_starterror30days');
			$fromTime = date ( "Y-m-d", strtotime ( "-30 day" ) );
		
		}
		
		if ($timePhase == "3month") {
			$title = lang('errortitle_starterror3month');
			$fromTime = date ( "Y-m-d", strtotime ( "-90 day" ) );
		
		}
		
		if ($timePhase == "all") {
			$title = lang('errortitle_starterrorall');
			$fromTime = $currentProduct->date;
		
		}
		
		if ($timePhase == "any") {
			$title = lang('errortitle_starterroranytime');
			$fromTime = $fromDate;
			$toTime = $toDate;
		
		}
		
		$fromTime = $this->product->getReportStartDate ( $currentProduct, $fromTime );
		$query = $this->errormodel->geterrorstartbyversion ( $fromTime, $toTime, $product_id, $version );
		$ret ['version'] = $version;
		$ret ["title"] = $title;
		$ret ["content"] = $query->result_array ();
		return $ret;
	}
	
	//更新错误列表信息
	function updaterrorlist() {
		$product = $this->common->getCurrentProduct ();
		$productid = $product->id;
		$isfix = $_POST ['isfix'];
		$devicename = $_POST ['devicename'];
		$query = $this->errormodel->geterrorlist($productid, $isfix, $devicename);
		$this->data ['nonum'] = $query->num_rows ();
		$querylist=$this->errormodel->getpageerrorlist($productid,$isfix,0,$devicename);
		if ($querylist != null && $querylist->num_rows () > 0) {
			if ($isfix == 0) {
				
				$this->data ['errorlistnofix'] = $querylist;
				
			} else {
				$this->data ['errorlistnofix'] = $querylist;				
			}
		}
		$this->data ['isfix'] = $isfix;
		$this->load->view ( 'report/errorlistview', $this->data );
	}
	//标记为已修复或未修复
	function fixerrorinfo() {
        $titlesk=$_POST['titlesk'];			
		$product_sk=$_POST['product_sk'];			
		$titles=$_POST['titles'];								
		$product_version = $_POST ['product_version'];			
		$fix = $_POST ['fix'];
		$product = $this->common->getCurrentProduct ();
		$productid = $product->id;
		if ($product_version == "all") {
			$this->errormodel->markfixallversion($productid,$fix);
		}
		$this->errormodel->markfixerrorinfo($productid, $product_version,$titlesk,$titles,$product_sk, $fix);
		
		$product = $this->common->getCurrentProduct ();
		$productkey = $product->id;
		$query = $this->errormodel->geterrorlist ( $productid );
		if ($query != null && $query->num_rows () > 0) {
			$this->data ['isfix'] = 0;
			$this->data ['errorlistnofix'] = $query;
			$this->data ['nonum'] = $query->num_rows ();
		
		}
		$this->load->view ( 'report/errorlistview', $this->data );
	
	}
	//获得设备名
	function getdevicename() {
		
		$product = $this->common->getCurrentProduct ();
		$productkey = $product->product_key;
		$device = $_POST ['device'];
		$query = $this->errormodel->geterrordevicename ( $device );
		if ($query != null && $query->num_rows () > 0) {
			foreach ( $query->result_array () as $row ) {
				echo '<li>' . $row ['devicebrand_name'] . '</li>';
			}
		
		}
	
	}
	//错误详情
	function detailstacktrace($titlesk, $productsk, $isfix) {
		$this->common->loadHeader ();		
		$query = $this->errormodel->geterrordetail($titlesk,$productsk,$isfix);
		if ($query != null && $query->num_rows () > 0) {		  
			$this->data ['errordetail'] = $query;
			$this->data ['num'] = $query->num_rows ();		
		}
		$this->data['titlesk']=$titlesk;
		$this->data['productsk']=$productsk;
		$this->data['isfix']=$isfix;		
		$this->data ['stacktrace'] = $this->errormodel->getdetailstacktrace ( $productsk, $titlesk, $isfix );
		$this->load->view ('report/errordetails', $this->data );
	}
	//设备分布情况饼图
	function deviceinfo($titlesk, $productsk, $isfix)
	{
				
		$data = $this->errormodel->deviceinfo($titlesk,$productsk,$isfix);		
		echo json_encode ( $data );
	}
	//操作系统分布情况饼图
	function operationinfo($titlesk, $productsk, $isfix)
	{			
		$data = $this->errormodel->operationinfo($titlesk,$productsk,$isfix);			
		echo json_encode ( $data );
	}
}

?>