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
class erroronos extends CI_Controller {
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
		$this->load->view ( 'errors/errorlogonosview');
		//add error list
		$product = $this->common->getCurrentProduct ();
			$productid = $product->id;
		$query = $this->errormodel->getErrorlistOnOs ($productid,$fromTime,$toTime);
		$fixed_error = array();
		$unfixed_error = array();
		if ($query != null && $query->num_rows () > 0) {
			foreach ($query->result() as $row)
			{
				$item = array();
				$item['title'] = $row->title;
				$item['title_sk'] = $row->title_sk;
				$item['deviceos_name'] = $row->deviceos_name;
				$item['time'] = $row->time;
				$item['isfix'] = $row->isfix;
				$item['errorcount'] = $row->errorcount;
				$item['deviceos_sk'] = $row->deviceos_sk;
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
		$this->load->view ( 'errors/erroronoslistview', $this->data );
	}
	/*load errorlogonos report*/
    function adderrorosreport($delete=null,$type=null)
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
    	$this->load->view('widgets/errorlogonos',$this->data);
    }

	//error all  data report
	function geterroralldata() {
		$product = $this->common->getCurrentProduct ();
		$productId = $product->id;
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$result = $this->errormodel->getErrorAllDataOnOs($productId,$fromTime,$toTime);
		echo json_encode($result);
	}



	
	//mark as repair or unrepair
	function changeErrorStatus() {
		$titlesk=$_POST['title_sk'];
		
		$fix = $_POST ['fix'];
		
		$this->errormodel->markfixerrorinfo($productid, $product_version,$titlesk,$titles,$product_sk, $fix);

		$product = $this->common->getCurrentProduct ();
		$productkey = $product->id;
		$query = $this->errormodel->geterrorlist ( $productid );
		if ($query != null && $query->num_rows () > 0) {
			$this->data ['isfix'] = 0;
			$this->data ['errorlistnofix'] = $query;
			$this->data ['nonum'] = $query->num_rows ();
		}
		$this->load->view ( 'errors/errorlistview', $this->data );
	}
	
	//error detail on os
	function detailstacktrace($title_sk,$deviceos_sk) {
		$this->common->loadHeader ();
		$product_id = $this->common->getCurrentProduct();
		$product_id=$product_id->id;
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$query = $this->errormodel->getErrorDetailOnOs($title_sk,$deviceos_sk,$product_id,$from,$to);
		if ($query != null && $query->num_rows () > 0) {
			$this->data ['errordetail'] = $query;
			$this->data ['num'] = $query->num_rows ();
			$this->data ['stacktrace'] = $query->first_row()->stacktrace;
			$this->data ['isfix'] = $query->first_row()->isfix;
		}
		
		$this->data['reportTitle'] = array(
			'errorOnDevice'=> getReportTitle(lang("v_rpt_err_deviceDistributionComment") , $from, $to),
			'errorOnOs'=>  getReportTitle(lang("v_rpt_err_appVersionD") , $from, $to),
		    'timePhase'=>getTimePhaseStr($from, $to)
		);
		$this->data['titlesk']=$title_sk;
		$this->data['deviceos_sk']=$deviceos_sk;
		$this->load->view ('errors/erroronosdetails', $this->data );
	}
	//device distriution pie report on os
	function getDeviceInfoOnOs($titlesk, $deviceos_sk)
	{
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$productid = $this->common->getCurrentProduct()->id;
		$data = $this->errormodel->getDeviceInfoOnOs($titlesk,$productid,$deviceos_sk,$from,$to);
		echo json_encode ( $data );
	}
	//version distribution pie report on os
	function getAppVersionOnOs($titlesk, $deviceos_sk)
	{
		$from = $this->common->getFromTime ();
		$to = $this->common->getToTime ();
		$productid = $this->common->getCurrentProduct()->id;
		$data = $this->errormodel->getAppVersionOnOs($titlesk,$productid,$deviceos_sk,$from,$to);
		echo json_encode ( $data );
	}

}

?>