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
 * Device Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Devicemodel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
    }

    /**
     * getActiveUsersPercentByDevice function
     * View the number and percentage of active users of the equipment
     *
     * @param int $fromTime  from time
     * @param int $toTime    to time
     * @param int $productId product id
     * @param int $count     count
     *
     * @return query
     */
    function getActiveUsersPercentByDevice ($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select
                b.devicebrand_name,
                count(distinct f.deviceidentifier)
                / (select count(distinct ff.deviceidentifier,bb.devicebrand_name) percent
            from 
                " . $dwdb->dbprefix('fact_clientdata') . " ff,
                " . $dwdb->dbprefix('dim_date') . " dd,
                " . $dwdb->dbprefix('dim_product') . " pp,
                " . $dwdb->dbprefix('dim_devicebrand') . " bb
            where 
                ff.date_sk = dd.date_sk
                and ff.product_sk = pp.product_sk
                and ff.devicebrand_sk = bb.devicebrand_sk
                and dd.datevalue between '$fromTime' and '$toTime'
                and pp.product_id = $productId and pp.product_active=1 
                and pp.channel_active=1 and pp.version_active=1) percentage
            from 
                " . $dwdb->dbprefix('fact_clientdata') . " f,
                " . $dwdb->dbprefix('dim_date') . " d,
                " . $dwdb->dbprefix('dim_product') . " p,
                " . $dwdb->dbprefix('dim_devicebrand') . " b
            where 
                f.date_sk = d.date_sk
                and f.product_sk = p.product_sk
                and f.devicebrand_sk = b.devicebrand_sk
                and d.datevalue between '$fromTime' and '$toTime'
                and p.product_id = $productId and p.product_active=1
                and p.channel_active=1 and p.version_active=1
            group by 
                b.devicebrand_name
            order by 
                percentage desc limit 10;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * getNewUserPercentByDevice function
     * View equipment number and percentage of new users
     *
     * @param int $fromTime  from time
     * @param int $toTime    to time
     * @param int $productId product id
     * @param int $count     count
     *
     * @return query
     */
    function getNewUserPercentByDevice ($fromTime, $toTime, $productId,$count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                b.devicebrand_name,count(distinct f.deviceidentifier)
                / (select count(distinct ff.deviceidentifier,bb.devicebrand_name) percent
            from 
                " . $dwdb->dbprefix('fact_clientdata') . "     ff,
                " . $dwdb->dbprefix('dim_date') . "  		 dd,
                " . $dwdb->dbprefix('dim_product') . "  		 pp,
                " . $dwdb->dbprefix('dim_devicebrand') . "  		 bb
            where 
                ff.date_sk = dd.date_sk and ff.product_sk = pp.product_sk
                and ff.devicebrand_sk = bb.devicebrand_sk
                and dd.datevalue between '$fromTime' and '$toTime'
                and pp.product_id = $productId and pp.product_active=1 
                and pp.channel_active=1 and pp.version_active=1 
                and ff.isnew=1) percentage
            from 
            " . $dwdb->dbprefix('fact_clientdata') . "      f,
		    " . $dwdb->dbprefix('dim_date') . "  		  d,
		    " . $dwdb->dbprefix('dim_product') . "  		  p,
		    " . $dwdb->dbprefix('dim_devicebrand') . "  		  b
            where 
                f.date_sk = d.date_sk and f.product_sk = p.product_sk
				and f.devicebrand_sk = b.devicebrand_sk
				and d.datevalue between '$fromTime' and '$toTime'
				and p.product_id = $productId and p.product_active=1 
				and p.channel_active=1 and p.version_active=1 and f.isnew=1
            group by 
                b.devicebrand_name
            order by 
                percentage desc limit 10;";
        // echo $sql;
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * getDeviceTypeDetail function
     * Get user percents by device
     *
     * @param int $productId product id
     * @param int $fromTime  from time
     * @param int $toTime    to time
     *
     * @return query
     */
    function getDeviceTypeDetail ($productId, $fromTime, $toTime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select b.devicebrand_name,sum(sessions) sessions,sum(newusers) newusers
                from " . $dwdb->dbprefix('sum_devicebrand') . " f, " .
                $dwdb->dbprefix('dim_date') . " d, " .
                $dwdb->dbprefix('dim_devicebrand') . " b
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId
                and d.date_sk = f.date_sk
                and f.devicebrand_sk = b.devicebrand_sk
                and b.devicebrand_name<> 'unknown'
                group by b.devicebrand_name
                order by sessions desc ";
        $query = $dwdb->query($sql);
        
        return $query;
    }
    
    /**
     * GetDeviceSessionTotal function
     *
     * @param string $productId productId
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     *
     * @return query
     */
    function getDeviceSessionTotal($productId, $fromTime, $toTime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select sum(newusers) newusers,sum(sessions) sessions
            from " . $dwdb->dbprefix('sum_devicebrand') . " f, " .
            $dwdb->dbprefix('dim_date') . " d, " .
            $dwdb->dbprefix('dim_devicebrand') . " b
            where d.datevalue between '$fromTime' and '$toTime'
            and f.product_id = $productId
            and d.date_sk = f.date_sk
            and f.devicebrand_sk = b.devicebrand_sk
            and b.devicebrand_name<> 'unknown' ";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetSessionByDevicetop function
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     *
     * @return query
     */
    function getSessionByDevicetop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select b.devicebrand_name,sum(sessions) sessions
                from " . $dwdb->dbprefix('sum_devicebrand') . " f, " .
                $dwdb->dbprefix('dim_date') . " d, " .
                $dwdb->dbprefix('dim_devicebrand') . " b
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId
                and d.date_sk = f.date_sk
                and f.devicebrand_sk = b.devicebrand_sk
                and b.devicebrand_name<> 'unknown'
                group by b.devicebrand_name
                order by sessions desc
                limit 10";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetNewuserByDevicetop function
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     *
     * @return query
     */
    function getNewuserByDevicetop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select b.devicebrand_name,sum(newusers) newusers
                from " . $dwdb->dbprefix('sum_devicebrand') . " f, " .
                $dwdb->dbprefix('dim_date') . " d, " .
                $dwdb->dbprefix('dim_devicebrand') . " b
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId
                and d.date_sk = f.date_sk
                and f.devicebrand_sk = b.devicebrand_sk
                and b.devicebrand_name<> 'unknown'
                group by b.devicebrand_name
                order by newusers desc
                limit 10";
        $query = $dwdb->query($sql);
        return $query;
    }
    
}
?>