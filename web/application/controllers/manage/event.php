<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Hint Message
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Event Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Event extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('url');
        $this->load->Model('event/userEvent', 'event');
        $this->load->Model('common');
        $this->common->requireLogin();
        $this->common->requireProduct();
    }

    /**
     * Index funtion
     *
     * @return void
     */
    function index()
    {
        $this->common->loadHeader();
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        
        $data['eventList'] = $this->event->getProductEventByProuctId($productId);
        
        $this->load->view('manage/productEvent', $data);
    }

    /**
     * AddEvent function
     * Add event
     *
     * @return boolean
     */
    function addEvent()
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $event_id = $_POST['eventid'];
        $event_name = $_POST['eventname'];
        $isUnique = $this->event->isUnique($productId, $event_id);
        if (!empty($isUnique)) {
            echo false;
        } else {
            $this->event->addEvent($event_id, $event_name);
            echo true;
        }
    }

    /**
     * EditEvent function
     * Edit event
     *
     * @param int $eventId eventId
     *            
     * @return void
     */
    function editEvent($eventId)
    {
        $this->common->loadHeader();
        $data['eventlist'] = $this->event->geteventbyid($eventId);
        $this->load->view('manage/editEvent', $data);
    }

    /**
     * ModifyEvent function
     * Modify event
     *
     * @return boolean
     */
    function modifyEvent()
    {
        $id = $_POST['id'];
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $eventId = $_POST['eventId'];
        $eventName = $_POST['eventName'];
        $isUniqueid = $this->event->isUnique($productId, $eventId);
        if (!empty($isUniqueid)) {
            foreach ($isUniqueid as $row) {
                $currentid = $row->event_id;
            }
            if ($currentid != $id) {
                echo false;
                return;
            }
        }
        
        $isUniqueName = $this->event->isUniqueName($productId, $eventName);
        if (!empty($isUniqueName)) {
            foreach ($isUniqueName as $row) {
                $currentid = $row->event_id;
            }
            if ($currentid != $id) {
                
                echo false;
                return;
            }
        }
        
        $this->event->modifyEvent($id, $eventId, $eventName);
        echo true;
    }

    /**
     * StopEvent function
     * Stop event
     * 
     * @param int $id id
     *
     * @return void
     */
    function stopEvent($id)
    {
        $this->event->stopEvent($id);
        $this->index();
    }

    /**
     * StartEvent function
     * Start event
     * 
     * @param int $id id
     *
     * @return void
     */
    function startEvent($id)
    {
        $this->event->startEvent($id);
        $this->index();
    }

    /**
     * StopEvent function
     * Stop event
     *
     * @param int $id id
     * 
     * @return void
     */
    function resetEvent($id)
    {
        $this->event->resetEvent($id);
        $this->index();
    }
}