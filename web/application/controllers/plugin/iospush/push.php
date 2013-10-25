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
		$registerid = $_POST ['registerid'];
		$bundleid = $_POST['bundleid'];
		$userKey = $_POST ['userKey'];
		$userSecret = $_POST ['userSecret'];
		//$selectvalue = $_POST ['pushType'];
		$tagvalue = $_POST['tagvalue'];// getted tag value to get deviceid
		$productid = $_POST['productid'];
		
		// $sendtime = $_POST['sendtime'];
		$updatesign = $_POST ['updatesign'];
		$appcontent = $_POST ['appcontent'];
		$paravalue = $_POST ['paravalue'];
		$paraname = $_POST ['paraname'];
		$tagtype = $_POST ['tagtype'];
		
		$data = array (
				'registerid' => $registerid,
				'bundleid'=>$bundleid,
				'userkey' => $userKey,
				'usersecret' => $userSecret,
				'updatesign' => $updatesign,
				// 'sendtime'=>$sendtime,
				'appcontent' => $appcontent,
				'paravalue' => $paravalue,
				'paraname' => $paraname,
				'tagtype' => $tagtype,
				'tagvalue' => $tagvalue,
				'productid' => $productid
		);
			
		//根据deviceid  500  循环  发送$dwdb = $this->load->database ( 'dw', TRUE );
		
		$flag =true;
		$i=0;

		while ($flag) {
			// $devicelist=$this->common->curl_post(site_url()."/Tag/tags/getDeviceidList",$requestdata);
			$devicelist=$this->tag->getDeviceidList($productid,$tagvalue,$i,500);
			$resarr = $devicelist->result_array();
			// echo $a[0]['deviceidentifier'];

			$data['devicelist']=json_encode($resarr);
			
			$data['tag'] = $tagvalue;
			$result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/api/apns/push',$data);
		
			if(count($resarr)<500){
				$flag=false;
			}
			$i=$i+1;
		}
		
		$resu= json_decode ( $result,true );
		
		// print_r($result);
		// echo $result;
		if ($result['result']=='ok') {
			$res = array (
					'flag' => 1,
					'msg' => 'ok' 
			);
			$uid =$this->common->getUserId();		
			// echo $uid;
			$arr=array(
				'userid'=>$uid,
				'productid'=>$productid,
				'title'=>'push message by IOS',
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
		
		$registerid = $_POST['registerid'];
		$userKey = $_POST['userKey'];
		$pushUser = $_POST['pushUser'];
		$userSecret = $_POST['userSecret'];
		$bundleid = $_POST['bundleid'];
		$tagvalue = $_POST['tagvalue'];
		$transmissionContentNotify = $_POST['transmissionContentNotify'];
		$offlined  = $_POST['offlined'];
		$offlineTime = $_POST['offlineTime'];
		$productid = $_POST['productid'];
		$data = array(
			'registerid'=>$registerid,
			'bundleid'=>$bundleid,
			'userKey'=>$userKey,
			'userSecret'=>$userSecret,
			'pushUser'=>$pushUser,
			'tagvalue'=>$tagvalue,
			'transmissionContentNotify'=>$transmissionContentNotify,
			'offlined'=>$offlined,
			'offlineTime'=>$offlineTime
			);


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
			// print_r($data);

			$result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/push/transmission',$data);
			 // echo $result;
			if(count($resarr)<500){
				$flag=false;
			}
			$i=$i+1;
		}




		
		
		// $result=$this->common->curl_post('http://localhost/usercenter/index.php?/push/transmission',$data);
		$result= json_decode ( $result,true );
		// print_r($result);
		if ($result['result']=='ok') {
			$res = array (
					'flag' => 1,
					'msg' => 'ok' 
			);
			$uid =$this->common->getUserId();		
			// echo $uid;
			$arr=array(
				'userid'=>$uid,
				'productid'=>$productid,
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