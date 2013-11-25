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

class User extends CI_Model {
	
	function __construct() {
		parent::__construct();

		$this->load->library('tank_auth');
		$this->load->model('common');
		$this->load->model('user/ums_user', 'ums_user');
	}

	/**
	 * @return string
	 */
	function getUserId() {
		return $this->tank_auth->get_user_id();
	}

	/**
	 * @return string
	 */
	function getUserName() {
		return $this->tank_auth->get_username();
	}

	/**
     * @param userid
	 *
	 * @return object 
	 */
    function getUserInfoById($id) {
    	return $this->ums_user->getUserInfoById($id);
    }

	/**
	 * @param userid
	 * 
	 * @return string
	 */
	function getUserRoleById($id) {
		return $this->common->getUserRoleById($id);
	}

	/**
	 * @return Boolean
	 */
	function isAdmin() {
		return $this->common->isAdmin();
	}

	/**
	 * @return Boolean
	 */
	function isUserLogin() {
        return $this->tank_auth->is_logged_in();
    }

}

?>