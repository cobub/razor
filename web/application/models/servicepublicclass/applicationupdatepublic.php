<?php
class applicationupdatepublic extends CI_Model {

    var $appkey;
    var $version_code;

    function loadapplicationupdate($content) {
        $this -> appkey = $content -> appkey;
        $this -> version_code = $content -> version_code;
    }

}
