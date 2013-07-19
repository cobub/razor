<?php
class activitypublic extends CI_Model {
    var $appkey;
    var $session_id;
    var $start_millis;
    var $end_millis;
    var $activities;
    var $duration;
    var $version;

    function loadactivity($content) {
        $this -> appkey = $content -> appkey;
        $this -> session_id = $content -> session_id;
        $this -> start_millis = $content -> start_millis;
        $this -> end_millis = $content -> end_millis;
        $this -> activities = $content -> activities;
        $this -> duration = $content -> duration;
        $this -> version = isset($content -> version) ? $content -> version : '';

    }

}
