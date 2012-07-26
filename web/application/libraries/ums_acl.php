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
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
require_once BASEPATH . 'libraries/Zend/Acl.php';
class Ums_acl extends Zend_Acl {
	
	function __construct() {
	
		$CI = &get_instance ();
		$this->acl = new Zend_Acl ();
		
		$CI->db->order_by ( 'ParentId', 'ASC' ); //Get the roles
		$query = $CI->db->get ( 'user_roles' );
		$roles = $query->result ();
		
		$CI->db->order_by ( 'parentId', 'ASC' ); //Get the resources
		$query = $CI->db->get ( 'user_resources' );
		$resources = $query->result ();
		
		$query = $CI->db->get ( 'user_permissions' ); //Get the permissions
		$permissions = $query->result ();
		
		foreach ( $roles as $roles ) { //Add the roles to the ACL
			$role = new Zend_Acl_Role ( $roles->id );
			$roles->parentId != null ? $this->acl->addRole ( $role, $roles->parentId ) : $this->acl->addRole ( $role );
		}
		
		foreach ( $resources as $resources ) { //Add the resources to the ACL
			$resource = new Zend_Acl_Resource ( $resources->id );
			$resources->parentId != null ? $this->acl->add ( $resource, $resources->parentId ) : $this->acl->add ( $resource );
		}
		
		foreach ( $permissions as $perms ) { //Add the permissions to the ACL
			$perms->read == '1' ? $this->acl->allow ( $perms->role, $perms->resource, 'read' ) : $this->acl->deny ( $perms->role, $perms->resource, 'read' );
			$perms->write == '1' ? $this->acl->allow ( $perms->role, $perms->resource, 'write' ) : $this->acl->deny ( $perms->role, $perms->resource, 'write' );
			$perms->modify == '1' ? $this->acl->allow ( $perms->role, $perms->resource, 'modify' ) : $this->acl->deny ( $perms->role, $perms->resource, 'modify' );
			$perms->publish == '1' ? $this->acl->allow ( $perms->role, $perms->resource, 'publish' ) : $this->acl->deny ( $perms->role, $perms->resource, 'publish' );
			$perms->delete == '1' ? $this->acl->allow ( $perms->role, $perms->resource, 'delete' ) : $this->acl->deny ( $perms->role, $perms->resource, 'delete' );
		}
	
		//		$this->acl->allow('1'); //Change this to whatever id your adminstrators group is
	}
	/*
	 * Methods to query the ACL.
	 */
	
	function can_read($role, $resource) {
		return $this->acl->isAllowed ( $role, $resource, 'read' ) ? TRUE : FALSE;
	}
	function can_write($role, $resource) {
		return $this->acl->isAllowed ( $role, $resource, 'write' ) ? TRUE : FALSE;
	}
	function can_modify($role, $resource) {
		return $this->acl->isAllowed ( $role, $resource, 'modify' ) ? TRUE : FALSE;
	}
	function can_delete($role, $resource) {
		return $this->acl->isAllowed ( $role, $resource, 'delete' ) ? TRUE : FALSE;
	}
	function can_publish($role, $resource) {
		return $this->acl->isAllowed ( $role, $resource, 'publish' ) ? TRUE : FALSE;
	}
	
	function aclCreate() {
		$this->addRole ( new Zend_Acl_Role ( 'guest' ) );
		//$this->deny('guest', null, 'view');
		

		$this->addRole ( new Zend_Acl_Role ( 'member' ) );
		$this->allow ( 'member', null, array ('view' ) );
		
		// Administrator does not inherit access controls
		$this->addRole ( new Zend_Acl_Role ( 'admin' ), 'member' );
		$this->allow ( 'admin', null, array ('add', 'edit', 'delete' ) );
		
		$this->add ( new Zend_Acl_Resource ( 'adminArea' ) );
		$this->add ( new Zend_Acl_Resource ( 'jobsearch' ) );
	}

}
