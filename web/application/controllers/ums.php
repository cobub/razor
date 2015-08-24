<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * UMS Controller
 *
 * Post interface controller, receiver all post data and save them to mysql or
 * redis.
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Ums extends CI_Controller
{
    private $_jsondata;
    private $_prefix;
    var $rawdata = "php://input";

    /**
     * Ums
     *
     * @return void
     */
    function Ums()
    {
        parent::__construct();
        $isRedisEnabled = $this->config->item('redis');
        if ($isRedisEnabled) {
            $this->_prefix = "redis_service";
        } else {
            $this->_prefix = "service";
        }
        $this->load->model($this->_prefix . '/utility', 'utility');

    }

    /**
     * Interface to accept client data
     *
     * @return void
     */
    function postClientData()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/clientdata', 'clientdata');
                $this->clientdata->addClientdata($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        log_message('debug', json_encode($ret));
        echo json_encode($ret);
    }

    /**
     * Interface to accept Activity Log
     *
     * @return void
     */
    function postActivityLog()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/activitylog', 'activitylog');
                $this->activitylog->addActivitylog($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
     * Interface to accept event log by client
     * Must pass parameters:appkey,event_identifier,time,activity,version
     *
     * @return void
     */
    function postEvent()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            $this->load->model($this->_prefix . '/event', 'event');
            $isgetEventid = $this->event->addEvent($this->_jsondata);
            if (!$isgetEventid) {
                $ret = array(
                    'flag' => -5,
                    'msg' => 'event_identifier is not defined'
                );
                echo json_encode($ret);
                return;
            } else {
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
     * Interface to accept error log by client
     *
     * @return void
     */
    function postErrorLog()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/userlog', 'userlog');
                $this->userlog->addUserlog($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
     * Interface to accept total log
     *
     * @return void
     */
    function uploadLog()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/uploadlog', 'uploadlog');
                $this->uploadlog->addUploadlog($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
     * Interface to accept user id  for user tag
     *
     * @return void
     */
    function postTag()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/usertag', 'usertag');
                $this->usertag->addUserTag($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
     * Uncompress the gzipped data
     *
     * @return void
     */
    function unCompressGzip()
    {
        $data = $_POST['content'];
        $this->utility->gzdecode($data);
    }

    /**
     * Get Application Update by version no
     *
     * @return void
     */
    function getApplicationUpdate()
    {
        $ret = $this->_checkJsonData();
        header("Content-Type:application/json");
        if ($ret == null) {
            $this->load->model($this->_prefix . '/update', 'update');
            $key = $this->_jsondata->appkey;
            $version_code = $this->_jsondata->version_code;
            $haveNewversion = $this->update->haveNewversion($key, $version_code);
            if (!$haveNewversion) {
                $ret = array(
                    'flag' => -7,
                    'msg' => 'no new version'
                );
            } else {
                try {
                    $product = $this->update->getProductUpdate($key);
                    if ($product != null) {
                        $ret = array(
                            'flag' => 1,
                            'msg' => 'ok',
                            'fileurl' => $product->updateurl,
                            'forceupdate' => $product->man,
                            'description' => $product->description,
                            'time' => $product->date,
                            'version' => $product->version
                        );
                    }
                } catch ( Exception $ex ) {
                    $ret = array(
                        'flag' => -4,
                        'msg' => 'DB Error'
                    );
                }
            }
        }
        echo json_encode($ret);
    }

    /**
     * Used to get Online Configuration
     *
     * @return void
     */
    function getOnlineConfiguration()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/onlineconfig', 'onlineconfig');
                $productid = $this->onlineconfig->getProductid($key);
                $configmessage = $this->onlineconfig->getConfigMessage($productid);
                if ($configmessage != null) {
                    $ret = array(
                        'flag' => 1,
                        'msg' => 'ok',
                        'autogetlocation' => $configmessage->autogetlocation,
                        'updateonlywifi' => $configmessage->updateonlywifi,
                        'sessionmillis' => $configmessage->sessionmillis,
                        'reportpolicy' => $configmessage->reportpolicy
                    );
                }
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
     * Check json format
     *
     * @return array
     */
    private function _checkJsonData()
    {
        $encoded_content = file_get_contents($this->rawdata, 'r');
        if (empty($encoded_content)) {
            $ret = array(
                'flag' => -3,
                'msg' => 'Invalid content from php://input.'
            );
            return $ret;
        } else {
            //remove 'content=', and urldecode the post json string.
            $jsonstr = urldecode(substr($encoded_content, 8));
            $this->_jsondata = json_decode($jsonstr);

            if ($this->_jsondata == null) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'Parse jsondata failed. Error No. is ' . 
                    json_last_error()
                );
                return $ret;
            }
        }

        if (!property_exists($this->_jsondata, 'appkey')) {
            $ret = array(
                'flag' => -5,
                'msg' => 'Appkey is not set in json.'
            );
            return $ret;
        }

        $appkey = $this->_jsondata->appkey;

        if (!$this->utility->isKeyAvailale($appkey)) {
            $ret = array(
                'flag' => -1,
                'msg' => 'Invalid app key:' . $appkey
            );
            return $ret;
        }

        return null;
    }

}
