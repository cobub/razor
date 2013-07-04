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
		$this -> load -> database();
	}

	function addActivitylog($content) {
		$this -> load -> model('servicepublicclass/activitypublic', 'activitypublic');
		$activitylog = new activitypublic();
		$activitylog -> loadactivity($content);
		$nowtime = date('Y-m-d H:i:s');
		if (isset($activitylog -> start_millis)) {
			$nowtime = $activitylog -> start_millis;
			if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
				$nowtime = date('Y-m-d H:i:s');
			}
		}
		$nowtime2 = date('Y-m-d H:i:s');
		if (isset($activitylog -> end_millis)) {
			$nowtime2 = $activitylog -> end_millis;
			if (strtotime($nowtime2) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime2) == '') {
				$nowtime2 = date('Y-m-d H:i:s');
			}
		}
		$data = array('appkey' => $activitylog -> appkey, 'session_id' => $activitylog -> session_id, 'start_millis' => $nowtime, 'end_millis' => $nowtime2, 'activities' => $activitylog -> activities, 'duration' => $activitylog -> duration, 'version' => isset($activitylog -> version) ? $activitylog -> version : '');

		$this -> db -> insert('clientusinglog', $data);
	}

}
?>