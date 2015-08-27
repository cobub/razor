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
 * Getuicl Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class getuicl extends CI_Controller
{
    private $data = array ();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->language('plugin_getui');
        $this->load->Model('common');
        $this->load->model('plugin/getui/applistmodel', 'plugina');
        $this->common->requireLogin();
    }

    /**
     * Index funciton, load view pushnote
     *
     * @return void
     */
    function index()
    {
        $productid=$_POST['product_id'];
        $tagtype = $_POST['tag_type'];
        $tag =$_POST['tag_data'];
        $appinfo = $this->plugina->getappinfo($productid);
        $productname = $this->plugina->getProductName($productid);
        $uid=$this->common->getUserId();
        $userinfo = $this->plugina->getUserinfo($uid);
        $userKey = $userinfo[0]['user_key'];
        $userSecret = $userinfo[0]['user_secret'];
        $appid = $appinfo[0]['app_id'];
        $appname =$productname;
        $appkey = $appinfo[0]['app_key'];
        $mastersecret =$appinfo[0]['app_mastersecret'];
        $producrid= $this->plugina->getproductid($appid);
        // $this->common->cleanCurrentProduct ();
        $this->data['productid']=$producrid;
        $this->data ['appname'] = $appname;
        $this->data ['appid'] = $appid;
        $this->data ['userSecret'] = $userSecret;
        $this->data ['userKey'] = $userKey;
        $this->data ['appkey'] = $appkey;
        $this->data ['mastersecret'] = $mastersecret;
        $this->data['tagvalue']=$tag;
        $this->data['tagtype']=0;
        if ($tagtype=='all') {
            $this->data['tagtype']=1;
        }
        $this->common->loadHeader(lang('getui'));
        $this->load->view('plugin/getui/pushnote', $this->data);
    }


    /**
     * Transmission funciton, load view transmission
     *
     * @return void
     */
    function transmission()
    {
        // get   userkey userSecret appid appname appkey
        $productid=$_POST['product_id'];
        $tagtype = $_POST['tag_type'];//all  
        $tag =$_POST['tag_data'];
         $appinfo = $this->plugina->getappinfo($productid);
        $productname = $this->plugina->getProductName($productid);
        $uid=$this->common->getUserId();
        $userinfo = $this->plugina->getUserinfo($uid);
        $userKey = $userinfo[0]['user_key'];
        $userSecret = $userinfo[0]['user_secret'];
        $appid = $appinfo[0]['app_id'];
        $appname =$productname;
        $appkey = $appinfo[0]['app_key'];
        $mastersecret =$appinfo[0]['app_mastersecret'];
        $producrid= $this->plugina->getproductid($appid);
        $this->data['tagtype']=false;
        if ($tagtype=='all') {
            $this->data['tagtype']=true;
        }
        // $this->common->cleanCurrentProduct ();
        $this->data['productid']=$producrid;
        $this->data['tagvalue']=$tag;
        $this->data ['appname'] = $appname;
        $this->data ['appid'] = $appid;
        $this->data ['userSecret'] = $userSecret;
        $this->data ['userKey'] = $userKey;
        $this->data ['appkey'] = $appkey;
        $this->data ['mastersecret'] = $mastersecret;
        $this->common->loadHeader(lang('getui'));
        $this->load->view('plugin/getui/transmission', $this->data);
    }
}

?>