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
 * Activate Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Activate extends CI_Controller
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
        $this->load->language('plugin_getui');
        $this->load->library('form_validation');
        $this->load->Model('common');
        $this->common->requireLogin();
        $this->load->Model('plugin/getui/activatemodel', 'activatemodel');
    }

    /**
     * Index funciton, load view activateview
     *
     * @return void
     */
    function index()
    {
        $appName = $_GET ['appName'];
        $appid = $_GET ['appid'];    
        $this->data ['appName'] = $appName;
        $this->data ['appid'] = $appid;
        $this->common->loadHeader(lang('v_activateApp'));
        $this->load->view('plugin/getui/activateview', $this->data);
    }

    /**
     * ActivateApp funciton, activate app
     *
     * @return void
     */
    function activateApp()
    {
        $userId = $this->common->getUserId();
        $userKeys = $this->activatemodel->getUserKeys($userId);
        $this->data ['userKey'] = $userKeys->user_key;
        $this->data ['userSecret'] = $userKeys->user_secret;
        $this->form_validation->set_rules('packagename', 'PackageName', 'trim|required|xss_clean');
        $appName = $this->input->post("appname");
        $appid = $this->input->post("appid");
        $this->data ['appName'] = $appName;
        $this->data ['appid'] = $appid;
        if ($this->form_validation->run()) {
            $app_identifier = $this->input->post("packagename");
            $this->data ['app_identifier'] = $app_identifier;
            $url_active = SERVER_BASE_URL."/index.php?/api/igetui/register";
            $response = $this->common->curl_post($url_active, $this->data);
            $obj = json_decode($response, true);
            $flag = $obj ['flag'];
            // response infomation can not be null, or activate failure
            if ($flag == -1) {
                $this->data ['msg'] = lang('v_warning1');
                $this->common->loadHeader(lang('v_activateApp'));
                $this->load->view('plugin/getui/activateview', $this->data);
            } else if ($flag == -2) {
                $this->data ['msg'] = lang('v_warning2');
                $this->common->loadHeader(lang('v_activateApp'));
                $this->load->view('plugin/getui/activateview', $this->data);
            } else if ($flag == -3) {
                $this->data ['msg'] = lang('v_warning3');
                $this->common->loadHeader(lang('v_activateApp'));
                $this->load->view('plugin/getui/activateview', $this->data);
            } else if (1 == $flag) {
                $appId = $obj ['appid'];
                $appKey = $obj ['appkey'];
                $appSecret = $obj ['appsecret'];
                $masterSecret = $obj ['mastersecret'];
                $this->responseArray ['flag'] = $flag;
                $this->responseArray ['appId'] = $appId;
                $this->responseArray ['appKey'] = $appKey;
                $this->responseArray ['appSecret'] = $appSecret;
                $this->responseArray ['masterSecret'] = $masterSecret;
                $this->responseArray ['appName'] = $appName;
                $this->responseArray ['app_identifier'] = $app_identifier;
                $this->responseArray ['userId'] = $userId;
                $this->responseArray ['activateDate'] = $obj['createtime'];
                $product_id = $appid;
                $this->responseArray ['productId'] = $product_id;
                $this->responseArray ['appid'] = $product_id;
                if ($this->activatemodel->saveUsersInfo($this->responseArray)) {
                    $this->common->loadHeader(lang('v_keysInfo'));
                    $this->load->view('plugin/getui/activateview', $this->responseArray);
                } else {
                    $this->data ['msg'] = lang('v_warningInfo1');
                    $this->common->loadHeader(lang('v_activateApp'));
                    $this->load->view('plugin/getui/activateview', $this->data);
                }
            } 
        } else {
            $this->data ['msg'] = lang('v_warningInfo4');
            $this->common->loadHeader(lang('v_activateApp'));
            $this->load->view('plugin/getui/activateview', $this->data);
        }
    }

    /**
     * Checkinfo funciton, check information
     *
     * @return void
     */
    function checkInfo()
    {
        $appName = $_GET ['appName'];
        $appid = $_GET ['appid'];
        $this->data = $this->activatemodel->checkInfo($appName, $appid);
        $this->common->loadHeader(lang('v_keysInfo'));
        $this->load->view('plugin/getui/activateview', $this->data);
    }
}
