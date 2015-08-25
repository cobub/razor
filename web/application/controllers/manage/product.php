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
 * Product Controller
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Product extends CI_Controller
{

    /**
     * Data array $data
     */
    private $_data = array();

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
        $this->load->Model('common');
        $this->load->Model('user/ums_user');
        $this->load->model('channelmodel', 'channel');
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/productanalyzemodel');
        $this->load->model('product/newusermodel', 'newusermodel');
        $this->common->requireLogin();
    }

    /**
     * ChangeProduct function
     * Change product
     *
     * @param string $productId productId
     *            
     * @return json
     */
    function changeProduct($productId)
    {
        $this->common->cleanCurrentProduct();
        $this->common->setCurrentProduct($productId);
        $ret = array();
        $ret["msg"] = "ok";
        echo json_encode($ret);
    }

    /**
     * Create function
     * Create product
     *
     * @return void
     */
    function create()
    {
        $this->canRead = $this->common->canRead($this->router->fetch_class());
        if ($this->canRead) {
            $this->common->loadHeader(lang('m_new_app'));
            $this->_data['platform'] = $this->channel->getplatform();
            $this->_data['category'] = $this->ums_user->getproductCategories();
            $this->_data['guest_roleid'] = $this->common->getUserRoleById($this->common->getUserId());
            $this->load->view('manage/createproduct', $this->_data);
        } else {
            $this->common->loadHeader();
            $this->load->view('forbidden');
        }
    }

    /**
     * Uploadchannel function
     * Upload channel
     *
     * @return json
     */
    function uploadchannel()
    {
        $platform = $_POST['platform'];
        $channel = $this->channel->getchanbyplat($platform);
        echo json_encode($channel);
    }

    /**
     * SaveApp function
     * Save App
     *
     * @return json
     */
    function saveApp()
    {
        $this->common->loadHeader();
        $tablename = $this->common->getdbprefixtable('product');
        $this->form_validation->set_rules('appname', lang('v_man_au_appName'), 'trim|required|xss_clean|is_unique[' . $tablename . '.name' . ']');
        // $this->form_validation->set_rules('description',
        // lang('createproduct_descriptionlbl'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('platform', lang('v_platform'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('channel', lang('v_man_ch_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('category', lang('m_appType'), 'trim|required|xss_clean');
        $userId = $this->common->getUserId();
        if ($this->form_validation->run()) {
            $appname = $this->input->post('appname');
            $channel = $this->input->post('channel');
            $platform = $this->input->post('platform');
            $category = $this->input->post('category');
            $description = $this->input->post('description');
            $key = $this->product->addProduct($userId, $appname, $channel, $platform, $category, $description);
            $this->common->show_message(lang('v_man_au_addSuccess') . ",AppKey:$key," .anchor('/report/console', lang('v_man_pr_submitSuccessReturn')));
        } else {
            $this->_data['guest_roleid'] = $this->common->getUserRoleById($userId);
            $this->_data['platform'] = $this->channel->getplatform();
            $this->_data['category'] = $this->product->getProductCategory();
            $this->_data['selectplatform'] = $this->input->post('platform');
            $this->_data['selectchannel'] = $this->input->post('channel');
            $this->_data['selectcategory'] = $this->input->post('category');
            $this->_data['description'] = $this->input->post('description');
            $this->load->view('manage/createproduct', $this->_data);
        }
    }

    /**
     * Editproduct function
     * Edit product
     *
     * @return void
     */
    function editproduct()
    {
        $this->common->loadHeader(lang('v_man_pr_editApp'));
        $product = $this->common->getCurrentProduct();
        if (! empty($product)) {
            $this->_data['product'] = $this->product->getproductinfo($product->id);
            $this->_data['category'] = $this->product->getProductCategory();
            
            $this->load->view('manage/editproduct', $this->_data);
        } else {
            redirect('/auth/login/');
        }
    }

    /**
     * Saveedit function
     * Save edit
     *
     * @param string $product_id product_id
     *
     * @return void
     */
    function saveedit($product_id)
    {
        $this->common->loadHeader();
        $tablename = $this->common->getdbprefixtable('product');
        $product = $this->common->getCurrentProduct();
        if (! empty($product)) {
            $productkey = $product->product_key;
            $appname = $this->input->post('appname');
            $productname = $product->name;
            if ($productname == $appname) {
                $this->form_validation->set_rules('appname', lang('v_man_au_appName'), 'trim|required|xss_clean');
            } else {
                $this->form_validation->set_rules('appname', lang('v_man_au_appName'), 'trim|required|xss_clean|is_unique[' . $tablename .'.name' . ']');
            }
            // $this->form_validation->set_rules('description',
            // lang('createproduct_descriptionlbl'),
            // 'trim|required|xss_clean|min_length[10]');
            if ($this->form_validation->run()) {
                $appname = $this->input->post('appname');
                $category = $this->input->post('category');
                $description = $this->input->post('description');
                $this->product->updateproduct($appname, $category, $description, $product_id, $productkey);
                $this->common->show_message(lang('v_man_au_editSuccess') .anchor('/report/console', lang('v_man_pr_submitSuccessReturn')));
            } else {
                $this->_data['product'] = $this->product->getproductinfo($product_id);
                $this->_data['selectcategory'] = $this->input->post('category');
                $this->_data['category'] = $this->product->getProductCategory();
                $this->load->view('manage/editproduct', $this->_data);
            }
        } else {
            redirect('/auth/login/');
        }
    }

    /**
     * Delete
     *
     * @param string $productId productId
     *
     * @return void
     */
    function delete($productId)
    {
        $userId = $this->common->getUserId();
        $_data['productId'] = $productId;
        $flag = $this->product->deleteProduct($productId, $userId);
        if ($flag > 0) {
            $this->_data["message"] = lang('v_man_au_failDelete');
        } else {
            $this->_data["message"] = lang('v_man_au_failDelete');
        }
        redirect('/report/console');
    }
}
