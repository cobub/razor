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
 * Ums_user Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Ums_user extends CI_Model
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        $this->load->database();
    }

    /**
     * GetUserList function
     * Get user list
     *
     * @return query result
     */
    function getUserList()
    {
        $this->db->select('users.id,users.username,users.email,user_roles.name');
        $this->db->from('users');
        $this->db->join('user2role', 'users.id = user2role.userid', 'left');
        $this->db->join('user_roles', 'user2role.roleid = user_roles.id', 'left');
        $query = $this->db->get();
        return $query;
    }

    /**
     * GetUserProducts function
     * Get user products
     *
     * @param int $userId userId
     *
     * @return array
     */
    function getUserProducts($userId)
    {
        $allProductQuery = $this->db->query("select * from " . $this->db->dbprefix('product') . " where active = 1");
        $productsArray = array();
        if ($allProductQuery && $allProductQuery->num_rows() > 0) {
            foreach ($allProductQuery->result() as $row) {
                $hasPermission = $this->isUserHasPermissionToProduct($userId, $row->id);
                $product = array('id' => $row->id,'name' => $row->name,'permission' => $hasPermission);
                array_push($productsArray, $product);
            }
        }
        return $productsArray;
    }

    /**
     * BindUserProducts function
     * Bind user products
     *
     * @param int   $userId       userId
     * @param array $productArray productArray
     * 
     * @return void
     */
    function bindUserProducts($userId, $productArray)
    {
        $this->db->query("delete from " . $this->db->dbprefix('user2product') . " where user_id = $userId");
        
        if ($productArray && count($productArray) > 0) {
            foreach ($productArray as $value) {
                $data = array('user_id' => $userId,'product_id' => $value);
                $this->db->insert("user2product", $data);
            }
        }
    }

    /**
     * IsUserHasPermissionToProduct function
     * Judge user permission to product
     *
     * @param int $userId    userId
     * @param int $productId productId
     *
     * @return bool
     */
    function isUserHasPermissionToProduct($userId, $productId)
    {
        $query = $this->db->get_where('user2product', array('user_id' => $userId,'product_id' => $productId));
        if ($query && $query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * GetRoles function
     * Get roles
     *
     * @return query result
     */
    function getRoles()
    {
        $result = $this->db->get('user_roles');
        return $result;
    }
    
    /**
     * GetproductCategories function
     * Get app type
     *
     * @return query
     */
    function getproductCategories()
    {
        $sql = "select id,name from " . $this->db->dbprefix('product_category') . "  where active=1";
        $query = $this->db->query($sql);
        return $query;
    }
    

    /**
     * Getcategoryname function
     * Get categoryinfo by categoryid
     *
     * @param int $id id
     *
     * @return query result
     */
    function getcategoryname($id)
    {
        $sql = "select id,name from " . $this->db->dbprefix('product_category') . "  where active=1 and id=$id";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row;
        }
        return null;
    }
    
    /**
     * AddtypeOfapplication function
     * Add app typed
     * 
     * @param string $type_applicationName applicationname type
     *
     * @return void
     */
    function addtypeOfapplication($type_applicationName)
    {
        $data = array('name' => $type_applicationName);
        $this->db->insert('product_category', $data);
    }
    
    /**
     * UpdatetypeOfapplica function
     * Update app type by id
     *
     * @param int    $id   id
     * @param string $name name
     *
     * @return void
     */
    function updatetypeOfapplica($id, $name)
    {
        $data = array('name' => $name);
        $this->db->where('id', $id);
        $this->db->update('product_category', $data);
    }
    
    /**
     * DeletetypeOfapplica function
     * Delete app type
     *
     * @param int $id id
     *
     * @return void
     */
    function deletetypeOfapplica($id)
    {
        $data = array('active' => 0);
        $this->db->where('id', $id);
        $this->db->update('product_category', $data);
    }
    
    /**
     * GetResources function
     * Get resources info
     *
     * @return query result
     */
    function getResources()
    {
        $result = $this->db->get('user_resources');
        return $result;
    }
    
    /**
     * Geteditresources function
     * Get resources info by resourceid
     *
     * @param int $id id
     *
     * @return query
     */
    function geteditresources($id)
    {
        $this->db->from('user_resources');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row;
        }
        return null;
    }

    /**
     * GetRoleCapability function
     * GetRoleCapability
     *
     * @param int    $roleid   roleid
     * @param string $resource resource
     * 
     * @return query
     */
    function getRoleCapability($roleid, $resource)
    {
        $this->db->from('user_permissions');
        $this->db->where('role', $roleid);
        $this->db->where('resource', $resource);
        $query = $this->db->get();
        if ($query != null && $query->num_rows() > 0)
            return $query->first_row()->read;
        return 0;
    }

    /**
     * GetResourcesByRole function
     * Get resources by role
     *
     * @param int $roleid role id
     *
     * @return query
     */
    function getResourcesByRole($roleid)
    {
        $sql = "select " . $this->db->dbprefix('user_resources') . ".id," . $this->db->dbprefix('user_resources') . ".name," . $this->db->dbprefix('user_resources') . ".description," . $this->db->dbprefix('user_permissions') . ".resource," . $this->db->dbprefix('user_permissions') . ".read from  " . $this->db->dbprefix('user_resources') . " left outer join " . $this->db->dbprefix('user_permissions') . "   on " . $this->db->dbprefix('user_resources') . ".id=" . $this->db->dbprefix('user_permissions') . ".resource  and " . $this->db->dbprefix('user_permissions') . ".role = " . $roleid;
        $query = $this->db->query($sql);
        return $query;
    }

    /**
     * ModifyRoleCapability function
     * Modify role capability
     *
     * @param string $role       role
     * @param string $resource   resource
     * @param string $capability capability
     * 
     * @return void
     */
    function modifyRoleCapability($role, $resource, $capability)
    {
        $this->db->from('user_permissions');
        $this->db->where('role', $role);
        $this->db->where('resource', $resource);
        $r = $this->db->get();
        if ($r != null && $r->num_rows > 0) {
            $data = array('read' => $capability);
            $this->db->where('role', $role);
            $this->db->where('resource', $resource);
            $this->db->update('user_permissions', $data);
        } else {
            $data = array('role' => $role,'resource' => $resource,'read' => $capability,'write' => '0','modify' => '0','delete' => '0','publish' => '0');
            $this->db->insert('user_permissions', $data);
        }
    }

    /**
     * GetRoleidByRolename function
     * Get role id by role name
     *
     * @param string $name name
     *
     * @return id
     */
    function getRoleidByRolename($name)
    {
        $this->db->from('user_roles');
        $this->db->where('name', $name);
        $r = $this->db->get();
        return $r->first_row()->id;
    }

    /**
     * IsUnique function
     * Judge is unique
     *
     * @param string $tablename tablename
     * @param string $name      name
     * 
     * @return query result
     */
    function isUnique($tablename, $name)
    {
        $this->db->from($tablename);
        $this->db->where('name', $name);
        $r = $this->db->get();
        return $r->result();
    }

    /**
     * isUniqueApp function
     * Judge is unique app
     *
     * @param string $tablename tablename
     * @param string $name      name
     *
     * @return query result
     */
    function isUniqueApp($tablename, $name)
    {
        $this->db->from($tablename);
        $this->db->where('name', $name);
        $this->db->where('active', '1');
        $r = $this->db->get();
        return $r->result();
    }

    /**
     * AddRole function
     * Add role
     *
     * @param string $role        role
     * @param string $description description
     *
     * @return void
     */
    function addRole($role, $description)
    {
        $data = array('name' => $role,'description' => $description);
        $this->db->insert('user_roles', $data);
    }

    /**
     * AddResource function
     * Add resource
     *
     * @param string $resourceName resourceName
     * @param string $description  description
     *
     * @return void
     */
    function addResource($resourceName, $description)
    {
        $data = array('name' => $resourceName,'description' => $description);
        $this->db->insert('user_resources', $data);
    }

    /**
     * Modifyresource function
     * Modify resource
     *
     * @param int    $id          id
     * @param string $name        name
     * @param string $description description
     *
     * @return void
     */
    function modifyresource($id, $name, $description)
    {
        $data = array('name' => $name,'description' => $description);
        $this->db->where('id', $id);
        $this->db->update('user_resources', $data);
    }

    /**
     * GetUserInfoById function
     * Get user nnfo by id
     *
     * @param int $id id
     *
     * @return query result
     */
    function getUserInfoById($id)
    {
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row;
        } else
            return null;
    }

    /**
     * DeletetypeOfapplica function
     * Delete app type
     *
     * @param int $id     id
     * @param int $roleId roleId
     *
     * @return void
     */
    function modifyuserRole($id, $roleId)
    {
        $data = array('roleid' => $roleId);
        $this->db->where('userid', $id);
        $this->db->update('user2role', $data);
    }

    /**
     * BindUserRole function
     * Bind user role
     *
     * @param int $userId userId
     * @param int $roleId roleId
     *
     * @return void
     */
    function bindUserRole($userId, $roleId)
    {
        $data = array('userid' => $userId,'roleid' => $roleId);
        $this->db->insert('user2role', $data);
    }
}