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
 * Resolutionmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Resolutionmodel extends CI_Model
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
     * GetActiveUsersPercentByOrientation
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getActiveUsersPercentByOrientation($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select   r.deviceresolution_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier) percent
						 from  " . $dwdb -> dbprefix('fact_clientdata') . "    ff,
								 " . $dwdb -> dbprefix('dim_date') . "  		 dd,
								 " . $dwdb -> dbprefix('dim_product') . "  		 pp,
								 " . $dwdb -> dbprefix('dim_deviceresolution') . "  		 rr
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.deviceresolution_sk = rr.deviceresolution_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId
										and pp.product_active = 1
										and pp.channel_active = 1
										and pp.version_active = 1) percentage
from  " . $dwdb -> dbprefix('fact_clientdata') . "  f,
	 " . $dwdb -> dbprefix('dim_date') . "   d,
	 " . $dwdb -> dbprefix('dim_product') . "    p,
	 " . $dwdb -> dbprefix('dim_deviceresolution') . "    r
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
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetNewUsersPercentByOrientation
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getNewUsersPercentByOrientation($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select   r.deviceresolution_name,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct ff.deviceidentifier) percent
						 from  " . $dwdb -> dbprefix('fact_clientdata') . "    ff,
								 " . $dwdb -> dbprefix('dim_date') . "  		 dd,
								 " . $dwdb -> dbprefix('dim_product') . "  		 pp,
								 " . $dwdb -> dbprefix('dim_deviceresolution') . "  		 rr
						 where  ff.date_sk = dd.date_sk
										and ff.product_sk = pp.product_sk
										and ff.deviceresolution_sk = rr.deviceresolution_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and pp.product_id = $productId
										and pp.product_active = 1
										and pp.channel_active = 1
										and pp.version_active = 1 and ff.isnew=1) percentage
			from     " . $dwdb -> dbprefix('fact_clientdata') . "      f,
		 	   		 " . $dwdb -> dbprefix('dim_date') . "  		  d,
					 " . $dwdb -> dbprefix('dim_product') . "  		  p,
					 " . $dwdb -> dbprefix('dim_deviceresolution') . "  		  r
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
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * GetTotalUsersPercentByResolution
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getTotalUsersPercentByResolution($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select r.deviceresolution_name,sum(sessions) sessions,sum(newusers) newusers
            from " . $dwdb->dbprefix('sum_deviceresolution') . " f, " 
            . $dwdb->dbprefix('dim_date') . " d, " 
            . $dwdb->dbprefix('dim_deviceresolution') . " r
            where d.datevalue between '$fromTime' and '$toTime'
            and f.product_id = $productId and d.date_sk = f.date_sk 
            and f.deviceresolution_sk = r.deviceresolution_sk 
            and r.deviceresolution_name<> 'unknown'
            group by r.deviceresolution_name order by sessions desc";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetSessionNewuserByResolution
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * 
     * @return query
     */
    function getSessionNewuserByResolution($fromTime, $toTime, $productId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select IFNULL(sum(sessions),0) sessions,IFNULL(sum(newusers),0) newusers
                from " . $dwdb->dbprefix('sum_deviceresolution') . " f, "
                . $dwdb->dbprefix('dim_date') . " d, "
                . $dwdb->dbprefix('dim_deviceresolution') . " r
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId 
                and d.date_sk = f.date_sk 
                and f.deviceresolution_sk = r.deviceresolution_sk 
                and r.deviceresolution_name<> 'unknown' ";
        $query = $dwdb->query($sql);
        
        return $query;
    }
    
    /**
     * GetSessionByOrientiontop
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getSessionByOrientiontop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select r.deviceresolution_name,sum(sessions) sessions
                from " . $dwdb->dbprefix('sum_deviceresolution') . " f, " 
                . $dwdb->dbprefix('dim_date') . " d, " 
                . $dwdb->dbprefix('dim_deviceresolution') . " r
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId
                and d.date_sk = f.date_sk
                and f.deviceresolution_sk = r.deviceresolution_sk
                and r.deviceresolution_name<> 'unknown'
                group by r.deviceresolution_name
                order by sessions desc limit 10 ";
        $query = $dwdb->query($sql);
        return $query;
    }
    
    /**
     * GetNewuserByOrientiontop
     *
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * @param int    $count     count
     * 
     * @return query
     */
    function getNewuserByOrientiontop($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select r.deviceresolution_name,sum(newusers) newusers
                from " . $dwdb->dbprefix('sum_deviceresolution') . " f, " 
                . $dwdb->dbprefix('dim_date') . " d, " 
                . $dwdb->dbprefix('dim_deviceresolution') . " r
                where d.datevalue between '$fromTime' and '$toTime'
                and f.product_id = $productId and d.date_sk = f.date_sk 
                and f.deviceresolution_sk = r.deviceresolution_sk 
                and r.deviceresolution_name<> 'unknown'
                group by r.deviceresolution_name order by newusers desc limit 10 ";
        $query = $dwdb->query($sql);
        return $query;
    }
    

}
?>