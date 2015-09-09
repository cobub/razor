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
 * PUSH Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class push extends CI_Controller
{
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
        $this->common->requireLogin();
        $this->load->Model('pluginM');
        $this->load->model('point_mark');
        $this->load->model('tag/tagmodel', 'tag');
    }

    /**
     * Index funciton
     *
     * @return json encode 
     */
    function index()
    {
        $appid = $_POST ['appid'];
        $appkey = $_POST['appkey'];
        $userKey = $_POST ['userKey'];
        $userSecret = $_POST ['userSecret'];
        $selectvalue = $_POST ['pushType'];
        $mastersecret = $_POST['mastersecret'];
        $tagvalue = $_POST['tagvalue'];
        // getted tag value to get deviceid
        $productid = $_POST['productid'];
        // $sendtime = $_POST['sendtime'];
        $transmissionContentNotify = $_POST ['transmissionContentNotify'];
        $notyCleared = $_POST ['notyCleared'];
        $notyBelled = $_POST ['notyBelled'];
        $notyVibrationed = $_POST ['notyVibrationed'];
        $offlined = $_POST ['offlined'];
        $logo_url = $_POST ['logo_url'];
        $pushUser = $_POST ['pushUser'];
        $appntitle = $_POST ['appntitle'];
        $appcontent = $_POST ['appcontent'];
        $offlineTime = $_POST ['offlineTime'];
        $data = array(
                'appid' => $appid,
                'appkey'=>$appkey,
                'userKey' => $userKey,
                'userSecret' => $userSecret,
                'selectvalue' => $selectvalue,
                'mastersecret'=>$mastersecret,
                // 'sendtime'=>$sendtime,
                'transmissionContentNotify' => $transmissionContentNotify,
                'notyCleared' => $notyCleared,
                'notyBelled' => $notyBelled,
                'notyVibrationed' => $notyVibrationed,
                'offlined' => $offlined,
                'logo_url' => $logo_url,
                'pushUser' => $pushUser,
                'appntitle' => $appntitle,
                'appcontent' => $appcontent,
                'offlineTime' => $offlineTime 
        );
            $opencheck = $_POST ['opencheck'];
            $urladdress = $_POST ['urladdress'];
            $data ['opencheck'] = $opencheck;
            $data ['urladdress'] = $urladdress;
            $data['popTitle']=$_POST['popTitle'];
            $data['popPicture_url']=$_POST ['popPicture_url'];
            $data['showmessage']=$_POST ['showmessage'];
            $data['popFirstButton']=$_POST ['popFirstButton'];
            $data['popSecondButton']=$_POST ['popSecondButton'];
            $data['apkurladdress']=$_POST ['apkurladdress'];
            $data['apkname']=$_POST ['apkname'];
        if ($pushUser==1) {
                        $data['devicelist']='';
                        $data['tag'] = $tagvalue;
                        $push_time=date("Y-m-d H:i");
                        log_message("debug", "------------------推送时间：$push_time---------------------------");
                        log_message("debug", '参数=='.$data['appid']."    appkey==".$data['appkey']);
                        $result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push', $data);
                        log_message('debug', 'getui 返回值：'.$result);
        } else {
                $flag =true;
                $i=0;
            while ($flag) {
                        $devicelist=$this->tag->getDeviceidList($productid, $tagvalue, $i, 500);
                        $resarr = $devicelist->result_array();
                        $data['devicelist']=json_encode($resarr);
                        $data['tag'] = $tagvalue;
                        $push_time=date("Y-m-d H:i");
                        log_message("debug", "------------------推送时间：$push_time---------------------------");
                        log_message("debug", '参数=='.$data['appid']."    appkey==".$data['appkey']);
                        $result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push', $data);
                        log_message('debug', 'getui 返回值：' .$result);
                if (count($resarr)<500) {
                            $flag=false;
                }
                        $i=$i+1;
            }
        }
                $resu= json_decode($result, true);
        if ($resu['result']=='ok') {
                    $res = array('flag' => 1, 'msg' => 'ok');
                    $uid =$this->common->getUserId();
                    $arr=array(
                        'userid'=>$uid,
                        'productid'=>$productid,
                        'title'=>'push message by getui',
                        'description'=>"send message ".$appcontent,
                        'private'=>1,
                        'marktime'=> date("Y-m-d")
                    )
                    ;
                    $this->point_mark->addPointmark($arr);
        } else {
            $res = array(
                    'flag' => 0,
                    'msg' => $resu['msg']
            );
        }
        echo json_encode($res);
    }

    /**
     * Transmission funciton
     *
     * @return json encode
     */
    function transmission()
    {
        $appid = $_POST['appid'];
        $userKey = $_POST['userKey'];
        $pushUser = $_POST['pushUser'];
        $userSecret = $_POST['userSecret'];
        $mastersecret = $_POST['mastersecret'];
        $tagvalue = $_POST['tagvalue'];
        $appkey = $_POST['appkey'];
        $transmissionContentNotify = $_POST['transmissionContentNotify'];
        $offlined  = $_POST['offlined'];
        $offlineTime = $_POST['offlineTime'];
        $productid = $_POST['productid'];
        $data = array(
            'appid'=>$appid,
            'appkey'=>$appkey,
            'userKey'=>$userKey,
            'userSecret'=>$userSecret,
            'pushUser'=>$pushUser,
            'mastersecret'=>$mastersecret,
            'tagvalue'=>$tagvalue,
            'transmissionContentNotify'=>$transmissionContentNotify,
            'offlined'=>$offlined,
            'offlineTime'=>$offlineTime
            );
        if ($pushUser==1) {
                            $data['devicelist']='';
                            $data['tag'] = $tagvalue;
                            $result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push/transmission', $data);
        } else {
                $flag =true;
                $i=0;
            while ($flag) {
                            $devicelist=$this->tag->getDeviceidList($productid, $tagvalue, $i, 500);
                            $resarr = $devicelist->result_array();
                            $data['devicelist']=json_encode($resarr);
                            $data['tag'] = $tagvalue;
                            $result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push/transmission', $data);
                if (count($resarr)<500) {
                                $flag=false;
                }
                            $i=$i+1;
            }
        }
        $resu = json_decode($result,true);
        if ($resu['result']=='ok') {
            $res = array('flag' => 1, 'msg' => 'ok');
            $uid =$this->common->getUserId();
            $arr=array(
                'userid'=>$uid,
                'productid'=>$productid,
                'title'=>'push message by getui',
                'description'=>"send message ".$transmissionContentNotify,
                'private'=>1,
                'marktime'=> date("Y-m-d")
            )
            ;
            $this->point_mark->addPointmark($arr);
        } else {
            $res = array(
                    'flag' => 0,
                    'msg' => 'error' 
            );
        }
        echo json_encode($res);
    }
}
?>