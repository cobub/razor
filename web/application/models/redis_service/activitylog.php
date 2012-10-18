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
class Activitylog extends CI_Model
{
	function Activitylog()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('redis');
	}	
	
	function addActivitylog($activitylog)
	{
		$data = array(
			'appkey' => $activitylog->appkey,
			'session_id'=> $activitylog->session_id,
			'start_millis'=> $activitylog->start_millis,
			'end_millis' => $activitylog->end_millis,
			'activities' => $activitylog->activities,
			'duration'=>$activitylog->duration,
			'version'=>isset($activitylog->version)?$activitylog->version:''
		);
		$this->redis->lpush("razor_clientusinglogs",serialize($data));
		$this->processor->process();
	}
}
?>