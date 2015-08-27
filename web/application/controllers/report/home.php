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
 * Home Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Home extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->model('pluginm');
        $this->common->requireLogin();
        $this->load->model('pluginlistmodel');
    }

    /**
     * Index fuction
     *
     * @return void
     */
    function index()
    {
        $this->common->cleanCurrentProduct();
        $this->common->loadHeader();
        $userId = $this->common->getUserId();
        $userKeys = $this->pluginlistmodel->getUserKeys($userId);
        if ($userKeys) {
            $key = $userKeys->user_key;
            $secret = $userKeys->user_secret;
            
            $this->data['key'] = $key;
            $this->data['secret'] = $secret;
            
            $this->load->view('home', $this->data);
        } else {
            $this->data['msg'] = lang('plg_get_keysecret_home');
            $this->load->view('home', $this->data);
        }
    }
}
