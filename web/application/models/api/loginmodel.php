<?php
class loginmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database ();
	}
	
	function veriflogin($username,$password,$onlineip)
	{	
		try
		{
			$user=$this->verifyusername($username);
			$attnum=$this->get_attempts_num($onlineip, $username);
			if($user)
			{
				$pwd=$this->verifypassword($username, $password,$onlineip);
				if($pwd)
				{
					$sessionkey=$this->getsessionkey($username, $password);
					$logiofo=array(
							'flag'=>1,
							'msg'=>"success",
							'sessionkey'=>$sessionkey
					);
					return $logiofo;
				}
				else
				{					
					if($attnum<5)
					{
						$logiofo=array(
								'flag'=>-2,
								'msg'=>"Invalid password "
						);
					}
					else
					{
						$logiofo=array(
								'flag'=>-3,
								'msg'=>"too many login attempts "
						);
					}
					$this->increaseattempt($onlineip, $username);
					return $logiofo;
				}
			}
			else
			{
				if($attnum<5)
				{
					$logiofo=array(
							'flag'=>-1,
							'msg'=>"username not exists"
					);
				}
				else
				{
					$logiofo=array(
							'flag'=>-3,
							'msg'=>"too many login attempts "
					);
				}
				$this->increaseattempt($onlineip, $username);
				return $logiofo;
			}
		}
		catch ( Exception $ex )
		{
			$ret = array (
					'flag' => - 4,
					'msg' => 'DB Error'
			);
			return $ret;
		}
	}
	
	function verifyusername($username)
	{      
		$this->db->where('username',$username);		
		$result = $this->db->get('users');
		if($result && $result->num_rows()>0)
		{
			return true;
		}
		return false;
	}
	
	function verifypassword($username,$password,$onlineip)
	{		
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$result = $this->db->get('users');
		if($result && $result->num_rows()>0)
		{   
			$this->updatasessionkey($username,$password);
			$this->dealattempt_num($username, $password,$onlineip);
			return true;
		}
		return false;
	}
	
	function getsessionkey($username,$password)
	{
		$this->db->select('sessionkey');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$result = $this->db->get('users');
		if($result && $result->num_rows()>0)
		{
			$user= $result->row();
			return $user->sessionkey ;
		}
	}
	
	function increaseattempt($ip_address, $login)
	{
		$this->db->insert('login_attempts', array('ip_address' => $ip_address, 'login' => $login));
	}
	
	function get_attempts_num($ip_address, $login)
	{
		$this->db->select('1', FALSE);
		$this->db->where('ip_address', $ip_address);
		if (strlen($login) > 0) $this->db->or_where('login', $login);	
		$qres = $this->db->get('login_attempts');
		return $qres->num_rows();
	}
	
	function clear_attempts($ip_address, $login, $expire_period = 86400)
	{
		$this->db->where(array('ip_address' => $ip_address, 'login' => $login));	
		// Purge obsolete login attempts
		$this->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expire_period);	
		$this->db->delete('login_attempts');
	}
	
	function dealattempt_num($username,$password,$ip_address)
	{
		$this->db->select('activated');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$result = $this->db->get('users');
		if($result && $result->num_rows()>0)
		{
			$user= $result->row(); 
			if ($user->activated != 0) 
			{
				$this->clear_attempts($ip_address, $username);		
			}
		}
			
	}
	
	/*
	 * Generate user sessoionkey.
	*/
	function generate_session()
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?';
		$sessions='';
		$password = '';
		for ( $i = 0; $i <5; $i++ )
		{
			$password .= $chars[mt_rand(0, strlen($chars) - 1) ];
		}
		$sessions=md5($password.time());
		return $sessions;
	}
	
	function updatasessionkey($username,$password)
	{
		$sessionkey=$this->generate_session();
		$data = array(
				'sessionkey' => $sessionkey				
		);
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$this->db->update('users', $data);
	}
	
	
}