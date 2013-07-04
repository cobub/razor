<?php
class errorlogpublic extends CI_Model {
    var $appkey;
    var $stacktrace;
    var $os_version;
    var $time;
    var $deviceid;
    var $activity;
    var $version;
    function loaderrorlog($content) {
        $this -> appkey = $content -> appkey;
        $this -> stacktrace = $content -> stacktrace;
        $this -> os_version = $content -> os_version;
        $this -> time = $content -> time;
        $this -> deviceid = $content -> deviceid;
        $this -> activity = $content -> activity;
        $this -> version = isset($content -> version) ? $content -> version : '';
    }

}
