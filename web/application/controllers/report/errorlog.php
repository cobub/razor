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
		$this->load->Model ( 'common' );
		$this->load->library ('export');
		$this->common->checkCompareProduct();
	}
	function index() {
		$this->common->requireProduct();
		//add header
		$this->common->loadHeaderWithDateControl ();
		//add chart
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();		
		$this->load->view ('errors/errorlogview');
		//add error list
		$product = $this->common->getCurrentProduct ();
			$productid = $product->id;
		$query = $this->errormodel->geterrorlist ($productid,$fromTime,$toTime);
		$fixed_error = array();
		$unfixed_error = array();
		if ($query != null && $query->num_rows () > 0) {
			foreach ($query->result() as $row)
			{
				$item = array();
				$item['title'] = $row->title;
				$item['title_sk'] = $row->title_sk;
				$item['version_name'] = $row->version_name;
				$item['time'] = $row->time;
				$item['isfix'] = $row->isfix;
				$item['errorcount'] = $row->errorcount;
				if($row->isfix=='1')
				{
					array_push($fixed_error, $item);
				}
				else
				{
					array_push($unfixed_error, $item);
				}
			}
			
		}
		$this->data['fixed_error'] = $fixed_error;
		$this->data['unfixed_error'] = $unfixed_error;
		$this->load->view ( 'errors/errorlistview', $this->data );
	}
	/*compare errorlog product*/
	function compareErrorlog(){
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
				'errorCount'=> getReportTitle(lang("v_rpt_err_errorNums") , $fromTime, $toTime),
				'errorCountPerSession'=>  getReportTitle(lang("v_rpt_err_errorNumsInSessions") , $fromTime, $toTime),
				'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
		$this->common->loadCompareHeader(lang('m_rpt_errors'));
		$this->load->view('compare/error',$this->data);
	}
	//error logs of the compare data
	function compareErrorDetail(){
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$products=$this->common->getCompareProducts();
		if(count($products)==0){echo json_encode('noproducts');return;}
		$results=array();
		for($i=0;$i<count($products);$i++){
			$results[$products[$i]->name]=$this->errormodel->getCompareErrorData($products[$i]->id,$fromTime,$toTime);
		}
		echo json_encode($results);
	}

	/*load errorlog report*/
	function adderrorversionreport($delete=null,$type=null)
	{
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$this->data['reportTitle'] = array(
			'errorCount'=> getReportTitle(lang("v_rpt_err_errorNums") , $fromTime, $toTime),
			'errorCountPerSession'=>  getReportTitle(lang("v_rpt_err_errorNumsInSessions") , $fromTime, $toTime),
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
		$this->load->view('widgets/errorlog',$this->data);
	}
	
	//error all  data report
	function geterroralldata() {
		$this->common->requireProduct();
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$result = $this->errormodel->geterroralldata($productId,$fromTime,$toTime);
		echo json_encode($result);
	}
	
	//error detail
	function detailstacktrace($title_sk,$version_name) {
		$this->common->requireProduct();
		$this->common->loadHeader ();
		$product_id = $this->common->getCurrentProduct();
		$product_id=$product_id->id;
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$query = $this->errormodel->geterrordetail($title_sk,$version_name,$product_id,$from,$to);
		if ($query != null && $query->num_rows () > 0) {
			$this->data ['errordetail'] = $query;
			$this->data ['num'] = $query->num_rows ();
			$this->data ['stacktrace'] = $query->first_row()->stacktrace;
			$this->data ['isfix'] = $query->first_row()->isfix;
		}
		
		$this->data['reportTitle'] = array(
			'errorOnDevice'=> getReportTitle(lang("v_rpt_err_deviceDistributionComment") , $from, $to),
			'errorOnOs'=>  getReportTitle(lang("v_rpt_err_OSDistributionComment") , $from, $to),
		    'timePhase'=>getTimePhaseStr($from, $to)
		);
		$this->data['titlesk']=$title_sk;
		$this->data['version_name']=$version_name;

		//$this->data['isfix']=$isfix;
		$this->load->view ('errors/errordetails', $this->data );
	}
	//device distriution pie report of version
	function getDeviceInfoOfVersion($titlesk, $version_name)
	{
		$this->common->requireProduct();
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$productid = $this->common->getCurrentProduct()->id;
		$data = $this->errormodel->getDeviceInfoOfVersion($titlesk,$productid,$version_name,$from,$to);
		echo json_encode ( $data );
	}
	//os distribution pie report of version
	function getOsOfVersion($titlesk, $version_name)
	{
		$this->common->requireProduct();
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$productid = $this->common->getCurrentProduct()->id;
		$data = $this->errormodel->getOsOfVersion($titlesk,$productid,$version_name,$from,$to);
		echo json_encode ( $data );
	}
	
//mark as repair or unrepair
	function changeErrorStatus() {
		$title_sk=$_POST['title_sk'];
		$fix = $_POST ['fix'];
		$this->errormodel->changeErrorStatus($title_sk, $fix);
	}
	//export the compares error data
	function exportComparedata(){
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$products = $this->common->getCompareProducts();
		if(empty($products)){
			$this->common->requireProduct();
			return;
		}
		$this->load->library ( 'export' );
		$export = new Export ();
		$titlename=getExportReportTitle("Compare",lang("m_rpt_errors"),$fromTime, $toTime);
		$titlename=iconv("UTF-8", "GBK", $titlename);
		$export->setFileName ($titlename);
		$j=0;
		$mk=0;
		$maxlength=0;
		$title[$j++]=iconv("UTF-8", "GBK",'');
		$space[$mk++]=lang('g_date');
		for($i=0;$i<count($products);$i++){
			$detailData[$i]=$this->errormodel->getCompareErrorData($products[$i]->id,$fromTime,$toTime);
			$maxlength=count($detailData[$i]['content']);
			$title[$j++]=iconv("UTF-8", "GBK",$products[$i]->name);
			$title[$j++]=iconv("UTF-8", "GBK",'');
			$space[$mk++]=lang('v_rpt_err_errorNums');
			$space[$mk++]=lang('v_rpt_err_errorNumsInSessions');
		}
		$export->setTitle ($title);
		$export->addRow($space);
		$k=0;
		$j=0;
		for($m=0;$m<$maxlength;$m++){
			$detailcontent=array();
			for($j=0;$j<count($products);$j++){
				$obj=$detailData[$j]['content'];
				if($j==0){
					array_push($detailcontent,$obj[$m]['date']);
				}
				array_push($detailcontent,$obj[$m]['count']);
				array_push($detailcontent,$obj[$m]['percentage']);		
			}
			$export->addRow($detailcontent);
		}
		$export->export ();
		die ();
	}
}

?>