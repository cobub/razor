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
class Regionmodel extends CI_Model {
	function __construct() {
		$this->load->database ();
		$this->load->model ( 'common' );
	
	}
	
	/*
	 * Get active user percent by country
	 */
	function getactivebycountry($fromTime, $toTime, $productid,$pageFrom=0,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );	
		$sql="
        select   l.country, count(distinct f.deviceidentifier) as access,
         count(distinct f.deviceidentifier)
           / (select count(distinct ff.deviceidentifier,ll.country) percent
              from  ".$dwdb->dbprefix('fact_clientdata')."   ff,
                    ".$dwdb->dbprefix('dim_date')."  dd,
                    ".$dwdb->dbprefix('dim_product')."  pp,
                   ".$dwdb->dbprefix('dim_location')."   ll
              where  ff.date_sk = dd.date_sk
                     and ff.product_sk = pp.product_sk
                     and ff.location_sk = ll.location_sk
                     and dd.datevalue between '$fromTime' and '$toTime'
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."     f,
      ".$dwdb->dbprefix('dim_date')."     d,
       ".$dwdb->dbprefix('dim_product')."    p,
       ".$dwdb->dbprefix('dim_location')."    l
where    f.date_sk = d.date_sk
         and f.product_sk = p.product_sk
         and f.location_sk = l.location_sk
         and d.datevalue between '$fromTime' and '$toTime'
         and p.product_id = $productid and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by l.country
order by percentage desc  limit $pageFrom,$count;
		";
		
		$query = $dwdb->query ( $sql );		
		return $query;
	
	}
	
