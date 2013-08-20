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
	
	function getUserRemainCountByDay($version,$productId,$from,$to)
	{
	    $dwdb = $this->load->database ( 'dw', TRUE );
	    $sql="select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			f.version_name,
			f.usercount,
			    f.day1,
			       f.day2,
			       f.day3,
			       f.day4,
			       f.day5,
			       f.day6,
			       f.day7,
			       f.day8
			from  ".$dwdb->dbprefix('fact_reserveusers_daily')."   f,
			     ".$dwdb->dbprefix('dim_date')."    d1,
			     ".$dwdb->dbprefix('dim_date')."    d2
			where  f.startdate_sk = d1.date_sk
			       and f.enddate_sk = d2.date_sk
		      and d1.datevalue >= '$from'
		       and d2.datevalue <= '$to'
		      and f.product_id = $productId 
		      and f.version_name='$version'
	         order by d1.datevalue;";	  
	    $query = $dwdb->query ( $sql );
	    return $query;
	}
	
	function getUserRemainCountByWeek($version,$productId,$from,$to)
	{
	      $dwdb = $this->load->database ( 'dw', TRUE );
	    $sql="select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			f.version_name,
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
			       and f.enddate_sk = d2.date_sk
		      and d1.datevalue >= '$from'
		       and d2.datevalue <= '$to'
		      and f.product_id = $productId 
		      and f.version_name='$version'
	         order by d1.datevalue;";	  
	    $query = $dwdb->query ( $sql );
	    return $query;
	}
	
    function getUserRemainCountByMonth($version,$productId,$from,$to)
	{
$dwdb = $this->load->database ( 'dw', TRUE );
	    $sql="	select date(d1.datevalue) startdate,
				date(d2.datevalue) enddate,
				f.version_name,
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
				and f.enddate_sk = d2.date_sk 
	             and d1.datevalue >= '$from'
			      and d2.datevalue <= '$to'
			      and f.product_id = $productId
			      and f.version_name = '$version'
					order by d1.datevalue;";

	    $query = $dwdb->query ( $sql );
	    
	    return $query;
	}

	
	
}
