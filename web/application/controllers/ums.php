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
     * Interface to accept clientdata
     *
     * @return void
     */
    function clientdata()
    {
        $ret = $this->_checkJsonData();
		if ($ret != null) {
            echo json_encode($ret);
			return;
        }
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/clientdata', 'clientdata');
				$clientdata = $this->_jsondata->data;
				$num = count($clientdata);
        		for ($i=0; $i <$num; $i++) {
					$this->clientdata->addClientdata($clientdata[$i]);
				}
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
            log_message('debug', json_encode($ret));
        	echo json_encode($ret);
        }
        
    }

    /**
     * Interface to accept Activity Log
     *
     * @return void
     */
    function usinglog()
    {
        $ret = $this->_checkJsonData();
		if ($ret != null) {
            echo json_encode($ret);
			return;
        }
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/activitylog', 'activitylog');
				$usinglogdata = $this->_jsondata->data;
				$num = count($usinglogdata);
        		for ($i=0; $i < $num; $i++) {
					$this->activitylog->addActivitylog($usinglogdata[$i]);
				}
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
			echo json_encode($ret);
        }
        
    }

    /**
     * Interface to accept event log by client
     * Must pass parameters:appkey,event_identifier,time,activity,version
     *
     * @return void
     */
    function eventlog()
    {
        $ret = $this->_checkJsonData();
		if ($ret != null) {
            echo json_encode($ret);
			return;
        }
        try {
            $this->load->model($this->_prefix . '/event', 'event');
			$eventdata = $this->_jsondata->data;
			$num = count($eventdata);
        	for ($i=0; $i < $num ; $i++) {
				$this->event->addEvent($eventdata[$i]);
			}
            $ret = array(
                'flag' => 1,
                'msg' => 'ok'
            );
		} catch (Exception $ex) {
            $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
            );
        }
        echo json_encode($ret);
		return;
    }

    /**
     * Interface to accept error log by client
     *
     * @return void
     */
    function errorlog()
    {
        $ret = $this->_checkJsonData();
		if ($ret != null) {
            echo json_encode($ret);
			return;
        }
		 
        try {
            $this->load->model($this->_prefix . '/userlog', 'userlog');
			$errordata = $this->_jsondata->data;
			$num = count($errordata);
			for ($i=0; $i < $num; $i++) { 
				 $this->userlog->addUserlog($errordata[$i]);
			}
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
        echo json_encode($ret);
		return;
    }

    
    /**
     * Interface to accept user id  for user tag
     *
     * @return void
     */
    function tag()
    {
        $ret = $this->_checkJsonData();
		if($ret!=null){
			echo json_encode($ret);
			return;
		}
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/usertag', 'usertag');
				$tag = $this->_jsondata->data;
				$num = count($tag);
        		for ($i=0; $i < $num ; $i++) {
					$this->usertag->addUserTag($tag[$i]);
				}
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
		return;
    }

  

    /**
     * Get Application Update by version no
     *
     * @return void
     */
    function appupdate()
    {
        $ret = $this->checkUpdateJsonFailed();
        if ($ret != null) {
            echo json_encode($ret);
			return;
        }
		
		$returnCode = array(
            	"domain"=>'',
            	"type"=>'S',
            	"code"=>'AAAAAA'
            );

        if ($ret == null) {
            $this->load->model($this->_prefix . '/update', 'update');
			$key = $this->_jsondata->appKey;
        	$version_code = $this->_jsondata->versionCode;
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
                            'fileUrl' => $product->updateurl,
                            'forceupdate' => $product->man,
                            'description' => $product->description,
                            'time' => $product->date,
                            'versionName' => $product->version
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

		$ret['returnCode'] = $returnCode;
		$reply['reply'] = $ret;
		
        echo json_encode($reply);
		return;
    }

    /**
     * Used to get Online Configuration
     *
     * @return void
     */
    function pushpolicyquery()
    {
		$ret = $this->checkConfigJsonFailed();
		if($ret!=null){
			echo json_encode($ret);
			return;
		}
		
		$returnCode = array(
            	"domain"=>'',
            	"type"=>'S',
            	"code"=>'AAAAAA'
            );
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/onlineconfig', 'onlineconfig');

                $productid = $this->onlineconfig->getProductid($this->_jsondata->appKey);
                $configmessage = $this->onlineconfig->getConfigMessage($productid);
                if ($configmessage != null) {
                    $ret = array(
                        'fileSize'=>1,
                    	'flag' => 1,
                    	'msg' => 'ok',
                    	'autoGetLocation' => $configmessage->autogetlocation,
                    	'updateOnlyWifi' => $configmessage->updateonlywifi,
                    	'sessionMillis' => $configmessage->sessionmillis,
                    	'intervalTime' =>5,
                    	'reportPolicy' => $configmessage->reportpolicy
                    );
                }
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;

        	echo json_encode($reply);
			return;
        }

    }

    /**
     * Check json format
     *
     * @return array
     */
    private function _checkJsonData()
    {
		$encoded_content = file_get_contents($this->rawdata, 'r');
        if (empty($encoded_content) || (strlen($encoded_content)<8) ) {
            $ret = array(
                'flag' => -3,
                'msg' => 'Invalid content from php://input.'
            );
            return $ret;
        } else {
            $encoded_content = urldecode($encoded_content);
            $jsonstr = substr($encoded_content, 8);
            $this->_jsondata = json_decode($jsonstr);
            if ( !isset($this->_jsondata->data) || !is_array($this->_jsondata->data)) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'Parse json data failed.' 
                );
                return $ret;
            }
             if ( is_array($this->_jsondata->data) && count($this->_jsondata->data)<1) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'Parse json data failed.'
                );
                return $ret;
            }        
        }
		////check appkey;
		$alldata = $this->_jsondata->data;
		for ($i=0; $i < count($alldata); $i++) { 
			if (!property_exists($alldata[$i], 'appkey')) {
            	$ret = array(
                	'flag' => -5,
                	'msg' => 'Appkey is not set in json.'
            	);
            	 return $ret;
        	}

        	$appkey = $alldata[$i]->appkey;
        	if (!$this->utility->isKeyAvailale($appkey)) {
            	$ret = array(
                	'flag' => -1,
                	'msg' => 'Invalid appkey:' . $appkey
            	);
            	 return $ret;
        	}
		}

        return null;
    }

 	private function checkConfigJsonFailed()
    {
        // //Post content is not content=xxxxxx
        $encoded_content = file_get_contents($this->rawdata, 'r');
		$returnCode = array(
            	"domain"=>'',
            	"type"=>'S',
            	"code"=>'AAAAAA'
            );
           
        if (empty($encoded_content)) 
        {
            $ret = array(
                'fileSize'=>1,
                'flag' => -3,
                'msg' => 'Invalid content from php://input.'
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        } else {
            //remove 'content='
            $encoded_content = urldecode($encoded_content);
            $jsonstr = substr($encoded_content, 8);
            $this->_jsondata = json_decode($jsonstr);

            if ($this->_jsondata == null) {
                $ret = array(
                	'fileSize'=>1,
                    'flag' => -4,
                    'msg' => 'Parse json data failed. The error No. is ' . json_last_error()
                );
				$ret['returnCode'] = $returnCode;
				$reply['reply'] = $ret;
            	return $reply;
            }
        }

        if (!property_exists($this->_jsondata, 'appKey')) {
            $ret = array(
                'fileSize'=>1,
                'flag' => -5,
                'msg' => 'Appkey is not in json.'
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        }

        $appkey = $this->_jsondata->appKey;

        if (!$this->utility->isKeyAvailale($appkey)) {
            $ret = array(
                'fileSize'=>1,
                'flag' => -1,
                'msg' => 'Invalid appkey:' . $appkey
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        }

        return null;
    }

	private function checkUpdateJsonFailed()
    {
        // //Post content is not content=xxxxxx
        $encoded_content = file_get_contents($this->rawdata, 'r');
		$returnCode = array(
            	"domain"=>'',
            	"type"=>'S',
            	"code"=>'AAAAAA'
            );
           
        if (empty($encoded_content)) 
        {
            $ret = array(
                'flag' => -3,
                'msg' => 'Invalid content from php://input.'
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        } else {
            //remove 'content='
            $encoded_content = urldecode($encoded_content);
            $jsonstr = substr($encoded_content, 8);
            $this->_jsondata = json_decode($jsonstr);

            if ($this->_jsondata == null) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'Parse json data failed. The error No. is ' . json_last_error()
                );
				$ret['returnCode'] = $returnCode;
				$reply['reply'] = $ret;
            	return $reply;
            }
        }

        if (!property_exists($this->_jsondata, 'appKey')) {
            $ret = array(
                'flag' => -5,
                'msg' => 'Appkey is not in json.'
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        }
		
		 if (!property_exists($this->_jsondata, 'versionCode')) {
            $ret = array(
                'flag' => -5,
                'msg' => 'versionCode is not in json.'
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        }

        $appkey = $this->_jsondata->appKey;

        if (!$this->utility->isKeyAvailale($appkey)) {
            $ret = array(
                'flag' => -1,
                'msg' => 'Invalid appkey:' . $appkey
            );
			$ret['returnCode'] = $returnCode;
			$reply['reply'] = $ret;
            return $reply;
        }

        return null;
    }

	/**
     * Interface to accept total log 
     *
     * @return void
     */
     /*
	  * disable
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
	*/
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
     * Interface to accept Push Id
     *
     * @return void
     */
     /*
	  * disable
    function postPushid()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/deviceidpushid', 'pushid');
                $this->pushid->addDeviceidPushid($this->_jsondata);
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
	  */
	
	/**
     * Interface to accept User Id
     *
     * @return void
     */
     /*
	  * disable
    function postUserid()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/deviceiduid', 'userid');
                $this->userid->addDeviceidUid($this->_jsondata);
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
	*/


}
