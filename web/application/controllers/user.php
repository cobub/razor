<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
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
class User extends CI_Controller {
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( 'url' );
		$this->load->model ( 'user/ums_user', 'user' );
		$this->load->Model ( 'common' );
		$this->load->Model ( 'user/ums_user' );
		$this->canRead = $this->common->canRead ( $this->router->fetch_class () );
		$this->common->loadHeader ();
		$this->common->requireLogin ();
	}
	
	function index() {
		
		if ($this->canRead) {
			$query = $this->user->getUserList ();
			$data ['userlist'] = $query;
			$r = $this->user->getRoles ();
			$data ['roleslist'] = $r;
			$this->load->view ( 'user/user', $data );
		} else {
			$this->load->view ( 'forbidden' );
		}
	
	}
	
	function roleManage() {
		if ($this->canRead) {
			$query = $this->ums_user->getRoles ();
			$data ['rolelist'] = $query;
			$resource = $this->ums_user->getResources ();
			$data ['resourcelist'] = $resource;
			$this->load->view ( 'user/roles', $data );
		
		} else {
			$this->load->view ( 'forbidden' );
		}
	}
	
	function roleManageDetail($roleid, $rolename) {
		if ($this->canRead) {
			// $query = $this->ums_user->getRoles ();
			// $data ['rolelist'] = $query;
			
			$resource = $this->ums_user->getResourcesByRole( $roleid );
			$data ['roleid'] = $roleid;
			$data ['resourcelist'] = $resource;
			$data ['rolename'] = $rolename;
			
			$this->load->view ( 'user/roledetail', $data );
		
		} else {
			$this->load->view ( 'forbidden' );
		}
	}
	
	function resourceManage() {
		if ($this->canRead) {
			$query = $this->ums_user->getResources ();
			$data ['resourcelist'] = $query;
			$this->load->view ( 'user/resource', $data );
		
		} else {
			$this->load->view ( 'forbidden' );
		}
	}
	
	function modifyRoleCapability() {
		if ($this->canRead) {
			$role = $_POST ['role'];
			$resource = $_POST ['resource'];
			$capability = $_POST ['capability'];
			if ($resource != '' && $role != '' && $capability != '')
				$this->ums_user->modifyRoleCapability( $role, $resource, $capability );
		} else {
			
			$this->load->view ( 'forbidden' );
		}
	
	}
	
	function editResource($id) {
		if ($this->canRead) {
			// $data = array('id'=>$id,'name'=>$name,'description' =>
			// $description);
			// $data['id'] = $id;
			// $data['name'] = $name;
			// $data['description'] = $description;
			
			$data ['resourceinfo'] = $this->ums_user->geteditresources ( $id );
			$this->load->view ( 'user/resourceEdit', $data );
		} else {
			
			$this->load->view ( 'forbidden' );
		}
	
	}
	function modifyresource() {
		if ($this->canRead) {
			$id = $_POST ['id'];
			$name = $_POST ['name'];
			$description = $_POST ['description'];
			$this->ums_user->modifyresource ( $id, $name, $description );
		} else {
			
			$this->load->view ( 'forbidden' );
		}
	
	}
	
	// function deleteRole()
	// {
	// $role = $_POST['role'];
	// $this->ums_user->deleteRole($role);
	// }
	
	function addRole() {
		
		if ($this->canRead) {
			$role = $_POST ['role'];
			
			$description = $_POST ['description'];
			if ($role != '' && $description != '')
				
				$this->ums_user->addRole ( $role, $description );
		}
	}
	
	function addResource() {
		
		if ($this->canRead) {
			$resourceName = $_POST ['resourceName'];
			
			$description = $_POST ['description'];
			if ($resourceName != '' && $description != '')
				
				$this->ums_user->addResource ( $resourceName, $description );
		}
	}
	
	function userRoleManage() {
		if ($this->canRead) {
			$id = $_GET ['id'];
			$data ['userinfo'] = $this->ums_user->getUserInfoById ( $id );
			
			$this->load->view ( 'user/userRoleEdit', $data );
		} else {
			
			$this->load->view ( 'forbidden' );
		}
	}
	
	function modifyUserRole() {
		if ($this->canRead) {
			$id = $_POST ['id'];
			$rolename = $_POST ['rolename'];
			$roleid = $this->user->getRoleidByRolename ( $rolename );
			
			$this->user->modifyuserRole ( $id, $roleid );
		} else {
			
			$this->load->view ( 'forbidden' );
		}
	}
	
	function applicationManagement() {
		if ($this->canRead) {
			$query = $this->ums_user->getproductCategories ();
			$data ['productcategorylist'] = $query;
			$this->load->view ( 'user/productCategory', $data );
		
		} else {
			$this->load->view ( 'forbidden' );
		}
	}
	
	function addtypeOfapplication() {
		
		if ($this->canRead) {
			$type_applicationName = $_POST ['type_applicationName'];
			
			if ($type_applicationName != '')
				
				$this->ums_user->addtypeOfapplication ( $type_applicationName );
		}
	}
	
	// 编辑应用类型
	function edittypeOfapplication($typeOfapplicationid) {
		if ($this->canRead) {				     
			$this->data['catagory']=$this->ums_user->getcategoryname($typeOfapplicationid);
			$this->load->view ( 'user/typeOfapplicaedit.php', $this->data );
		} else {
			$this->load->view ( 'forbidden' );
		}
	
	}
	function modifytypeOfapplica(){
	    $id=$_POST['type_applicathead_id'];
		$name=$_POST['type_applicathead_name'];
	    if ($name != '')
	    {		
		  $this->ums_user->updatetypeOfapplica($id, $name);		   		   
		}
	}                            
	//删除应用类型
	function deletetypeOfapplication($id){
		$this->ums_user->deletetypeOfapplica($id);
		$this->applicationManagement();
	}

}