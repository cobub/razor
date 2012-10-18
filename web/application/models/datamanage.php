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
class Datamanage extends CI_Model
{      
		function __construct()
		{		
			parent::__construct ();
			$this->load->config('tank_auth', TRUE);
			require_once('PasswordHash.php');
			
		}
		
		//Returns the current URL does not include the index.php
		function  createurl()
		{
			$url="http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
			$res=explode("/index",$url);
			$newurl=$res['0'];
			return $newurl;
			
		}
        // create superuser
		function createuser($username, $email, $password, $email_activation)
		{		
			    $this->load->database();
				// Hash password using phpass
				$hasher = new PasswordHash(
						$this->config->item('phpass_hash_strength', 'tank_auth'),
						$this->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($password);	           
				$data = array(
					'username'	=> $username,
					'password'	=> $hashed_password,
					'email'		=> $email,
					'last_ip'	=> $this->input->ip_address(),
				);
	
				if ($email_activation) {
					$data['new_email_key'] = md5(rand().microtime());
				}
				if (!is_null($res = $this->create_user($data, !$email_activation))) {
					$data['user_id'] = $res['user_id'];
					$data['password'] = $password;
					unset($data['last_ip']);
					return $data;
				}
			
			return NULL;
		}	
		
		//create userinfo and insert to database
		function create_user($data, $activated = TRUE)
		{
			$this->load->database();
			$data['created'] = date('Y-m-d H:i:s');
			$data['activated'] = $activated ? 1 : 0;
	
			if ($this->db->insert('users', $data)) {
				$user_id = $this->db->insert_id();
				if ($activated)	$this->create_profile($user_id);				
				return array('user_id' => $user_id);
			}
			return NULL;
		}
		//active user
		function activateuser($user_id, $activation_key, $activate_by_email = TRUE)
			{
				$this->purge_na($this->config->item('email_activation_expire', 'tank_auth'));
		
				if ((strlen($user_id) > 0) AND (strlen($activation_key) > 0)) {
					return $this->activate_user($user_id, $activation_key, $activate_by_email);
				}
				return FALSE;
			}
	    //real active user
		function activate_user($user_id, $activation_key, $activate_by_email)
		{
			$this->load->database();
			$this->db->select('1', FALSE);
			$this->db->where('id', $user_id);
			if ($activate_by_email) {
				$this->db->where('new_email_key', $activation_key);
			} else {
				$this->db->where('new_password_key', $activation_key);
			}
			$this->db->where('activated', 0);
			$query = $this->db->get('users');
	
			if ($query->num_rows() == 1) {
	
				$this->db->set('activated', 1);
				$this->db->set('new_email_key', NULL);
				$this->db->where('id', $user_id);
				$this->db->update('users');
	
				$this->create_profile($user_id);
				return TRUE;
			}
			return FALSE;
		}
		function purge_na($expire_period = 172800)
		{
			$this->load->database();
			$this->db->where('activated', 0);
			$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
			$this->db->delete('users');
		}
		//create user
		function createsuperuser($username,$password,$email,$activated = TRUE)
		{
			    $this->load->database();
			    $data['created'] = date('Y-m-d H:i:s');
			    $data['activated'] = $activated ? 1 : 0;
				$hasher = new PasswordHash(
			    $this->config->item('phpass_hash_strength', 'tank_auth'),
				$this->config->item('phpass_hash_portable', 'tank_auth'));
			$hashed_password = $hasher->HashPassword($password);			
			$data = array(
				'username'	=> $username,
				'password'	=> $hashed_password,
				'email'		=> $email,
				'last_ip'	=> $this->input->ip_address(),
			);		    
		    if ($this->db->insert('users', $data)) {
				$user_id = $this->db->insert_id();
				if ($activated)	$this->create_profile($user_id);
				$this->insertrole($email);	
				return array('user_id' => $user_id);
			}
			return false;
		     	    
		}
		//insert default role
		function insertrole($email)
			{
				$this->load->database();
				$user = $this->get_user_by_email($email);
				if($user!=null && isset($user->id))
				{
					$data = array(
							'userid'=>$user->id,
					        'roleid'=>3
							);		
		      $this->db->insert('user2role',$data);
				}
			}
           //get user by eamil
			function get_user_by_email($email)
			{
				$this->load->database();
		     $this->db->where('LOWER(email)=', strtolower($email));
		
				$query = $this->db->get('users');
				if ($query->num_rows() == 1) return $query->row();
				return NULL;
			}
			//create profile
		 function create_profile($user_id)
		{     
			$this->load->database();
			$this->db->set('user_id', $user_id);
			return $this->db->insert('user_profiles');
		}
	
}