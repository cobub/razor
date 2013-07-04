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
class Userlog extends CI_Model
{
	function Userlog()
	{
		parent::__construct();
		$this->load->database();
	}	
	
	function addUserlog($content)
	{
		$this->load->model('servicepublicclass/errorlogpublic','errorlogpublic');
		$userlog= new errorlogpublic();
		$userlog->loaderrorlog($content);
		$strArr=explode("\n",$userlog->stacktrace);
		if(count($strArr) >= 3)
		{
			$title= $strArr[0]."\n".$strArr[1]."\n".$strArr[2];		  
		}
		else
		{
			$title= $strArr[0];
		}
		$nowtime = date ( 'Y-m-d H:i:s');
		if(isset($userlog->time)){
			$nowtime=$userlog->time;
			if(strtotime($nowtime)<strtotime('1970-01-01 00:00:00')||
					strtotime($nowtime)==''){
				$nowtime = date ( 'Y-m-d H:i:s');
			}
		}
		$data = array(
			'appkey' => $userlog->appkey,
		    'title' => $title,
			'stacktrace'=> $userlog->stacktrace,
			'os_version'=> $userlog->os_version,
			'time' => $nowtime,
			'device' => $userlog->deviceid,
			'activity'=>$userlog->activity,
		    'version'=>isset($userlog->version)?$userlog->version:''
		
		);
		
		$this->db->insert('errorlog',$data);
	}
}
?>