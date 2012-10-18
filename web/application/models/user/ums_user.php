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
class Ums_user extends CI_Model {
	function __construct() {
		$this->load->database ();
	}
	
	function getUserList() {
		$this->db->select('users.id,users.username,users.email,user_roles.name');
		$this->db->from('users');
		$this->db->join('user2role','users.id = user2role.userid','left');
		$this->db->join('user_roles','user2role.roleid = user_roles.id','left');
		
//		$sql = "select users.*,user2role.*,user_roles.id as role_id, user_roles.name from users left outer join user2role on users.id = user2role.userid left outer join user_roles on user2role.roleid = user_roles.id";
		$query = $this->db->get();
//		$query = $this->db->query ( $sql );
		return $query;
		
		
	}
	
	function getRoles()
	{
	    $result = $this->db->get('user_roles');
	    return $result;
	}
	//get app type
	function getproductCategories()
	{
		$sql = "select id,name from ".$this->db->dbprefix('product_category')."  where active=1";
		$query = $this->db->query($sql);
		return $query;
	}
	// get categoryinfo by categoryid
	function getcategoryname($id)
	{
		$sql = "select id,name from ".$this->db->dbprefix('product_category')."  where active=1 and id=$id";	  
		$query = $this->db->query($sql);
		$row = $query->first_row ();
		if ($query->num_rows > 0) {
			return $row;
		}		
		return null;
	}
	// add app type
	function addtypeOfapplication($type_applicationName) {
	
		$data = array ('name' => $type_applicationName);
		$this->db->insert ('product_category', $data );
	}
	//update app type by id
	function updatetypeOfapplica($id,$name){
		$data = array ('name' => $name);
		$this->db->where ( 'id', $id );
		$this->db->update ( 'product_category', $data );
	}
	//delete app type
	function deletetypeOfapplica($id){
		$data=array(
				'active'=>0
		);
		$this->db->where('id', $id);
		$this->db->update('product_category', $data);
	}
	//get resources info
	function getResources()
	{
	    $result = $this->db->get('user_resources');
	    return $result;
	}
	//get resources info by resourceid
	function geteditresources($id)
	{
		$this->db->from ( 'user_resources' );
		$this->db->where ( 'id', $id );
		$query = $this->db->get ();
		$row = $query->first_row ();
		if ($query->num_rows > 0) {
			return $row;
		}		
		return null;
	}
	function getRoleCapability($roleid,$resource)
	{
	    $this->db->from('user_permissions');
	    $this->db->where('role',$roleid);
	    $this->db->where('resource',$resource);
		$query = $this->db->get ();
		if ($query!=null && $query->num_rows()>0)
		return $query->first_row()->read;
		return 0;
	}
	
	
	function getResourcesByRole($roleid) {
		$sql = "select ".$this->db->dbprefix('user_resources').".id,".$this->db->dbprefix('user_resources').".name,".$this->db->dbprefix('user_resources').".description,".$this->db->dbprefix('user_permissions').".resource,".$this->db->dbprefix('user_permissions').".read from  ".$this->db->dbprefix('user_resources')." left outer join ".$this->db->dbprefix('user_permissions')."   on ".$this->db->dbprefix('user_resources').".id=".$this->db->dbprefix('user_permissions').".resource  and ".$this->db->dbprefix('user_permissions').".role = ".$roleid;
//		echo $sql;
		$query = $this->db->query($sql);
//        $this->db->select('user_permissions.resource,user_permissions.read,user_resources.name,user_resources.description,user_resources.id');
//		$this->db->from ( 'user_resources' );
//		$this->db->join ( 'user_permissions','user_permissions.role = user_resources.id ','left outer');		
//		$this->db->where('role',$roleid);
//		$query = $this->db->get ();	
		return $query;
	}
	
	function modifyRoleCapability($role, $resource, $capability) {
		$this->db->from ( 'user_permissions' );
		$this->db->where ( 'role', $role );
		$this->db->where ( 'resource', $resource );
		$r = $this->db->get ();
		if ($r != null && $r->num_rows > 0) {
			$data = array ('read' => $capability );
			$this->db->where ( 'role', $role );
			$this->db->where ( 'resource', $resource );
			$this->db->update ( 'user_permissions', $data );
		} else {
			$data = array ('role' => $role, 'resource' => $resource, 'read' => $capability, 'write' => '0', 'modify' => '0', 'delete' => '0', 'publish' => '0' );
			$this->db->insert ( 'user_permissions', $data );
		}
	}
	
	//	function deleteRole($role)
	//	{
	//	    $this->db->where('name', $role);
	//        $this->db->delete('user_role'); 
	//		
	//	}
	function  getRoleidByRolename($name)
	{
	    $this->db->from('user_roles');
	    $this->db->where('name',$name);
	    $r = $this->db->get();
	    return $r->first_row()->id;
	}
	
	function isUnique($tablename,$name){
		$this->db->from($tablename);
		$this->db->where('name',$name);
		$r = $this->db->get();
		return $r->result();
		
	}
	
	function isUniqueApp($tablename,$name){
		$this->db->from($tablename);
		$this->db->where('name',$name);
		$this->db->where('active','1');
		$r = $this->db->get();
		return $r->result();
	
	}

	function addRole($role,$description) {
		

		$data = array ('name' => $role, 'description' => $description );		
		$this->db->insert ( 'user_roles', $data );
	}
	
	function addResource($resourceName, $description) {
		
		$data = array ('name' => $resourceName, 'description' => $description );
		$this->db->insert ( 'user_resources', $data );
	}
	
	function modifyresource($id, $name, $description) {
		$data = array ('name' => $name, 'description' => $description );
		$this->db->where ( 'id', $id );
		$this->db->update ( 'user_resources', $data );
	}
	
function getUserInfoById($id) {
		
		$this->db->from ( 'users' );
		$this->db->where ( 'id', $id );
		$query = $this->db->get ();
		$row = $query->first_row ();
		if ($query->num_rows > 0) {
			return $row;
		}
		else 
		return null;
	}
	function modifyuserRole($id,$roleId)
	{
	    $data = array ('roleid' => $roleId );
		$this->db->where ( 'userid', $id );
		$this->db->update ( 'user2role', $data );
	}
}