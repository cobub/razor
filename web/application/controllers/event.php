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

class Event extends CI_Controller {
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( 'url' );
		$this->load->Model ( 'event/userEvent', 'event' );
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
	    $this->common->loadHeader();
	    $product = $this->common->getCurrentProduct();
	    $this->productId = $product->id;
	    

	}
	
	function index()
	{
		$data['eventList'] = $this->event->getProductEventByProuctId($this->productId);
		
        $this->load->view ( 'event/productEvent',$data);
	}
	
	function addEvent() {

		$event_id = $_POST ['eventid'];
		$event_name = $_POST ['eventname'];
		$this->event->addEvent($event_id,$event_name);
	}
	
	function editEvent($eventId)
	{    
	    $data['eventlist'] = $this->event->geteventbyid($eventId);
	    $this->load->view('event/editEvent',$data);
	}
	
	function modifyEvent()
	{
	    $id = $_POST['id'];
	    $eventId  = $_POST['eventId'];
	    $eventName = $_POST['eventName'];
	    $this->event->modifyEvent($id,$eventId,$eventName);
	}
	
	function stopEvent($id)
	{
	    $this->event->stopEvent($id);
	    $this->index();
	}
	
	function startEvent($id)
	{
	    $this->event->startEvent($id);
	    $this->index();
	}
	
	function resetEvent($id)
	{   
	    $this->event->resetEvent($id);
	    $this->index();
	}
	

}