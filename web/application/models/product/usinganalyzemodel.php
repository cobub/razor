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
 * Usinganalyzemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Usinganalyzemodel extends CI_Model
{



    /** 
     * Construct load 
     * Construct function 
     * 
     * @return void 
     */
    function __construct()
    {
        parent::__construct();
    }
    /** 
     * Get using time by product 
     * GetUsingTimeByProduct function 
     * 
     * @param string $productId productid 
     * @param string $fromTime  fromtime 
     * @param string $toTime    totime 
     * 
     * @return query
     */
    function getUsingTimeByProduct($productId, $fromTime, $toTime)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select   s.segment_sk,
           s.segment_name,
           count(fs.segment_sk) numbers,
           count(fs.segment_sk) / (select count(* )
              from   " . $dwdb -> dbprefix('fact_usinglog_daily') . " ff,
              		" . $dwdb -> dbprefix('dim_date') . " dd , 
                    " . $dwdb -> dbprefix('dim_product') . "     pp
              where  
              		 dd.date_sk = ff.date_sk and dd.datevalue between '$fromTime' and '$toTime' and
              		 pp.product_id = $productId
                     and pp.product_sk = ff.product_sk and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
                     from    " . $dwdb -> dbprefix('dim_segment_usinglog') . " s
              left join (select f.segment_sk
                    from  " . $dwdb -> dbprefix('fact_usinglog_daily') . "     f,
                          " . $dwdb -> dbprefix('dim_product') . "     p,
                          " . $dwdb -> dbprefix('dim_date') . " d 
                    where  d.date_sk = f.date_sk and d.datevalue between '$fromTime' and '$toTime' and p.product_id = $productId
                           and p.product_sk = f.product_sk) fs
           on fs.segment_sk = s.segment_sk
           group by s.segment_sk,s.segment_name
           order by s.segment_sk;
		";
        $query = $dwdb -> query($sql);
        return $query;
    }
    
     /** 
      * Get using frequence 
      * GetUsingFrequenceByProduct function 
      * 
      * @param string $productId productid 
      * @param string $fromTime  fromTime 
      * @param string $toTime    totime 
      * 
      * @return query 
      */
    function getUsingFrequenceByProduct($productId, $fromTime, $toTime)
    {
        $dwdb = $this -> load -> database('dw', true);

        $sql = "select s.segment_sk,s.segment_name,
       				   ifnull(sum(f.accesscount),0) access,
       				   ifnull(sum(f.accesscount),0)
         				/ (select sum(ifnull(ff.accesscount,0)) 
         from   " . $dwdb -> dbprefix('fact_launch_daily') . " ff," . $dwdb -> dbprefix('dim_date') . " dd,  " . $dwdb -> dbprefix('dim_product') . " pp  
         		where 
         		ff.date_sk = dd.date_sk and dd.datevalue between '$fromTime' and '$toTime' and 
         		ff.product_sk = pp.product_sk 
         		and pp.product_id = $productId 
         		and pp.product_active=1
         		 and pp.channel_active=1 
         		 and pp.version_active=1) percentage 
         from   " . $dwdb -> dbprefix('fact_launch_daily') . " f
       				inner join " . $dwdb -> dbprefix('dim_product') . " p
         			on f.product_sk = p.product_sk 
         			inner join " . $dwdb -> dbprefix('dim_date') . " d 
         			on f.date_sk = d.date_sk 
         			and d.datevalue between '$fromTime' and '$toTime' 
            		and p.product_id = $productId 
            		and p.product_active=1 
            		and p.channel_active=1 
            		and p.version_active=1 
            		right join " . $dwdb -> dbprefix('dim_segment_launch') . " s
         			on f.segment_sk = s.segment_sk 
         			group by s.segment_sk 
		order by s.segment_sk;";

        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Get usingtime 
     * GetUsingTimeByDayAndChannelId function 
     * 
     * @param string $fromTime  fromTime 
     * @param string $toTime    toTime 
     * @param string $productId productid 
     * @param string $channelId channelid 
     * 
     * @return query 
     */
    function getUsingTimeByDayAndChannelId($fromTime, $toTime, $productId, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select ddd.datevalue,ifnull(ppp.aver,0) aver
				from (select dd.datevalue
				from  " . $dwdb -> dbprefix('dim_date_day') . "   dd	
	            where dd.datevalue between '$fromTime' and '$toTime') ddd
	            left join (select d.datevalue,
	            sum(f.duration)/count(f.session_id)/1000 aver
	            from  " . $dwdb -> dbprefix('fact_usinglog_daily') . "   f,
	            " . $dwdb -> dbprefix('dim_date_day') . "   d,
	            " . $dwdb -> dbprefix('dim_product') . "   p
	            where f.date_sk = d.date_sk
	            and d.datevalue between '$fromTime' and '$toTime'
	            and f.product_sk = p.product_sk
	            and p.product_id = $productId 
	            and p.channel_id = $channelId 
	            group by d.datevalue
	            order by d.datevalue) ppp
	            on ddd.datevalue = ppp.datevalue;";

        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Get average usingtime 
     * GetAverageUsingTimeByDay function 
     * 
     * @param string $fromTime  fromtime 
     * @param string $toTime    totime 
     * @param string $productId productid 
     * @param int    $pageIndex pageIndex 
     * @param int    $pageNums  pagenums 
     * @param string $order     order 
     * 
     * @return query 
     */
    function getAverageUsingTimeByDay($fromTime, $toTime, $productId, $pageIndex = 0, $pageNums = RECORD_NUM, $order = "ASC")
    {
        $from = ($pageIndex * $pageNums);
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select d.datevalue,ifnull(sum(usingtime),0) totalaccess
		from  " . $dwdb -> dbprefix('sum_basic_all') . "   s inner join  " . $dwdb -> dbprefix('dim_product') . "    p on  p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from  " . $dwdb -> dbprefix('dim_date') . "   where datevalue between '$fromTime' and '$toTime' order by date_sk $order) d on s.date_sk = d.date_sk group by d.datevalue limit $from,$pageNums;";

        $query = $dwdb -> query($sql);
        return $query;
    }

}
