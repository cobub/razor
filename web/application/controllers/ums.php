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
class Ums extends CI_Controller {
    function Ums() {
        parent::__construct();

        $isRedisEnabled = $this -> config -> item('redis');
        if ($isRedisEnabled) {
            $servicePrefix = "redis_service";
        } else {
            $servicePrefix = "service";
        }
        $this -> load -> model($servicePrefix . '/utility', 'utility');
        $this -> load -> model($servicePrefix . '/event', 'event');
        $this -> load -> model($servicePrefix . '/userlog', 'userlog');
        $this -> load -> model($servicePrefix . '/update', 'update');
        $this -> load -> model($servicePrefix . '/clientdata', 'clientdata');
        $this -> load -> model($servicePrefix . '/activitylog', 'activitylog');
        $this -> load -> model($servicePrefix . '/onlineconfig', 'onlineconfig');
        $this -> load -> model($servicePrefix . '/uploadlog', 'uploadlog');
        $this -> load -> model($servicePrefix . '/usertag', 'usertag');
    }

    /*
     * Interface to accept event log by client
     * Must pass parameters:appkey,event_identifier,time,activity,version
     */
    function postEvent() {
        $this -> load -> model('servicepublicclass/eventpublic', 'eventpublic');
        if (!isset($_POST["content"])) {

            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }

        $encoded_content = $_POST["content"];
        log_message("debug", $encoded_content);
        $content = json_decode($encoded_content);
        $event = new eventpublic();
        $event -> loadevent($content);
        $retParamsCheck = $this -> utility -> isPraramerValue($content, $array = array('appkey', 'event_identifier', 'time', 'activity', 'version'));

        if ($retParamsCheck['flag'] <= 0) {
            $ret = array('flag' => -2, 'msg' => $retParamsCheck['msg']);
            echo json_encode($ret);
            return;
        }
        $key = $event -> appkey;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey ');
            echo json_encode($ret);
            return;
        } else {
            $isgetEventid = $this -> event -> addEvent($content);
            if (!$isgetEventid) {
                $ret = array('flag' => -5, 'msg' => 'event_identifier not defined in product with provided appkey');
                echo json_encode($ret);
                return;
            } else {
                $ret = array('flag' => 1, 'msg' => 'ok');
            }
            echo json_encode($ret);
        }
    }

    /*
     * Interface to accept error log by client
     */
    function postErrorLog() {
        $this -> load -> model('servicepublicclass/errorlogpublic', 'errorlogpublic');
        if (!isset($_POST["content"])) {
            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST["content"];
        $content = json_decode($encoded_content);
        log_message('debug', $encoded_content);
        $errorlog = new errorlogpublic();
        $errorlog -> loaderrorlog($content);
        $retParamsCheck = $this -> utility -> isPraramerValue($content, $array = array("appkey", "stacktrace", "time", "activity", "os_version", "deviceid"));
        if ($retParamsCheck["flag"] <= 0) {
            $ret = array('flag' => -2, 'msg' => $retParamsCheck['msg']);
            echo json_encode($ret);
            return;
        }
        $key = $errorlog -> appkey;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey  ');
            echo json_encode($ret);
            return;
        } else {
            try {
                $this -> userlog -> addUserlog($content);
                $ret = array('flag' => 1, 'msg' => 'ok');
            } catch ( Exception $ex ) {
                $ret = array('flag' => -4, 'msg' => 'DB Error');
            }
        }
        echo json_encode($ret);
    }

    /*
     * Interface to accept client data
     */
    function postClientData() {
        $this -> load -> model('servicepublicclass/clientdatapublic', 'clientdatapublic');
        if (!isset($_POST["content"])) {
            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST["content"];
        $content = json_decode($encoded_content);
        $clientdata = new clientdatapublic();
        $clientdata -> loadclientdata($content);
        $retParamsCheck = $this -> utility -> isPraramerValue($content, $array = array("appkey", "platform", "os_version", "language", "deviceid", "resolution"));
        if ($retParamsCheck["flag"] <= 0) {
            $ret = array('flag' => -2, 'msg' => $retParamsCheck['msg']);
            echo json_encode($ret);
            return;
        }
        $key = $clientdata -> appkey;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'Invalid app key');
            echo json_encode($ret);
            return;
        } else {
            try {
                $this -> clientdata -> addClientdata($content);
                $ret = array('flag' => 1, 'msg' => 'ok');
            } catch ( Exception $ex ) {
                $ret = array('flag' => -4, 'msg' => 'DB Error');
            }
        }
        log_message('debug', json_encode($ret));
        echo json_encode($ret);
    }

    /*
     * Interface to accept Activity Log
     */
    function postActivityLog() {
        $this -> load -> model('servicepublicclass/activitypublic', 'activitypublic');

        if (!isset($_POST["content"])) {
            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST["content"];
        log_message("debug", $encoded_content);
        $content = json_decode($encoded_content);
        $activitylog = new activitypublic();
        $activitylog -> loadactivity($content);
        $retParamsCheck = $this -> utility -> isPraramerValue($content, $array = array("appkey", "session_id", "start_millis", "end_millis", "duration", "activities"));
        if ($retParamsCheck["flag"] <= 0) {
            $ret = array('flag' => -2, 'msg' => $retParamsCheck['msg']);
            echo json_encode($ret);
            return;
        }
        $key = $activitylog -> appkey;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey ');
            echo json_encode($ret);
            return;
        } else {
            try {
                $this -> activitylog -> addActivitylog($content);
                $ret = array('flag' => 1, 'msg' => 'ok');
            } catch ( Exception $ex ) {
                $ret = array('flag' => -4, 'msg' => 'DB Error');
            }
        }
        echo json_encode($ret);
    }

    /*
     * Interface to accept user id  for user tag
     */

    function postTag() {
        $this -> load -> model('servicepublicclass/posttagpublic', 'posttagpublic');
        if (!isset($_POST["content"])) {
            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST['content'];
        log_message("debug", $encoded_content);
        $content = json_decode($encoded_content);
        $posttag = new posttagpublic();
        $posttag -> loadtag($content);
        $retParamsCheck = $this -> utility -> isPraramerValue($content, $array = array("deviceid", "tags", "productkey"));
        if ($retParamsCheck["flag"] <= 0) {
            $ret = array('flag' => -2, 'msg' => $retParamsCheck['msg']);
            echo json_encode($ret);
            return;
        }
        $key = $posttag -> productkey;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey  ');
            echo json_encode($ret);
            return;
        } else {
            try {
                $this -> usertag -> addUserTag($content);
                $ret = array('flag' => 1, 'msg' => 'ok');
            } catch ( Exception $ex ) {
                $ret = array('flag' => -4, 'msg' => 'DB Error');
            }
        }
        echo json_encode($ret);
    }

    /*
     * Interface to accept total log
     */
    function uploadLog() {
        $this -> load -> model('servicepublicclass/uploadlogpublic', 'uploadlogpublic');
        if (!isset($_POST["content"])) {
            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST['content'];
        log_message("debug", $encoded_content);
        $content = json_decode($encoded_content);
        $uploadlog = new uploadlogpublic();
        $uploadlog -> loaduploadlog($content);
        $key = $uploadlog -> appkey;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey  ');
            echo json_encode($ret);
            return;
        } else {
            try {
                $this -> uploadlog -> addUploadlog($content);
                $ret = array('flag' => 1, 'msg' => 'ok');
            } catch ( Exception $ex ) {
                $ret = array('flag' => -4, 'msg' => 'DB Error');
            }
        }
        echo json_encode($ret);
    }

    function Gzip() {
        $data = $_POST['content'];
        $this -> utility -> gzdecode($data);
    }

    /*
     * Get Application Update by version no
     */
    function getApplicationUpdate() {
        $this -> load -> model('servicepublicclass/applicationupdatepublic', 'applicationupdatepublic');
        header("Content-Type:application/json");
        if (!isset($_POST["content"])) {

            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST["content"];
        log_message("debug", $encoded_content);
        $content = json_decode($encoded_content);
        $application = new applicationupdatepublic();
        $application -> loadapplicationupdate($content);
        $retParamsCheck = $this -> utility -> isPraramerValue($content, $array = array("appkey", "version_code"));
        if ($retParamsCheck["flag"] <= 0) {
            $ret = array('flag' => -2, 'msg' => $retParamsCheck['msg']);
            echo json_encode($ret);
            return;
        }
        $key = $application -> appkey;
        $version_code = $application -> version_code;
        $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
        if (!$isKeyAvailable) {
            $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey ');
            echo json_encode($ret);
            return;
        } else {
            $haveNewversion = $this -> update -> haveNewversion($key, $version_code);
            if (!$haveNewversion) {
                $ret = array('flag' => -7, 'msg' => 'no new version');
                echo json_encode($ret);
                return;
            } else {
                try {
                    $product = $this -> update -> getProductUpdate($key);
                    if ($product != null) {
                        $ret = array('flag' => 1, 'msg' => 'ok', 'fileurl' => $product -> updateurl, 'forceupdate' => $product -> man, 'description' => $product -> description, 'time' => $product -> date, 'version' => $product -> version);
                    }
                } catch ( Exception $ex ) {
                    $ret = array('flag' => -4, 'msg' => 'DB Error');
                }
            }
            echo json_encode($ret);
        }
    }

    /*
     * Used to get Online Configuration
     */
    function getOnlineConfiguration() {
        $this -> load -> model('servicepublicclass/onlineconfigpublic', 'onlineconfigpublic');
        if (!isset($_POST["content"])) {

            $ret = array('flag' => -3, 'msg' => 'Invalid content.');
            echo json_encode($ret);
            return;
        }
        $encoded_content = $_POST['content'];
        log_message('debug', $encoded_content);
        $content = json_decode($encoded_content);
        $onlineconfig = new onlineconfigpublic();
        $onlineconfig -> loadonlineconfig($content);
        $key = $onlineconfig -> appkey;
        log_message('debug', $key);
        if (!isset($key)) {
            $ret = array('flag' => -2, 'msg' => 'Invalid key.');
            echo json_encode($ret);
            return;
        } else {
            $isKeyAvailable = $this -> utility -> isKeyAvailale($key);
            if (!$isKeyAvailable) {
                $ret = array('flag' => -1, 'msg' => 'NotAvailable appkey ');
                echo json_encode($ret);
                return;
            } else {
                try {
                    $productid = $this -> onlineconfig -> getProductid($key);
                    $configmessage = $this -> onlineconfig -> getConfigMessage($productid);
                    if ($configmessage != null) {
                        $ret = array('flag' => 1, 'msg' => 'ok', 'autogetlocation' => $configmessage -> autogetlocation, 'updateonlywifi' => $configmessage -> updateonlywifi, 'sessionmillis' => $configmessage -> sessionmillis, 'reportpolicy' => $configmessage -> reportpolicy);
                    }
                } catch ( Exception $ex ) {
                    $ret = array('flag' => -4, 'msg' => 'DB Error');
                }
            }
            echo json_encode($ret);
        }
    }

}
