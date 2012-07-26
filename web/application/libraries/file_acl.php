<?php
require_once BASEPATH . 'libraries/Zend/Acl.php';
require_once BASEPATH . 'libraries/Zend/Acl/Role.php';
require_once BASEPATH . 'libraries/Zend/Acl/Resource.php';

class File_ACL extends Zend_ACL {
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
?>