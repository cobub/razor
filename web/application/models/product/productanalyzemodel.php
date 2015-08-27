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
 * Productanalyzemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Productanalyzemodel extends CI_Model
{



    /** 
     * Construct load
     * COnstruct function
     * 
     * @return void
     */
    function __construct()
    {
        $this -> load -> database();
    }
    
    /** 
     * Get all analyze data
     * GetAllAnalyzeData function
     * 
     * @param string $date       date
     * @param string $product_id productid
     * 
     * @return array
     */
    function getAllAnalyzeData($date, $product_id)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select ppp.channel_id,ppp.channel_name,
        ifnull(allusers,0) allusers,
        ifnull(newusers,0) newusers,
        ifnull(startusers,0) startusers
        from (select channel_id,channel_name
        from " . $dwdb -> dbprefix('dim_product') . "
        where product_id=$product_id
         and channel_active=1
         group by channel_id ) ppp 
        left join (select channel_id,max(allusers) allusers
         from " . $dwdb -> dbprefix('sum_basic_channel') . "  bp,
         " . $dwdb -> dbprefix('dim_date') . " dd 
         where dd.datevalue='$date' and 
         bp.date_sk<=dd.date_sk and bp.product_id=$product_id
         group by channel_id) dpp 
         on dpp.channel_id=ppp.channel_id 
         left join 
         (select  pp.channel_id,newusers,
         startusers
        from " . $dwdb -> dbprefix('dim_product') . " p,
        " . $dwdb -> dbprefix('sum_basic_channel') . " pp,
        " . $dwdb -> dbprefix('dim_date') . "  d
        where d.datevalue='$date' and 
        d.date_sk=pp.date_sk and p.product_id=$product_id
        and product_active=1 and channel_active=1
         and version_active=1 
         and p.product_id=pp.product_id
          group by pp.channel_id) ff
          on ff.channel_id=ppp.channel_id 
          group by ppp.channel_id";
        $query = $dwdb -> query($sql);
        return $query;
    }
    
    /** 
     * Today data
     * GetTodayInfo function
     * 
     * @param string $productId productid
     * @param string $date      date
     * 
     * @return array
     */
    function getTodayInfo($productId, $date)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select ifnull(sessions,0) sessions,
		        ifnull(startusers,0) startusers, 
				ifnull(newusers,0) newusers, 
				ifnull(upgradeusers,0) upgradeusers, 
				ifnull(usingtime,0) usingtime, 
				ifnull(allusers,0) allusers,
				ifnull(allsessions,0) allsessions 
				from (select date_sk 
				from " . $dwdb -> dbprefix('dim_date') . "
				where datevalue='$date')  d 
				left join (select max(dd.date_sk) date_sk,
				max(allusers) allusers,
				max(allsessions) allsessions 
				from " . $dwdb -> dbprefix('sum_basic_product') . " bp,
				" . $dwdb -> dbprefix('dim_date') . " dd 
				where dd.datevalue='$date' 
				and bp.product_id=$productId and bp.date_sk<=dd.date_sk) dpp 
				on dpp.date_sk=d.date_sk left join
				 (select p.date_sk,sessions,startusers,
				 newusers,upgradeusers,usingtime 
				from " . $dwdb -> dbprefix('sum_basic_product') . " p,
				" . $dwdb -> dbprefix('dim_date') . " dd
				where dd.date_sk=p.date_sk and 
				dd.datevalue='$date' 
		and p.product_id=$productId) pp
		 on d.date_sk=pp.date_sk";
        $query = $dwdb -> query($sql);

        if ($query != null && $query -> num_rows() > 0) {
            $query = $query -> first_row();
        }
        return $query;
    }
    
    /** 
     * Get yestoday user
     * GetYestodayUpdateUser function
     * 
     * @param string $productId productId
     * @param string $date      date
     * 
     * @return int
     */
    function getYestodayUpdateUser($productId, $date)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "Select count(distinct f.deviceidentifier) as usercount from   " . $dwdb -> dbprefix('fact_clientdata') . "  f, 
		  " . $dwdb -> dbprefix('dim_product') . "  p,  " . $dwdb -> dbprefix('dim_date') . "   d where f.product_sk=p.product_sk and f.date_sk = d.date_sk and
		 p.product_id=$productId and d.datevalue='$date';";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * General overview
     * GetOverallInfo function
     * 
     * @param string $productId productid
     * 
     * @return query
     */
    function getOverallInfo($productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select * from " . $dwdb -> dbprefix('sum_basic_activeusers') . "  where product_id=$productId ";
        $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            $query = $query -> first_row();
        }
        return $query;
    }
    
    /** 
     * Time to obtain the cumulative number of starts
     * GetTotalStartCount function
     * 
     * @param string $productId productid
     * 
     * @return int
     */
    function getTotalStartCount($productId)
    {
        $sql = "select count(*) count from   " . $this -> db -> dbprefix('clientdata') . "  where productkey
		in (select productkey from   " . $this -> db -> dbprefix('channel_product') . "  where product_id = $productId);";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * Time to obtain the cumulative user
     * GetTotalUsers function
     * 
     * @param string $productId productid
     * 
     * @return int
     */
    function getTotalUsers($productId)
    {
        $sql = "select count(distinct deviceid) count from   " . $this -> db -> dbprefix('clientdata') . "  where productkey
		in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " where product_id = $productId);";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * View all products of this user new users
     * GetTotalNewUsersCountByUserId function
     * 
     * @param string $userId   userid
     * @param string $dateTime datetime
     * 
     * @return int
     */
    function getTotalNewUsersCountByUserId($userId, $dateTime)
    {
        $sql = "select count(distinct deviceid) count from  " . $this -> db -> dbprefix('clientdata') . " where
		  date(date) = '$dateTime' and productkey 
	in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " where user_id = $userId) 
	and deviceid not in (select distinct deviceid from  " . $this -> db -> dbprefix('clientdata') . "
		 where date < '$dateTime' and productkey in (select productkey from  
		" . $this -> db -> dbprefix('channel_product') . " where user_id = $userId))";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * View the number of users of the start of the user
     * GetStartUserCountByUserId function
     * 
     * @param string $userId   userid
     * @param string $dateTime datetime
     * 
     * @return int
     */
    function getStartUserCountByUserId($userId, $dateTime)
    {
        $sql = "select count(distinct deviceid) count from 
		 " . $this -> db -> dbprefix('clientdata') . " where productkey in
		 (select productkey from  " . $this -> db -> dbprefix('channel_product') . " 
		where user_id = $userId) and date(date) = '$dateTime' ";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
     
    /** 
     * Get today number of starts according to the time
     * GetUserStartCount function
     * 
     * @param string $productId productId
     * @param string $dataTime  dataTime
     * 
     * @return int
     */
    function getUserStartCount($productId, $dataTime)
    {
        $sql = "select count(*) count from  " . $this -> db -> dbprefix('clientdata') . " 
		where  date(date) = '$dataTime' and productkey 
		 in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " 
		where product_id = $productId);";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * Get today starts the user active users
     * GetUserStartUsersCount function
     * 
     * @param string $productId productId 
     * @param string $dataTime  dataTime 
     * 
     * @return int
     */
    function getUserStartUsersCount($productId, $dataTime)
    {
        $sql = "select count(distinct deviceid) count from  " . $this -> db -> dbprefix('clientdata') . " 
		where  date(date) = '$dataTime' and productkey
		in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " 
		where product_id = $productId);";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * Get today start users, active users 
     * GetUserStartUsersCountByChannel function
     * 
     * @param string $productId productId
     * @param string $chanelId  chanelId
     * @param string $dataTime  dataTime
     * 
     * @return int
     */
    function getUserStartUsersCountByChannel($productId, $chanelId, $dataTime)
    {
        $sql = "select count(distinct deviceid) count from 
		 " . $this -> db -> dbprefix('clientdata') . " where  date(date) = '$dataTime' and productkey
		in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " 
		where product_id = $productId and channel_id = $chanelId);";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;

    }
    
    /** 
     * Get today new user
     * GetNewUsersCount function
     * 
     * @param string $productId productId 
     * @param string $dataTime  dataTime 
     * 
     * @return int
     */
    function getNewUsersCount($productId, $dataTime)
    {
        $sql = "select count(distinct deviceid) count from  " . $this -> db -> dbprefix('clientdata') . " where
		  date(date) = '$dataTime' and productkey 
	in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " where product_id = $productId) 
	and deviceid not in (select distinct deviceid from  " . $this -> db -> dbprefix('clientdata') . "
		 where date < '$dataTime' and productkey in (select productkey from 
		 " . $this -> db -> dbprefix('channel_product') . " where product_id = $productId))";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * According to the channels to obtain new user
     * GetNewUsersCountByChannel function
     * 
     * @param string $productId productId
     * @param string $chanelId  chanelId
     * @param string $dataTime  dataTime
     * 
     * @return int
     */
    function getNewUsersCountByChannel($productId, $chanelId, $dataTime)
    {
        $sql = "select count(distinct deviceid) count from  " . $this -> db -> dbprefix('clientdata') . " where
		  date(date) = '$dataTime' and productkey 
	in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " where product_id = $productId and channel_id = $chanelId) 
	and deviceid not in (select distinct deviceid from " . $this -> db -> dbprefix('clientdata') . " 
		 where date < '$dataTime' and productkey in (select productkey from  " . $this -> db -> dbprefix('channel_product') . " where product_id = $productId and channel_id = $chanelId))";
        // echo $sql."<br>"."<br>";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> count;
    }
    
    /** 
     * According  the number of new
     * GetYestodayNewUserCountByChannel function
     * 
     * @param string $dateTime  dateTime  
     * @param string $productId productId 
     * @param string $chanelId  chanelId
     * 
     * @return int
     */
    function getYestodayNewUserCountByChannel($dateTime, $productId, $chanelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from  " . $dwdb -> dbprefix('fact_clientdata') . "
		f, " . $dwdb -> dbprefix('dim_date') . "  d,  " . $dwdb -> dbprefix('dim_product') . " p 
		where f.date_sk=d.date_sk and f.product_sk=p.product_sk and
		d.year=year('$dateTime') and d.month=month('$dateTime') 
		and d.day=day('$dateTime') and p.product_id=$productId 
		and channel_id = $chanelId;";
        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * Get the number of new users yesterday according to time
     * GetYestodayNewUserCount function
     * 
     * @param string $dateTime  dateTime 
     * @param string $productId productId 
     * 
     * @return int
     */
    function getYestodayNewUserCount($dateTime, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from  " . $dwdb -> dbprefix('fact_clientdata') . "
		f,  " . $dwdb -> dbprefix('dim_date') . " d,  " . $dwdb -> dbprefix('dim_product') . " p 
		where f.date_sk=d.date_sk and f.product_sk=p.product_sk and
		d.year=year('$dateTime') and d.month=month('$dateTime') 
		and d.day=day('$dateTime') and p.product_id=$productId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * Get the number 
     * GetYestodayStartCountByChannelId function
     * 
     * @param string $dateTime  dateTime 
     * @param string $productId productId 
     * @param string $channelId channelId 
     * 
     * @return string
     */
    function getYestodayStartCountByChannelId($dateTime, $productId, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(*) starttimes from  " . $dwdb -> dbprefix('fact_clientdata') . " f,  " . $dwdb -> dbprefix('dim_date') . " d,  " . $dwdb -> dbprefix('dim_product') . "
		 p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and d.year=year('$dateTime') and
		 d.month=month('$dateTime') and d.day=day('$dateTime') and p.product_id=$productId and p.channel_id=$channelId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> starttimes;
    }
    
    /** 
     * According get the number of starts 
     * GetYestodayStartCount function
     * 
     * @param string $dateTime  dateTime 
     * @param string $productId productId 
     * 
     * @return string
     */
    function getYestodayStartCount($dateTime, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(*) starttimes from  " . $dwdb -> dbprefix('fact_clientdata') . " f, " . $dwdb -> dbprefix('dim_date') . "  d,  " . $dwdb -> dbprefix('dim_product') . "
		p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and d.year=year('$dateTime') and
		d.month=month('$dateTime') and d.day=day('$dateTime') and p.product_id=$productId;";
        $query = $dwdb -> query($sql);
        return $query -> first_row() -> starttimes;
    }

    /** 
     * Get yesterday the number
     * GetActiveUserCountByChannelId function 
     * 
     * @param string $dateTime  datetime 
     * @param string $productId productId 
     * @param string $channelId channelId
     * 
     * @return int
     */
    function getActiveUserCountByChannelId($dateTime, $productId, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from  " . $dwdb -> dbprefix('fact_clientdata') . "
		 f, " . $dwdb -> dbprefix('dim_date') . " d, " . $dwdb -> dbprefix('dim_product') . " p where f.date_sk=d.date_sk and
		 f.product_sk=p.product_sk and d.year=year('$dateTime') and d.month=month('$dateTime') and d.day=day('$dateTime') and
		 p.product_id=$productId and p.channel_id=$channelId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * Get active users 
     * GetActiveUserCount function
     * 
     * @param string $dateTime  dateTime 
     * @param string $productId productId 
     * 
     * @return int
     */
    function getActiveUserCount($dateTime, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from  " . $dwdb -> dbprefix('fact_clientdata') . " 
		f,   " . $dwdb -> dbprefix('dim_date') . "  d,   " . $dwdb -> dbprefix('dim_product') . "  p where f.date_sk=d.date_sk and
		f.product_sk=p.product_sk and d.year=year('$dateTime') and d.month=month('$dateTime') and d.day=day('$dateTime') and
		p.product_id=$productId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * Depending on the product, channel ID to obtain the total number of users
     * GetTotalUserByChannel function
     * 
     * @param string $productId productid 
     * @param string $channelId channelId 
     * 
     * @return int
     */
    function getTotalUserByChannel($productId, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from 
		  " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "  f,   " . $dwdb -> dbprefix('dim_product') . "  p 
		where f.product_sk=p.product_sk and p.product_id=$productId and p.channel_id=$channelId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * According to the product to obtain the total number of users
     * GetTotalUserByProductId function
     * 
     * @param string $productId productId
     * 
     * @return int
     */
    function getTotalUserByProductId($productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from
		  " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "  f,   " . $dwdb -> dbprefix('dim_product') . "  p
		 where f.product_sk=p.product_sk and p.product_id=$productId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * Depending cumulative number of 
     * GetTotalStartUserCountByChannel function
     * 
     * @param string $productId productId 
     * @param string $channelId channelId 
     * 
     * @return int
     */
    function getTotalStartUserCountByChannel($productId, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(1) usercount from   " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "  f, 
		  " . $dwdb -> dbprefix('dim_product') . "  p 
		where f.product_sk=p.product_sk 
		and p.product_id=$productId and p.channel_id=$channelId;";
        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * Depending on the product  
     * GetTotalStartUserCountByProductId function
     * 
     * @param string $productId productId
     * 
     * @return int
     */
    function getTotalStartUserCountByProductId($productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(1) usercount from   " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "  f,  " . $dwdb -> dbprefix('dim_product') . "   p where f.product_sk=p.product_sk and p.product_id=$productId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * According to the time period 
     * GetActiveUserByPeriodAndChannel function
     * 
     * @param string $fromTime  fromtime
     * @param string $toTime    totime
     * @param string $productId productid
     * @param string $channelId channelid
     * 
     * @return int
     */
    function getActiveUserByPeriodAndChannel($fromTime, $toTime, $productId, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select count(distinct f.deviceidentifier) usercount from   " . $dwdb -> dbprefix('fact_clientdata') . "  f, 
	  " . $dwdb -> dbprefix('dim_date') . " 	 d,   " . $dwdb -> dbprefix('dim_product') . "  p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and d.datevalue
		 between '$fromTime' and '$toTime' and p.product_id = $productId and p.channel_id = $channelId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> usercount;
    }
    
    /** 
     * According to the time 
     * GetActiveUserByPeriod function
     * 
     * @param string $type      type
     * @param string $productId productid
     * 
     * @return int
     */
    function getActiveUserByPeriod($type, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select * from 
		" . $dwdb -> dbprefix('sum_basic_activeusers') . "
		 where product_id=$productId ";
        $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            foreach ($query->result() as $row) {
                if ($type == "week") {
                    $activeuser = $row -> week_activeuser;
                    break;
                }
                if ($type == "month") {
                    $activeuser = $row -> month_activeuser;
                    break;
                }
            }
        } else {
            $activeuser = 0;
        }
        return $activeuser;
    }
    
    /** 
     * According the number 
     * GetActiveUserTillToday function
     * 
     * @param string $toTime    totime
     * @param string $productId productId
     * 
     * @return int
     */
    function getActiveUserTillToday($toTime, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "select ifnull(sum(startusers),0) activeusers 
		from (select date_sk from 
		" . $dwdb -> dbprefix('dim_date') . "
		 where datevalue<='$toTime') d 
		left join ( select startusers,date_sk 
		from " . $dwdb -> dbprefix('sum_basic_product') . "
		where product_id =$productId) p 
		on p.date_sk=d.date_sk ";
        $query = $dwdb -> query($sql);
        return $query -> first_row() -> activeusers;
    }
    
    /** 
     * Get average usingtime 
     * GetAverageUsingTimeByChannel function
     * 
     * @param string $dateTime  dateTime 
     * @param string $productID productid 
     * @param string $channelId channelid 
     * 
     * @return query
     */
    function getAverageUsingTimeByChannel($dateTime, $productID, $channelId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "Select sum(u.duration)/count(distinct session_id) from   
        " . $dwdb -> dbprefix('fact_usinglog') . " 
		 u,  " . $dwdb -> dbprefix('dim_product') . "   p,   " . $dwdb -> dbprefix('dim_date') . "  d where u.product_sk = p.product_sk
		 and u.date_sk=d.date_sk and d.datevalue='$dateTime' and p.product_id = $productID and p.channel_id = $channelId;";

        $query = $dwdb -> query($sql);
        return $query;
    }
     
    /** 
     * Return the product within the specified date average often
     * GetAverageUsingTimeByChannel function
     * 
     * @param string $dateTime  datetime
     * @param string $productId productId
     * 
     * @return string
     */
    function getAverageUsingTimeByProduct($dateTime, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "Select sum(f.duration)/count(f.session_id) as avertime from  " . $dwdb -> dbprefix('fact_usinglog_daily') . "   f,   " . $dwdb -> dbprefix('dim_product') . "  p,   " . $dwdb -> dbprefix('dim_date') . "  d 
		where f.date_sk=d.date_sk and f.product_sk = p.product_sk and
		 d.datevalue='$dateTime' and p.product_id=$productId;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> avertime;
    }
    
    /** 
     * Get real-time data on average use from time to time (today)
     * GetAverageUsingTimeByProductAtRealTime function
     * 
     * @param string $dateTime  datetime
     * @param string $productId productid
     * 
     * @return string
     */
    function getAverageUsingTimeByProductAtRealTime($dateTime, $productId)
    {
        $sql = "Select sum(u.duration)/count(distinct session_id) avertime from   " . $this -> db -> dbprefix('clientusinglog') . "  u
  join   " . $this -> db -> dbprefix('channel_product') . "   cp on u.appkey = cp.productkey
 where date(u.start_millis) = '$dateTime' and cp.product_id = $productId ";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> avertime;
    }
    
    /** 
     * Get real-time data on average use from time to time (today)
     * GetAverageUsingTimeByProductAndChannelAtRealTime function
     * 
     * @param string $dateTime  dateTime
     * @param string $channelId channelId
     * 
     * @return string
     */
    function getAverageUsingTimeByProductAndChannelAtRealTime($dateTime, $channelId)
    {
        $sql = "Select sum(u.duration)/count(distinct session_id) avertime from   " . $this -> db -> dbprefix('clientusinglog') . "  u
		join    " . $this -> db -> dbprefix('channel_product') . "  cp on u.appkey = cp.productkey
		where date(u.start_millis) = '$dateTime' and cp.cp_id = $channelId ";
        $query = $this -> db -> query($sql);
        return $query -> first_row() -> avertime;
    }
    
    /** 
     * Get the average usage of a specified time period long
     * GetAverageUsingTimeByPeriod function
     * 
     * @param string $fromTime  fromTime
     * @param string $toTime    toTime
     * @param string $productId productId
     * 
     * @return string
     */
    function getAverageUsingTimeByPeriod($fromTime, $toTime, $productId)
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "Select d.datevalue,sum(u.duration)/count(distinct session_id) avertime
		 from   " . $dwdb -> dbprefix('fact_usinglog') . "  u,   " . $dwdb -> dbprefix('dim_product') . "  p,   " . $dwdb -> dbprefix('dim_date') . "  d where u.product_sk = p.product_sk
		 and u.date_sk=d.date_sk and d.datevalue between '$fromTime' and '$toTime' and p.product_id = $productId 
		 group by d.datevalue order by d.datevalue;";

        $query = $dwdb -> query($sql);
        return $query -> first_row() -> avertime;
    }

}
?>