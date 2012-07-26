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
	
	//查询时间段内各个国家的活跃用户百分比
	function getactivebycountry($fromTime, $toTime, $productid,$pageFrom=0,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.country, count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.country) percent  from
// 	 fact_activeusers_clientdata ff, dim_date dd, dim_product pp,dim_location
// 	 ll where ff.date_sk=dd.date_sk and ff.product_sk=pp.product_sk and
// 	 ff.location_sk=ll.location_sk and dd.startdate between '$fromTime' and '$toTime'
// 	  and pp.product_id=$productid) percentage  from
// 	 fact_activeusers_clientdata f, dim_date d, dim_product p,dim_location l
// 	 where f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk
// 	 = l.location_sk and d.startdate between '$fromTime' and '$toTime' and
// 	 p.product_id=$productid group by l.country order by
// 	 count(distinct f.deviceidentifier) desc limit $pageFrom,$count";	
		$sql="
        select   l.country,
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
	
	//查询时间段内各个国家的新用户百分比
	function getnewbycountry($fromTime, $toTime, $productid,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.country, count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.country) percent  from
// 	 fact_newusers_clientdata_by_product ff, dim_date dd, dim_product
// 	 pp,dim_location ll where ff.date_sk=dd.date_sk and
// 	 ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk and
// 	 dd.startdate between '$fromTime' and '$toTime' and pp.product_id=$productid)
// 	  percentage  from fact_newusers_clientdata_by_product f,
// 	 dim_date d, dim_product p,dim_location l where f.date_sk=d.date_sk and
// 	 f.product_sk=p.product_sk and f.location_sk = l.location_sk and
// 	 d.startdate between '$fromTime' and '$toTime' and p.product_id=$productid
// 	  group by l.country order by count(distinct
// 	 f.deviceidentifier) desc limit 0, $count";
		$sql="
select   l.country,
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
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and f.isnew=1) percentage
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
	
	//查询时间段内各个省份的活跃用户百分比	
	function getactivebypro($fromTime, $toTime, $productid,$country,$pageFrom=0,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.region, count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.region) percent  from
// 	 fact_activeusers_clientdata ff, dim_date dd, dim_product pp,dim_location
// 	 ll where ll.country='$country' and ff.date_sk=dd.date_sk and
// 	 ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk and
// 	 dd.startdate between '$fromTime' and '$toTime' and pp.product_id=$productid)
// 	  percentage  from fact_activeusers_clientdata f, dim_date
// 	 d, dim_product p,dim_location l where l.country='$country' and
// 	 f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk =
// 	 l.location_sk and d.startdate between '$fromTime' and '$toTime' and
// 	 p.product_id=$productid group by l.region order by
// 	 count(distinct f.deviceidentifier) desc limit $pageFrom,$count";		
		$sql="
