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
class User_profile extends CI_Model
{
		function __construct()
		{
			$this->load->database();
		}
		
		
		function getUserPorfile($uerid)
		{
//			$query = $this->db->query("select * from users where id = $uerid");
//			if($query!=null&&$query->num_rows()>0)
//			{
//			  $ret=array(
//			         'username'=>$query->first_row()->username,
//			          'email'=>$query->first_row()->email
//			 );
//			 return $ret;
//			}
        $sql = "select ".$this->db->dbprefix('users').".username,".$this->db->dbprefix('users').".email useremail,".$this->db->dbprefix('user_profiles').".* from ".$this->db->dbprefix('users')."   left join ".$this->db->dbprefix('user_profiles')."  on ".$this->db->dbprefix('user_profiles').".user_id = ".$this->db->dbprefix('users').".id where ".$this->db->dbprefix('users').".id = ".$uerid;
		$query = $this->db->query($sql);
		
		return $query->first_row();
		}				
		function addUserPorfile($userId,$username,$companyname,$contact,$telephone,$QQ,$MSN,$Gtalk)
		{
			
			$userId = $this->common->getUserId();
			$username = $this->input->post('username');
			$data = array(
				'companyname'=>$companyname,
				'contact' => $contact,
				'telephone' => $telephone,
				'QQ' => $QQ,
				'MSN' => $MSN,
				'Gtalk' => $Gtalk,
					);
			$query = $this->db->query("select * from ".$this->db->dbprefix('user_profiles')."  where user_id = $userId");
			if ($query!=null)
			{
				$this->db->where('user_id',$userId);
				$this->db->update('user_profiles',$data);
			}
			else 
			{$this->db->insert('user_profiles',$data);}
			$query = $this->db->query("select * from ".$this->db->dbprefix('users')." where id = ".$userId);
			$date2=array(
			'username'=>$username);
			if ($query!=null&&$query->num_rows()>0)
			{
				$this->db->where('id',$userId);
				$this->db->update('users',$date2);	
			}
		
		}
}