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
class Networkmodel extends CI_Model
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
     * GetActiveUserNetWorkType
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getActiveUserNetWorkType($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select   n.networkname,
                count(distinct f.deviceidentifier)
                / (select count(distinct nn.network_sk,ff.deviceidentifier)
                from  " . $dwdb -> dbprefix('fact_clientdata') . "     ff,
                " . $dwdb -> dbprefix('dim_product') . "  	 pp,
                " . $dwdb -> dbprefix('dim_date') . "  	 dd,
                " . $dwdb -> dbprefix('dim_network') . "  	 nn
                where  ff.date_sk = dd.date_sk
                and dd.datevalue between '$fromTime' and '$toTime'
                and ff.network_sk = nn.network_sk and ff.product_sk = pp.product_sk
                and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
                from   " . $dwdb -> dbprefix('fact_clientdata') . "      f,
                " . $dwdb -> dbprefix('dim_product') . "  		  p,
                " . $dwdb -> dbprefix('dim_date') . "  	  d,
                " . $dwdb -> dbprefix('dim_network') . "  	  n
                where    f.date_sk = d.date_sk
                and d.datevalue between '$fromTime' and '$toTime'
                and f.product_sk = p.product_sk
                and p.product_id = $productId
                and f.network_sk = n.network_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1
                group by n.networkname
                order by percentage desc limit 0, $count;";

        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetNewUserNetWorkType
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getNewUserNetWorkType($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select   n.networkname,
                count(distinct f.deviceidentifier)
                / (select count(distinct nn.network_sk,ff.deviceidentifier)
                from " . $dwdb -> dbprefix('fact_clientdata') . "      ff,
                " . $dwdb -> dbprefix('dim_product') . "  		 pp,
                " . $dwdb -> dbprefix('dim_date') . "  		 dd,
                " . $dwdb -> dbprefix('dim_network') . "  		 nn
                where  ff.date_sk = dd.date_sk
                and dd.datevalue between '$fromTime' and '$toTime'
                and ff.network_sk = nn.network_sk and ff.product_sk = pp.product_sk
                and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
                from   " . $dwdb -> dbprefix('fact_clientdata') . "      f,
                " . $dwdb -> dbprefix('dim_product') . "  		  p,
                " . $dwdb -> dbprefix('dim_date') . "  		  d,
                " . $dwdb -> dbprefix('dim_network') . "  		  n
                where    f.date_sk = d.date_sk
                and d.datevalue between '$fromTime' and '$toTime'
                and f.product_sk = p.product_sk
                and p.product_id = $productId
                and f.network_sk = n.network_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
                group by n.networkname
                order by percentage desc limit 0, $count;";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetALlNetWorkData
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * 
     * @return query
     */
    function getALlNetWorkData($productid, $fromTime, $toTime)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select n.networkname,sum(sessions) sessions,sum(newusers) newusers
                from  " . $dwdb -> dbprefix('sum_devicenetwork') . " f,
                " . $dwdb -> dbprefix('dim_date') . " d,
                " . $dwdb -> dbprefix('dim_network') . " n
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productid
                and d.date_sk = f.date_sk
                and f.devicenetwork_sk = n.network_sk
                and n.networkname <> 'unknown'
                group by n.networkname
                order by sessions desc ";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetSessionNetWorkTop
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getSessionNetWorkTop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select n.networkname,sum(sessions) sessions
                from  " . $dwdb -> dbprefix('sum_devicenetwork') . " f,
                " . $dwdb -> dbprefix('dim_date') . " d,
                " . $dwdb -> dbprefix('dim_network') . " n
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId
                and d.date_sk = f.date_sk
                and f.devicenetwork_sk = n.network_sk
                and n.networkname <> 'unknown'
                group by n.networkname
                order by sessions desc limit 10 ";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetNewuserNetWorkTop
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getNewuserNetWorkTop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select n.networkname,sum(newusers) newusers
                from  " . $dwdb -> dbprefix('sum_devicenetwork') . " f,
                " . $dwdb -> dbprefix('dim_date') . " d,
                " . $dwdb -> dbprefix('dim_network') . " n
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId
                and d.date_sk = f.date_sk
                and f.devicenetwork_sk = n.network_sk
                and n.networkname <> 'unknown'
                group by n.networkname
                order by newusers desc limit 10 ";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetSessionNewusersNumByNetwork
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * 
     * @return query
     */
    function getSessionNewusersNumByNetwork($fromTime, $toTime, $productid)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select IFNULL(sum(sessions),0) sessions,
                IFNULL(sum(newusers),0) newusers
                from  " . $dwdb -> dbprefix('sum_devicenetwork') . " f,
                " . $dwdb -> dbprefix('dim_date') . " d,
                " . $dwdb -> dbprefix('dim_network') . " n
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productid
                and d.date_sk = f.date_sk
                and f.devicenetwork_sk = n.network_sk
                and n.networkname <> 'unknown' ";
        $query = $dwdb -> query($sql);
        return $query;
    }

}
?>