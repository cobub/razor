<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/*
 
 * library Acl
 
 * @auth Liuguoqing
 
 * date 20091225
 
 * Using the Zend Framework ACL Library in Codeigniter 

 * Acl.php
 
 * $roles ：角色
 
 * $resources： 资源
 
 * $permissions： 权限
 
 */
 
require_once BASEPATH .'libraries/zend/Acl.php';
 
class My_Acl extends Zend_Acl {
 
    /*
 
     * 初始化Acl
 
     */
 
    function __construct() {
 
       $CI = &get_instance();
 
       $this->acl = new Zend_Acl();
 
        //获取角色
 
       $CI->db->order_by('ParentId', 'ASC'); 

       $query = $CI->db->get('cw_roles');
 
       $roles = $query->result();
 
        //获取资源
 
       $CI->db->order_by('parentId', 'ASC'); 

       $query = $CI->db->get('cw_resources');
 
       $resources = $query->result();
 
        //获取权限
 
       $query = $CI->db->get('cw_permissions'); 

       $permissions = $query->result();
 
        //Add the roles to the ACL
 
       foreach ($roles as $roles) { 

           $role = new Zend_Acl_Role($roles->id);
 
           $roles->parentId != null ?
 
              $this->acl->addRole($role,$roles->parentId): 

              $this->acl->addRole($role);
 
       }
 
        //Add the resources to the ACL
 
       foreach($resources as $resources) { 

           $resource = new Zend_Acl_Resource($resources->id);
 
           $resources->parentId != null ?
 
              $this->acl->add($resource, $resources->parentId):
 
              $this->acl->add($resource);
 
       }
 
        //Add the permissions to the ACL
 
       foreach($permissions as $perms) { 

           $perms->read == '1' ? 

              $this->acl->allow($perms->role, $perms->resource, 'read') : 

              $this->acl->deny($perms->role, $perms->resource, 'read');
 
           $perms->write == '1' ? 

              $this->acl->allow($perms->role, $perms->resource, 'write') : 

              $this->acl->deny($perms->role, $perms->resource, 'write');
 
           $perms->modify == '1' ? 

              $this->acl->allow($perms->role, $perms->resource, 'modify') : 

              $this->acl->deny($perms->role, $perms->resource, 'modify');
 
           $perms->publish == '1' ? 

              $this->acl->allow($perms->role, $perms->resource, 'publish') : 

              $this->acl->deny($perms->role, $perms->resource, 'publish');
 
           $perms->delete == '1' ? 

              $this->acl->allow($perms->role, $perms->resource, 'delete') : 

              $this->acl->deny($perms->role, $perms->resource, 'delete');
 
       }      

       //Change this to whatever id your adminstrators group is
 
       //管理员默认拥有所有权限
 
       $this->acl->allow('1'); 

    }
 
    /*
 
     * Methods to query the ACL.
 
     */
 
 
 
    function can_read($role, $resource) {
 
       return $this->acl->isAllowed($role, $resource, 'read')? TRUE : FALSE;
 
    }
 
    function can_write($role, $resource) {
 
       return $this->acl->isAllowed($role, $resource, 'write')? TRUE : FALSE;
 
    }
 
    function can_modify($role, $resource) {
 
       return $this->acl->isAllowed($role, $resource, 'modify')? TRUE : FALSE;
 
    }
 
    function can_delete($role, $resource) {
 
       return $this->acl->isAllowed($role, $resource, 'delete')? TRUE : FALSE;
 
    }
 
    function can_publish($role, $resource) {
 
       return $this->acl->isAllowed($role, $resource, 'publish')? TRUE : FALSE;
 
    }
 
}
