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
 * User Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */

class User extends CI_Controller
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
        $this -> load -> model('user/ums_user', 'user');
        $this -> load -> Model('common');
        $this -> load -> Model('user/ums_user');
        $this -> canRead = $this -> common -> canRead($this -> router -> fetch_class());
        $this -> common -> requireLogin();
    }
    
    /**
     * Index
     * 
     * @return void
     */
    function index()
    {

        if ($this -> canRead) {
            $query = $this -> user -> getUserList();
            $data['userlist'] = $query;
            $r = $this -> user -> getRoles();
            $data['roleslist'] = $r;
            $data['currentuserid'] = $this -> common -> getUserId();
            $data['guest_roleid'] = $this -> common -> getUserRoleById($data['currentuserid']);
            $this -> common -> loadHeader(lang('m_userManagement'));
            $this -> load -> view('user/user', $data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }

    }
    
    /**
     * CreateNewUser
     * 
     * @return void
     */
    function createNewUser()
    {
        if ($this -> canRead) {
            $r = $this -> user -> getRoles();
            $data['roleslist'] = $r;
            $data['currentuserid'] = $this -> common -> getUserId();
            $data['use_username'] = true;
            $data['captcha_registration'] = false;
            $this -> form_validation -> set_rules('username', lang('l_username'), 'required|xss_clean|is_user_unique[users.username]');
            $this -> form_validation -> set_rules('email', lang('l_re_email'), 'trim|required|xss_clean|valid_email|is_user_unique[users.email]');
            $this -> form_validation -> set_rules('password', lang('l_password'), 'trim|required|xss_clean|min_length[' . $this -> config -> item('password_min_length', 'tank_auth') . ']|max_length[' . $this -> config -> item('password_max_length', 'tank_auth') . ']|alpha_dash');
            $this -> form_validation -> set_rules('confirm_password', lang('l_re_confirmPassword'), 'trim|required|xss_clean|matches[password]');
            $data['errors'] = array();
            if ($this -> form_validation -> run()) {
                $username = $this -> input -> post('username');
                $password = $this -> input -> post('password');
                $email = $this -> input -> post('email');

                $hasher = new PasswordHash($this -> ci -> config -> item('phpass_hash_strength', 'tank_auth'), $this -> ci -> config -> item('phpass_hash_portable', 'tank_auth'));
                $hashed_password = $hasher -> HashPassword($password);

                $data = array('username' => $username, 'password' => $hashed_password, 'email' => $email, 'last_ip' => $this -> ci -> input -> ip_address());

                // 				$isuserNameAvailable = $this->ci->users->is_username_available($username);
                // 				$isuserEmailAvailable = $this->ci->users->is_email_available($email);

                $userinfo = $this -> users -> create_user($data, true);
                if ($userinfo) {
                    $userId = $userinfo['user_id'];
                    $roleid = $this -> input -> post("userrole");
                    $this -> ums_user -> bindUserRole($userId, $roleid);
                    $this -> common -> show_message(lang('v_user_createTip') . anchor('/user', lang('v_user_userList')));
                } else {

                }
            } else {
                $this -> common -> loadHeader(lang('m_userManagement'));
                $this -> load -> view('user/newuser', $data);
            }
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * NewUser
     * 
     * @return void
     */
    function newUser()
    {
        if ($this -> canRead) {
            $data['use_username'] = true;
            $data['captcha_registration'] = false;
            $r = $this -> user -> getRoles();
            $data['roleslist'] = $r;
            $data['currentuserid'] = $this -> common -> getUserId();
            $userid = $this -> common -> getUserId();
            $data['guest_roleid'] = $this -> common -> getUserRoleById($userid);
            $this -> common -> loadHeader(lang('m_userManagement'));
            $this -> load -> view('user/newuser', $data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * AssignProducts
     * 
     * @param string $userId userId
     * 
     * @return void
     */
    function assignProducts($userId)
    {
        if ($this -> canRead) {
            $data['userid'] = $userId;
            $data["products"] = $this -> user -> getUserProducts($userId);
            $data['guest_roleid'] = $this -> common -> getUserRoleById($this -> common -> getUserId());
            $this -> common -> loadHeader(lang('m_userManagement'));
            $this -> load -> view('user/assignproducts', $data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * DoAssignProducts
     * 
     * @return void
     */
    function doAssignProducts()
    {
        if ($this -> canRead) {
            $userId = $this -> input -> post('userid');
            $selectedProduct = $this -> input -> post('product');
            $this -> ums_user -> bindUserProducts($userId, $selectedProduct);

            $data['userid'] = $userId;
            $data["products"] = $this -> user -> getUserProducts($userId);
            $this -> common -> loadHeader(lang('m_userManagement'));
            $this -> common -> show_message(lang('v_tip_assign_products') . anchor('/user', lang('v_user_userList')));

        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * RoleManage
     * 
     * @return void
     */
    function roleManage()
    {
        if ($this -> canRead) {
            $query = $this -> ums_user -> getRoles();
            $data['rolelist'] = $query;
            $resource = $this -> ums_user -> getResources();
            $data['resourcelist'] = $resource;
            $userid = $this -> common -> getUserId();
            $data['guest_roleid'] = $this -> common -> getUserRoleById($userid);
            $this -> common -> loadHeader(lang('m_roleManagement'));
            $this -> load -> view('user/roles', $data);

        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * RoleManageDetail
     * 
     * @param string $roleid   roleid
     * @param string $rolename rolename
     * 
     * @return void
     */
    function roleManageDetail($roleid, $rolename)
    {
        if ($this -> canRead) {
            // $query = $this->ums_user->getRoles ();
            // $data ['rolelist'] = $query;

            $resource = $this -> ums_user -> getResourcesByRole($roleid);
            $data['roleid'] = $roleid;
            $data['resourcelist'] = $resource;
            $data['rolename'] = $rolename;
            $userid = $this -> common -> getUserId();
            $data['guest_roleid'] = $this -> common -> getUserRoleById($userid);
            $this -> common -> loadHeader(lang('v_user_rolem_setResourceP'));
            $this -> load -> view('user/roledetail', $data);

        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * ResourceManage
     * 
     * @return void
     */
    function resourceManage()
    {
        if ($this -> canRead) {
            $query = $this -> ums_user -> getResources();
            $data['resourcelist'] = $query;
            $this -> common -> loadHeader(lang('m_resourceManagement'));
            $this -> load -> view('user/resource', $data);

        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * ModifyRoleCapability
     * 
     * @return void
     */
    function modifyRoleCapability()
    {
        if ($this -> canRead) {
            $role = $_POST['role'];
            $resource = $_POST['resource'];
            $capability = $_POST['capability'];
            if ($resource != '' && $role != '' && $capability != '')
                $this -> ums_user -> modifyRoleCapability($role, $resource, $capability);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }

    }
    
    /**
     * EditResource
     * 
     * @param string $id id
     * 
     * @return void
     */
    function editResource($id)
    {
        if ($this -> canRead) {
            $data['resourceinfo'] = $this -> ums_user -> geteditresources($id);
            $this -> common -> loadHeader(lang('v_user_resm_editResource'));
            $this -> load -> view('user/resourceEdit', $data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }

    }
    
    /**
     * Modifyresource
     * 
     * @return void
     */
    function modifyresource()
    {
        if ($this -> canRead) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $tablename = $this -> common -> getdbprefixtable('user_resources');
            $result = $this -> ums_user -> isUnique($tablename, $name);
            if (!empty($result)) {
                echo false;
            } else {
                $description = $_POST['description'];
                $this -> ums_user -> modifyresource($id, $name, $description);
                echo true;
            }
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }

    }
    
    /**
     * AddRole
     * 
     * @return bool
     */
    function addRole()
    {

        if ($this -> canRead) {
            $role = $_POST['role'];
            $description = $_POST['description'];
            $tablename = $this -> common -> getdbprefixtable('user_roles');
            $result = $this -> ums_user -> isUnique($tablename, $role);
            if (!empty($result)) {
                echo false;
            } else {
                if ($role != '' && $description != '') {
                    $this -> ums_user -> addRole($role, $description);
                    echo true;
                }
            }
        }
    }
    
    /**
     * AddResource
     * 
     * @return bool
     */
    function addResource()
    {

        if ($this -> canRead) {
            $resourceName = $_POST['resourceName'];
            $description = $_POST['description'];
            $tablename = $this -> common -> getdbprefixtable('user_resources');
            $result = $this -> ums_user -> isUnique($tablename, $resourceName);
            if (!empty($result)) {
                echo false;
            } else {
                if ($resourceName != '' && $description != '') {
                    $this -> ums_user -> addResource($resourceName, $description);
                }
                echo true;
            }
        }
    }
    
    /**
     * UserRoleManage
     * 
     * @return void
     */
    function userRoleManage()
    {
        if ($this -> canRead) {
            $id = $_GET['id'];
            $data['userinfo'] = $this -> ums_user -> getUserInfoById($id);
            $this -> common -> loadHeader();
            $this -> load -> view('user/userRoleEdit', $data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * ModifyUserRole
     * 
     * @return void
     */
    function modifyUserRole()
    {
        if ($this -> canRead) {
            $id = $_POST['id'];
            $rolename = $_POST['rolename'];
            $roleid = $this -> user -> getRoleidByRolename($rolename);

            $this -> user -> modifyuserRole($id, $roleid);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * ApplicationManagement
     * 
     * @return void
     */
    function applicationManagement()
    {
        if ($this -> canRead) {
            $query = $this -> ums_user -> getproductCategories();
            $data['productcategorylist'] = $query;
            $userid = $this -> common -> getUserId();
            $data['guest_roleid'] = $this -> common -> getUserRoleById($userid);
            $this -> common -> loadHeader(lang('m_appType'));
            $this -> load -> view('user/productCategory', $data);

        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }
    }
    
    /**
     * AddtypeOfapplication
     * 
     * @return bool
     */
    function addtypeOfapplication()
    {

        if ($this -> canRead) {
            $type_applicationName = $_POST['type_applicationName'];
            $tablename = $this -> common -> getdbprefixtable('product_category');
            $isUnique = $this -> ums_user -> isUniqueApp($tablename, $type_applicationName);
            if (!empty($isUnique)) {
                echo false;
            } else {
                if ($type_applicationName != '') {
                    $this -> ums_user -> addtypeOfapplication($type_applicationName);
                    echo true;
                }
            }
        }
    }
    
    /**
     * EdittypeOfapplication edit app type
     * 
     * @param string $typeOfapplicationid typeOfapplicationid
     * 
     * @return bool
     */
    function edittypeOfapplication($typeOfapplicationid)
    {
        if ($this -> canRead) {
            $data['catagory'] = $this -> ums_user -> getcategoryname($typeOfapplicationid);
            $userid = $this -> common -> getUserId();
            $data['guest_roleid'] = $this -> common -> getUserRoleById($userid);
            $this -> common -> loadHeader(lang('v_user_appM_editAppT'));
            $this -> load -> view('user/typeOfapplicaedit', $data);
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('forbidden');
        }

    }
    
    /**
     * ModifytypeOfapplica
     * 
     * @return bool
     */
    function modifytypeOfapplica()
    {
        $id = $_POST['type_applicathead_id'];
        $name = $_POST['type_applicathead_name'];
        $tablename = $this -> common -> getdbprefixtable('product_category');
        $isUnique = $this -> ums_user -> isUniqueApp($tablename, $name);
        if (!empty($isUnique)) {
            echo false;
        } else {
            if ($name != '') {
                $this -> ums_user -> updatetypeOfapplica($id, $name);
                echo true;
            }
        }
    }
    
    /**
     * DeletetypeOfapplication delete app type
     * 
     * @param string $id id
     * 
     * @return bool
     */
    function deletetypeOfapplication($id)
    {
        $this -> ums_user -> deletetypeOfapplica($id);
        $this -> applicationManagement();
    }

}
