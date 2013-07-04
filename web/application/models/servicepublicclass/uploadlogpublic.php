<?php
class uploadlogpublic extends CI_Model {

    var $eventInfo;
    var $errorInfo;
    var $appkey;
    var $clientData;
    var $activityInfo;
    var $tags;

    function loaduploadlog($content) {
        $this -> eventInfo = isset($content -> eventInfo) ? $content -> eventInfo : '';
        $this -> errorInfo = isset($content -> errorInfo) ? $content -> errorInfo : '';
        $this -> appkey = isset($content -> appkey) ? $content -> appkey : '';
        $this -> clientData = isset($content -> clientData) ? $content -> clientData : '';
        $this -> activityInfo = isset($content -> activityInfo) ? $content -> activityInfo : '';
        $this -> tags = isset($content -> tags) ? $content -> tags : '';
    }

}
