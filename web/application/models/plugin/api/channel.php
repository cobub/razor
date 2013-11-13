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
class Channel extends CI_Model {
	function __construct() {
		$this->load->database ();
		$this->load->model ( 'common' );
	}
	
	// get all platforms
	function getplatform() {
		$sql = "select * from  " . $this->db->dbprefix ( 'platform' ) . "  ";
		$query = $this->db->query ( $sql );
		if ($query != null && $query->num_rows () > 0) {
			return $query->result_array ();
		}
		return null;
	}
	
	// //1=>Android,2=>iOS,3=>windows phone
	// through platform get channel
	function getallchanbyplatform($platform) {
		$userid = $this->common->getUserId ();
		$sql = "select * from  " . $this->db->dbprefix ( 'channel' ) . "  where active=1 and platform='$platform' and type='system' union 
		    select * from  " . $this->db->dbprefix ( 'channel' ) . "  where active=1 and platform='$platform' and type='user'and user_id=$userid";
		$query = $this->db->query ( $sql );
		
		if ($query != null && $query->num_rows () > 0) {
			return $query->result_array ();
		}
		
		return null;
	}

	//get all System channels one platform 
	function getallsychannelbyplatform($platform)
	{
		$sql = "select c.*,p.name from  ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."  p on c.platform = p.id where c.type='system' and c.active=1 and c.platform=$platform ";
		$query = $this->db->query($sql);
		if($query!=null&&$query->num_rows()>0)
		{
			return $query->result_array();
		}
		return null;
	}
	
	//through $userid,$platform get self-built channels
	function getdechannelbyplatform($userid,$platform)
	{
		$sql = "select c.*,p.name from ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."   p on c.platform = p.id where c.user_id = $userid and c.type='user' and c.active=1 and c.platform=$platform ";
		$query = $this->db->query($sql);
		if($query!=null&&$query->num_rows()>0)
		{
			return $query->result_array();
		}
		return null;
	}
	
}