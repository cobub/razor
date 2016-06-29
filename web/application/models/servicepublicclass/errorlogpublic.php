<?php
class errorlogpublic extends CI_Model {
    var $appkey;
    var $stacktrace;
    var $os_version;
    var $time;
    var $deviceid;
    var $activity;
    var $version;
	var $error_type;
	var $session_id;
	var $useridentifier;
	var $devicename;
	var $lib_version;
    function loaderrorlog($content) {
        $this -> appkey = $content -> appkey;
        $this -> stacktrace = $content -> stacktrace;
        $this -> os_version = $content -> os_version;
        $this -> time = $content -> time;
        $this -> deviceid = $content -> deviceid;
        $this -> activity = $content -> activity;
        $this -> version = isset($content -> version) ? $content -> version : '';
		$this->error_type = isset($content->error_type) ? $content->error_type : 0;
		$this->session_id = isset($content->session_id) ? $content->session_id : '';
		$this->useridentifier = isset($content->useridentifier) ? $content->useridentifier : '';
		$this->devicename = isset($content->devicename) ? $content->devicename : '';
		$this->lib_version = isset($content->lib_version) ? $content->lib_version : '';
    }

}
