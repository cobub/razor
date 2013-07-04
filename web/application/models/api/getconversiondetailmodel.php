<?php
class getconversiondetailmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getdataofconversiondetail($sessionkey,$productid,$fromtime,$totime,$targetid,$version){

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basicAct = $this->getconversiondata($userid,$productid, $fromtime, $totime,$version,$targetid);
					
					if ($basicAct)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' =>$basicAct
						);
					}
					else
					{
						$productinfo = array (
								'flag' => - 4,
								'msg' => 'No data information'
						);
					}
				}
				else
				{
					$productinfo = array (
							'flag' => - 6,
							'msg' => 'Do not have permission'
					);
				}
				return $productinfo;
			}
			else
			{
				$productinfo = array (
						'flag' => - 2,
						'msg' => 'Sessionkey is invalide '
				);
				return $productinfo;
			}
		}
		catch ( Exception $ex )
		{
			$productinfo = array (
					'flag' => - 3,
					'msg' => 'DB Error'
			);
			return $productinfo;
		}
		
	}
	
	
	function detailfunnel2($fromdate, $todate, $version,$productId,$eventid) 
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql1 = 'select t.event_id,  t.eventname,count(*) num
		from ' . $dwdb->dbprefix ( 'fact_event' ) . ' e, ' . $dwdb->dbprefix ( 'dim_date' ) . ' d,
		' . $dwdb->dbprefix ( 'dim_product' ) . ' p,' . $dwdb->dbprefix ( 'dim_event' ) . ' t
		where e.event_sk = t.event_sk and e.product_sk = p.product_sk and p.product_id = '.$productId.'
		and p.version_name="' . $version . '" and e.date_sk = d.date_sk and t.event_id='.$eventid.'  
		and d.datevalue between "' . $fromdate . '" and "' . $todate . '" group by e.event_sk';
		$sql2 = 'select t.event_id, t.eventname,count(*) num
		from ' . $dwdb->dbprefix ( 'fact_event' ) . ' e, ' . $dwdb->dbprefix ( 'dim_date' ) . ' d,
		' . $dwdb->dbprefix ( 'dim_product' ) . ' p,' . $dwdb->dbprefix ( 'dim_event' ) . ' t
		where e.event_sk = t.event_sk and e.product_sk = p.product_sk and p.product_id = "'.$productId.'"
		and e.date_sk = d.date_sk  and t.event_id="'.$eventid.' " 
		and d.datevalue between "' . $fromdate . '" and "' . $todate . '" group by e.event_sk';
		if ($version != 'all')
		{
			$queryresult = $dwdb->query ( $sql1 );
		}
		else
		{
			$queryresult = $dwdb->query ( $sql2 );
		}
		if (! empty ( $queryresult ))
		{
			
			return $queryresult->result_array();
		}else{
			return false;
		}
		
	}
	
	
	
	function getconversiondata($userid, $productid, $fromtime, $totime,$version,$targetid){
		$dwdb = $this->load->database ( 'dw', TRUE );
		
		$sql = "select t.targetname,te.eventid,t.unitprice,te.eventalias eventname,te.eventid sid
		from  " . $this->db->dbprefix ( 'target' ) . "  t, " . $this->db->dbprefix ( 'targetevent' ) . " te
		where t.userid = '$userid' and t.productid = '$productid' and t.tid = te.targetid and te.targetid=$targetid 
		and te.sequence = (select max(sequence) from " . $this->db->dbprefix ( 'targetevent' ) . " where targetid = t.tid) ;";

		$query = $this->db->query ( $sql );
		
		if($query != null && $query->num_rows () > 0){
			$queryarr = $query->result_array();
			$ret = array();
			for($i =0;$i<count($queryarr);$i++){
				$eventid = $queryarr[$i]['eventid'];
				$eventpic = $queryarr[$i]['unitprice'];
				$result=$this->detailfunnel2($fromtime, $totime, $version, $productid,$eventid);
				
				if(count($result)==0){
					continue;
				}
				
				$num = $result[0]['num'];
				$eventname = $result[0]['eventname'];
				$obj = array(
						'id'=>$i+1,
						'eventid'=>$eventid,
						'eventname'=>$eventname,
						'num'=>$num,
						'conversion'=>$num*$eventpic
				);
				array_push($ret, $obj);
			}
			return  $ret;
		}else{
			return false;
		}
	}

}