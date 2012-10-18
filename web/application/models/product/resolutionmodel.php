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
 * @filesource  resolutionmodel.php
 */
class resolutionmodel extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	
	//Get active user percent by resolution
	function getActiveUsersPercentByOrientation($fromTime,$toTime,$productId,$count=REPORT_TOP_TEN)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql="select   r.deviceresolution_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
								 ".$dwdb->dbprefix('dim_date')."  		 dd,
								 ".$dwdb->dbprefix('dim_product')."  		 pp,
								 ".$dwdb->dbprefix('dim_deviceresolution')."  		 rr
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.deviceresolution_sk = rr.deviceresolution_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId
										and pp.product_active = 1
										and pp.channel_active = 1
										and pp.version_active = 1) percentage
from  ".$dwdb->dbprefix('fact_clientdata')."  f,
	 ".$dwdb->dbprefix('dim_date')."   d,
	 ".$dwdb->dbprefix('dim_product')."    p,
	 ".$dwdb->dbprefix('dim_deviceresolution')."    r
where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.deviceresolution_sk = r.deviceresolution_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId
				 and p.product_active = 1
				 and p.channel_active = 1
				 and p.version_active = 1
group by r.deviceresolution_name
order by percentage desc limit 0,$count;
		";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//Get new user percent by resolution
	function getNewUsersPercentByOrientation($fromTime,$toTime,$productId,$count=REPORT_TOP_TEN)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql="select   r.deviceresolution_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
								 ".$dwdb->dbprefix('dim_date')."  		 dd,
								 ".$dwdb->dbprefix('dim_product')."  		 pp,
								 ".$dwdb->dbprefix('dim_deviceresolution')."  		 rr
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.deviceresolution_sk = rr.deviceresolution_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId
										and pp.product_active = 1
										and pp.channel_active = 1
										and pp.version_active = 1 and ff.isnew=1) percentage
			from     ".$dwdb->dbprefix('fact_clientdata')."      f,
		 	   		 ".$dwdb->dbprefix('dim_date')."  		  d,
					 ".$dwdb->dbprefix('dim_product')."  		  p,
					 ".$dwdb->dbprefix('dim_deviceresolution')."  		  r
			where    f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.deviceresolution_sk = r.deviceresolution_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId
				 and p.product_active = 1
				 and p.channel_active = 1
				 and p.version_active = 1
				 and f.isnew = 1
group by r.deviceresolution_name
order by percentage desc limit 0,$count;
		";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//Get user percent by resolution
	function getTotalUsersPercentByResolution($fromTime,$toTime,$productId,$count=REPORT_TOP_TEN)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql="select   r.deviceresolution_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier) percent
						 from  ".$dwdb->dbprefix('fact_clientdata')."      ff,
						 		".$dwdb->dbprefix('dim_date')."  		 dd,
								 ".$dwdb->dbprefix('dim_product')." 		 pp,
								 ".$dwdb->dbprefix('dim_deviceresolution')." 		 rr
						 where   ff.date_sk = dd.date_sk 
						 				and ff.product_sk = pp.product_sk
										and ff.deviceresolution_sk = rr.deviceresolution_sk 
										and dd.datevalue between '$fromTime' and '$toTime' 
										and pp.product_id = $productId
										and pp.product_active = 1
										and pp.channel_active = 1
										and pp.version_active = 1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."     f,
		 ".$dwdb->dbprefix('dim_product')." 		  p,
		 		".$dwdb->dbprefix('dim_date')."  		  d,
		 ".$dwdb->dbprefix('dim_deviceresolution')." 		  r
where   
				 f.date_sk = d.date_sk
				 and f.product_sk = p.product_sk
				 and f.deviceresolution_sk = r.deviceresolution_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and p.product_id = $productId
				 and p.product_active = 1
				 and p.channel_active = 1
				 and p.version_active = 1
group by r.deviceresolution_name
order by percentage desc ;
		";
		$query = $dwdb->query($sql);
		return $query;
	}
}

?>