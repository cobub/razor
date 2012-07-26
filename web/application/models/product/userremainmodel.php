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
 * @since		Version 1.0
 * @filesource
 */
class Userremainmodel extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	function getUserRemainCountByWeek($timePhase,$productId,$from,$to)
	{
	      $dwdb = $this->load->database ( 'dw', TRUE );
	    $sql="select date(d1.datevalue) startdate,
date(d2.datevalue) enddate,
f.usercount,
    f.week1,
       f.week2,
       f.week3,
       f.week4,
       f.week5,
       f.week6,
       f.week7,
       f.week8
from  ".$dwdb->dbprefix('fact_reserveusers_weekly')."   f,
     ".$dwdb->dbprefix('dim_date')."    d1,
     ".$dwdb->dbprefix('dim_date')."    d2
where  f.startdate_sk = d1.date_sk
       and f.enddate_sk = d2.date_sk ";
	    if($timePhase!='all')
	    	$sql.="and d1.datevalue >= '$from'
       and d2.datevalue <= '$to'";
       $sql.=" and f.product_id = $productId order by d1.datevalue;";
	  
	    $query = $dwdb->query ( $sql );
	    return $query;
	}
	
    function getUserRemainCountByMonth($timePhase,$productId,$from,$to)
	{
$dwdb = $this->load->database ( 'dw', TRUE );
	    $sql="
select date(d1.datevalue) startdate,
date(d2.datevalue) enddate,
f.usercount,
    f.month1,
       f. month2,
       f.month3,
       f.month4,
       f.month5,
       f.month6,
       f.month7,
       f.month8
from ".$dwdb->dbprefix('fact_reserveusers_monthly')."   f,
     ".$dwdb->dbprefix('dim_date')."    d1,
    ".$dwdb->dbprefix('dim_date')."     d2
where  f.startdate_sk = d1.date_sk 
and f.enddate_sk = d2.date_sk ";
if($timePhase!='all')
	$sql.="and d1.datevalue >= '$from'
       and d2.datevalue <= '$to' ";
       
       $sql.="and f.product_id = $productId order by d1.datevalue;";
	    
	    $query = $dwdb->query ( $sql );
	    
	    return $query;
	}
	
	function getUserRemainCountByMonthJSON($timePhase,$productId,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="
		select date(d1.datevalue) startdate,
		date(d2.datevalue) enddate,
		f.usercount,
		f.month1,
		f. month2,
		f.month3,
		f.month4,
		f.month5,
		f.month6,
		f.month7,
		f.month8
		from ".$dwdb->dbprefix('fact_reserveusers_monthly')."   f,
		".$dwdb->dbprefix('dim_date')."    d1,
		".$dwdb->dbprefix('dim_date')."     d2
		where  f.startdate_sk = d1.date_sk
		and f.enddate_sk = d2.date_sk ";
		if($timePhase!='all')
			$sql.="and d1.datevalue >= '$from'
			and d2.datevalue <= '$to' ";
		 
		$sql.="and f.product_id = $productId order by d1.datevalue;";
	
		$query = $dwdb->query ( $sql );
		$ret=array();
		if ($query != null && $query->num_rows > 0) {
				
			$arr = $query->result_array ();
				
			$content_arr = array ();
			for($i = 0; $i < count ( $arr ); $i ++) {
				$row = $arr [$i];
				
				$tmp = array ();
				$tmp['startdate']=$row['startdate'];
				$tmp['enddate']=$row['enddate'];
				$tmp['usercount'] = $row ['usercount'];
				$tmp ['month1'] = $row ['month1'];
				$tmp['month2'] = $row['month2'];
				$tmp['month3'] = $row['month3'];
				$tmp['month4'] = $row['month4'];
				$tmp['month5'] = $row['month5'];
				$tmp['month6'] = $row['month6'];
				$tmp['month7'] = $row['month7'];
				$tmp['month8'] = $row['month8'];
				
				array_push ( $content_arr , $tmp );
		
			}
			$all_version_name = array_keys($content_arr);
			return $content_arr;
				
		}
		
		
		return $ret;
	}
	
	function getUserRemainCountByWeekJSON($timePhase,$productId,$from,$to){

		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select date(d1.datevalue) startdate,
		date(d2.datevalue) enddate,
		f.usercount,
		f.week1,
		f.week2,
		f.week3,
		f.week4,
		f.week5,
		f.week6,
		f.week7,
		f.week8
		from  ".$dwdb->dbprefix('fact_reserveusers_weekly')."   f,
		".$dwdb->dbprefix('dim_date')."    d1,
		".$dwdb->dbprefix('dim_date')."    d2
		where  f.startdate_sk = d1.date_sk
		and f.enddate_sk = d2.date_sk ";
		if($timePhase!='all')
			$sql.="and d1.datevalue >= '$from'
			and d2.datevalue <= '$to'";
		$sql.=" and f.product_id = $productId order by d1.datevalue;";
		
		 
		$query = $dwdb->query ( $sql );
		$ret  = array();
		if ($query != null && $query->num_rows > 0) {
		
			$arr = $query->result_array ();
		
			$content_arr = array ();
			for($i = 0; $i < count ( $arr ); $i ++) {
				$row = $arr [$i];
				
				$tmp = array ();
				$tmp['startdate']=$row['startdate'];
				$tmp['enddate']=$row['enddate'];
				$tmp['usercount'] = $row ['usercount'];
				$tmp ['week1'] = $row ['week1'];
				$tmp['week2'] = $row['week2'];
				$tmp['week3'] = $row['week3'];
				$tmp['week4'] = $row['week4'];
				$tmp['week5'] = $row['week5'];
				$tmp['week6'] = $row['week6'];
				$tmp['week7'] = $row['week7'];
				$tmp['week8'] = $row['week8'];
				
				array_push ( $content_arr , $tmp );
			}
			$all_version_name = array_keys($tmp);
			return $content_arr;
		
		}
		return $ret;
		
	}
	
	
}