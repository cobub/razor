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
 * Onlineconfig Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Onlineconfig extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('onlineconfig/models_onlineconfig', 'models_onlineconfig');
        $this->load->model('common');
        $this->common->requireLogin();
        $this->common->requireProduct();
        $this->common->loadHeader();
        $this->canRead = $this->common->canRead($this->router->fetch_class());
    }

    /**
     * Index funtion
     *
     * @return void
     */
    function index()
    {
        $product = $this->common->getCurrentProduct();
        if (!empty($product)) {
            $id = $this->productId = $product->id;
            $data['onlineconfigList'] = $this->models_onlineconfig->getOnlineConfigByProuctId($id);
            
            $this->load->view('manage/view_onlineconfig', $data);
        } else {
            redirect('/auth/login/');
        }
    }

    /**
     * Modifyonlineconfig function
     * Modify online config
     *
     * @return void
     */
    function modifyonlineconfig()
    {
        $this->form_validation->set_rules('autogetlocation', 'autogetlocation', 'trim');
        $this->form_validation->set_rules('updateonlywifi', 'updateonlywifi', 'trim');
        $this->form_validation->set_rules('sessionmillis', 'time interval', 'trim|less_than[300]|is_natural_no_zero|required');
        // $this->form_validation->set_rules('sessionmillis', 'sessionmillis','trim');
        $this->form_validation->set_rules('reportpolicy', 'reportpolicy', 'trim');
        if ($this->form_validation->run()) {
            // $userId = $this->common->getUserId();
            $product = $this->common->getCurrentProduct();
            $id = $this->productId = $product->id;
            $autogetlocation = $this->input->post('autogetlocation');
            $updateonlywifi = $this->input->post('updateonlywifi');
            $reportpolicy = $this->input->post('reportPolicy');
            $sessionmillis = $this->input->post('sessionmillis');
            $this->models_onlineconfig->modifyonlineconfig($id, $autogetlocation, $updateonlywifi, $sessionmillis, $reportpolicy);
            $this->common->show_message(lang('v_man_oc_configSuccess'));
        } else {
            $this->load->view('manage/view_onlineconfig');
        }
    }
}