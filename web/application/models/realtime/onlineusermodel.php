<?php
class Onlineusermodel extends CI_Model{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
		$this->load->library('redis');
	}
	
	function getOnlineUsers($productId)
	{
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
		$ret = array();
		for($i=30;$i>=0;$i--)
		{
			 $dataStr = date('Y-m-d-H-i',strtotime("-$i minutes", strtotime($timezonestime)));
			 $size = $this->redis->hlen ("razor_r_u_p_".$productId."_". $dataStr);
			 if($i == 0)
			 {
			 	$onlinedata = array(
			 			'minutes'=>lang("v_rpt_realtime_now"),
			 			'size'=>$size
			 	);
			 }
			 else
			 {
			 	$onlinedata = array(
			 			'minutes'=>"- ".$i.lang("v_rpt_realtime_minutes"),
			 			'size'=>$size
			 	);
			 }
			 array_push($ret, $onlinedata);
		}
		return $ret;
	}
}

?>