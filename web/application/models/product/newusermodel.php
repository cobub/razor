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
 * NewUser Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class newusermodel extends CI_Model
{
    /**
    * Construct funciton, to pre-load models configuration
    *
    * @return void
    */
    function __construct()
    {
        $this->load->model("common");
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/usinganalyzemodel', 'usinganalyzemodel');
    }

    /**
     * GetSumUsersByDay function
     * According to the time period, the application ID 
     * to get the total number of users
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *
     * @return void
     */
    function getSumUsersByDay($fromTime,$toTime,$productId)
    {
    
    }

    /**
     * GetAlldataofVisittrends function
     * get all data access trend
     *
     *@param string $fromtime from time
     *@param string $totime   to time
     *@param int    $userId   user id
     *
     * @return query ret
     */
    function getAlldataofVisittrends($fromtime,$totime,$userId)
    {
        $dbdb = $this->load->database('default', true);
        $dwdb = $this->load->database('dw', true);
        //get user role 3 is admin,
        $sqlrole = "
                    select 
                        * 
                    from 
                        " . $dbdb->dbprefix('user2role') . " 
                    where userid=$userId";

        $roleid = 1;
        $query_role = $dbdb->query($sqlrole);
        if ($query_role != null && $query_role->num_rows() > 0) {
            $row = $query_role->first_row();
            $roleid = $row->roleid;
        }
        $sql = " ";
        if ($roleid == 3) {
            $sql = "
                select 
                    date(datevalue) date,
                    ifnull(newusers,0) newusers,
                    ifnull(startusers,0) startusers,
                    ifnull(sessions,0) sessions 
                from 
                    (select 
                        dd.datevalue 
                    from 
                        " . $dwdb->dbprefix('dim_date') . " dd
                    where 
                        dd.datevalue between '$fromtime' and '$totime') ds 
                        left join 
                            (select 
                                ff.datevalue date,
                                ifnull(sum(newusers),0) newusers,
                                ifnull(sum(startusers),0) startusers,
                                ifnull(sum(sessions),0) sessions
                            from
                                (select 
                                    d.datevalue, 
                                    p.product_id,
                                    p.newusers,
                                    p.startusers,
                                    p.sessions
                                from 
                                    " . $dwdb->dbprefix('dim_date') . " d ,
                                    " . $dwdb->dbprefix('sum_basic_product') . "  p,
                                    " . $dbdb->database . "." . $dbdb->dbprefix('product') . " pinf 
                                where 
                                    d.datevalue between '$fromtime'and '$totime' and 
                                    p.date_sk=d.date_sk and 
                                    p.product_id=pinf.id and 
                                    pinf.active = 1 
                                    group by p.product_id,d.datevalue) ff
                                group by ff.datevalue) fff on fff.date=ds.datevalue order by ds.datevalue
                        ";
        } else {
            $sql = "
                select 
                    date(datevalue) date,
                    ifnull(newusers,0) newusers,
                    ifnull(startusers,0) startusers,
                    ifnull(sessions,0) sessions 
                from 
                    (select 
                        dd.datevalue 
                    from 
                        " . $dwdb->dbprefix('dim_date') . " dd
                    where 
                        dd.datevalue between '$fromtime' and '$totime') ds 
                        left join 
                            (select 
                                ff.datevalue date,
                                ifnull(sum(newusers),0) newusers,
                                ifnull(sum(startusers),0) startusers,
                                ifnull(sum(sessions),0) sessions
                            from
                                (select 
                                    d.datevalue, 
                                    p.product_id,
                                    p.newusers,
                                    p.startusers,
                                    p.sessions
                                from 
                                    " . $dwdb->dbprefix('dim_date') . " d ,
                                    " . $dwdb->dbprefix('sum_basic_product') . "  p,
                                    " . $dbdb->database . "." . $dbdb->dbprefix('user2product') . " up ,
                                    " . $dbdb->database . "." . $dbdb->dbprefix('product') . " pinf 
                                where 
                                    d.datevalue between '$fromtime'and '$totime' and 
                                    p.date_sk=d.date_sk and 
                                    up.product_id=p.product_id and 
                                    pinf.id=up.product_id and 
                                    pinf.id=p.product_id and 
                                    pinf.active = 1 and 
                                    up.user_id=$userId 
                                    group by p.product_id,d.datevalue) ff
                                group by ff.datevalue) fff on fff.date=ds.datevalue order by ds.datevalue
                    ";
        }

        $query = $dwdb->query($sql);
        $ret = array();
        if ($query != null && $query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                $record = array();
                $record["datevalue"] = $row->date;
                $record["newusers"] = $row->newusers;
                $record["startusers"] = $row->startusers;
                $record["sessions"] = $row->sessions;
                array_push($ret, $record);
            }
        }
        return $ret;
    }

    /**
     * GetActiveUsersByDay function
     * According to the time period, the application ID 
     * to get the total number of active users
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *@param int    $pageIndex index page
     *@param int    $pageNums  page nums
     *@param string $order     order
     *
     * @return query $query
     */
    function getActiveUsersByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order="ASC")
    {
        $from = ($pageIndex*$pageNums);
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sum(startusers),0) totalaccess 
            from   
                ".$dwdb->dbprefix('sum_basic_all')."  s 
            inner join   
                ".$dwdb->dbprefix('dim_product')."   p 
            on  
                p.product_id = $productId and 
                p.product_sk = s.product_sk and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
                right join (
                select 
                    date_sk, datevalue 
                from    
                    ".$dwdb->dbprefix('dim_date')." 
                where 
                    datevalue between '$fromTime' and '$toTime' 
                order by date_sk $order) d 
                on s.date_sk = d.date_sk 
            group by d.datevalue 
            limit $from,$pageNums;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetTotalUsersByDay function
     * According to the time period, the application ID 
     * to obtain the cumulative total number of users
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *@param int    $pageIndex index page
     *@param int    $pageNums  page nums
     *@param string $order     order
     *
     *@return query ret
     */
    function getTotalUsersByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order="ASC")
    {
        $from = ($pageIndex*$pageNums);
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sum(allusers),0) totalaccess
            from    
                ".$dwdb->dbprefix('sum_basic_all')."  s 
            inner join    
                ".$dwdb->dbprefix('dim_product')."  p 
            on 
                p.product_id = $productId and 
                p.product_sk = s.product_sk and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
                right join 
                (
		            select 
		                date_sk,
		                datevalue 
                    from   
                        ".$dwdb->dbprefix('dim_date')."
                    where 
                        datevalue between '$fromTime' and '$toTime' 
                order by date_sk $order) d 
                on s.date_sk = d.date_sk 
                group by d.datevalue 
            limit $from,$pageNums;";
        $query = $dwdb->query($sql);
        $ret = array();
        if ($query!=null && $query->num_rows()>0) {
            $preTotal = 0;
            foreach ($query->result() as $row) {
                $record = array();
                $record["datevalue"] = $row->datevalue;
                if ($row->totalaccess == null || $row->totalaccess == 'null') {
                    $record["totalaccess"] = $preTotal;
                } else {
                    $preTotal = $row->totalaccess;
                    $record["totalaccess"] = $preTotal;
                }
                array_push($ret, $record);
            }
        }
        return $ret;
    }

    /**
     * GetActiveUsersByUserID function
     * According to the time period, the user ID for the number of active users
     *
     *@param string $fromTime from time
     *@param string $toTime   to time
     *@param int    $userId   user id
     *
     *@return query query
     */
    function getActiveUsersByUserID($fromTime,$toTime,$userId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                dd.startdate,
                ifnull(totalaccess,0) totalusers 
            from 
                (
                select distinct 
                    startdate
                from   
                    ".$dwdb->dbprefix('dim_date')."   
                where startdate between '$fromTime' and '$toTime') dd 
            left join
                (
            select 
                d.startdate,
                count(distinct(deviceidentifier)) totalaccess 
            from    
                ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,    
                ".$dwdb->dbprefix('dim_date')."  d,    
                ".$dwdb->dbprefix('dim_product')."  p
            where 
                d.startdate between '$fromTime' and '$toTime' and 
                d.date_sk=f.date_sk and p.product_sk=f.product_sk and 
                p.product_id in 
                (
                select 
                    product_id 
                from    
                    ".$dwdb->dbprefix('dim_product')." 
                where 
                    product_userid = $userId
                ) 
            group by 
                d.startdate 
            order by 
                d.startdate) ddd 
            on 
            ddd.startdate = dd.startdate;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetActiveUsersByDayAndChinnel function
     * According to the time period, the application ID, channel ID 
     * to get the total number of active users
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *@param int    $channelId channel id
     *
     *@return query query
     */
    function getActiveUsersByDayAndChinnel($fromTime,$toTime,$productId,$channelId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                dd.startdate,
                ifnull(totalaccess,0) as totalusers 
            from 
            (
                select distinct 
                    startdate 
                from    
                    ".$dwdb->dbprefix('dim_date')."  
                where 
                    startdate between '$fromTime' and '$toTime') dd 
                left join
                (
                select 
                    d.startdate,
                    count(distinct(deviceidentifier)) totalaccess 
                from    
                    ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,   
                    ".$dwdb->dbprefix('dim_date')."  d,    
                    ".$dwdb->dbprefix('dim_product')."  p
                where 
                    d.startdate between '$fromTime' and '$toTime' and 
                    d.date_sk=f.date_sk and p.product_sk=f.product_sk and 
                    p.product_id=$productId and 
                    p.channel_id=$channelId 
                group by 
                    d.startdate 
                order by 
                    d.startdate) ddd 
                on 
                    ddd.startdate = dd.startdate;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetTotalStartUserByDay function
     * According to the time period, the product ID 
     * for a period of time new users start times accurate to daily
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *@param int    $pageIndex index page
     *@param int    $pageNums  page nums
     *@param string $order     order
     *
     *@return query query
     */
    function getTotalStartUserByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order='ASC')
    {
        $from = $pageIndex*$pageNums;
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sum(sessions),0) totalaccess
            from   
                ".$dwdb->dbprefix('sum_basic_all')."   s 
            inner join    
                ".$dwdb->dbprefix('dim_product')."  p 
            on  
                p.product_id = $productId and 
                p.product_sk = s.product_sk and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
            right join 
            (
            select 
                date_sk, 
                datevalue 
            from   
                ".$dwdb->dbprefix('dim_date')."   
            where 
                datevalue between '$fromTime' and '$toTime' 
            order by 
                date_sk $order) d 
            on 
                s.date_sk = d.date_sk 
            group by 
                d.datevalue 
            limit 
                $from,$pageNums;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetTotalStartUserByUserId function
     *According to the time period, the user ID to obtain 
     *the number of the period of time the user starts accurate to daily
     *
     *@param string $fromTime from time
     *@param string $toTime   to time
     *@param int    $userId   user id
     *
     *@return query query
    */
    function getTotalStartUserByUserId($fromTime,$toTime,$userId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                dd.startdate,
                ifnull(totalaccess,0) totalusers 
            from 
            (
            select distinct 
                startdate
            from   
                ".$dwdb->dbprefix('dim_date')."  
            where 
                startdate between '$fromTime' and '$toTime') dd 
            left join
            (
            select 
                d.startdate,count(*) totalaccess 
            from    
                ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,  
                ".$dwdb->dbprefix('dim_date')."  d,   
                ".$dwdb->dbprefix('dim_product')."  p
            where 
                d.startdate between '$fromTime' and '$toTime' and 
                d.date_sk=f.date_sk and 
                p.product_sk=f.product_sk and 
                p.product_id 
                in 
                (
                select 
                    product_id 
                from  
                    ".$dwdb->dbprefix('dim_product')."   
                where 
                    product_userid = $userId
                ) 
                group by 
                    d.startdate 
            order by 
                d.startdate
            ) ddd 
            on 
                ddd.startdate = dd.startdate;";
        $query = $dwdb->query($sql);
           return $query;
    }

        /**
      *GetTotalStartUserByDayAndChannel function
      *According to the time period, the product ID, channel ID to obtain the 
      *number of the period of time the user starts and accurate to the daily
      *
      *@param string $fromTime  from time
      *@param string $toTime    to time
      *@param int    $productId product id
      *@param int    $channelId channel id
      *
      *@return query query
      */
    function getTotalStartUserByDayAndChannel($fromTime,$toTime,$productId,$channelId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                dd.startdate,
                ifnull(totalaccess,0) as totalusers 
            from 
            (
            select distinct 
                startdate 
            from   
                ".$dwdb->dbprefix('dim_date')."  
            where 
                startdate between '$fromTime' and '$toTime') dd 
            left join
            (
            select 
                d.startdate,count(*) totalaccess 
            from    
                ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,  
                ".$dwdb->dbprefix('dim_date')."  d,   
                ".$dwdb->dbprefix('dim_product')."  p
            where 
                d.startdate between '$fromTime' and '$toTime' and 
                d.date_sk=f.date_sk and p.product_sk=f.product_sk and 
                p.product_id=$productId and 
                p.channel_id=$channelId 
            group by 
                d.startdate 
            order by 
                d.startdate
            ) ddd 
            on 
                    ddd.startdate = dd.startdate;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetNewUserByDay function
     *Get time period the number of new users according to 
     *the time period, the product ID, accurate to daily
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *@param int    $pageIndex index page
     *@param int    $pageNums  page nums
     *@param string $order     order
     *
     *@return query query
     */
    function getNewUserByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order='ASC')
    {
        $from = $pageIndex*$pageNums;
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sum(newusers),0) totalaccess 
            from   
                ".$dwdb->dbprefix('sum_basic_all')."  s 
            inner join   
                ".$dwdb->dbprefix('dim_product')."  p 
            on  
                p.product_id = $productId and 
                p.product_sk = s.product_sk and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
            right join 
            (
                select 
                    date_sk, 
                    datevalue 
                from   
                    ".$dwdb->dbprefix('dim_date')."   
                where 
                    datevalue between '$fromTime' and '$toTime' 
                order by 
                    date_sk $order
            ) d 
            on 
                s.date_sk = d.date_sk 
            group by 
                d.datevalue 
            limit 
                $from, $pageNums;";
        log_message('error', $sql);
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetNewUsersByUserId function
     *Get period the number of new users in the user according to 
     *the time period, the user ID, accurate to daily
     *
     *@param string $fromTime from time
     *@param string $toTime   to time
     *@param int    $userId   user idr
     *
     *@return query query
     */
    function getNewUsersByUserId($fromTime,$toTime,$userId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.startdate, 
                ifnull(sum(h.newusers),0) totalusers
            from 
            (
            select distinct 
                startdate 
            from    
                ".$dwdb->dbprefix('dim_date')."  
            where 
            startdate between '$fromTime' and '$toTime'
            ) d 
            left join 
                ".$dwdb->dbprefix('history_newusers_day_hour')." h
            on 
                h.newdate = d.startdate and 
                h.product_id 
                    in 
                    (
                    select 
                        product_id 
                    from   
                        ".$dwdb->dbprefix('dim_product')."  
                    where 
                        product_userid = $userId
                    ) 
                    group by 
                        d.startdate;";
        log_message('error', $sql);
        $query = $dwdb->query($sql);
        return $query; 
    }

    /**
     *GetNewUserByDayAndChannelId function
     *According to the time period, the product ID, channel ID to obtain 
     *a period of time the number of new users, accurate to daily
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId user id
     *@param int    $channelId channel id
     *
     *@return query query
     */
    function getNewUserByDayAndChannelId($fromTime,$toTime,$productId,$channelId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.startdate, 
                ifnull(sum(h.newusers),0) totalusers 
            from 
            (
            select distinct 
                startdate 
            from    
                ".$dwdb->dbprefix('dim_date')."  
            where 
                startdate between '$fromTime' and '$toTime'
            ) d 
            left join   
                ".$dwdb->dbprefix('history_newusers_day_hour')."   h
            on 
                h.newdate = d.startdate and 
                h.product_id=$productId and 
                h.channel_id=$channelId 
            group by 
                d.startdate;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetNewUserHistoryBy24Hour function
     *Get 24-hour segments new user statistics based on 
     *the time period and the product ID
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId user id
     *
     *@return query query
    */
    function getNewUserHistoryBy24Hour($fromTime,$toTime,$productId)
    {
        $dwdb = $this->load->database('dw', true);   
        $sql = "
            select 
                h.hour,
                ifnull(sum(newusers),0) count 
            from   
                ".$dwdb->dbprefix('dim_date')."   d 
            inner join 
                ".$dwdb->dbprefix('sum_basic_byhour')." s 
            on 
                d.datevalue between '$fromTime' and '$toTime' and 
                d.date_sk = s.date_sk 
            inner join 
                ".$dwdb->dbprefix('dim_product')." p 
            on 
                p.product_id = $productId and 
                p.product_sk = s.product_sk and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
            right join 
                ".$dwdb->dbprefix('hour24')." h 
            on 
                h.hour=s.hour_sk 
            group by 
                h.hour 
            order by 
                h.hour;";
        $query = $dwdb->query($sql);
           return $query;
    }

    /**
     *GetNewUserHistoryBy24HourAndChannel function
     *Get 24-hour segments new user statistics based on
     *the time period and the product ID
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId user id
     *@param int    $channelId channel id
     *
     *@return query query
    */
    function getNewUserHistoryBy24HourAndChannel($fromTime,$toTime,$productId,$channelId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                hour24.hour,
                ifnull(sum(newusers),0) totalusers 
            from   
                ".$dwdb->dbprefix('history_newusers_day_hour')."    h 
            right join
                ".$dwdb->dbprefix('hour24')." 
            on   
                ".$dwdb->dbprefix('hour24').".hour=h.hour and 
                h.newdate between '$fromTime' and '$toTime' and 
                h.product_id=$productId and 
                h.channel_id= $channelId
            group  by   
                ".$dwdb->dbprefix('hour24').".hour 
            order by   
                    ".$dwdb->dbprefix('hour24').".hour;";
            $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetNewUserHistoryBy24HourAndChannel function
     *get report Detail data
     *
     *@param string $fromTime from time
     *@param string $toTime   to time
     *
     *@return query query
     */
    function getallUserData($fromTime,$toTime)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sessions,0) sessions,
                ifnull(startusers,0) startusers,
                ifnull(newusers,0) newusers,
                ifnull(usingtime,0) usingtime,
            (
            select 
                ifnull(max(allusers),0) 
            from 
                ".$dwdb->dbprefix('dim_date')." dd,
                ".$dwdb->dbprefix('sum_basic_product')." pp 
            where 
                dd.datevalue=d.datevalue and 
                pp.date_sk<=dd.date_sk and 
                pp.product_id=$productId) allusers
            from  
                ".$dwdb->dbprefix('sum_basic_product')." s 
            inner join  
                ".$dwdb->dbprefix('dim_product')." p 
            on  
                p.product_id = $productId and 
                p.product_id = s.product_id and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
            right join 
            (
            select 
                date_sk, 
                datevalue 
            from 
                ".$dwdb->dbprefix('dim_date')." 
            where 
                datevalue between '$fromTime' and '$toTime'
            ) d 
            on 
                s.date_sk = d.date_sk 
            group by 
                d.datevalue";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetallUserDataBy function
     *get all user data
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productid producr id
     *
     *@return query query
     */
    function getallUserDataBy($fromTime,$toTime,$productid)
    {
        $productId= $productid;
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sum(sessions),0) sessions,
                ifnull(sum(startusers),0) startusers,
                ifnull(sum(newusers),0) newusers,
                ifnull(sum(usingtime),0) usingtime,
                ifnull(sum(allusers),0) allusers
            from   
                ".$dwdb->dbprefix('sum_basic_product')."  s 
            inner join  
                ".$dwdb->dbprefix('dim_product')."   p 
            on  
                p.product_id = $productId and 
                p.product_id = s.product_id and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
            right join 
            (
            select 
                date_sk, 
                datevalue 
            from   
                ".$dwdb->dbprefix('dim_date')." 
                where datevalue between '$fromTime' and '$toTime'
            ) d 
            on 
                s.date_sk = d.date_sk 
            group by 
                d.datevalue 
            order by 
                d.datevalue ASC;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     *GetallUserDataByPid function
     *get all user data by id
     *
     *@param string $fromTime  from time
     *@param string $toTime    to time
     *@param int    $productId product id
     *
     *@return query query
     */
    function getallUserDataByPid($fromTime,$toTime,$productId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql="
            select 
                d.datevalue,
                ifnull(sessions,0) sessions,
                ifnull(startusers,0) startusers,
                ifnull(newusers,0) newusers,
                ifnull(usingtime,0) usingtime,
            (
            select 
                ifnull(max(allusers),0) 
            from
                ".$dwdb->dbprefix('dim_date')." dd,
                ".$dwdb->dbprefix('sum_basic_product')." pp
            where 
                dd.datevalue=d.datevalue and 
                pp.date_sk<=dd.date_sk and 
                pp.product_id=$productId) allusers
            from  
                ".$dwdb->dbprefix('sum_basic_product')." s
            inner join  
                ".$dwdb->dbprefix('dim_product')." p
            on  
                p.product_id = $productId and 
                p.product_id = s.product_id and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1
           right join 
           (
           select 
               date_sk, 
               datevalue 
           from
               ".$dwdb->dbprefix('dim_date')." 
           where
               datevalue between '$fromTime' and '$toTime') d
           on 
               s.date_sk = d.date_sk
           group by 
               d.datevalue"; 
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetDetailUserData function
     * get detailed data
     *
     * @param string $fromTime from time
     * @param string $toTime   to time
     *
     * @return query query
     */
    function getDetailUserData($fromTime,$toTime)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        $sql = "
            select 
                d.datevalue,
                ifnull(sessions,0) sessions,
                ifnull(startusers,0) startusers,
                ifnull(newusers,0) newusers,
                ifnull(usingtime,0) usingtime,
            (
            select 
                ifnull(max(allusers),0) 
            from 
                ".$dwdb->dbprefix('dim_date')." dd,
                ".$dwdb->dbprefix('sum_basic_product')." pp 
            where 
                dd.datevalue=d.datevalue and 
                pp.date_sk<=dd.date_sk and 
                pp.product_id=$productId) allusers 
            from  
                ".$dwdb->dbprefix('sum_basic_product')." s 
            inner join  
                ".$dwdb->dbprefix('dim_product')." p 
            on 
                p.product_id = $productId and 
                p.product_id = s.product_id and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 
            right join 
            (
            select 
                date_sk, 
                datevalue 
            from 
                ".$dwdb->dbprefix('dim_date')." 
            where 
                datevalue between '$fromTime' and '$toTime') d 
            on 
                s.date_sk = d.date_sk 
            group by 
                d.datevalue 
            order by 
                d.datevalue DESC;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetDetailUserDataByDay function
     * get detailed data
     *
     * @param string $fromTime from time
     * @param string $toTime   to time
     *
     * @return query list
     */
    function getDetailUserDataByDay($fromTime,$toTime)
    {
        $list = array();
        $query = $this->getDetailUserData($fromTime, $toTime);
        $activeUserRow = $query->first_row();
        for ($i=0;$i<$query->num_rows();$i++) {
            $fRow = array();
            $fRow["date"] = substr($activeUserRow->datevalue, 0, 10);
            $fRow['newuser'] = $activeUserRow->newusers;
            $fRow['total'] = $activeUserRow->allusers;
            $fRow['active'] = $activeUserRow->startusers;
            $fRow['start'] = $activeUserRow->sessions;
            $fRow['aver'] = $activeUserRow->usingtime; 
            $activeUserRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }
}
?>