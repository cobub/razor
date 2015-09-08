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
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Alert Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Alert extends CI_Controller
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
        $this->load->Model('alert/alertmodel', 'alert');
        $this->load->Model('common');
        $this->common->requireLogin();
        $this->common->requireProduct();
    }

    /**
     * Index function
     *
     * @return void
     */
    function index()
    {
        $this->common->loadHeader();
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $data['alertList'] = $this->alert->getProductAlertByProuctId($productId);
        $this->load->view('manage/productAlert', $data);
    }

    /**
     * EditAlert function
     *
     * @param int    $id        id
     * @param string $condition condition
     *
     * @return void
     */
    function editAlert($id, $condition)
    {
        $this->common->loadHeader();
        $data['alertlist'] = $this->alert->getalertbyid($id, $condition);
        $this->load->view('manage/editalert', $data);
    }

    /**
     * Addalertlab function
     *
     * @return void
     */
    function addalertlab()
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        // echo $productId;
        $exceptionlab = $_POST['exceptionlab'];
        $condition = $_POST['condition'];
        $emalstr = $_POST['emailstr'];
        $isUnique = $this->alert->isUnique($exceptionlab, $condition, $emalstr);
        if (count($isUnique->result_array()) >= 1) {
            echo false;
        } else {
            $this->alert->addlab($exceptionlab, $condition, $emalstr);
            echo true;
        }
    }

    /**
     * DelAlert function
     *
     * @param int    $id        id
     * @param string $condition condition
     *
     * @return void
     */
    function delAlert($id, $condition)
    {
        $this->alert->delalert($id, $condition);
        $this->index();
    }

    /**
     * StartAlert function
     *
     * @param int $id id
     *
     * @return void
     */
    function startAlert($id)
    {
        $this->alert->startEvent($id);
        $this->index();
    }

    /**
     * Resetalertlab function
     *
     * @return void
     */
    function resetalertlab()
    {
        // $product = $this->common->getCurrentProduct();
        $Id = $_POST['id'];
        $exceptionlab = $_POST['exceptionlab'];
        $condition = $_POST['condition'];
        $emails = $_POST['emailstr'];
        $isUnique = $this->alert->isUnique($exceptionlab, $condition, $emails);
        if (count($isUnique->result_array()) >= 1) {
            echo false;
        } else {
            $this->alert->resetalert($Id, $exceptionlab, $condition, $emails);
            echo true;
        }
    }
}
