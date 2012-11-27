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
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Alert extends CI_Controller {
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( 'url' );
		$this->load->Model ( 'alert/alertmodel', 'alert' );
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
		$this->common->requireProduct();

	}
	
	function index()
	{
		$this->common->loadHeader();
		$product = $this->common->getCurrentProduct();
		$productId = $product->id;
		$data['alertList'] = $this->alert->getProductAlertByProuctId($productId);
        $this->load->view ( 'manage/productAlert',$data);
	}
	
	function editAlert($lab,$condition)
	{
		$this->common->loadHeader();
	    $data['alertlist'] = $this->alert->getalertbyid($lab,$condition);
	    $this->load->view('manage/editalert',$data);
	}
	
	function addalertlab()
	{
	    
	    $product = $this->common->getCurrentProduct();
	    $productId = $product->id;
	    echo $productId;
	    $exceptionlab  = $_POST['exceptionlab'];
	    $condition = $_POST['condition'];
	    $emalstr = $_POST['emailstr'];
	    $isUnique=$this->alert->isUnique($exceptionlab,$condition);
	    if(count($isUnique->result_array())>=1){
	    	echo false;
	    }else{
	    $this->alert->addlab($exceptionlab,$condition,$emalstr);
	    echo true;
	}
	}
	
	function delAlert($label)
	{
	    $this->alert->delalert($label);
	    $this->index();
	}
	
	function startAlert($id)
	{
	    $this->alert->startEvent($id);
	    $this->index();
	}
	
	function resetalertlab($id)
	{   
		$exceptionlab  = $_POST['exceptionlab'];
		$condition = $_POST['condition'];
	    $this->alert->resetalert($exceptionlab,$condition);
// 	    $this->index();
	}
	
}
