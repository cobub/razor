<?php
class onlineconfigpublic extends CI_Model {
    var $appkey;
    function loadonlineconfig($content) {
        $this -> appkey = isset($content -> appkey) ? $content -> appkey : '';
    }

}
