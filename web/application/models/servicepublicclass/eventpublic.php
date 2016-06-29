<?php
class eventpublic extends CI_Model {
    var $event_identifier;
    var $time;
    var $activity;
    var $appkey;
    var $acc;
    var $label;
    var $version;
    var $attachment;
    var $deviceid;
	var $useridentifier;
	var $session_id;
	var $lib_version;

    function loadevent($content) {
        $this->event_identifier = isset($content->event_identifier)?$content->event_identifier:'';
        $this->time = $content->time;
        $this->activity = isset($content->activity)?$content->activity:'';
        $this->appkey = isset($content->appkey)?$content->appkey:'';
        $this -> acc = isset($content -> acc) ? $content -> acc : 1;
        $this -> label = isset($content -> label) ? $content -> label : '';
        $this->version = isset($content->version)?$content->version:'';
        $this->attachment = isset($content->attachment) ? $content->attachment : '';
        $this->deviceid = isset($content->deviceid) ? $content->deviceid : '';
        $this->useridentifier = isset($content->useridentifier)?$content->useridentifier:'';
		$this->session_id = isset($content->session_id)?$content->session_id:'';
		$this->lib_version = isset($content->lib_version)?$content->lib_version:'';
    }

}
