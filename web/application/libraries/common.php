<?php
class Common extends CI_Model{
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'tank_auth' );
		$this->load->library ( 'ums_acl' );
		$this->load->database ();
	}
	
	function requireLogin()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} 
	}
	
	function isAdmin()
	{
		$userid = $this->tank_auth->get_user_id ();
		$role = $this->getUserRoleById ( $userid );
		if($role == 3)
		{
			return true;
		}
		return false;
	}
	
	function getUserId()
	{
		return  $this->tank_auth->get_user_id();
	}
	
	function getUserName()
	{
		return $this->tank_auth->get_username();
	}
	
	function isUserLogin()
	{
		return $this->tank_auth->is_logged_in();
	}
	
	function  canRead($controllerName)
	{
		$id = $this->getResourceIdByControllerName($controllerName);
		if($id)
		{
			$acl = new Ums_acl ();
			$userid = $this->tank_auth->get_user_id ();
			$role = $this->getUserRoleById ( $userid );
			
			if ($acl->can_read ( $role, $id )) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	//private functiosn
	function getResourceIdByControllerName($controllerName)
	{
		$this->db->where('name',$controllerName);
		$query = $this->db->get('user_resources');
		if($query!=null && $query->num_rows()>0)
		{
			return $query->first_row()->id;
		}
		return null;
	}
	
	function getUserRoleById($id) {
		if ($id == '')
			return '2';
		$this->db->select ( 'roleid' );
		$this->db->where ( 'userid', $id );
		$query = $this->db->get ( 'user2role' );
		$row = $query->first_row ();
		if ($query->num_rows > 0) {
			return $row->roleid;
		} else
			return '2';
	}
	
}