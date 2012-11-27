<?php 
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 3.0
 * @filesource
 */
 class Point_mark extends CI_Model{
 
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	//add a point mark
	public function addPointmark($data=array()){
		$this->db->insert($this->db->dbprefix('markevent'),$data);
		return $this->db->affected_rows();
	}
	//remove a point mark
	public function removePointmark($userid,$productid,$date){
		$sql='delete from razor_markevent where userid='.$userid.' and productid='.$productid.' and marktime='.'"'.$date.'"';
		return $this->db->query($sql);
	}
	//return a point list and content to charts
	public function listPointviewtochart($userid,$productid,$fromdate,$enddate,$type=''){
		$sql='select u.username,m.userid,m.title,m.description,m.marktime,m.private from '.$this->db->dbprefix('markevent').' m
left join '.$this->db->dbprefix('users').' u on m.userid=u.id
LEFT JOIN '.$this->db->dbprefix('product').' p on p.id=m.productid
where m.marktime BETWEEN "'.$fromdate.'" and "'.$enddate.'" and p.id='.$productid.' and m.userid='.$userid.' or(m.userid!='.$userid.' and private=1 and p.id='.$productid.') GROUP BY u.username,m.userid,m.title,m.description,m.marktime,m.private ORDER BY m.marktime asc';
		if('listcount'==$type){
		$sql='select count(1) c,m.userid from '.$this->db->dbprefix('markevent').' m
		left join '.$this->db->dbprefix('users').' u on m.userid=u.id
		LEFT JOIN '.$this->db->dbprefix('product').' p on p.id=m.productid
		where m.marktime BETWEEN "'.$fromdate.'" and "'.$enddate.'" and p.id='.$productid.' and m.userid='.$userid.' or(m.userid!='.$userid.' and private=1) GROUP BY m.userid';
		return $this->db->query($sql)->result_array();
		}
		return $this->db->query($sql);
	}
	
	//modify a point mark
	public function modifyPointmark($data=array(),$userid,$productid,$markdate){
		$where = 'userid = '.$userid.' AND productid = '.$productid.' and marktime="'.$markdate.'"';
		$sql = $this->db->update_string($this->db->dbprefix('markevent'), $data, $where);
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	//manage all point marks
	public function managePointmarkpagelist($userid,$productid,$fromTime,$toreTime){
		$sql='select m.id,u.username,m.userid,m.title,m.description,m.marktime,m.private from '.$this->db->dbprefix('markevent').' m
left join '.$this->db->dbprefix('users').' u on m.userid=u.id
LEFT JOIN '.$this->db->dbprefix('product').' p on p.id=m.productid
where p.id='.$productid.' and m.userid='.$userid.' and m.marktime BETWEEN \''.$fromTime.'\' and \''.$toreTime.'\'';
		return $this->db->query($sql);
	}
 
	// check the same date for user whether if insert
	public function ifcaninsert($userid, $productid, $date) {
		$query = $this->db->query ( 'SELECT * FROM '.$this->db->dbprefix('markevent').' WHERE userid=' . $userid . ' AND productid=' . $productid . ' AND marktime="'.$date.'"' );
		$count= $query->num_rows();
		if($count>=1){
			return false;
		}
		return true;
	}
	
	function timediff( $begin_time, $end_time )
	{
		if ( $begin_time < $end_time ) {
			$starttime = $begin_time;
			$endtime = $end_time;
		} else {
			$starttime = $end_time;
			$endtime = $begin_time;
		}
		$timediff = $endtime - $starttime;
		$days = intval( $timediff / 86400 );
		$remain = $timediff % 86400;
		$hours = intval( $remain / 3600 );
		$remain = $remain % 3600;
		$mins = intval( $remain / 60 );
		$secs = $remain % 60;
		$res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
		return $res;
	}
	
 }
?>