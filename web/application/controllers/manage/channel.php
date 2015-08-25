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
 * Channel Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Channel extends CI_Controller
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
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->load->library('session');
        $this->load->model('common');
        $this->load->model('channelmodel', 'channel');
        $this->load->model('product/productmodel', 'product');
    }

    /**
     * Index
     *
     * @return void
     */
    function index()
    {
        $userid = $this->common->getUserId();
        $this->data['platform'] = $this->channel->getplatform();
        $this->data['num'] = $this->channel->getdechannelnum($userid);
        $this->data['channel'] = $this->channel->getdechannel($userid);
        $this->data['allsychannel'] = $this->channel->getallsychannel();
        $this->data['isAdmin'] = $this->common->isAdmin();
        $userid = $this->common->getUserId();
        $this->data['guest_roleid'] = $this->common->getUserRoleById($userid);
        $this->common->loadHeader(lang('m_channelManagement'));
        $this->load->view('manage/channelview', $this->data);
    }

    /**
     * Addchannel add custom channel
     *
     * @return bool
     */
    function addchannel()
    {
        $userid = $this->common->getUserId();
        $channel_name = $_POST['channel_name'];
        $platform = $_POST['platform'];
        $isUnique = $this->channel->isUniqueChannel($userid, $channel_name, $platform);
        if (!empty($isUnique)) {
            echo false;
        } else {
            if ($channel_name != '' && $platform != '') {
                
                $this->channel->addchannel($channel_name, $platform, $userid);
                echo true;
            }
        }
    }

    /**
     * Addsychannel add system channel
     *
     * @return bool
     */
    function addsychannel()
    {
        $userid = $this->common->getUserId();
        $channel_name = $_POST['channel_name'];
        $platform = $_POST['platform'];
        $isUnique = $this->channel->isUniqueSystemchannel($channel_name, $platform);
        if (!empty($isUnique)) {
            echo false;
        } else {
            if ($channel_name != '' && $platform != '') {
                $this->channel->addsychannel($channel_name, $platform, $userid);
                echo true;
            }
        }
    }

    /**
     * Editchannel function
     * edit channel
     *
     * @param string $channel_id channel_id
     *            
     * @return void
     */
    function editchannel($channel_id)
    {
        $userid = $this->common->getUserId();
        // $channel_id=$_GET['id'];
        $this->data['platform'] = $this->channel->getplatform();
        $this->data['edit'] = $this->channel->getdechaninfo($userid, $channel_id);
        $this->data['guest_roleid'] = $this->common->getUserRoleById($userid);
        $edit = $this->channel->getdechaninfo($userid, $channel_id);
        $this->common->loadHeader(lang('v_man_pr_editChannel'));
        $this->load->view('manage/channeledit', $this->data);
    }

    /**
     * Modifychannel function
     * modify channel
     *
     * @return bool
     */
    function modifychannel()
    {
        $channel_id = $_POST['channel_id'];
        $channel_name = $_POST['channel_name'];
        $platform = $_POST['platform'];
        $type = $this->channel->getChannelType($channel_id);
        $isUnique = '';
        if ($type == "user") {
            $userid = $this->common->getUserId();
            $isUnique = $this->channel->isUniqueChannel($userid, $channel_name, $platform);
        }
        if ($type == "system") {
            $isUnique = $this->channel->isUniqueSystemchannel($channel_name, $platform);
        }
        if (!empty($isUnique)) {
            echo false;
        } else {
            if ($channel_name != '' && $platform != '') {
                $this->channel->updatechannel($channel_name, $platform, $channel_id);
                echo true;
            }
        }
    }

    /**
     * Deletechannel function
     * delete channel
     *
     * @param string $channel_id channel_id
     *            
     * @return void
     */
    function deletechannel($channel_id)
    {
        // $channel_id=$_GET['id'];
        $this->channel->deletechannel($channel_id);
        $this->index();
    }

    /**
     * Appchannel function
     * app channel
     *
     * @return void
     */
    function appchannel()
    {
        $user_id = $this->common->getUserId();
        $product = $this->common->getCurrentProduct();
        if (!empty($product)) {
            $product_id = $product->id;
            $platform = $this->common->getCurrentProduct()->product_platform;
            // echo $platform;
            $this->data['productkey'] = $this->channel->getproductkey($user_id, $product_id, $platform);
            $this->data['deproductkey'] = $this->channel->getdefineproductkey($user_id, $product_id, $platform);
            $this->data['channel'] = $this->channel->getdefinechannel($user_id, $product_id, $platform);
            $this->data['sychannel'] = $this->channel->getsychannel($user_id, $product_id, $platform);
            $this->common->loadHeader(lang('m_rpt_appChannel'));
            $this->load->view('manage/appchannel', $this->data);
        } else {
            redirect('/auth/login/');
        }
    }

    /**
     * Openchannel function
     * open channel
     *
     * @param string $channel_id channel_id
     *            
     * @return void
     */
    function openchannel($channel_id)
    {
        $user_id = $this->common->getUserId();
        $product = $this->common->getCurrentProduct();
        if (!empty($product)) {
            $product_id = $product->id;
            // $channel_id=$_GET['channelid'];
            $this->product->addproductchannel($user_id, $product_id, $channel_id);
            $this->appchannel();
        } else {
            redirect('/auth/login/');
        }
    }
}