	/*
	 * Get new user percent by country
	 */
	function getnewbycountry($fromTime, $toTime, $productid,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="
select   l.country,count(distinct f.deviceidentifier)  as access,
         count(distinct f.deviceidentifier) 
           / (select count(distinct ff.deviceidentifier,ll.country) percent
              from  ".$dwdb->dbprefix('fact_clientdata')."     ff,
                   ".$dwdb->dbprefix('dim_date')."     dd,
                   ".$dwdb->dbprefix('dim_product')."     pp,
                   ".$dwdb->dbprefix('dim_location')."     ll
              where  ff.date_sk = dd.date_sk
                     and ff.product_sk = pp.product_sk
                     and ff.location_sk = ll.location_sk
                     and dd.datevalue between '$fromTime' and '$toTime'
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from    ".$dwdb->dbprefix('fact_clientdata')."     f,
      ".$dwdb->dbprefix('dim_date')."      d,
      ".$dwdb->dbprefix('dim_product')."      p,
      ".$dwdb->dbprefix('dim_location')."      l
where    f.date_sk = d.date_sk
         and f.product_sk = p.product_sk
         and f.location_sk = l.location_sk
         and d.datevalue between '$fromTime' and '$toTime'
         and p.product_id = $productid and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
group by l.country
order by percentage desc limit 0, $count;

		";
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	/*
	 * Get  active users percent group by region
	 */
	function getactivebypro($fromTime, $toTime, $productid,$country,$pageFrom=0,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );	
		$sql="
select   l.region,count(distinct f.deviceidentifier) as access,
         count(distinct f.deviceidentifier)
           / (select count(distinct ff.deviceidentifier,ll.region) percent
              from   ".$dwdb->dbprefix('fact_clientdata')."    ff,
                    ".$dwdb->dbprefix('dim_date')."    dd,
                    ".$dwdb->dbprefix('dim_product')."    pp,
                   ".$dwdb->dbprefix('dim_location')."     ll
              where  ll.country = '$country'
                     and ff.date_sk = dd.date_sk
                     and ff.product_sk = pp.product_sk
                     and ff.location_sk = ll.location_sk
                     and dd.datevalue between '$fromTime' and '$toTime'
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from    ".$dwdb->dbprefix('fact_clientdata')."     f,
      ".$dwdb->dbprefix('dim_date')."      d,
      ".$dwdb->dbprefix('dim_product')."      p,
       ".$dwdb->dbprefix('dim_location')."     l
where    l.country = '$country'
         and f.date_sk = d.date_sk
         and f.product_sk = p.product_sk
         and f.location_sk = l.location_sk
         and d.datevalue between '$fromTime' and '$toTime'
         and p.product_id = $productid
group by l.region
order by percentage desc limit $pageFrom,$count;

		";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	/*
	 * Get new user percent group by region
	 */
	function getnewbypro($fromTime, $toTime, $productid,$country,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="
select   l.region,count(distinct f.deviceidentifier) as access, 
         count(distinct f.deviceidentifier)
           / (select count(distinct ff.deviceidentifier,ll.region) percent
              from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
                    ".$dwdb->dbprefix('dim_date')."    dd,
                    ".$dwdb->dbprefix('dim_product')."    pp,
                    ".$dwdb->dbprefix('dim_location')."    ll
              where  ll.country = '$country'
                     and ff.date_sk = dd.date_sk
                     and ff.product_sk = pp.product_sk
                     and ff.location_sk = ll.location_sk
                     and dd.datevalue between '$fromTime' and '$toTime'
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from   ".$dwdb->dbprefix('fact_clientdata')."      f,
         ".$dwdb->dbprefix('dim_date')."   d,
         ".$dwdb->dbprefix('dim_product')."   p,
         ".$dwdb->dbprefix('dim_location')."   l
where    l.country = '$country'
         and f.date_sk = d.date_sk
         and f.product_sk = p.product_sk
         and f.location_sk = l.location_sk
         and d.datevalue between '$fromTime' and '$toTime'
         and p.product_id = $productid and f.isnew=1
group by l.region
order by percentage desc  limit 0, $count;

		";
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	/*
	 * Get country export data
	 */
    function getcountryexport($fromTime, $toTime, $productid) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="
		select   l.country,count(distinct f.deviceidentifier) as access,
		count(distinct f.deviceidentifier)
		/ (select count(distinct ff.deviceidentifier,ll.country) percent
		from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
	 ".$dwdb->dbprefix('dim_date')." 	 dd,
	 ".$dwdb->dbprefix('dim_product')." 	 pp,
	 ".$dwdb->dbprefix('dim_location')." 	 ll
		where  ff.date_sk = dd.date_sk
		and ff.product_sk = pp.product_sk
		and ff.location_sk = ll.location_sk
		and dd.datevalue between '$fromTime' and '$toTime'
		and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
		from  ".$dwdb->dbprefix('fact_clientdata')."      f,
		 ".$dwdb->dbprefix('dim_date')."  d,
		 ".$dwdb->dbprefix('dim_product')."  p,
		 ".$dwdb->dbprefix('dim_location')."  l
		where    f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and f.location_sk = l.location_sk
		and d.datevalue between '$fromTime' and '$toTime'
		and p.product_id = $productid and p.product_active=1 and p.channel_active=1 and p.version_active=1
		group by l.country
		order by percentage desc ;
		";
		
		$query = $dwdb->query ( $sql );
		return $query;
	
	}
	
	/*
	 * Get region export data
	 */
	function getproexport($fromTime, $toTime, $productid,$country){
	$dwdb = $this->load->database ( 'dw', TRUE );
	$sql="
	select   l.region, count(distinct f.deviceidentifier) as access,
	count(distinct f.deviceidentifier)
	/ (select count(distinct ff.deviceidentifier,ll.region) percent
	from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
	 ".$dwdb->dbprefix('dim_date')."  dd,
	 ".$dwdb->dbprefix('dim_product')."  pp,
	 ".$dwdb->dbprefix('dim_location')."  ll
	where  ll.country = '$country'
	and ff.date_sk = dd.date_sk
	and ff.product_sk = pp.product_sk
	and ff.location_sk = ll.location_sk
	and dd.datevalue between '$fromTime' and '$toTime'
	and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
	from   ".$dwdb->dbprefix('fact_clientdata')."     f,
	 ".$dwdb->dbprefix('dim_date')."  d,
	 ".$dwdb->dbprefix('dim_product')."  p,
	 ".$dwdb->dbprefix('dim_location')."  l
	where    l.country = '$country'
	and f.date_sk = d.date_sk
	and f.product_sk = p.product_sk
	and f.location_sk = l.location_sk
	and d.datevalue between '$fromTime' and '$toTime'
	and p.product_id = $productid
	group by l.region
	order by percentage desc ;
	
	";	
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	
	/*
	 * Get total users by country
	 */
	function gettotalacbycountry($fromTime, $toTime, $productid) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="
		select   l.country, count(distinct f.deviceidentifier) as access,
		count(distinct f.deviceidentifier)
		/ (select count(distinct ff.deviceidentifier,ll.country) percent
		from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
		 ".$dwdb->dbprefix('dim_date')."  dd,
		 ".$dwdb->dbprefix('dim_product')."  pp,
		 ".$dwdb->dbprefix('dim_location')."  ll
		where  ff.date_sk = dd.date_sk
		and ff.product_sk = pp.product_sk
		and ff.location_sk = ll.location_sk
		and dd.datevalue between '$fromTime' and '$toTime'
		and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
		from   ".$dwdb->dbprefix('fact_clientdata')."     f,
		 ".$dwdb->dbprefix('dim_date')."  d,
		 ".$dwdb->dbprefix('dim_product')."  p,
		 ".$dwdb->dbprefix('dim_location')."  l
		where    f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and f.location_sk = l.location_sk
		and d.datevalue between '$fromTime' and '$toTime'
		and p.product_id = $productid and p.product_active=1 and p.channel_active=1 and p.version_active=1
		group by l.country
		order by percentage desc ;
		";
	 $query = $dwdb->query ( $sql );
	 if($query!=null && $query->num_rows()>0)
	 {
	 	return $query->num_rows();
	 }
	 else
	 {
	 	return 0;
	 }
	}
	
	/*
	 * Get total users by province
	 */
	function gettotalactivebypro($fromTime, $toTime, $productid,$country) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="
		select   l.region,
		count(distinct f.deviceidentifier)
		/ (select count(distinct ff.deviceidentifier,ll.region) percent
		from  ".$dwdb->dbprefix('fact_clientdata')."    ff,
		 ".$dwdb->dbprefix('dim_date')."  dd,
		 ".$dwdb->dbprefix('dim_product')."  pp,
		 ".$dwdb->dbprefix('dim_location')."  ll
		where  ll.country = '$country'
		and ff.date_sk = dd.date_sk
		and ff.product_sk = pp.product_sk
		and ff.location_sk = ll.location_sk
		and dd.datevalue between '$fromTime' and '$toTime'
		and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
		from   ".$dwdb->dbprefix('fact_clientdata')."     f,
		 ".$dwdb->dbprefix('dim_date')."  d,
	 ".$dwdb->dbprefix('dim_product')." 	 p,
		 ".$dwdb->dbprefix('dim_location')."  l
		where    l.country = '$country'
		and f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and f.location_sk = l.location_sk
		and d.datevalue between '$fromTime' and '$toTime'
		and p.product_id = $productid
		group by l.region
		order by percentage desc ;
		
		";
	 $query = $dwdb->query ( $sql );
	 if($query!=null && $query->num_rows()>0)
	 {
	 	return $query->num_rows();
	 }
	 else
	 {
	 	return 0;
	 }
	}
	
	/*
	 * Get Total users percent by country
	 */
	function getTotalUsersPercentByCountry($productid) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select l.country, count(distinct f.deviceidentifier) total,
		 count(distinct f.deviceidentifier) /( select count(distinct ff.deviceidentifier,ll.country)
		 percent from  ".$dwdb->dbprefix('fact_activeusers_clientdata')."   ff,   ".$dwdb->dbprefix('dim_product')."  pp,  ".$dwdb->dbprefix('dim_location')."  ll
		 where ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk
		 and pp.product_id=$productid) percentage from  ".$dwdb->dbprefix('fact_activeusers_clientdata')." 
		  f,  ".$dwdb->dbprefix('dim_product')."   p,  ".$dwdb->dbprefix('dim_location')."  l
		 where f.product_sk=p.product_sk and f.location_sk = l.location_sk
		 and p.product_id=$productid group by l.country order by count(distinct f.deviceidentifier) desc";
		$query = $dwdb->query ( $sql );
		return $query;
	}
}