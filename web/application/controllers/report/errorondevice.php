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
class errorondevice extends CI_Controller {
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
		$this->common->requireProduct();
		$this->load->Model ( 'common' );
	}
	function index() {
		//add header
		$this->common->loadHeaderWithDateControl ();
		//add chart
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();		
		$this->load->view ( 'errors/errorlogondeviceview');
		//add error list
		$product = $this->common->getCurrentProduct ();
		$productid = $product->id;
		$query = $this->errormodel->getErrorlistOnDevice ($productid,$fromTime,$toTime);
		$fixed_error = array();
		$unfixed_error = array();
		if ($query != null && $query->num_rows () > 0) {
			foreach ($query->result() as $row)
			{
				$item = array();
				$item['title'] = $row->title;
				$item['title_sk'] = $row->title_sk;
				$item['devicebrand_name'] = $row->devicebrand_name;
				$item['time'] = $row->time;
				$item['isfix'] = $row->isfix;
				$item['errorcount'] = $row->errorcount;
				$item['devicebrand_sk'] = $row->devicebrand_sk;
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
		$this->load->view ( 'errors/errorondevicelistview', $this->data );
	}

	/*load errorlogonos report*/
	function adderrordevicereport($delete=null,$type=null)
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
		$this->load->view('widgets/errorlogondevice',$this->data);
	}
	//error all  data report
	function geterroralldata() {
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$result = $this->errormodel->getErrorAllDataOnDevice($productId,$fromTime,$toTime);
		echo json_encode($result);
	}

	
	//error detail
	function detailstacktrace($title_sk,$devicebrand_sk) {
		$this->common->loadHeader ();
		$product_id = $this->common->getCurrentProduct();
		$product_id=$product_id->id;
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$query = $this->errormodel->getErrorDetailOnDevice($title_sk,$devicebrand_sk,$product_id,$from,$to);
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
		$this->data['devicebrand_sk']=$devicebrand_sk;
		$this->load->view ('errors/errorondevicedetails', $this->data );
	}
	//app version distribution pie report on device
	function getVersionInfoOnDevice($titlesk, $devicebrand_sk)
	{
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$productid = $this->common->getCurrentProduct()->id;
		$data = $this->errormodel->getVersionInfoOnDevice($titlesk,$productid,$devicebrand_sk,$from,$to);
		echo json_encode ( $data );
	}
	//os distribution pie report on device
	function getOsInfoOnDevice($titlesk, $devicebrand_sk)
	{
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$productid = $this->common->getCurrentProduct()->id;
		$data = $this->errormodel->getOsInfoOnDevice($titlesk,$productid,$devicebrand_sk,$from,$to);
		echo json_encode ( $data );
	}

}

?>