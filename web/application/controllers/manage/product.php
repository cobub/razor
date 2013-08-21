<?php
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
class Product extends CI_Controller {
    private $data = array();

    function __construct() {
        parent::__construct();
        $this -> load -> helper(array('form', 'url'));
        $this -> load -> library('form_validation');
        $this -> load -> Model('common');
        $this -> load -> Model('user/ums_user');
        $this -> load -> model('channelmodel', 'channel');
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> model('product/productanalyzemodel');
        $this -> load -> model('product/newusermodel', 'newusermodel');
        $this -> common -> requireLogin();

    }

    function changeProduct($productId) {
        $this -> common -> cleanCurrentProduct();
        $this -> common -> setCurrentProduct($productId);
        $ret = array();
        $ret["msg"] = "ok";
        echo json_encode($ret);
    }

    //create product
    function create() {
        $this -> canRead = $this -> common -> canRead($this -> router -> fetch_class());
        if ($this -> canRead) {
            $this -> common -> loadHeader(lang('m_new_app'));
            $this -> data['platform'] = $this -> channel -> getplatform();
            $this -> data['category'] = $this -> ums_user -> getproductCategories();
            $this -> load -> view('manage/createproduct', $this -> data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }

    function uploadchannel() {
        $platform = $_POST['platform'];
        $channel = $this -> channel -> getchanbyplat($platform);
        echo json_encode($channel);

    }

    //save product
    function saveApp() {
        $this -> common -> loadHeader();
        $tablename = $this -> common -> getdbprefixtable('product');
        $this -> form_validation -> set_rules('appname', lang('v_man_au_appName'), 'trim|required|xss_clean|is_unique[' . $tablename . '.name' . ']');
        // 		$this->form_validation->set_rules('description', lang('createproduct_descriptionlbl'), 'trim|required|xss_clean');
        $this -> form_validation -> set_rules('platform', lang('v_platform'), 'trim|required|xss_clean');
        $this -> form_validation -> set_rules('channel', lang('v_man_ch_name'), 'trim|required|xss_clean');
        $this -> form_validation -> set_rules('category', lang('m_appType'), 'trim|required|xss_clean');
        if ($this -> form_validation -> run()) {
            $userId = $this -> common -> getUserId();
            $appname = $this -> input -> post('appname');
            $channel = $this -> input -> post('channel');
            $platform = $this -> input -> post('platform');
            $category = $this -> input -> post('category');
            $description = $this -> input -> post('description');
            $key = $this -> product -> addProduct($userId, $appname, $channel, $platform, $category, $description);
            $this -> common -> show_message(lang('v_man_au_addSuccess') . ",AppKey:$key," . anchor('/report/console', lang('v_man_pr_submitSuccessReturn')));
            
        } else {
            $this -> data['platform'] = $this -> channel -> getplatform();
            $this -> data['category'] = $this -> product -> getProductCategory();
            $this -> data['selectplatform'] = $this -> input -> post('platform');
            $this -> data['selectchannel'] = $this -> input -> post('channel');
            $this -> data['selectcategory'] = $this -> input -> post('category');
            $this -> data['description'] = $this -> input -> post('description');
            $this -> load -> view('manage/createproduct', $this -> data); 
        }
    }

    //edit product
    function editproduct() {
        $this -> common -> loadHeader(lang('v_man_pr_editApp'));
        $product = $this -> common -> getCurrentProduct();
        if (!empty($product)) {
            $this -> data['product'] = $this -> product -> getproductinfo($product -> id);
            $this -> data['category'] = $this -> product -> getProductCategory();

            $this -> load -> view('manage/editproduct', $this -> data);
        } else {
            redirect('/auth/login/');
        }
    }

    //save edit
    function saveedit($product_id) {
        $this -> common -> loadHeader();
        $tablename = $this -> common -> getdbprefixtable('product');
        $product = $this -> common -> getCurrentProduct();
        if (!empty($product)) {
            $productkey = $product -> product_key;
            $appname = $this -> input -> post('appname');
            $productname = $product -> name;
            if ($productname == $appname) {
                $this -> form_validation -> set_rules('appname', lang('v_man_au_appName'), 'trim|required|xss_clean');
            } else {
                $this -> form_validation -> set_rules('appname', lang('v_man_au_appName'), 'trim|required|xss_clean|is_unique[' . $tablename . '.name' . ']');
            }
            //$this->form_validation->set_rules('description', lang('createproduct_descriptionlbl'), 'trim|required|xss_clean|min_length[10]');
            if ($this -> form_validation -> run()) {
                $appname = $this -> input -> post('appname');
                $category = $this -> input -> post('category');
                $description = $this -> input -> post('description');
                $this -> product -> updateproduct($appname, $category, $description, $product_id, $productkey);
                $this -> common -> show_message(lang('v_man_au_editSuccess') . anchor('/report/console', lang('v_man_pr_submitSuccessReturn')));
            } else {
                $this -> data['product'] = $this -> product -> getproductinfo($product_id);
                $this -> data['selectcategory'] = $this -> input -> post('category');
                $this -> data['category'] = $this -> product -> getProductCategory();
                $this -> load -> view('manage/editproduct', $this -> data);
            }
        } else {
            redirect('/auth/login/');
        }
    }

    function delete($productId) {
        $userId = $this -> common -> getUserId();
        $data['productId'] = $productId;
        $flag = $this -> product -> deleteProduct($productId, $userId);
        if ($flag > 0) {
            $this -> data["message"] = lang('v_man_au_failDelete');
        } else {
            $this -> data["message"] = lang('v_man_au_failDelete');
        }
        redirect('/report/console');
    }

}
