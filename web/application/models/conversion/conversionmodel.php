<?php
class Conversionmodel extends CI_Model {
	function __construct() {
		parent::__construct ();
		$this->load->database ();
		
	}
	function getConversionListByProductIdAndUserId($productid, $userid, $fromdate, $todate, $version) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql_1 = 'select t.tid,t.unitprice,t.targetname,te.eventalias a1,tee.eventalias a2,te.eventid sid,tee.eventid eid
from ' . $this->db->dbprefix ( 'target' ) . ' t, ' . $this->db->dbprefix ( 'targetevent' ) . ' te,' . $this->db->dbprefix ( 'targetevent' ) . ' tee
where t.userid = ? and t.productid = ? and t.tid = te.targetid and t.tid = tee.targetid 
and te.sequence = 1 and 
tee.sequence = (select max(sequence) from ' . $this->db->dbprefix ( 'targetevent' ) . '  where targetid = t.tid)  GROUP BY t.tid';
		$sql_2 = 'select t.event_id,count(*) num,d.datevalue from ' . $dwdb->dbprefix ( 'fact_event' ) . ' e, ' . $dwdb->dbprefix ( 'dim_date' ) . ' d, ' . $dwdb->dbprefix ( 'dim_product' ) . ' p,' . $dwdb->dbprefix ( 'dim_event' ) . ' t where e.event_sk = t.event_sk and 
e.product_sk = p.product_sk and p.product_id = ?  and 
e.date_sk = d.date_sk and d.datevalue between \'' . $fromdate . '\' and \'' . $todate . '\' 
group by e.event_sk,d.datevalue';
		$data ['targetdata'] = $this->db->query ( $sql_1, array (
				$userid,
				$productid 
		) )->result_array ();
		$data ['eventdata'] = $dwdb->query ( $sql_2, array (
				$productid 
		) )->result_array ();
		return $data;
	}
	function addConversionrate($userid, $productid, $targetname,$unitprie, $data = array()) {
		$r = $this->db->query ( 'select * from ' . $this->db->dbprefix ( 'target' ) . ' where targetname=\'' . $targetname . '\' and userid=? and productid=?', array (
				$userid,
				$productid 
		) );
		$r1 = $this->db->query ( 'select * from ' . $this->db->dbprefix ( 'target' ) . ' where userid=? and productid=?', array (
				$userid,
				$productid 
		) );
		$num_row1 = $r1->num_rows ();
		$num_row = $r->num_rows ();
		// return $num_row;
		if ($num_row > 0) {
			return 'existsname';
		} else if ($num_row1 >= 10) {
			return 'max';
		} else {
			$this->db->trans_start ();
			$this->db->query ( 'insert into ' . $this->db->dbprefix ( 'target' ) . '(userid,productid,targetname,unitprice,createdate)values(' . $userid . ',' . $productid . ',\'' . $targetname . '\','.$unitprie.',sysdate())' );
			$targetid = $this->db->insert_id ();
			if ($data) {
				for($i = 0; $i < count ( $data ['events'] ) - 1; $i ++) {
					$this->db->query ( 'insert into ' . $this->db->dbprefix ( 'targetevent' ) . '(targetid,eventid,eventalias,sequence)values(' . $targetid . ',' . $data ['events'] [$i] . ',\'' . $data ['names'] [$i] . '\',' . ($i+1) . ')' );
				}
			}
			$affect_row = $this->db->affected_rows ();
			$this->db->trans_complete ();
			if ($affect_row) {
				return 'success';
			} else {
				return 'error';
			}
		}
	}
	function deltefunnel($userid, $targetid) {
		$this->db->trans_start ();
		$this->db->query ( 'delete from ' . $this->db->dbprefix ( 'targetevent' ) . ' where targetid=' . $targetid );
		$this->db->query ( 'delete from ' . $this->db->dbprefix ( 'target' ) . ' where tid=' . $targetid . ' and userid=' . $userid );
		$this->db->trans_complete ();
		return $this->db->affected_rows ();
	}
	function delteFunnelEvent($targetid, $eventid) {
		$sql = 'DELETE FROM ' . $this->db->dbprefix ( 'targetevent' ) . ' WHERE targetid=? AND eventid=?';
		$this->db->query ( $sql, array (
				$targetid,
				$eventid 
		) );
		return $this->db->affected_rows ();
	}
	function checkIsDeleteFunnelEvent($targetid) {
		$sql = 'SELECT * from ' . $this->db->dbprefix ( 'targetevent' ) . ' where targetid=' . $targetid;
		$result = $this->db->query ( $sql );
		return $result->num_rows ();
	}

	function detailfunnel($targetid) {
		$sql='select t.targetname,te.eventalias,te.eventid,te.sequence 
		from '.$this->db->dbprefix('target').' t,'.$this->db->dbprefix('targetevent').' te 
		where t.tid = te.targetid and te.targetid = '.$targetid.' order by te.sequence';
		$queryresult=$this->db->query ( $sql);
		if(!empty($queryresult)){
			$queryresult=$queryresult->result();
		}
		return $queryresult;
	}
	function detailfunnel2($fromdate, $todate,$version) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql1='select t.event_id,count(*) num 
		from '.$dwdb->dbprefix('fact_event').' e, '.$dwdb->dbprefix('dim_date').' d,
		'.$dwdb->dbprefix('dim_product').' p,'.$dwdb->dbprefix('dim_event').' t 
		where e.event_sk = t.event_sk and e.product_sk = p.product_sk and p.product_id = 1 
		and p.version_name="'.$version.'" and e.date_sk = d.date_sk 
		and d.datevalue between "'.$fromdate.'" and "'.$todate.'" group by e.event_sk';
		$sql2='select t.event_id,count(*) num
		from '.$dwdb->dbprefix('fact_event').' e, '.$dwdb->dbprefix('dim_date').' d,
		'.$dwdb->dbprefix('dim_product').' p,'.$dwdb->dbprefix('dim_event').' t
		where e.event_sk = t.event_sk and e.product_sk = p.product_sk and p.product_id = 1 
		and e.date_sk = d.date_sk
		and d.datevalue between "'.$fromdate.'" and "'.$todate.'" group by e.event_sk';
		if($version!='all'){
			$queryresult=$dwdb->query($sql1);
		}else{
			$queryresult=$dwdb->query($sql2);
		}
		if(!empty($queryresult)){
			$queryresult=$queryresult->result();
		}
		return $queryresult;
	}
	function getFunnelByTargetid($targetid) {
		$sql = 'select t.tid,t.unitprice,t.userid,t.targetname,e.eventalias,e.sequence,e.eventid,d.event_name from ' . $this->db->dbprefix ( 'target' ) . ' t
left JOIN ' . $this->db->dbprefix ( 'targetevent' ) . '  e on t.tid=e.targetid
 inner join ' . $this->db->dbprefix ( 'event_defination' ) . ' d on e.eventid=d.event_id where t.tid=' . $targetid;
		$result = $this->db->query ( $sql );
		return $result;
	}
	function modifyFunnel($targetid, $target_name, $unitprice,$data = array()) {
		$this->db->trans_start ();
		$this->db->query ( 'UPDATE ' . $this->db->dbprefix ( 'target' ) . ' SET targetname=?,unitprice=? WHERE tid=?', array (
				$target_name,
				$unitprice, 
				$targetid
		) );
		for($i = 0; $i <= count ( $data ['event_ids'] ) - 1; $i ++) {
			$this->db->query ( 'update ' . $this->db->dbprefix ( 'targetevent' ) . ' set eventalias=?,sequence=? where targetid=? and eventid=?', array (
					$data ['event_names'] [$i],
					$i,
					$targetid,
					$data ['event_ids'] [$i] 
			) );
		}
		$this->db->trans_complete ();
		return $this->db->affected_rows ();
	}
	
    //get target count
	function  getAllUserTarget($userid,$productid)
	{
		$sql = "select t.targetname,t.unitprice,te.eventalias a1,te.eventid sid
from  " . $this->db->dbprefix ( 'target' ) ."  t, " . $this->db->dbprefix ( 'targetevent' ) ." te
where t.userid = $userid and t.productid = $productid and t.tid = te.targetid
and te.sequence = (select max(sequence) from " . $this->db->dbprefix ( 'targetevent' ) . " where targetid = t.tid) ;";
		//echo $sql;
		$query = $this->db->query ( $sql );
		return $query;
		
	}
	
	//get target event count 
	function getTargetEventNumPerDay($productid,$from,$to)
	{
	    $dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select t.event_id, date(d.datevalue) d, ifnull(s.num,0) num from (select date_sk, datevalue from " . $dwdb->dbprefix ( 'dim_date' ) . " where datevalue between '$from' and '$to') d cross join " . $dwdb->dbprefix ( 'dim_event' ) . " t 
left join (select event_sk, date_sk, count(*) num from " . $dwdb->dbprefix ( 'fact_event' ) . " f, ". $dwdb->dbprefix ( 'dim_product' ) . " p   where f.product_sk = p.product_sk and p.product_id = $productid group by event_sk,date_sk) s on d.date_sk = s.date_sk and t.event_sk = s.event_sk;";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	//get chart data
	function getChartData($userid,$productid,$from,$to)
	{
		
		$target = $this->getAllUserTarget($userid,$productid) ;
		$numofAllTarget = $this->getTargetEventNumPerDay($productid, $from, $to);
		
		$result = array();
		if ($target!=null && $target->num_rows()>0)
		{
			$array = $target->result_array ();
			$target_array = array();
		    foreach ($array as $row)
		    {
		    	//array_push($target_array, $row['targetname']);
		    	$target_Item["targetname"] = $row["targetname"];
		    	$target_Item['unitprice']=$row["unitprice"];
		    	$target_Item["eventname"] = $row["a1"];
		    	$time_array = array();
		    	$num_array = array();
		    	$event_id = $row["sid"];
		    	
		    	foreach($numofAllTarget->result_array() as $row2)
		    	{
		    		if ($row2["event_id"]==$event_id)
		    		{
		    			array_push($time_array, $row2["d"]);
		    			array_push($num_array, $row2["num"]);
		    		}
		    	}
		    	$target_Item['eventtime'] = $time_array;
		    	$target_Item['eventnum'] = $num_array;
		    	array_push($result, $target_Item);
		    
		    }
		    
		}
		else {
			$result = '';
		}
		return $result;
	
	
	}
	
	
}