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
class devicemodel extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	//View the number and percentage of active users of the equipment
	function getActiveUsersPercentByDevice($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   b.devicebrand_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,bb.devicebrand_name) percent
						 from ".$dwdb->dbprefix('fact_clientdata')."   ff,
							 ".$dwdb->dbprefix('dim_date')."  			 dd,
							 ".$dwdb->dbprefix('dim_product')."  			 pp,
							 ".$dwdb->dbprefix('dim_devicebrand')."  			 bb
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.devicebrand_sk = bb.devicebrand_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."      f,
		 ".$dwdb->dbprefix('dim_date')."  		  d,
		 ".$dwdb->dbprefix('dim_product')."  		  p,
		 ".$dwdb->dbprefix('dim_devicebrand')."  		  b
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicebrand_sk = b.devicebrand_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by b.devicebrand_name
order by percentage desc limit 10;
		";
		
		$query = $dwdb->query ( $sql );
		return $query;  
	}
	
	//View equipment number and percentage of new users
	function getNewUserPercentByDevice($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
	$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   b.devicebrand_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,bb.devicebrand_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."     ff,
								 ".$dwdb->dbprefix('dim_date')."  		 dd,
								 ".$dwdb->dbprefix('dim_product')."  		 pp,
								 ".$dwdb->dbprefix('dim_devicebrand')."  		 bb
						 where  ff.date_sk = dd.date_sk
								and ff.product_sk = pp.product_sk
							and ff.devicebrand_sk = bb.devicebrand_sk
					and dd.datevalue between '$fromTime' and '$toTime'
					and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."      f,
		 ".$dwdb->dbprefix('dim_date')."  		  d,
		 ".$dwdb->dbprefix('dim_product')."  		  p,
		 ".$dwdb->dbprefix('dim_devicebrand')."  		  b
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicebrand_sk = b.devicebrand_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
group by b.devicebrand_name
order by percentage desc limit 10;
		";
		//		echo $sql;
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	/*
	 * Get user percents by device
	 */
	function getDeviceTypeDetail($productId,$fromTime,$toTime) {
	$dwdb = $this->load->database ( 'dw', TRUE );	
	$sql = "select   b.devicebrand_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier,bb.devicebrand_name) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."     ff,
						 		".$dwdb->dbprefix('dim_date')."  		 dd,
								 ".$dwdb->dbprefix('dim_product')."  		 pp,
								 ".$dwdb->dbprefix('dim_devicebrand')."  		 bb
						 where ff.date_sk = dd.date_sk and
						    ff.product_sk = pp.product_sk
							and ff.devicebrand_sk = bb.devicebrand_sk
					and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."      f,
		".$dwdb->dbprefix('dim_date')."  		  d,
		 ".$dwdb->dbprefix('dim_product')."  		  p,
	 ".$dwdb->dbprefix('dim_devicebrand')."  		  b
where f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.devicebrand_sk = b.devicebrand_sk
				 and d.datevalue between '$fromTime' and '$toTime' 
				 and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by b.devicebrand_name
order by percentage desc;
		
		";		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	
	
}

?>