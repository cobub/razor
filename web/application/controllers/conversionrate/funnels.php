<?php
class Funnels extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'common' );
		$this->load->helper ( array (
				'form',
				'url' 
		) );
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'event/userEvent', 'event' );
		$this->load->model ( 'conversion/conversionmodel', 'conversion' );
		$this->common->requireLogin ();
		$this->common->requireProduct();
	}
	function index() {
		$this->common->loadHeaderWithDateControl ();
		$user_id = $this->common->getUserId ();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$product_id = $this->common->getCurrentProduct ()->id;
		$data = $this->conversion->getConversionListByProductIdAndUserId ( $product_id, $user_id, $fromTime, $toTime );
		$result = array ();
		$targetdata = $data['targetdata'];
		$eventdata = $data['eventdata'];
		for($i = 0; $i < count ( $targetdata ); $i ++) {
			$target = $targetdata [$i];
			$result ['tid'] [$i] = $target ['tid'];
			$result ['targetname'] [$i] = $target ['targetname'];
			$result ['event1'] [$i] = $target ['a1'];
			$result ['event2'] [$i] = $target ['a2'];
			if(empty($eventdata))
			{
				$result ['event1_c'] [$i] = 0;
				$result ['event2_c'] [$i] = 0;
				
			}
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
	
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$data['reportTitle'] = array(
			'errorCount'=> getReportTitle(lang("v_rpt_err_errorNums") , $fromTime, $toTime),
			'errorCountPerSession'=>  getReportTitle(lang("v_rpt_err_errorNumsInSessions") , $fromTime, $toTime),
		    'timePhase'=>getTimePhaseStr($fromTime, $toTime)
		);
		$this->load->view ( 'conversionrate/funnelsview', $data );
	}
	function addFunnel() {
		$target_name = $_POST ['funnel_name'];
		$step_events = $_POST ['event_ids'];
		$step_names = $_POST ['step_names'];
		$user_id = $this->common->getUserId ();
		$product_id = $this->common->getCurrentProduct ()->id;
		$data ['events'] = explode ( ',', $step_events );
		$data ['names'] = explode ( ',', $step_names );
		$info = $this->conversion->addConversionrate ( $user_id, $product_id, $target_name, $data );
		echo $info;
	}
	function viewDetail($targetid) {
		$this->common->loadHeaderWithDateControl ();
		$productId = $this->common->getCurrentProduct ();
		$productId=$productId->id;
		$fromdate=$this->common->getFromTime();
		$todate=$this->common->getToTime();
		$this->data['reportTitle'] = array(
				'eventCount'=> getReportTitle(lang("v_rpt_re_eventcount") , $fromdate, $todate),
				'timePhase'=>getTimePhaseStr($fromdate, $todate)
		);
		$this->data ['versions'] = $this->event->getProductVersions ( $productId );
		$this->data['targetid']=$targetid;
		$this->load->view ( 'conversionrate/funneldetailview',$this->data);
	}
	function getViewDetail($targetid,$version){
		$fromdate=$this->common->getFromTime();
		$todate=$this->common->getToTime();
		$eventDetaildata=array();
		$eventDetail=array();
		$razordata=$this->conversion->detailfunnel($targetid);
		$razordwdata=$this->conversion->detailfunnel2($fromdate,$todate,$version);
	    $i=0;
		foreach ( $razordata as $row ) {
			$data=array();
			$i++;
			$event_id =$row->eventid;
			$data['eventalias']=$row->eventalias;
			$data['num']=0;
			foreach ( $razordwdata as $dwrow ) {
			$dwevent_id = $dwrow->event_id;
			if ($event_id==$dwevent_id){
				$data['num']=$dwrow->num;
				break;}
			}
			array_push($eventDetail, $data);
		}
		if($eventDetail!=null){
			$eventDetaildata['content']=$eventDetail;
		}
		echo json_encode($eventDetaildata);
	}
	function delteFunnelEvent() {
		$target_id = $_POST ['target_id'];
		$event_id = $_POST ['event_id'];
		if ($this->conversion->checkIsDeleteFunnelEvent ( $target_id ) <= 2) {
			echo json_encode ( 'lt2' ); // less than 2
			return;
		}
		$affrow = $this->conversion->delteFunnelEvent ( $target_id, $event_id );
		echo json_encode ( $affrow );
	}
	function editFunnel($targetid) {
		$this->common->loadHeader ();
		$user_id = $this->common->getUserId ();
		$product_id = $this->common->getCurrentProduct ()->id;
		$data ['eventlist'] = $this->event->getEventListByProductIdAndProductVersion ( $product_id, 'all' );
		$data ['steplist'] = $this->conversion->getFunnelByTargetid ( $targetid );
		$this->load->view ( 'conversionrate/modify', $data );
	}
	function modifyFunnel() {
		$event_ids = $_POST ['event_ids'];
		$step_names = $_POST ['step_names'];
		$target_id = $_POST ['target_id'];
		$target_name = $_POST ['funnel_name'];
		$data ['event_ids'] = explode ( ',', $event_ids );
		$data ['event_names'] = explode ( ',', $step_names );
		
		$aff_row = $this->conversion->modifyFunnel ( $target_id, $target_name, $data );
		echo json_encode ( $aff_row );
	}
	
	function getChartData()
	{
	    $productId = $this->common->getCurrentProduct ()->id;
	    $user_id = $this->common->getUserId ();
		$fromTime = $this->common->getFromTime ();
		$toTime = $this->common->getToTime ();
		$result = $this->conversion->getChartData($user_id,$productId,$fromTime,$toTime);
		echo json_encode($result);
	}
}
