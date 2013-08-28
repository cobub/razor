<?php
class report extends CI_Controller {
	
	var $appid;
	
	function __construct() {
		parent::__construct ();
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
		if(isset($result->status)){
			if($result->status=='Succ'){
			$data['onlineuser']=$result->count;
			}
		}
		else{
			$data['onlineuser']=0;
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
		
		echo $ret;
	}


}

?>