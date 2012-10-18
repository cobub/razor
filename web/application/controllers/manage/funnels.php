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
		$this->common->loadHeader ();
		$user_id = $this->common->getUserId ();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$product_id = $this->common->getCurrentProduct ()->id;
		$data ['eventlist'] = $this->event->getEventListByProductIdAndProductVersion ( $product_id, 'all' );
		$data ['data'] = $this->conversion->getConversionListByProductIdAndUserId ( $product_id, $user_id, $fromTime, $toTime );
		// $this->load->view ( 'conversionrate/funnelsview', $data );eventdata
		// argetname,startevent,endevent,c1,c2,conversionrate
		$result = array ();
		$targetdata = $data ['data'] ['targetdata'];
		$eventdata = $data ['data'] ['eventdata'];
		for($i = 0; $i < count ( $targetdata ); $i ++) {
			$target = $targetdata [$i];
			$result ['tid'] [$i] = $target ['tid'];
			$result ['targetname'] [$i] = $target ['targetname'];
			$result ['event1'] [$i] = $target ['a1'];
			$result ['event2'] [$i] = $target ['a2'];
			for($j = 0; $j < count ( $eventdata ); $j ++) {
				$event = $eventdata [$j];
				if ($target ['sid'] == $event ['event_id']) {
					$result ['event1_c'] [$i] = $event ['num'];
				}
				if ($target ['eid'] == $event ['event_id']) {
					$result ['event2_c'] [$i] = $event ['num'];
				}
			}
		}
		$data ['result'] = $result;
		$this->load->view ( 'manage/funnel', $data );
	}
	function deleteFunnel($targetid) {
		$userid = $this->common->getUserId ();
		$this->conversion->deltefunnel ( $userid, $targetid );
		$this->index ();
	}
}