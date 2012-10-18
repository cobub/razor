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
class operatormodel extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	//get active user percent by operator
	function getActiveUsersPercentByOperator($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
	
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
							 ".$dwdb->dbprefix('dim_date')." dd,
							 ".$dwdb->dbprefix('dim_product')."  pp,
							 ".$dwdb->dbprefix('dim_devicesupplier')."  ss
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.devicesupplier_sk = ss.devicesupplier_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."  f,
	 ".$dwdb->dbprefix('dim_date')."  d,
	 ".$dwdb->dbprefix('dim_product')."   p,
	 ".$dwdb->dbprefix('dim_devicesupplier')."   s
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicesupplier_sk = s.devicesupplier_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by s.devicesupplier_name
order by percentage desc limit 0,$count;
		";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	//Get new users percent by operator
	function getNewUsersPercentByOperator($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
							   ".$dwdb->dbprefix('dim_date')."  			 dd,
								 ".$dwdb->dbprefix('dim_product')."  		 pp,
								 ".$dwdb->dbprefix('dim_devicesupplier')."  		 ss
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.devicesupplier_sk = ss.devicesupplier_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."    f,
	 ".$dwdb->dbprefix('dim_date')."    d,
	 ".$dwdb->dbprefix('dim_product')."    p,
	 ".$dwdb->dbprefix('dim_devicesupplier')."    s
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicesupplier_sk = s.devicesupplier_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
group by s.devicesupplier_name
order by percentage desc limit 0,$count;
		";
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	//Get users percent by operator
	function getTotalUsersPercentByOperator($fromTime, $toTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   s.devicesupplier_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,ss.devicesupplier_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
						 		".$dwdb->dbprefix('dim_date')."  			 dd,
							 ".$dwdb->dbprefix('dim_product')." 			 pp,
							 ".$dwdb->dbprefix('dim_devicesupplier')." 			 ss
						 where   ff.date_sk = dd.date_sk and ff.product_sk = pp.product_sk
						 and dd.datevalue between '$fromTime' and '$toTime' 
										and ff.devicesupplier_sk = ss.devicesupplier_sk
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."     f,
		 ".$dwdb->dbprefix('dim_product')." 		  p,
		 		".$dwdb->dbprefix('dim_date')."    d,
		 ".$dwdb->dbprefix('dim_devicesupplier')." 		  s
where   f.date_sk = d.date_sk and   f.product_sk = p.product_sk
				 and f.devicesupplier_sk = s.devicesupplier_sk
				 and d.datevalue between '$fromTime' and '$toTime' 
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by s.devicesupplier_name
order by percentage desc;
		";
		$query = $dwdb->query ( $sql );	  
		return $query;
	}
}

?>