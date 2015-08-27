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
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Tags Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Tags extends CI_Controller
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this -> ci = &get_instance();
        $this -> ci -> load -> config('tank_auth', true);
        $this -> ci -> load -> library('session');
        $this -> load -> helper(array('form', 'url'));
        $this -> load -> model('tank_auth/users', 'users');
        $this -> load -> library('form_validation');
        $this -> load -> helper('url');
        $this -> load -> Model('common');
        $this -> load -> model('tag/tagmodel', 'tagmodel');
        $this -> canRead = $this -> common -> canRead($this -> router -> fetch_class());
        $this -> common -> requireLogin();
    }

        /**
         * Index funciton, to load tagview
         *
         * @return void
         */
    function index()
    {
        $this->common->loadHeader(lang('tag_head'));
        $productId = $_GET['product_id']!=null?$_GET['product_id']:1;
        $url = $_GET['url'];
        $data['url'] = $url;
        $data['usernum'] = $this->getUserNumAndPercent($productId);
        $data['tagsgroup'] = $this->getTagsGroup($productId);
        $data['tagsgroupjson'] = $this->getTagsGroupJson($productId);
        $data['version']=$this->getProductVersionById($productId);
        $data['channel']=$this->getProductChannelById($productId);
        $data['region'] = $this->getRegion($productId);
        $data['productId'] = $productId;
        $this->load->view('tags/tagview', $data);
    }

    /**
     * Addtagwidgets funciton, to load tags
     *
     * @return void
     */
    function addTagWidgets()
    {
        $this->load->view('widgets/tags');
    }

    /**
     * Getdeviceidlist funciton, get device list
     *
     * @return device list
     */
    function getDeviceidList()
    {
        $productId = $_POST['product_id'];
        $tags = $_POST['tags'];
        $index = $_POST['index'];
        $size = $_POST['size'];
        $ret = $this->tagmodel->getDeviceidList($productId, $tags, $index, $size);
        return $ret;
    }

    /**
     * Getusernumandpercent funciton, get user number and percent
     *
     *@param int $product_id produiuct id
     *
     * @return query ret
     */
    function getUserNumAndPercent($product_id)
    {
        $ret = $this->tagmodel->getUserNumAndPercent($product_id);
        return $ret;
    }

    /**
     * Getregion funciton, get region
     *
     *@param int $productId product id
     * 
     * @return query res
     */
    function getRegion($productId)
    {
        $res = $this->tagmodel->getRegion($productId);
        return $res;
    }

    /**
     * Getproductversionbyid funciton, get product version
     *
     *@param int $id product id
     *
     * @return query res
     */
    function getProductVersionById($id)
    {
        $res = $this->tagmodel->getVersionById($id);
        return $res;
    }

    /**
     * Getproductchannelbyid funciton, get product channel
     *
     *@param int $id product id
     *
     * @return query res
     */
    function getProductChannelById($id)
    {
        $res = $this->tagmodel->getChannelById($id);
        return $res;
    }

    /**
     * Addtagsgroup funciton, add tag group
     *
     * @return void
     */
    function addTagsGroup()
    {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $tags = $_POST['tags'];
        $res = $this->tagmodel->addTagsGroup($product_id, $name, $tags);
    }

    /**
     * Gettaggroup funciton, get tag group
     *
     *@param int $productId product id
     *
     * @return query res
     */
    function getTagsGroup($productId)
    {
        $res = $this->tagmodel->getTagsGroup($productId);
        return $res;
    }

    /**
     * Gettaggroupjson funciton, get tag group json
     *
     *@param int $productId product id
     *
     * @return query res
     */
    function getTagsGroupJson($productId)
    {
        $res = $this->tagmodel->getTagsGroupJson($productId);
        return $res;
    }
}
