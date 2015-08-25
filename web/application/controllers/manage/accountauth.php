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
 * Channel Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Accountauth extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
        
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->load->library('session');
        $this->load->model('common');
        $this->load->model('pluginlistmodel');
    }

    /**
     * Index
     *
     * @return void
     */
    function index ()
    {
        $userId = $this->common->getUserId();
        // user role
        $this->data['guest_roleid'] = $this->common->getUserRoleById($userId);
        $userKeys = $this->pluginlistmodel->getUserKeys($userId);
        $plugins = array();
        if ($userKeys) {
            $this->data['puserkey'] = $userKeys->user_key;
            $this->data['pusersecret'] = $userKeys->user_secret;
            $this->data['succesmsg'] = lang('plg_keysecret_error');
            $this->common->loadHeader(lang('v_plugins_account'));
            $this->load->view('manage/accountauthview', $this->data);
        } else {
            $this->common->loadHeader(lang('v_plugins_account'));
            $this->load->view('manage/accountauthview', $this->data);
        }
    }

    /**
     * SaveUserKeys ave user's key&secret in razor_ table
     *
     * @return void
     */
    function saveUserKeys ()
    {
        $this->form_validation->set_rules('userkey', 'UserKey', 'trim|required|xss_clean');
        $this->form_validation->set_rules('usersecret', 'UserSecret', 'trim|required|xss_clean');
        if ($this->form_validation->run()) {
            $userKey = $this->input->post("userkey");
            $userSecret = $this->input->post("usersecret");
            if ($this->pluginlistmodel->verifyUserKeys($userKey, $userSecret)) {
                $userId = $this->common->getUserId();
                $this->pluginlistmodel->saveUserKeys($userId, $userKey, $userSecret);
                // redirect ( site_url () . "/manage/accountauth",$this->data );
                $this->data['puserkey'] = $userKey;
                $this->data['pusersecret'] = $userSecret;
                $this->data['successmsg'] = lang('plg_keysecret_success');
                $this->common->loadHeader(lang('v_plugins_account'));
                $this->load->view('manage/accountauthview', $this->data);
            } else {
                $this->data['msg'] = lang('plg_keysecret_error');
                $this->common->loadHeader(lang('v_plugins_account'));
                $this->load->view('manage/accountauthview', $this->data);
            }
        } else {
            $this->data['msg'] = lang('plg_keysecret_error');
            $this->common->loadHeader(lang('v_plugins_account'));
            $this->load->view('manage/accountauthview', $this->data);
        }
    }
}
