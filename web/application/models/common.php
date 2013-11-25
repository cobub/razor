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
class Common extends CI_Model {
    function __construct() {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('url');
        $this -> load -> library('tank_auth');
        $this -> load -> library('ums_acl');
        $this -> load -> library('export');
        $this->load->model('pluginm');
        $this -> load -> database();
    }

    function getdbprefixtable($name) {
        $tablename = $this -> db -> dbprefix($name);
        return $tablename;
    }

    function getMaxY($count) {
        if ($count <= 5) {
            return 5;
        } else {
            return $count + $count / 10;
        }
    }

    function getTimeTick($days) {
        if ($days <= 7) {
            return 1;
        }

        if ($days > 7 && $days <= 30) {
            return 2;
        }

        if ($days > 30 && $days <= 90) {
            return 10;
        }

        if ($days > 90 && $days <= 270) {
            return 30;
        }

        if ($days > 270 && $days <= 720) {
            return 70;
        }
        return 1;
    }

    function getStepY($count) {
        if ($count <= 5) {
            return 1;
        } else {
            return round($count / 5, 0);
        }
    }

    function loadCompareHeader($viewname = "", $showDate = TRUE) {
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> helper('cookie');
        if (!$this -> common -> isUserLogin()) {
            $dataheader['login'] = false;
            $this -> load -> view('layout/compareHeader', $dataheader);
        } else {
            $dataheader['user_id'] = $this -> getUserId();
            $dataheader['pageTitle'] = lang("c_" . $this -> router -> fetch_class());
            if ($this -> isAdmin()) {
                $dataheader['admin'] = true;
            }
            $dataheader['login'] = true;
            $dataheader['username'] = $this -> getUserName();
            $product = $this -> getCurrentProduct();
            if (isset($product) && $product != null) {
                $dataheader['product'] = $product;
                log_message("error", "HAS Product");
            }

            $productList = $this -> product -> getAllProducts($dataheader['user_id']);
            if ($productList != null && $productList -> num_rows() > 0) {
                $productInfo = array();
                $userId = $this -> getUserId();
                foreach ($productList->result() as $row) {
                    if (!$this -> product -> isAdmin() && !$this -> product -> isUserHasProductPermission($userId, $row -> id)) {
                        continue;
                    }
                    $productObj = array('id' => $row -> id, 'name' => $row -> name);
                    array_push($productInfo, $productObj);
                }
                if (count($productInfo) > 0) {
                    $dataheader["productList"] = $productInfo;
                }
            }
            $products = $this -> getCompareProducts();
            $dataheader['products'] = $products;
            log_message("error", "Load Header 123");
            $dataheader['language'] = $this -> config -> item('language');
            $dataheader['viewname'] = $viewname;
            if ($showDate) {
                $dataheader["showDate"] = true;
            }
            $this -> load -> view('layout/compareHeader', $dataheader);
        }
    }

    function checkCompareProduct() {
        $products = $this -> common -> getCompareProducts();
        if (isset($_GET['type']) && 'compare' == $_GET['type']) {
            if (empty($products) || count($products) < 2 || count($products) > 4) {
                redirect(base_url());
            }
        } else {
        }
    }

    function loadHeaderWithDateControl($viewname = "") {
        $this -> loadHeader($viewname, TRUE);
    }

