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
 * Userremainmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Userremainmodel extends CI_Model
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
     * Get user remain country 
     * GetUserRemainCountByWeek 
     * 
     * @param string $version   version 
     * @param string $productId productid 
     * @param string $from      from 
     * @param string $to        to 
     * @param string $channel   channel 
     * 
     * @return query
     */
    function getUserRemainCountByWeek($version, $productId, $from, $to, $channel = 'all')
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select date(d1.datevalue) startdate,
            date(d2.datevalue) enddate,
            f.version_name,
            f.usercount,
            f.week1,
            f.week2,
            f.week3,
            f.week4,
            f.week5,
            f.week6,
            f.week7,
            f.week8
            from  " . $dwdb -> dbprefix('sum_reserveusers_weekly') . "   f,
                " . $dwdb -> dbprefix('dim_date') . "    d1,
                " . $dwdb -> dbprefix('dim_date') . "    d2
                where  f.startdate_sk = d1.date_sk
                and f.enddate_sk = d2.date_sk
                and d1.datevalue >= '$from'
                and d2.datevalue <= '$to'
                and f.product_id = $productId 
                and f.version_name='$version'
                and f.channel_name='$channel'
                order by d1.datevalue desc;";
        log_message('debug', 'getUserRemainCountByWeek() SQL: ' . $sql);
        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Get user remain count day 
     * GetUserRemainCountByDay function 
     * 
     * @param string $version   version 
     * @param string $productId productid 
     * @param string $from      from 
     * @param string $to        to 
     * @param string $channel   channel 
     * 
     * @return query 
     */
    function getUserRemainCountByDay($version, $productId, $from, $to, $channel = 'all')
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			f.version_name,
			f.usercount,
			    f.day1,
			       f.day2,
			       f.day3,
			       f.day4,
			       f.day5,
			       f.day6,
			       f.day7,
			       f.day8
			from  " . $dwdb -> dbprefix('sum_reserveusers_daily') . "   f,
			     " . $dwdb -> dbprefix('dim_date') . "    d1,
			     " . $dwdb -> dbprefix('dim_date') . "    d2
			where  f.startdate_sk = d1.date_sk
			       and f.enddate_sk = d2.date_sk
		      and d1.datevalue >= '$from'
		       and d2.datevalue <= '$to'
		      and f.product_id = $productId 
		      and f.version_name='$version'
		      and f.channel_name='$channel'
	         order by d1.datevalue desc;";
        log_message('debug', 'getUserRemainCountByDay() SQL: ' . $sql);
        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Get user remain count month 
     * GetUserRemainCountByMonth function 
     * 
     * @param string $version   version 
     * @param string $productId productid 
     * @param string $from      from 
     * @param string $to        to 
     * @param string $channel   channel 
     * 
     * @return query 
     */
    function getUserRemainCountByMonth($version, $productId, $from, $to, $channel)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "	select date(d1.datevalue) startdate,
            date(d2.datevalue) enddate,
            f.version_name,
            f.usercount,
            f.month1,
            f. month2,
            f.month3,
            f.month4,
            f.month5,
            f.month6,
            f.month7,
            f.month8
            from " . $dwdb -> dbprefix('sum_reserveusers_monthly') . "   f,
                " . $dwdb -> dbprefix('dim_date') . "    d1,
                " . $dwdb -> dbprefix('dim_date') . "     d2
                where  f.startdate_sk = d1.date_sk 
                and f.enddate_sk = d2.date_sk 
                and d1.datevalue >= '$from'
                and d2.datevalue <= '$to'
                and f.product_id = $productId
                and f.version_name = '$version'
                and f.channel_name = '$channel'
                order by d1.datevalue desc;";

        log_message('debug', 'getUserRemainCountByMonth() SQL: ' . $sql);
        $query = $dwdb -> query($sql);

        return $query;
    }

}
