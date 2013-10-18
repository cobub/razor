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
class Activitylog extends CI_Model {
	function Activitylog() {
		parent::__construct();
		$this->load->library('redis');
	}	
	
	function addActivitylog($activitylog) {
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
		
		$productId = $this->utility->getProductIdByKey($activitylog->appkey);
		$key = "razor_r_ac_p_".$productId."_". date('Y-m-d-H-i-s', time());
		$this->redis->hset ($key,array("$activitylog->activities"=>1));
		$this->redis->expire($key,30*60);
		$this->processor->process ();
	}
}
?>
