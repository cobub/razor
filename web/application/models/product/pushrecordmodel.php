<?php
class PushRecordModel extends CI_Model
{
	function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'common' );
	}
	
	function confirm($UserId,$UserName, $AppName, $ChannnelName, $PushNum, $Content, $Date) {
		/* $data = array (
				'user_id' => $UserId,
				'user_name'=>$UserName,
				'appname' => $AppName,
				'channel_name' => $ChannnelName,
				'push_num' => $PushNum,
				'content' => $Content,
				'date' => $Date 
		);
		
		 $flag = $this->db->insert ( $this->db->dbprefix ( 'push_record' ), $data );
		if($flag)
		{
			$ret = array('flag'=> '1',
					'msg'=>'Record Success!');
		}
		else
		{
			$ret = array('flag'=> '0',
					'msg'=>'Record Failed!');
		}
		
		return $ret; */
	
	}
	
}