    function loadHeader($viewname = "", $showDate = FALSE) {
        $this -> load -> model('interface/getnewversioninfo', 'getnewversioninfo');
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> model('pluginlistmodel','plugin');
        $this -> load -> helper('cookie');
        if (!$this -> common -> isUserLogin()) {

            $dataheader['login'] = false;
            $version = $this -> config -> item('version');
            $versiondata = $this -> getnewversioninfo -> newversioninfo($version);
            if ($versiondata) {
                $dataheader['versionvalue'] = $versiondata['version'];
                $dataheader['version'] = $versiondata['updateurl'];
            }
            $inform = $this -> session -> userdata('newversion');
            if ($inform == "noinform") {
                $dataheader['versioninform'] = $inform;
            }
            $this -> load -> view('layout/header', $dataheader);
        } else {

            $dataheader['user_id'] = $this -> getUserId();
            $dataheader['pageTitle'] = lang("c_" . $this -> router -> fetch_class());
            if ($this -> isAdmin()) {
                $dataheader['admin'] = true;
            }
            $dataheader['login'] = true;
            $dataheader['username'] = $this -> getUserName();
            $product = $this -> getCurrentProduct();
            if (isset($product) && $product != null) {
                $dataheader['product'] = $product;
                log_message("error", "HAS Product");
            }

            $productList = $this -> product -> getAllProducts($dataheader['user_id']);
            if ($productList != null && $productList -> num_rows() > 0) {
                $productInfo = array();
                $userId = $this -> getUserId();
                foreach ($productList->result() as $row) {
                    if (!$this -> product -> isAdmin() && !$this -> product -> isUserHasProductPermission($userId, $row -> id)) {
                        continue;
                    }
                    $productObj = array('id' => $row -> id, 'name' => $row -> name);
                    array_push($productInfo, $productObj);
                }
                if (count($productInfo) > 0) {
                    $dataheader["productList"] = $productInfo;
                }
            }
            log_message("error", "Load Header 123");
            $dataheader['language'] = $this -> config -> item('language');
            $dataheader['viewname'] = $viewname;
            if ($showDate) {
                $dataheader["showDate"] = true;
            }
            $version = $this -> config -> item('version');
            $versiondata = $this -> getnewversioninfo -> newversioninfo($version);
            if ($versiondata) {
                $dataheader['versionvalue'] = $versiondata['version'];
                $dataheader['version'] = $versiondata['updateurl'];
            }
            $dataheader['versionvalue'] = $versiondata['version'];
            $dataheader['version'] = $versiondata['updateurl'];
            $inform = $this -> session -> userdata('newversion');
            if ($inform == "noinform") {
                $dataheader['versioninform'] = $inform;
            }
           
            
            $this->load->model ( 'pluginlistmodel' );
            	
            $userId = $this -> getUserId();
            $userKeys = $this->pluginlistmodel->getUserKeys ( $userId );
            if ($userKeys) {
            	$dataheader['key'] = $userKeys->user_key;
            	$dataheader['secret'] = $userKeys->user_secret;
            }

            $this -> load -> view('layout/header', $dataheader);
        }
    }

    

    function show_message($message) {
        $this -> session -> set_userdata('message', $message);
        redirect('/auth/');
    }

    function requireLogin() {
        if (!$this -> tank_auth -> is_logged_in()) {
            redirect('/auth/login/');
        }
    }

    function requireProduct() {
        $product = $this -> getCurrentProduct();
        if (empty($product)) {
            redirect(site_url());
        } else {
            $userId = $this -> getUserId();
            $productId = $product -> id;
            $this -> checkUserPermissionToProduct($userId, $productId);
        }
    }

    function checkUserPermissionToProduct($userId, $productId) {
        $this -> load -> model('product/productmodel', 'product');
        if (!$this -> isAdmin() && !$this -> product -> isUserHasProductPermission($userId, $productId)) {
            $this -> session -> set_userdata('message', "You don't have permission to view this product.");
            redirect(site_url());
        }
    }

    function isAdmin() {
        $userid = $this -> tank_auth -> get_user_id();
        $role = $this -> getUserRoleById($userid);
        if ($role == 3) {
            return true;
        }
        return false;
    }

    function getUserId() {
        return $this -> tank_auth -> get_user_id();
    }

    function getUserName() {
        return $this -> tank_auth -> get_username();
    }

    function isUserLogin() {
        return $this -> tank_auth -> is_logged_in();
    }

