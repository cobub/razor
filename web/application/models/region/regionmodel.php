<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Regionmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Regionmodel extends CI_Model
{


    /** 
     * Construct load 
     * Construct function 
     * 
     * @return void 
     */
    function __construct()
    {
        $this -> load -> database();
        $this -> load -> model('common');

    }
    
    /** 
     * Get active percent by country 
     * Getactivebycountry function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param int    $pageFrom  pageFrom 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function getactivebycountry($fromTime, $toTime, $productid, $pageFrom = 0, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
        select   l.country, count(distinct f.deviceidentifier) as access,
         count(distinct f.deviceidentifier)
           / (select count(distinct ff.deviceidentifier,ll.country) percent
              from  " . $dwdb -> dbprefix('fact_clientdata') . "   ff,
                    " . $dwdb -> dbprefix('dim_date') . "  dd,
                    " . $dwdb -> dbprefix('dim_product') . "  pp,
                   " . $dwdb -> dbprefix('dim_location') . "   ll
              where  ff.date_sk = dd.date_sk
                     and ff.product_sk = pp.product_sk
                     and ff.location_sk = ll.location_sk
                     and dd.datevalue between '$fromTime' and '$toTime'
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   " . $dwdb -> dbprefix('fact_clientdata') . "     f,
      " . $dwdb -> dbprefix('dim_date') . "     d,
       " . $dwdb -> dbprefix('dim_product') . "    p,
       " . $dwdb -> dbprefix('dim_location') . "    l
where    f.date_sk = d.date_sk
         and f.product_sk = p.product_sk
         and f.location_sk = l.location_sk
         and d.datevalue between '$fromTime' and '$toTime'
         and p.product_id = $productid and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by l.country
order by percentage desc  limit $pageFrom,$count;
		";

        $query = $dwdb -> query($sql);
        return $query;

    }
    
    /** 
     * Get new user region 
     * Getnewbypro function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function getnewbypro($fromTime, $toTime, $productid, $country, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
select   l.region,count(distinct f.deviceidentifier) as access, 
         count(distinct f.deviceidentifier)
           / (select count(distinct ff.deviceidentifier,ll.region) percent
              from  " . $dwdb -> dbprefix('fact_clientdata') . "    ff,
                    " . $dwdb -> dbprefix('dim_date') . "    dd,
                    " . $dwdb -> dbprefix('dim_product') . "    pp,
                    " . $dwdb -> dbprefix('dim_location') . "    ll
              where  ll.country = '$country'
                     and ff.date_sk = dd.date_sk
                     and ff.product_sk = pp.product_sk
                     and ff.location_sk = ll.location_sk
                     and dd.datevalue between '$fromTime' and '$toTime'
                     and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from   " . $dwdb -> dbprefix('fact_clientdata') . "      f,
         " . $dwdb -> dbprefix('dim_date') . "   d,
         " . $dwdb -> dbprefix('dim_product') . "   p,
         " . $dwdb -> dbprefix('dim_location') . "   l
where    l.country = '$country'
         and f.date_sk = d.date_sk
         and f.product_sk = p.product_sk
         and f.location_sk = l.location_sk
         and d.datevalue between '$fromTime' and '$toTime'
         and p.product_id = $productid and f.isnew=1
group by l.region
order by percentage desc  limit 0, $count;

		";
        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Get country export data 
     * Getcountryexport function 
     * 
     * @param string $fromTime  fromtime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * 
     * @return array 
     */
    function getcountryexport($fromTime, $toTime, $productid)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.country country_name,sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . "  d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk 
        and f.location_sk = l.location_sk
        and l.country <> 'unknown' 
        and l.country <> '局域网'
        group by l.country 
        order by sessions desc ";
        $query = $dwdb->query($sql);
        return $query;

    }
    
    /** 
     * Get region export data 
     * Getproexport function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * 
     * @return array 
     */
    function getproexport($fromTime, $toTime, $productid, $country)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.region region_name, sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.country <> 'unknown'
        and l.country <> '局域网'
        and l.region <> 'unknown'
        and l.region <> '局域网'
        group by l.country,l.region
        order by sessions desc";
        $query = $dwdb->query($sql);
        
        return $query;
    }
    
    /** 
     * Getcity export data 
     * Getcityexport function 
     * 
     * @param string $fromTime  fromtime 
     * @param string $toTime    totime 
     * @param string $productid productid 
     * @param string $country   country 
     * 
     * @return array 
     */
    function getcityexport($fromTime, $toTime, $productid, $country)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.city city_name,sum(sessions) sessions,sum(newusers) newusers from
        " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.country <> 'unknown'
        and l.country <> '局域网'
        and l.region <> 'unknown'
        and l.region <> '局域网'
        and l.city <> 'unknown'
        and l.city <> '局域网'
        and l.city <> ''
        group by l.country,l.region,l.city
        order by sessions desc";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get total by country 
     * Gettotalacbycountry function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * 
     * @return array 
     */
    function gettotalacbycountry($fromTime, $toTime, $productid)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
		select   l.country, count(distinct f.deviceidentifier) as access,
		count(distinct f.deviceidentifier)
		/ (select count(distinct ff.deviceidentifier,ll.country) percent
		from  " . $dwdb -> dbprefix('fact_clientdata') . "    ff,
		 " . $dwdb -> dbprefix('dim_date') . "  dd,
		 " . $dwdb -> dbprefix('dim_product') . "  pp,
		 " . $dwdb -> dbprefix('dim_location') . "  ll
		where  ff.date_sk = dd.date_sk
		and ff.product_sk = pp.product_sk
		and ff.location_sk = ll.location_sk
		and dd.datevalue between '$fromTime' and '$toTime'
		and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
		from   " . $dwdb -> dbprefix('fact_clientdata') . "     f,
		 " . $dwdb -> dbprefix('dim_date') . "  d,
		 " . $dwdb -> dbprefix('dim_product') . "  p,
		 " . $dwdb -> dbprefix('dim_location') . "  l
		where    f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and f.location_sk = l.location_sk
		and d.datevalue between '$fromTime' and '$toTime'
		and p.product_id = $productid and p.product_active=1 and p.channel_active=1 and p.version_active=1
		group by l.country
		order by percentage desc ;
		";
        $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> num_rows();
        } else {
            return 0;
        }
    }
    
    /** 
     * Get total by province 
     * gettotalactivebypro function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * 
     * @return array 
     */
    function gettotalactivebypro($fromTime, $toTime, $productid, $country)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
		select   l.region,
		count(distinct f.deviceidentifier)
		/ (select count(distinct ff.deviceidentifier,ll.region) percent
		from  " . $dwdb -> dbprefix('fact_clientdata') . "    ff,
		 " . $dwdb -> dbprefix('dim_date') . "  dd,
		 " . $dwdb -> dbprefix('dim_product') . "  pp,
		 " . $dwdb -> dbprefix('dim_location') . "  ll
		where  ll.country = '$country'
		and ff.date_sk = dd.date_sk
		and ff.product_sk = pp.product_sk
		and ff.location_sk = ll.location_sk
		and dd.datevalue between '$fromTime' and '$toTime'
		and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
		from   " . $dwdb -> dbprefix('fact_clientdata') . "     f,
		 " . $dwdb -> dbprefix('dim_date') . "  d,
	 " . $dwdb -> dbprefix('dim_product') . " 	 p,
		 " . $dwdb -> dbprefix('dim_location') . "  l
		where    l.country = '$country'
		and f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and f.location_sk = l.location_sk
		and d.datevalue between '$fromTime' and '$toTime'
		and p.product_id = $productid
		group by l.region
		order by percentage desc ;
		
		";
        $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> num_rows();
        } else {
            return 0;
        }
    }
    /** 
     * Get total user percent country 
     * getTotalUsersPercentByCountry function 
     * 
     * @param string $productid productid 
     * 
     * @return array 
     */
    function getTotalUsersPercentByCountry($productid)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select l.country, count(distinct f.deviceidentifier) total,
		 count(distinct f.deviceidentifier) /( select count(distinct ff.deviceidentifier,ll.country)
		 percent from  " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "   ff,   " . $dwdb -> dbprefix('dim_product') . "  pp,  " . $dwdb -> dbprefix('dim_location') . "  ll
		 where ff.product_sk=pp.product_sk and ff.location_sk=ll.location_sk
		 and pp.product_id=$productid) percentage from  " . $dwdb -> dbprefix('fact_activeusers_clientdata') . " 
		  f,  " . $dwdb -> dbprefix('dim_product') . "   p,  " . $dwdb -> dbprefix('dim_location') . "  l
		 where f.product_sk=p.product_sk and f.location_sk = l.location_sk
		 and p.product_id=$productid group by l.country order by count(distinct f.deviceidentifier) desc";
        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Get country num,total,sessions,newusers 
     * Getcountrynum function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productId productId 
     * 
     * @return array 
     */
    function getcountrynum($fromTime, $toTime, $productId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.country country_name,sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . "  d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId
        and d.date_sk = f.date_sk 
        and f.location_sk = l.location_sk
        and l.country <> 'unknown' 
        and l.country <> '局域网'
        group by l.country";
        $query = $dwdb->query($sql);

        if ($query != null && $query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    /** 
     * Get onepage sessions,newusers by country 
     * Gettotalbycountry function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productId productId 
     * @param int    $pageFrom  pageFrom 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function gettotalbycountry($fromTime, $toTime, $productId, $pageFrom = 0, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.country country_name, sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . "  d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId
        and d.date_sk = f.date_sk 
        and f.location_sk = l.location_sk
        and l.country <> 'unknown' 
        and l.country <> '局域网'
        group by l.country 
        order by sessions desc limit $pageFrom,$count; ";
        $query = $dwdb->query($sql);

        return $query;
    }
    
    /** 
     * Get sessions,newusers region 
     * Gettotalbypro function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * @param int    $pageFrom  pageFrom 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function gettotalbypro($fromTime, $toTime, $productid, $country, $pageFrom = 0, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.region region_name, sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.region <> 'unknown'
        and l.region <> '局域网'
        group by l.country,l.region
        order by sessions desc 
        limit $pageFrom,$count";

        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get city 
     * Getcitynum function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * 
     * @return array 
     */
    function getcitynum($fromTime, $toTime, $productid, $country)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.city city_name,sum(sessions) sessions,sum(newusers) newusers from  
        " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.country <> 'unknown'
        and l.country <> '局域网'
        and l.region <> 'unknown'
        and l.region <> '局域网'
        and l.city <> 'unknown'
        and l.city <> '局域网'
        and l.city <> ''
        group by l.country,l.region,l.city
        order by sessions desc";

        $query = $dwdb->query($sql);
        if ($query != null && $query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    /** 
     * Get total city 
     * gettotlebycity function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * @param int    $pageFrom  pageFrom 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function gettotlebycity($fromTime, $toTime, $productid, $country, $pageFrom = 0, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.city city_name,sum(sessions) sessions,sum(newusers) newusers from  
        " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid 
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.country <> 'unknown'
        and l.country <> '局域网'
        and l.region <> 'unknown'
        and l.region <> '局域网'
        and l.city <> 'unknown'
        and l.city <> '局域网'
        and l.city <> ''
        group by l.country,l.region,l.city
        order by sessions desc limit $pageFrom,$count";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get new user percent 
     * getnewbycountry function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param int    $pageFrom  pageFrom 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function getnewbycountry($fromTime, $toTime, $productid, $pageFrom = 0, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.country country_name, sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . " f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and  '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk 
        and f.location_sk = l.location_sk 
        and l.country <> 'unknown'
        and l.country <> '局域网'
        group by l.country 
        order by newusers desc limit 10";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get sessions newusers region 
     * Getactivebypro function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * @param int    $pageFrom  pageFrom 
     * @param int    $count     count 
     * 
     * @return array 
     */
    function getactivebypro($fromTime, $toTime, $productid, $country, $pageFrom = 0, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.region region_name, sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.region <> 'unknown'
        and l.region <> '局域网'
        group by l.country,l.region
        order by sessions desc limit $pageFrom,$count";

        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get pronum 
     * Getpronum function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productId productId 
     * @param string $country   country 
     * 
     * @return array 
     */
    function getpronum($fromTime, $toTime, $productId, $country)
    {
        $dwdb = $this->load->database('dw', true);

        $sql = "select l.region region_name, sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId      
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.region <> 'unknown'
        and l.region <> '局域网'
        group by l.country,l.region ";

        $query = $dwdb->query($sql);
        if ($query != null && $query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    
    /** 
     * Get session city top10 
     * Getsessionbycitytop function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productId productId 
     * 
     * @return array 
     */
    function getsessionbycitytop($fromTime, $toTime, $productId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.city city_name,sum(sessions) sessions from  
        " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.country <> 'unknown'
        and l.country <> '局域网'
        and l.region <> 'unknown'
        and l.region <> '局域网'
        and l.city <> 'unknown'
        and l.city <> '局域网'
        and l.city <> ''
        group by l.country,l.region,l.city
        order by sessions desc limit 10";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get newuser city top 
     * Getnewuserbycitytop function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productId productId 
     * 
     * @return array 
     */
    function getnewuserbycitytop($fromTime, $toTime, $productId)
    {
            $dwdb = $this->load->database('dw', true);
            $sql = "select l.city city_name,sum(newusers) newusers from  
        " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId 
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.country <> 'unknown'
        and l.country <> '局域网'
        and l.region <> 'unknown'
        and l.region <> '局域网'
        and l.city <> 'unknown'
        and l.city <> '局域网'
        and l.city <> ''
        group by l.country,l.region,l.city
        order by newusers desc limit 10";
            $query = $dwdb->query($sql);
            return $query;
    }
    
    /** 
     * Get new user percent country 
     * Getnewbycountrytop function 
     * 
     * @param string $fromTime  fromtime 
     * @param string $toTime    totime 
     * @param string $productid productid 
     * 
     * @return array 
     */
    function getnewbycountrytop($fromTime, $toTime, $productid)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.country country_name, sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_location') . " f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . " l
        where d.datevalue between '$fromTime' and  '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk 
        and f.location_sk = l.location_sk 
        and l.country <> 'unknown'
        and l.country <> '局域网'
        group by l.country 
        order by newusers desc limit 10";
        
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get active user percent country 
     * getsessionbycountrytop function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * 
     * @return array 
     */
    function getsessionbycountrytop($fromTime, $toTime, $productid)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.country country_name, sum(sessions) sessions
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . "  d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk 
        and f.location_sk = l.location_sk
        and l.country <> 'unknown' 
        and l.country <> '局域网'
        group by l.country 
        order by sessions desc limit 10";
        
        $query = $dwdb->query($sql);
        return $query;

    }
    
    /** 
     * Get session region top 
     * Getsessionbyregiontop function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * 
     * @return array 
     */
    function getsessionbyregiontop($fromTime, $toTime, $productid, $country)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select l.region region_name, sum(sessions) sessions
        from " . $dwdb->dbprefix('sum_location') . "  f,
        " . $dwdb->dbprefix('dim_date') . " d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.region <> 'unknown'
        and l.region <> '局域网'
        group by l.country,l.region
        order by sessions desc
        limit 10";

        $query = $dwdb->query($sql);
        return $query;
    }
    
    /** 
     * Get newuser region top 
     * Getnewuserbyregiontop function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productid productid 
     * @param string $country   country 
     * 
     * @return array 
     */
    function getnewuserbyregiontop($fromTime, $toTime, $productid, $country)
    {
            $dwdb = $this->load->database('dw', true);
            $sql = "select l.region region_name, sum(newusers) newusers
        from  " . $dwdb->dbprefix('sum_location') . " f,
        " . $dwdb->dbprefix('dim_date') . "  d ,
        " . $dwdb->dbprefix('dim_location') . "  l
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productid
        and d.date_sk = f.date_sk
        and f.location_sk = l.location_sk
        and l.region <> 'unknown'
        and l.region <> '局域网'
        group by l.country,l.region
        order by newusers desc
        limit 10";
            $query = $dwdb->query($sql);
            return $query;
    }
    

}
