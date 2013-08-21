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
class Pluginlistmodel extends CI_Model {
	
	function __construct() {
		$this->load->database ();
		$this->load->model ( 'common' );
		$this->load->model ( 'pluginm' );
	}
	
	function getUserKeys($userId) {
		if(!$userId)
			return false;
		
		$query = $this->db->query ( "select * from " . $this->db->dbprefix ( "userkeys" ) . " where user_id = $userId" );
		if ($query && $query->num_rows () > 0) {
			return $query->first_row ();
		}
		return false;
	}
	
	// get myself plugins
	function getMyPlugins($userId) {
		$pluginsArray = $this->pluginm->run ( "getPluginInfo", "" );
		return $pluginsArray;
	}
	
	// //get serve all plugins
	function getAllPlugins() {
		$url = SERVER_BASE_URL."/index.php?/api/plugin/getPluginList";
		$response = $this->common->curl_post ( $url, null );
		
		return $response;
	}
	
	// //test and verify user's userKey&userSecret Success:return true or return
	// false:
	function verifyUserKeys($userKey, $userSecret) {
		
		$url = SERVER_BASE_URL."/index.php?/api/igetui/auth";
		$data = array (
				'userKey' => $userKey,
				'userSecret' => $userSecret 
		);
		$response = $this->common->curl_post ( $url, $data );
		$responseArray= json_decode($response,true);
		if ($responseArray ['flag'] > 0) {
			return true;
		}
		return false;
	}
	
	// //save user's userkey and usersecret to razor_userkeys's table
	function saveUserKeys($userId, $userKey, $userSecret) {
		
		$this->db->from ( $this->db->dbprefix ( 'userkeys' ) );
		$this->db->where ( 'user_id', $userId );
		$query = $this->db->get ();
		if ($query && $query->num_rows () > 0) {
			
			$this->db->where ( 'user_id', $userId );
			$data = array (
					'user_key' => $userKey,
					'user_secret' => $userSecret
			);
			$this->db->update ( $this->db->dbprefix ( 'userkeys' ), $data );
			
		} else {
			
			$data = array (
					'user_id' => $userId,
					'user_key' => $userKey,
					'user_secret' => $userSecret 
			);
			$this->db->insert ( $this->db->dbprefix ( 'userkeys' ), $data );
		}
	
	}
	
	// //active plugin
	function activePlugin($userId, $pluginIdentifier) {
		if ($this->isPluginExist ( $pluginIdentifier, $userId )) {
			$data = array (
					'status' => 1 
			);
			// $this->db->where ( 'user_id', $userId );
			$this->db->where ( 'identifier', $pluginIdentifier );
			$this->db->update ( $this->db->dbprefix ( "plugins" ), $data );
		} else {
			$data = array (
					'status' => 1,
					'user_id' => $userId,
					'identifier' => $pluginIdentifier 
			);
			$this->db->insert ( $this->db->dbprefix ( "plugins" ), $data );
		}
	}
	
	// //plugin is exist:Success return true or return false
	function isPluginExist($pluginIdentifier, $userId) {
		$sql = "select * from " . $this->db->dbprefix ( "plugins" ) . " where user_id = $userId and identifier = '$pluginIdentifier'";
		$query = $this->db->query ( $sql );
		if ($query && $query->num_rows () > 0) {
			return true;
		}
		return false;
	}
	
	// //fobidden one plugin
	function disablePlugin($userId, $pluginIdentifier) {
		$data = array (
				'status' => 0 
		);
		// $this->db->where ( 'user_id', $userId );
		$this->db->where ( 'identifier', $pluginIdentifier );
		$this->db->update ( $this->db->dbprefix ( "plugins" ), $data );
	}
	
	// //get plugin's status 0-->active; 1-->fobidden
	function getPluginStatus($userId, $identifier) {
		// $this->db->where ( 'user_id', $userId );
		$this->db->where ( 'identifier', $identifier );
		$query = $this->db->get ( $this->db->dbprefix ( "plugins" ) );
		if ($query && $query->num_rows () > 0) {
			return $query->first_row ()->status;
		}
		return 0;
	}

	function getPluginStatusByIdentifier($identifier){
		$sql = "select * from ".$this->db->dbprefix('plugins')." where identifier='".$identifier."';"; 
                        // echo $sql;
        $ret =$this->db->query($sql);
        if($ret!=null&& $ret->num_rows()>0){
             $arraa = $ret->result_array();
            return $arraa[0]['status'];
         }else{
         	return 0;
         }
	}

	function getUserActive($uid){
		$sql ="select * from ".$this->db->dbprefix('userkeys')." where user_id='".$uid."';";
		$ret = $this->db->query($sql);
		if($ret!=null&& $ret->num_rows()>0){
			return true;
		}else{
			return false;
		}

	}

}





