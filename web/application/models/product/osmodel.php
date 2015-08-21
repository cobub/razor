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
 * Osmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Osmodel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * GetActiUsersPercentByOS
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getActiUsersPercentByOS($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select   o.deviceos_name,
		count(distinct f.deviceidentifier)/ (select count(distinct ff.deviceidentifier,o.deviceos_name) percent
		from  " . $dwdb -> dbprefix('fact_clientdata') . " ff," . $dwdb -> dbprefix('dim_date') . " dd," . $dwdb -> dbprefix('dim_product') . " pp," . $dwdb -> dbprefix('dim_deviceos') . " oo
		where  ff.date_sk = dd.date_sk and ff.product_sk = pp.product_sk and ff.deviceos_sk = oo.deviceos_sk 
		and dd.datevalue between '$fromTime' and '$toTime' 
		and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage 
		from " . $dwdb -> dbprefix('fact_clientdata') . " f," . $dwdb -> dbprefix('dim_date') . " d," . $dwdb -> dbprefix('dim_product') . " p," . $dwdb -> dbprefix('dim_deviceos') . " o 
		where  f.date_sk = d.date_sk and f.product_sk = p.product_sk and f.deviceos_sk = o.deviceos_sk 
		and d.datevalue between '$fromTime' and '$toTime' and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 
		group by o.deviceos_name 
		order by percentage desc limit 0,$count;";

        $query = $dwdb -> query($sql);
        return $query;

    }

    /**
     * GetNewUserPercentByOS
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getNewUserPercentByOS($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select o.deviceos_name,
		count(distinct f.deviceidentifier) / (select count(distinct ff.deviceidentifier,o.deviceos_name) percent 
		from  " . $dwdb -> dbprefix('fact_clientdata') . " ff," . $dwdb -> dbprefix('dim_date') . " dd," . $dwdb -> dbprefix('dim_product') . " pp," . $dwdb -> dbprefix('dim_deviceos') . " oo 
		where  
			ff.date_sk = dd.date_sk 
			and ff.product_sk = pp.product_sk 
			and ff.deviceos_sk = oo.deviceos_sk 
			and dd.datevalue between '$fromTime' and '$toTime' 
			and pp.product_id = $productId 
			and pp.product_active=1 
			and pp.channel_active=1 
			and pp.version_active=1 
			and ff.isnew=1) percentage 
		from   
			" . $dwdb -> dbprefix('fact_clientdata') . " f," . $dwdb -> dbprefix('dim_date') . " d," . $dwdb -> dbprefix('dim_product') . " p," . $dwdb -> dbprefix('dim_deviceos') . " o 
		where   
			f.date_sk = d.date_sk 
			and f.product_sk = p.product_sk 
			and f.deviceos_sk = o.deviceos_sk 
			and d.datevalue between '$fromTime' and '$toTime' 
			and p.product_id = $productId 
			and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1 
		group by o.deviceos_name 
		order by percentage desc limit 0,$count;";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetTotalUserPercentByOS
     *
     * @param string $productId productId
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * 
     * @return query
     */
    function getTotalUserPercentByOS($productId, $fromTime, $toTime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select o.deviceos_name,sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_deviceos') . " f, " . $dwdb->dbprefix('dim_date') . " d, " .
            $dwdb->dbprefix('dim_deviceos') . " o
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId 
        and d.date_sk = f.date_sk
        and f.deviceos_sk = o.deviceos_sk
        and o.deviceos_name<> 'unknown'
        group by o.deviceos_name
        order by sessions desc ";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetOsSessionNewuserTotal
     *
     * @param string $productId productId
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * 
     * @return query
     */
    function getOsSessionNewuserTotal($productId, $fromTime, $toTime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select sum(sessions) sessions,sum(newusers) newusers
        from " . $dwdb->dbprefix('sum_deviceos') . " f, " . $dwdb->dbprefix('dim_date') . " d, " .
            $dwdb->dbprefix('dim_deviceos') . " o
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId 
        and d.date_sk = f.date_sk
        and f.deviceos_sk = o.deviceos_sk
        and o.deviceos_name<> 'unknown' ";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetSessionsByOstop
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param string $count     count
     * 
     * @return query
     */
    function getSessionsByOstop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select o.deviceos_name,sum(sessions) sessions
        from " . $dwdb->dbprefix('sum_deviceos') . " f, " . $dwdb->dbprefix('dim_date') . " d, " .
            $dwdb->dbprefix('dim_deviceos') . " o
        where d.datevalue between '$fromTime' and '$toTime'
        and f.product_id = $productId 
        and d.date_sk = f.date_sk
        and f.deviceos_sk = o.deviceos_sk
        and o.deviceos_name<> 'unknown'
        group by o.deviceos_name
        order by sessions desc
        limit 10";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetNewusersByOstop
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param string $count     count
     * 
     * @return query
     */
    function getNewusersByOstop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select o.deviceos_name,sum(newusers) newusers
                from " . $dwdb->dbprefix('sum_deviceos') . " f, " . $dwdb->dbprefix('dim_date') . " d, " .
                $dwdb->dbprefix('dim_deviceos') . " o
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId 
                and d.date_sk = f.date_sk
                and f.deviceos_sk = o.deviceos_sk
                and o.deviceos_name<> 'unknown'
                group by o.deviceos_name
                order by newusers desc
                limit 10";
        $query = $dwdb->query($sql);
        return $query;
    }
    

}
?>