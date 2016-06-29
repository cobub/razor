<?php
class activitypublic extends CI_Model {
	var $appkey;
	var $session_id;
	var $start_millis;
	var $end_millis;
	var $activities;
	var $duration;
	var $version;
	var $deviceid;
	var $useridentifier;
	var $lib_version;

	function loadactivity($content) {
		$this -> appkey = isset($content -> appkey)?$content -> appkey:'unknow';
		$this -> session_id = isset($content -> session_id)?$content -> session_id:'unknow';
		$this -> start_millis = $content -> start_millis;
		$this -> end_millis = $content -> end_millis;
		$this -> activities = $content -> activities;
		$this -> duration = $content -> duration;
		$this -> version = isset($content -> version) ? $content -> version : 'unknow';
		$this -> deviceid = $content -> deviceid;
		$this -> userid = $content -> useridentifier;
		$this -> lib_version = isset($content -> lib_version) ? $content -> lib_version : 'unknow';
	}

}
