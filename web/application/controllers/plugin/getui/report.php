<?php
class report extends CI_Controller {
	
	var $appid;
	
	function __construct() {
		parent::__construct ();
		$this->load->language('plugin_getui');
		$this->load->Model ( 'common' );
		$this->load->Model ( 'plugin/getui/applistmodel', 'plugins' );
		$this->common->requireLogin ();
	
	}
	
	function index() {
		$productid = $_GET ['id'];
		$appid = $this->plugins->getAppid ( $productid );
		 $data = array ();
		$data ['appid'] = $appid;

		$num = $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getuiOnlineCnt",$data);
		$result= json_decode ( $num );
		$data['onlineuser']=0;
		if(isset($result->status)){
			if($result->status=='Succ'){
			$data['onlineuser']=$result->count;
			}
		}
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$days= abs(strtotime($toTime) - strtotime($fromTime))/60/60/24+1;
		if($days>31){
			$data['timeerror']=lang('time_chose_error');
		}
		$uid=$this->common->getUserId();
		$userinfo = $this->plugins->getUserinfo($uid);
		$userKey = $userinfo[0]['user_key'];
		$userSecret = $userinfo[0]['user_secret'];
		$data['user_key']=$userKey;
		$data['user_secret']=$userSecret;
		// print_r($data);

		//获取推送记录
		$pushrecords = $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getGetuiRecords",$data);
		$pushrecordsResult = json_decode($pushrecords);

		if(isset($pushrecordsResult->flag)){
			if($pushrecordsResult->flag>0){
				$data['pushrecords']=$pushrecordsResult->records;
				// print_r($pushrecordsResult->records);
			}
		}
		
		$this->common->loadHeaderWithDateControl ( lang ( 'getui_data' ) );
		$this->load->view ( 'plugin/getui/report', $data );
	}
	
	function getdata() {
		$appid = $_GET ['appid'];
		$type = $_GET ['type'];
		// $appid='6XwSrkppT67PbvjA8iTgv';
		// $type='user';
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		

		$data = array (
				'appid' => $appid,
				'startTime' => $fromTime,
				'endTime' => $toTime,
				'type' => $type 
		);
		// print_r($data);
		
		  $ret= $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getuiReport",$data);
		// $ret='{"dataList":[{"date":"2013-08-09","datas":[12,11]},{"date":"2013-08-08","datas":[2,10]},{"date":"2013-08-07","datas":[4,12]},{"date":"2013-08-06","datas":[2,14]},{"date":"2013-08-05","datas":[34,33]},{"date":"2013-08-04","datas":[33,45]},{"date":"2013-08-03","datas":[33,56]}],"status":"Succ","headList":["test","Total"]}';
		// print_r($ret);
		echo $ret;
	}


	function gettaskdata(){
		$taskid = $_GET['taskid'];
		$appid = $_GET['appid'];

		$data = array ();
		$uid=$this->common->getUserId();
		$userinfo = $this->plugins->getUserinfo($uid);
		$userKey = $userinfo[0]['user_key'];
		$userSecret = $userinfo[0]['user_secret'];
		$data['user_key']=$userKey;
		$data['user_secret']=$userSecret;
		$data['taskid']=$taskid;
		$data['appid']=$appid;
		$ret= $this->common->curl_post(SERVER_BASE_URL."/index.php?/api/igetui/getTaskdata",$data);
		// echo $ret;
		$result= json_decode ( $ret );
		// echo $result->flag;
		if(isset($result->flag)){
			if($result->flag==1){
					$retfromucenter = json_decode($result->result);
				if($retfromucenter->result=='ok'){
					$data['sendnum']=$retfromucenter->msgTotal;
				$data['receivenum']=$retfromucenter->msgProcess;
				}
				
			}

		}

		//$data['result']=$ret;
		$this->common->loadHeader('发送数和接收数报表');
		$this->load->view ( 'plugin/getui/taskreport', $data );
	}


}

?>