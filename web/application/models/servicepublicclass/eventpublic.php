<?php
class eventpublic extends CI_Model {
    var $event_identifier;
    var $time;
    var $activity;
    var $appkey;
    var $acc;
    var $label;
    var $version;

    function loadevent($content) {
        $this -> event_identifier = $content -> event_identifier;
        $this -> time = $content -> time;
        $this -> activity = $content -> activity;
        $this -> appkey = $content -> appkey;
        $this -> acc = isset($content -> acc) ? $content -> acc : 1;
        $this -> label = isset($content -> label) ? $content -> label : '';
        $this -> version = $content -> version;
    }

}