select   l.region,
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
	
	//查询时间段内各个省份新用户百分比
	function getnewbypro($fromTime, $toTime, $productid,$country,$count=REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select l.region, count(distinct f.deviceidentifier) /( select
	 count(distinct ff.deviceidentifier,ll.region) percent  from  ".$dwdb->dbprefix('fact_newusers_clientdata_by_product')." 
	  ff,   ".$dwdb->dbprefix('dim_date')."   dd,  ".$dwdb->dbprefix('dim_product')."  
	 pp,  ".$dwdb->dbprefix('dim_location')."   ll where ll.country='$country' and ff.date_sk=dd.date_sk and
	 ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk and
	 dd.startdate between '$fromTime' and '$toTime' and pp.product_id=$productid)
	  percentage  from   ".$dwdb->dbprefix('fact_newusers_clientdata_by_product')."  f,
	  ".$dwdb->dbprefix('dim_date')."   d,  ".$dwdb->dbprefix('dim_product')."   p, ".$dwdb->dbprefix('dim_location')."   l where l.country='$country' and
	 f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk =
	 l.location_sk and d.startdate between '$fromTime' and '$toTime' and
	 p.product_id=$productid group by l.region order by
	 count(distinct f.deviceidentifier) desc limit 0, $count";
		$sql="
select   l.region,
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
	
//用于导出报表的数据
	//国家分布
    function getcountryexport($fromTime, $toTime, $productid) {
		$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.country , (count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.country) percent  from
// 	 fact_activeusers_clientdata ff, dim_date dd, dim_product pp,dim_location
// 	 ll where ff.date_sk=dd.date_sk and ff.product_sk=pp.product_sk and
// 	 ff.location_sk=ll.location_sk and dd.startdate between '$fromTime' and '$toTime'
// 	  and pp.product_id=$productid))*100 percentage   from
// 	 fact_activeusers_clientdata f, dim_date d, dim_product p,dim_location l
// 	 where f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk
// 	 = l.location_sk and d.startdate between '$fromTime' and '$toTime' and
// 	 p.product_id=$productid group by l.country order by
// 	 count(distinct f.deviceidentifier) desc";
		$sql="
		select   l.country,
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
	//省市分布
	function getproexport($fromTime, $toTime, $productid,$country){
	$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.region, (count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.region) percent  from
// 	 fact_activeusers_clientdata ff, dim_date dd, dim_product pp,dim_location
// 	 ll where ll.country='$country' and ff.date_sk=dd.date_sk and
// 	 ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk and
// 	 dd.startdate between '$fromTime' and '$toTime' and pp.product_id=$productid))*100
// 	  percentage  from fact_activeusers_clientdata f, dim_date
// 	 d, dim_product p,dim_location l where l.country='$country' and
// 	 f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk =
// 	 l.location_sk and d.startdate between '$fromTime' and '$toTime' and
// 	 p.product_id=$productid group by l.region order by
// 	 count(distinct f.deviceidentifier) desc";	
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
	
	
	//查询时间段内国家的活跃用户总数

	function gettotalacbycountry($fromTime, $toTime, $productid) {
		$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.country, count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.country) percent  from
// 	 fact_activeusers_clientdata ff, dim_date dd, dim_product pp,dim_location
// 	 ll where ff.date_sk=dd.date_sk and ff.product_sk=pp.product_sk and
// 	 ff.location_sk=ll.location_sk and dd.startdate between '$fromTime' and '$toTime'
// 	  and pp.product_id=$productid) percentage  from
// 	 fact_activeusers_clientdata f, dim_date d, dim_product p,dim_location l
// 	 where f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk
// 	 = l.location_sk and d.startdate between '$fromTime' and '$toTime' and
// 	 p.product_id=$productid group by l.country order by
// 	 count(distinct f.deviceidentifier) desc ";	
		$sql="
		select   l.country,
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
	
//查询时间段内各个省份的活跃用户总数
	function gettotalactivebypro($fromTime, $toTime, $productid,$country) {
		$dwdb = $this->load->database ( 'dw', TRUE );
// 		$sql = "select l.region, count(distinct f.deviceidentifier) /( select
// 	 count(distinct ff.deviceidentifier,ll.region) percent  from
// 	 fact_activeusers_clientdata ff, dim_date dd, dim_product pp,dim_location
// 	 ll where ll.country='$country' and ff.date_sk=dd.date_sk and
// 	 ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk and
// 	 dd.startdate between '$fromTime' and '$toTime' and pp.product_id=$productid)
// 	  percentage  from fact_activeusers_clientdata f, dim_date
// 	 d, dim_product p,dim_location l where l.country='$country' and
// 	 f.date_sk=d.date_sk and f.product_sk=p.product_sk and f.location_sk =
// 	 l.location_sk and d.startdate between '$fromTime' and '$toTime' and
// 	 p.product_id=$productid group by l.region order by
// 	 count(distinct f.deviceidentifier) desc";	
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
	
	//累计用户的各地域分布总数与比例
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