    function canRead($controllerName) {
        $id = $this -> getResourceIdByControllerName($controllerName);
        if ($id) {
            $acl = new Ums_acl();
            $userid = $this -> tank_auth -> get_user_id();
            $role = $this -> getUserRoleById($userid);
            if ($acl -> can_read($role, $id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getPageTitle($controllerName) {
        $this -> db -> where('name', $controllerName);
        $query = $this -> db -> get('user_resources');
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> description;
        }
        return "";
    }

    // private functiosn
    function getResourceIdByControllerName($controllerName) {
        $this -> db -> where('name', $controllerName);
        $query = $this -> db -> get('user_resources');
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> id;
        }
        return null;
    }

    function getUserRoleById($id) {
        if ($id == '')
            return '2';
        $this -> db -> select('roleid');
        $this -> db -> where('userid', $id);
        $query = $this -> db -> get('user2role');
        $row = $query -> first_row();
        if ($query -> num_rows > 0) {
            return $row -> roleid;
        } else
            return '2';
    }

    function getCurrentProduct() {
        $currentProduct = $this -> session -> userdata("currentProduct");
        if ($currentProduct) {
            $userId = $this -> getUserId();
            $productId = $currentProduct -> id;
            $this -> checkUserPermissionToProduct($userId, $productId);
        }
        return $currentProduct;
    }

    function getCurrentProductIfExist() {
        $currentProduct = $this -> session -> userdata("currentProduct");
        if (isset($currentProduct)) {
            return $currentProduct;
        } else {
            return false;
        }
    }

    function cleanCurrentProduct() {
        $this -> session -> unset_userdata("currentProduct");
    }

    function setCurrentProduct($productId) {
        $this -> load -> model('product/productmodel', 'product');
        $currentProduct = $this -> product -> getProductById($productId);
        $this -> session -> set_userdata("currentProduct", $currentProduct);
    }

    function setCompareProducts($productIds = array()) {
        $this -> session -> set_userdata('compareProducts', $productIds);
    }

    function getCompareProducts() {
        $this -> load -> model('product/productmodel', 'product');
        $pids = $this -> session -> userdata("compareProducts");
        $products = array();
        for ($i = 0; $i < count($pids); $i++) {
            $product = $this -> product -> getProductById($pids[$i]);
            $products[$i] = $product;
        }
        return $products;
    }

    function changeTimeSegment($pase, $from, $to) {
        $this -> load -> model('product/productmodel', 'product');
        $toTime = date('Y-m-d', time());
        switch ($pase) {
            case "7day" :
                {
                    $fromTime = date("Y-m-d", strtotime("-6 day"));
                }
                break;
            case "1month" :
                {
                    $fromTime = date("Y-m-d", strtotime("-31 day"));
                }
                break;
            case "3month" :
                {
                    $fromTime = date("Y-m-d", strtotime("-92 day"));
                }
                break;
            case "all" :
                {
                    $currentProduct = $this -> getCurrentProductIfExist();
                    if ($currentProduct) {
                        $fromTime = $this -> product -> getReportStartDate($currentProduct, '1970-02-01');
                        $fromTime = date("Y-m-d", strtotime($fromTime));
                    } else {
                        $fromTime = $this -> product -> getUserStartDate($this -> getUserId(), '1970-01-01');
                        $fromTime = date("Y-m-d", strtotime($fromTime));
                    }
                }
                break;
            case "any" :
                {
                    $fromTime = $from;
                    $toTime = $to;
                }
                break;
            default :
                {
                    $fromTime = date("Y-m-d", strtotime("-6 day"));
                }
                break;
        }
        if ($fromTime > $toTime) {
            $tmp = $toTime;
            $toTime = $fromTime;
            $fromTime = $tmp;
        }
        $this -> session -> set_userdata('fromTime', $fromTime);
        $this -> session -> set_userdata('toTime', $toTime);
    }

    function getFromTime() {
        $fromTime = $this -> session -> userdata("fromTime");
        if (isset($fromTime) && $fromTime != null && $fromTime != "") {
            return $fromTime;
        }
        $fromTime = date("Y-m-d", strtotime("-6 day"));
        return $fromTime;
    }

    function getPredictiveValurFromTime() {
        $time = $this -> getFromTime();
        $fromTime = date("Y-m-d", strtotime("$time -5 day"));
        return $fromTime;
    }

    function getToTime() {
        $toTime = $this -> session -> userdata("toTime");
        if (isset($toTime) && $toTime != null && $toTime != "") {
            return $toTime;
        }
        $toTime = date('Y-m-d', time());
        return $toTime;
    }

    function getDateList($from, $to) {
        $defdate = array();
        for ($i = strtotime($from); $i <= strtotime($to); $i += 86400) {
            if (!in_array(date('Y-m-d', $i), $defdate))
                array_push($defdate, date('Y-m-d', $i));
        }
        return $defdate;
    }

    function export($from, $to, $data) {
        $productId = $this -> getCurrentProduct() -> id;
        $productName = $this -> getCurrentProduct() -> name;

        $export = new Export();

        $export -> setFileName($productName . '_' . $from . '_' . $to . '.xls');

        $fields = array();
        foreach ($data->list_fields () as $field) {
            array_push($fields, $field);
        }
        $export -> setTitle($fields);

        foreach ($data->result () as $row)
            $export -> addRow($row);
        $export -> export();
        die();
    }
    
    function curl_post($url, $vars) {
    	$ch = curl_init();
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_POST, 1 );
    	curl_setopt($ch, CURLOPT_HEADER, 0 ) ;
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    	$response = curl_exec($ch);
    	curl_close($ch);
    	
    	if ($response)
    	{
    		return $response;
    	}
    	else
    	{
    		return false;
    	}
    }

}
