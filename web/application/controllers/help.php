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
 * Help Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Help extends CI_Controller
{
    /**
     * Construct funciton, to pre-load lang,model and helper configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->lang->load('allview');
        $this->load->model('common');
        $this->load->helper('cookie');
    }

    /**
     * Index funciton, to load header and helperview
     *
     * @return void
     */
    function index()
    {
        $this->loadHeader();
        $this->load->view('helper/helpview');
    }

    /**
     *Loade header funciton, to load haeader
     *
     * @return void
     */
    function loadHeader()
    {
        if (! $this->common->isUserLogin()) {
            $dataheader ['login'] = false;
            $this->load->view('helper/header', $dataheader);
        } else {
            $dataheader ['user_id'] = $this->common->getUserId();
            $dataheader ['pageTitle'] = $this->common->getPageTitle($this->router->fetch_class());
            if ($this->common->isAdmin()) {
                $dataheader ['admin'] = true;
            }
            $dataheader ['login'] = true;
            $dataheader ['username'] = $this->common->getUserName();
            log_message("error", "Load Header 123");
            $dataheader ['language'] = $this->config->item('language');
            $this->load->view('helper/header', $dataheader);
        }
    }
}