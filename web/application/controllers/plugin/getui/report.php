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
 * Report Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class report extends CI_Controller
{
    var $appid;

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->language('plugin_getui');
        $this->load->Model('common');
        $this->load->Model('plugin/getui/applistmodel', 'plugins');
        $this->common->requireLogin();
    }

    /**
     * Index funciton,load view report
     *
     * @return void
     */
    function index()
    {
        $productid = $_GET ['id'];
        $appid = $this->plugins->getAppid($productid);
         $data = array();
        $data ['appid'] = $appid;
        $num = $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getuiOnlineCnt", $data);
        $result= json_decode($num);
        $data['onlineuser']=0;
        if (isset($result->status)) {
            if ($result->status=='Succ') {
                $data['onlineuser']=$result->count;
            }
        }
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $days= abs(strtotime($toTime) - strtotime($fromTime))/60/60/24+1;
        if ($days>31) {
            $data['timeerror']=lang('time_chose_error');
        }
        $uid=$this->common->getUserId();
        $userinfo = $this->plugins->getUserinfo($uid);
        $userKey = $userinfo[0]['user_key'];
        $userSecret = $userinfo[0]['user_secret'];
        $data['user_key']=$userKey;
        $data['user_secret']=$userSecret;
        $pushrecords = $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getGetuiRecords", $data);
        $pushrecordsResult = json_decode($pushrecords);
        if (isset($pushrecordsResult->flag)) {
            if ($pushrecordsResult->flag>0) {
                $data['pushrecords']=$pushrecordsResult->records;
            }
        }
        $this->common->loadHeaderWithDateControl(lang('getui_data'));
        $this->load->view('plugin/getui/report', $data);
    }

    /**
     * GetRecords funciton
     *
     * @return json encode
     */
    function getRecords()
    {
        $data = array();
        $data ['appid'] = $_GET ['appid'];
        $uid=$this->common->getUserId();
        $userinfo = $this->plugins->getUserinfo($uid);
        $userKey = $userinfo[0]['user_key'];
        $userSecret = $userinfo[0]['user_secret'];
        $data['user_key']=$userKey;
        $data['user_secret']=$userSecret;
        $pushrecords = $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getGetuiRecords", $data);
        $arr =json_decode($pushrecords);
        $arr->appid=$_GET['appid'];
        echo json_encode($arr);
    }

    /**
     * Getdata funciton
     *
     * @return query ret
     */
    function getdata()
    {
        $appid = $_GET ['appid'];
        $type = $_GET ['type'];
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $data = array ('appid' => $appid, 'startTime' => $fromTime, 'endTime' => $toTime, 'type' => $type );
          $ret= $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getuiReport", $data);
        echo $ret;
    }

    /**
     * Gettaskdata funciton,load task report
     *
     * @return void
     */
    function gettaskdata()
    {
        $taskid = $_GET['taskid'];
        $appid = $_GET['appid'];
        $data = array();
        $uid=$this->common->getUserId();
        $userinfo = $this->plugins->getUserinfo($uid);
        $userKey = $userinfo[0]['user_key'];
        $userSecret = $userinfo[0]['user_secret'];
        $data['user_key']=$userKey;
        $data['user_secret']=$userSecret;
        $data['taskid']=$taskid;
        $data['appid']=$appid;
        $ret= $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getTaskdata", $data);
        $result= json_decode($ret);
        if (isset($result->flag)) {
            if ($result->flag==1) {
                    $retfromucenter = json_decode($result->result);
                if ($retfromucenter->result=='ok') {
                    $data['sendnum']=$retfromucenter->msgTotal;
                    $data['receivenum']=$retfromucenter->msgProcess;
                }
            }
        }
        $this->common->loadHeader('发送数和接收数报表');
        $this->load->view('plugin/getui/taskreport', $data);
    }
}
?>