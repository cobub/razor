<?php
class push extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		
		$this->common->requireLogin ();
		$this->load->Model ( 'pluginM' );
		$this->load->model ( 'point_mark' );
		$this->load->model('tag/tagmodel','tag');
	
	}
	
	function index() {
		
		$appid = $_POST ['appid'];
		$appkey = $_POST['appkey'];
		$userKey = $_POST ['userKey'];
		$userSecret = $_POST ['userSecret'];
		$selectvalue = $_POST ['pushType'];
		$mastersecret = $_POST['mastersecret'];
		$tagvalue = $_POST['tagvalue'];// getted tag value to get deviceid
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
		$data = array (
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
	
		
		
// print_r($data);
		//根据deviceid  500  循环  发送$dwdb = $this->load->database ( 'dw', TRUE );
		
		$flag =true;
		$i=0;

		while ($flag) {
			// $devicelist=$this->common->curl_post(site_url()."/Tag/tags/getDeviceidList",$requestdata);
			$devicelist=$this->tag->getDeviceidList($productid,$tagvalue,$i,500);
			$resarr = $devicelist->result_array();
			// echo $a[0]['deviceidentifier'];
			// print_r( $resarr);
			$data['devicelist']=json_encode($resarr);
			$data['tag'] = $tagvalue;
			$result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push',$data);
			 // echo $result;
			if(count($resarr)<500){
				$flag=false;
			}
			$i=$i+1;
		}
		
		$resu= json_decode ( $result,true );
		// print_r($result);
		// echo $result;
		if ($resu['result']=='ok') {
			$res = array (
					'flag' => 1,
					'msg' => 'ok' 
			);
			$uid =$this->common->getUserId();		
			// echo $uid;
			$arr=array(
				'userid'=>$uid,
				'title'=>'push message by getui',
				'description'=>"send message ".$appcontent,
				'private'=>1,
				'marktime'=> date("Y-m-d")
			)
			;
			$this->point_mark->addPointmark ( $arr );
		
		} else {
			$res = array (
					'flag' => 0,
					'msg' => $resu['msg']
			);
		}
		echo json_encode ( $res );
	}
	function transmission(){
		
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

		$result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push/transmission',$data);
		// $result=$this->common->curl_post('http://localhost/usercenter/index.php?/push/transmission',$data);
		$result= json_decode ( $result );
		if ($result->result=='ok') {
			$res = array (
					'flag' => 1,
					'msg' => 'ok' 
			);
			$uid =$this->common->getUserId();		
			// echo $uid;
			$arr=array(
				'userid'=>$uid,
				'title'=>'push message by getui',
				'description'=>"send message ".$transmissionContentNotify,
				'private'=>1,
				'marktime'=> date("Y-m-d")
			)
			;
			$this->point_mark->addPointmark ( $arr );
		
		} else {
			$res = array (
					'flag' => 0,
					'msg' => 'error' 
			);
		}
		echo json_encode ( $res );

	}

}

?>