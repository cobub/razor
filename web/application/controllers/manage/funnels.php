<?php
class funnels extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'common' );
		$this->load->model ( 'event/userEvent', 'event' );
		$this->load->model ( 'conversion/conversionmodel', 'conversion' );
		$this->common->requireLogin ();
	}
	function index() {
		$this->common->loadHeaderWithDateControl ();
		$this->common->requireProduct();
		$user_id = $this->common->getUserId ();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$product_id = $this->common->getCurrentProduct ()->id;
		$data = $this->conversion->getConversionListByProductIdAndUserId ( $product_id, $user_id, $fromTime, $toTime );
		$targetdata = $data['targetdata'];
		$eventdata = $data['eventdata'];
		$data['eventlist']=$this->event->getProductEventByProuctId($product_id);
		$data ['result']=$this->prepareConversionData($targetdata,$eventdata);
		$this->load->view ( 'manage/funnel', $data );
	}
	//prepare conversion data
	function prepareConversionData($targetdata=array(),$eventdata=array()){
		$result=array();
		for($i = 0; $i < count ( $targetdata ); $i ++) {
			$target = $targetdata [$i];
			$result ['tid'] [$i] = $target ['tid'];
			$result ['targetname'] [$i] = $target ['targetname'];
			$result ['unitprice'] [$i] = $target ['unitprice'];
			$result ['event1'] [$i] = $target ['a1'];
			$result ['event2'] [$i] = $target ['a2'];
			if(empty($eventdata))
			{
				$result ['event1_c'] [$i] = 0;
				$result ['event2_c'] [$i] = 0;
			}
			$e1_c=0;
			$e2_c=0;
			for($j = 0; $j < count ( $eventdata ); $j ++) {
				$event = $eventdata [$j];
				if ($target ['sid'] == $event ['event_id']) {
					$e1_c++;
					$result ['event1_c'] [$i] = $event ['num'];
				}
				if ($target ['eid'] == $event ['event_id']) {
					$e2_c++;
					$result ['event2_c'] [$i] = $event ['num'];
				}
			}
			if($e1_c==0){$result['event1_c'][$i]=0;}
			if($e2_c==0){$result['event2_c'][$i]=0;}
		}
		return $result;
	
	}
	function deleteFunnel($targetid) {
		$userid = $this->common->getUserId ();
		$this->conversion->deltefunnel ( $userid, $targetid );
		$this->index ();
	}
}