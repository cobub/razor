<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class Tags extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this -> ci = &get_instance();

        $this -> ci -> load -> config('tank_auth', TRUE);

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
       
   

    function index() {
    	
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
		$this->load->view('tags/tagview',$data);
    }
    
    function addTagWidgets()
    {
    	$this->load->view('widgets/tags');
    }
    
    
    
    function getDeviceidList()
    {
    	
        $productId = $_POST['product_id'];
        $tags = $_POST['tags'];
        $index = $_POST['index'];
        $size = $_POST['size'];
    	$ret = $this->tagmodel->getDeviceidList($productId,$tags,$index,$size);
    	return $ret;
    }
    
    function getUserNumAndPercent($product_id)
    {
    	$ret = $this->tagmodel->getUserNumAndPercent($product_id);
    	return $ret;
    	
    }
    
    function getRegion($productId)
    {
    	$res = $this->tagmodel->getRegion($productId);
    	return $res;
    }
    
    function getProductVersionById($id)
    {
    	$res = $this->tagmodel->getVersionById($id);
    	return $res;
    }
    
    function getProductChannelById($id)
    {
    	$res = $this->tagmodel->getChannelById($id);
    	return $res;
    }
    
    function addTagsGroup()
    {
    	$product_id = $_POST['product_id'];
    	$name = $_POST['name'];
    	$tags = $_POST['tags'];
    	$res = $this->tagmodel->addTagsGroup($product_id,$name,$tags);
    }
    
    function getTagsGroup($productId)
    {
    	$res = $this->tagmodel->getTagsGroup($productId);
    	return $res;
    }
    
    
    function getTagsGroupJson($productId)
    {
    	$res = $this->tagmodel->getTagsGroupJson($productId);
    	return $res;
    }
    
    
}
