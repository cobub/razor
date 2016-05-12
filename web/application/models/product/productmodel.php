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
 * Pruduct Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class ProductModel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct() 
    {
        $this -> load -> database();
        $this -> load -> model("product/productanalyzemodel", 'productanalyzemodel');
    }

    /**
     * Get analyze data through date and priductId
     *
     * @param date $date       date
     * @param int  $product_id product
     * 
     * @return productanalyzemodel
     */
    function getAnalyzeDataByDateAndProductID($date, $product_id) 
    {
        return $this -> productanalyzemodel -> getAllAnalyzeData($date, $product_id);
    }

    /**
     * Get active user number
     *
     * @param date $startDate  date
     * @param date $endDate    date
     * @param int  $product_id productid
     *
     * @return query result
     */
    function getActiveUsersNum($startDate, $endDate, $product_id) 
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
            select 
                tt.channel_id, 
                ifnull(t.startusers,0) startusers 
            from ( select 
                       s.channel_id, 
                       sum(startusers)startusers
                   from " . $dwdb -> dbprefix('sum_basic_channel') . " s,
                        " . $dwdb -> dbprefix('dim_date') . " d  
                   where d.datevalue between '$startDate' and 
                         '$endDate'  and
                         d.date_sk = s.date_sk and
                         s.product_id = $product_id 
                         group by s.channel_id) t 
                   right join ( 
                   select 
                       distinct pp.channel_id 
                       from " . $dwdb -> dbprefix('dim_product') . " pp
             where pp.product_id =$product_id and 
                  pp.product_active=1 and 
                  pp.channel_active=1 and 
                  pp.version_active=1) tt 
                  on tt.channel_id = t.channel_id 
                  order by tt.channel_id";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * Get active days
     *
     * @param date    $startDate  date
     * @param boolean $flag       flag
     * @param int     $product_id productid
     *
     * @return query result
     */
    function getActiveDays($startDate, $flag, $product_id) 
    {
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
            select 
                tt.channel_id,
                ifnull(t.percent,0) percent 
            from 
               (select 
                    ca.channel_id,
                    percent 
                from 
                    " . $dwdb -> dbprefix('sum_basic_channel_activeusers') . " ca,
                    " . $dwdb -> dbprefix('dim_product') . " pp,
                    " . $dwdb -> dbprefix('dim_date') . " d 
                where
                    d.datevalue='$startDate' and
                    d.date_sk=ca.date_sk and
                    ca.flag=$flag and
                    pp.product_id= $product_id and
                    pp.product_active=1 and
                    pp.channel_active=1 and
                    pp.version_active=1 and
                    ca.product_id=pp.product_id group by ca.channel_id) t
                right join ( 
                select
                    distinct pp.channel_id 
                from " . $dwdb -> dbprefix('dim_product') . " pp 
                where pp.product_id =$product_id and 
                pp.product_active=1 and
                pp.channel_active=1 and
                pp.version_active=1) tt 
                on tt.channel_id = t.channel_id  
        order by tt.channel_id ";
        $query = $dwdb -> query($sql);
        return $query;
    }

    /**
     * Get active days
     *
     * @param int  $channel_id channelId
     * @param date $fromTime   startTime
     * @param date $toTime     endTime
     *
     * @return $ret
     */
    function getAllMarketData($channel_id, $fromTime, $toTime) 
    {
        $ret = array();
        $currentProduct = $this -> common -> getCurrentProduct();
        $productId = $currentProduct -> id;
        $dwdb = $this -> load -> database('dw', true);
        $channelname = $this -> getMarketNameById($channel_id);
        $sql = "
            select 
                d.datevalue,
                p.channel_id,
                p.channel_name,
                ifnull(startusers,0) startusers,
                ifnull(newusers,0) newusers,
                (select 
                     ifnull(max(allusers),0) 
                 from 
                     " . $dwdb -> dbprefix('sum_basic_channel') . " dp,
                     " . $dwdb -> dbprefix('dim_date') . " da
                 where 
                     da.datevalue=d.datevalue and
                     dp.date_sk<=da.date_sk and 
                     dp.channel_id=p.channel_id) allusers,
                     ifnull(sessions,0) sessions,
                     ifnull(usingtime,0) usingtime
            from
                (select 
                     date_sk,datevalue 
                 from 
                     " . $dwdb -> dbprefix('dim_date') . "  
                 where
                     datevalue between '$fromTime' and
                     '$toTime')  d 
                  cross join 
                 (select
                  pp.channel_id,
                  pp.channel_name 
                  from " . $dwdb -> dbprefix('dim_product') . " pp
                  where
                      pp.product_id = $productId and
                      pp.product_active=1 and
                      pp.channel_active=1 and 
                      pp.version_active=1 
                  group by pp.channel_id,pp.channel_name) p
            left join 
            (select
                 * 
             from 
                 " . $dwdb -> dbprefix('sum_basic_channel') . " 
             where 
                 product_id=$productId) s  on d.date_sk = s.date_sk and
                 s.channel_id = p.channel_id
            order by d.datevalue";                
        $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows > 0) {
            $arr = $query -> result_array();
            $content_arr = array();
            for ($i = 0; $i < count($arr); $i++) {
                $row = $arr[$i];
                $channel_name = $row['channel_name'];
                $allkey = array_keys($content_arr);
                if (!in_array($channel_name, $allkey))
                    $content_arr[$channel_name] = array();
                $tmp = array();
                $tmp['activeusers'] = $row['startusers'];
                $tmp['allusers'] = $row['allusers'];
                $tmp['newusers'] = $row['newusers'];
                $tmp['datevalue'] = $row['datevalue'];
                $tmp['sessions'] = $row['sessions'];
                $tmp['usingtime'] = $row['usingtime'];
                array_push($content_arr[$channel_name], $tmp);
            }
            $all_version_name = array_keys($content_arr);
            $ret['content'] = $content_arr;
        }
        return $ret;
    }

    /**
     * Get channel activerate
     *
     * @param int  $product_id productId
     * @param date $fromTime   startTime
     * @param date $toTime     endTime
     * @param int  $type       productType
     *
     * @return $ret
     */
    function getChannelActiverate($product_id, $fromTime, $toTime, $type)
    {
        $dwdb = $this -> load -> database('dw', true);
        if ($type == 0) {
            $fromTime = "DATE_SUB('" . $fromTime . "',INTERVAL 13 DAY)";
            $toTime = "DATE_SUB('" . $toTime . "',INTERVAL 7 DAY)";
            $sql = "
                select 
                    td.datevalue,
                    tc.channel_id,
                    tc.channel_name,
                    ifnull(tp.percent,0) percent
                from
                 (
                    select 
                        d.datevalue
                    from
                        " . $dwdb -> dbprefix('dim_date') . " d
                    where
                        d.datevalue between $fromTime and $toTime and
                        d.dayofweek=0 
                )td
                cross join
                (
                    select 
                        c.channel_id,  
                        c.channel_name
                    from
                        " . $dwdb -> dbprefix('dim_product') . " c
                    where
                        product_id=1 and 
                        product_active=1 and
                        channel_active=1 and
                        version_active=1
                    group by
                        c.channel_id  
                 )tc
                left join
                (
                    select 
                        p.date_sk,
                        p.percent,
                        d.datevalue
                    from 
                        " . $dwdb -> dbprefix('sum_basic_channel_activeusers') . " p,
                        " . $dwdb -> dbprefix('dim_date') . " d
                    where
                        d.datevalue between $fromTime and $toTime and
                        p.flag=0 and
                        d.date_sk=p.date_sk
                )tp
                on
                    tp.datevalue=td.datevalue
                order
                    by td.datevalue";
        } else {
            $fromTime = "DATE_SUB('" . $fromTime . "',INTERVAL 2 MONTH)";
            $fromTime = "DATE_SUB($fromTime,INTERVAL -1 DAY)";
            $toTime = "DATE_SUB('" . $toTime . "',INTERVAL 1 MONTH)";
            $sql = "
                select
                    td.datevalue,
                    tc.channel_id,
                    tc.channel_name,
                    ifnull(tp.percent,0) percent
                from
                (
                    select
                        d.datevalue
                    from
                        " . $dwdb -> dbprefix('dim_date') . " d
                    where
                        d.datevalue between $fromTime and $toTime and
                        d.day=1
                )td
               cross join
                (
                    select
                        c.channel_id,
                        c.channel_name
                    from
                        " . $dwdb -> dbprefix('dim_product') . " c
                    where
                        product_id=1 and
                        product_active=1 and
                        channel_active=1 and
                        version_active=1
                    group by 
                        c.channel_id
                )tc
               left join
                (
                    select
                        p.date_sk,
                        p.percent,
                        d.datevalue
                    from
                        " . $dwdb -> dbprefix('sum_basic_channel_activeusers') . " p,
                        " . $dwdb -> dbprefix('dim_date') . " d
                    where
                        d.datevalue between $fromTime and $toTime and
                        p.flag=1 and
                        d.date_sk=p.date_sk
                )tp
               on 
                   tp.datevalue=td.datevalue
               order by 
                   td.datevalue";
        } 
         $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            $ret = $query -> result_array();
        } else {
            $ret = null;
        }
        return $ret;
    }

    /**
     * Get active numbers
     *
     * @param int  $product_id productId
     * @param date $fromTime   startTime
     * @param date $toTime     endTime
     * @param int  $type       productType
     *
     * @return $ret
     */
    function getActiveNumbers($product_id, $fromTime, $toTime, $type) 
    {
        $ret = array();
        $getdata = $this -> getChannelActiverate(
            $product_id, $fromTime, $toTime, $type
        );
        if ($getdata != null) {
            $content_arr = array();
            foreach ($getdata as $row) {
                $channel_name = $row['channel_name'];
                $allkey = array_keys($content_arr);
                if (!in_array($channel_name, $allkey))
                    $content_arr[$channel_name] = array();
                $tmp = array();
                $tmp['percent'] = $row['percent'];
                $tmp['datevalue'] = $row['datevalue'];
                array_push($content_arr[$channel_name], $tmp);
            }
            $ret['content'] = $content_arr;
        }
        return $ret;
    }

    /**
     * Get active number
     *
     * @param int  $channel_id channelId
     * @param date $fromTime   startTime
     * @param date $toTime     endTime
     * @param int  $type       productType
     *
     * @return $ret
     */
    function getActiveNumber($channel_id, $fromTime, $toTime, $type) 
    {
        $ret = array();
        $currentProduct = $this -> common -> getCurrentProduct();
        $productId = $currentProduct -> id;
        $dwdb = $this -> load -> database('dw', true);
        $channelname = $this -> getMarketNameById($channel_id);
        if ($type == "weekrate") {
            $day = -6;
        }
        if ($type == "monthrate") {
            $day = -30;
        }
        $sql = "
            select 
                t.datevalue,
                t.channel_id,
                t.channel_name,
                ifnull(startusers,0) startusers,
                ifnull(allusers,0) allusers
            from 
                (select 
                     d.datevalue,
                     p.channel_id,
                     p.channel_name,
                     (select
                          ifnull(max(allusers),0) 
                      from 
                          " . $dwdb -> dbprefix('sum_basic_channel') . " ss,
                          " . $dwdb -> dbprefix('dim_date') . " dd 
                      where
                          ss.date_sk <= dd.date_sk and
                          dd.datevalue = d.datevalue and
                          ss.channel_id= p.channel_id and 
                          ss.product_id=$productId) allusers,
                      (select 
                           ifnull(sum(startusers),0) 
                       from 
                           " . $dwdb -> dbprefix('sum_basic_channel') . " ss,
                           " . $dwdb -> dbprefix('dim_date') . " dd 
                       where 
                           ss.date_sk = dd.date_sk and 
                           dd.datevalue between date_add(d.datevalue,interval $day day) and
                           d.datevalue and 
                           ss.channel_id= p.channel_id and 
                           ss.product_id=$productId) startusers
                           from  
                              (select date_sk,datevalue 
                 from 
                     " . $dwdb -> dbprefix('dim_date') . "
                 where
                     datevalue between '$fromTime' and '$toTime') d 
                cross join (select
                                distinct    pp.channel_id,
                                pp.channel_name,pp.product_id    
                            from 
                                " . $dwdb -> dbprefix('dim_product') . " pp
                            where
                                pp.product_id = $productId and
                                pp.product_active=1 and
                                pp.channel_active=1 and pp.version_active=1 ) p ) t
            group by t.datevalue,t.channel_id,t.channel_name
            order by t.datevalue,t.channel_id";
        $query = $dwdb -> query($sql);
        if ($query != null && $query -> num_rows > 0) {
            $arr = $query -> result_array();
            $content_arr = array();
            for ($i = 0; $i < count($arr); $i++) {
                $row = $arr[$i];
                $channel_name = $row['channel_name'];
                $allkey = array_keys($content_arr);
                if (!in_array($channel_name, $allkey))
                    $content_arr[$channel_name] = array();
                $tmp = array();
                $tmp['startusers'] = $row['startusers'];
                $tmp['allusersacc'] = $row['allusers'];
                $tmp['datevalue'] = $row['datevalue'];
                array_push($content_arr[$channel_name], $tmp);
            }
            $all_version_name = array_keys($content_arr);
            $ret['content'] = $content_arr;
        }
        return $ret;
    }

    /**
     * Get new user
     *
     * @param int  $productId productId
     * @param int  $markets   market
     * @param date $dataTime  dateTime
     * 
     * @return $newUserArray
     */
    function getNewUser($productId, $markets, $dataTime) 
    {

        $newUserArray = array();
        foreach ($markets->result() as $row) {
            $chanelId = $row -> channel_id;
            $count = $this -> productanalyzemodel -> getNewUsersCountByChannel(
                $productId, $chanelId, $dataTime
            );
            array_push($newUserArray, $count);
        }
        return $newUserArray;
    }

    /**
     * Get yesterday new user
     *
     * @param int  $productId productId
     * @param int  $markets   market
     * @param date $dataTime  dateTime
     * 
     * @return $newUserArray
     */
    function getNewUserYestoday($productId, $markets, $dataTime) 
    {
        $newUserArray = array();
        foreach ($markets->result() as $row) {
            $chanelId = $row -> channel_id;
            $count = $this -> productanalyzemodel -> getYestodayNewUserCountByChannel(
                $dataTime, $productId, $chanelId
            );
            array_push($newUserArray, $count);
        }
        return $newUserArray;
    }

    /**
     * Get active  user
     *
     * @param int  $productId productId
     * @param int  $markets   market
     * @param date $dataTime  dateTime
     * 
     * @return $activeUserArray
     */
    function getActiveUser($productId, $markets, $dataTime) 
    {
        $activeUserArray = array();
        foreach ($markets->result() as $row) {
            $channelId = $row -> channel_id;
            $count = $this -> productanalyzemodel -> getUserStartUsersCountByChannel($productId, $channelId, $dataTime);
            array_push($activeUserArray, $count);
        }
        return $activeUserArray;
    }

    /**
     * Get active  user
     *
     * @param int $productId productId
     * @param int $markets   market
     * 
     * @return $userCountArray
     */
    function getUserCountByChannel($productId, $markets) 
    {
        $userCountArray = array();
        foreach ($markets->result() as $row) {
            $channelId = $row -> channel_id;
            $count = $this -> productanalyzemodel -> getTotalUserByChannel(
                $productId, $channelId
            );
            array_push($userCountArray, $count);
        }
        return $userCountArray;
    }

    /**
     * Get active  user percent
     *
     * @param int  $productId productId
     * @param int  $markets   market
     * @param date $from      starttime
     * @param date $to        endtime
     * 
     * @return $activeUserArray
     */
    function getActiveUserPercent($productId, $markets, $from, $to) 
    {
        $dwdb = $this -> load -> database('dw', true);
        $activeUserArray = array();
        foreach ($markets->result() as $row) {
            $chanelId = $row -> channel_id;
            $sql = "
                    select  
                    ppp.channel_id,
                    ppp.product_channel,
                    ifnull(t.usercount,0) percentage
                from
                    (select  
                         p.channel_id,
                         count(distinct f.deviceidentifier)/ (select 
                                                                  count(distinct ff.deviceidentifier)
                                                              from   
                                                                  " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "   ff,
                                                                  " . $dwdb -> dbprefix('dim_date') . "  dd, " . $dwdb -> dbprefix('dim_product') . "  dp
                                                              where  
                                                                  ff.date_sk = dd.date_sk and 
                                                                  ff.product_sk = dp.product_sk and 
                                                                  dp.product_id = $productId and 
                                                                  dp.channel_id = p.channel_id) usercount
                     from  
                         " . $dwdb -> dbprefix('fact_activeusers_clientdata') . "   f,
                         " . $dwdb -> dbprefix('dim_date') . "  d,
                         " . $dwdb -> dbprefix('dim_product') . "  p
                     where
                         f.date_sk = d.date_sk and 
                         f.product_sk = p.product_sk and
                         d.startdate between '" . $from . "' and '" . $to . "'and 
                         p.product_id = $productId
                     group by p.channel_id) t
                     right join (select
                                     distinct pp.product_channel,
                                     pp.channel_id
                                 from  
                                     " . $dwdb -> dbprefix('dim_product') . "  pp
                                 where 
                                     pp.channel_id = $chanelId) ppp
                                     on ppp.channel_id = t.channel_id
                order by ppp.channel_id;";
            $query = $dwdb -> query($sql);
            if ($query -> num_rows > 0)
                array_push($activeUserArray, $query -> first_row() -> percentage);
            else
                array_push($activeUserArray, 0);
        }
        return $activeUserArray;
    }

    /**
     * Get new user through timephase   
     *
     * @param int  $productId productId
     * @param int  $markets   market
     * @param date $from      starttime
     * @param date $to        endtime
     * 
     * @return $activeUserArray
     */
    function getNewUserByTimePhase($productId, $markets, $from, $to) 
    {
        $dwdb = $this -> load -> database('dw', true);
        $activeUserArray = array();
        foreach ($markets->result() as $row) {
            $chanelId = $row -> channel_id;
            $sql = "
                select
                    ppp.channel_id,
                    ppp.product_channel,
                    ifnull(t.usercount,0) activeusers
                from (select 
                          p.channel_id,
                          count(distinct f.deviceidentifier)/ (select 
                                                                   count(distinct ff.deviceidentifier)
                                                               from
                                                                   " . $dwdb -> dbprefix('fact_newusers_clientdata_by_product') . "  ff,
                                                                   " . $dwdb -> dbprefix('dim_date') . "  dd,
                                                                   " . $dwdb -> dbprefix('dim_product') . "   dp
                                                               where
                                                                   ff.date_sk = dd.date_sk and
                                                                   ff.product_sk = dp.product_sk and
                                                                   dp.product_id = $productId and 
                                                                   dp.channel_id = p.channel_id) usercount
                      from 
                          " . $dwdb -> dbprefix('fact_newusers_clientdata_by_product') . "  f,
                          " . $dwdb -> dbprefix('dim_date') . "  d,
                          " . $dwdb -> dbprefix('dim_product') . " p
                      where 
                          f.date_sk = d.date_sk and
                          f.product_sk = p.product_sk and
                          d.startdate between '" . $from . "' and '" . $to . "'and
                          p.product_id = $productId
                      group by p.channel_id) t
                      right join (select
                                      distinct pp.product_channel,
                                      pp.channel_id
                                  from
                                      " . $dwdb -> dbprefix('dim_product') . "  pp
                                  where 
                                      pp.channel_id = $chanelId) ppp
                                      on ppp.channel_id = t.channel_id
                order by ppp.channel_id;";
            $query = $dwdb -> query($sql);
            if ($query -> num_rows > 0)
                array_push($activeUserArray, $query -> first_row() -> activeusers);
            else
                array_push($activeUserArray, 0);
        }
        return $activeUserArray;
    }

    /**
     * Get new user through timephase
     *
     * @param int  $platformId platformid
     * @param int  $userId     userid
     * @param date $today      today
     * @param date $yestoday   yestoday
     *
     * @return $appList
     */
    function getProductListByPlatform($platformId, $userId, $today, $yestoday) 
    {
        $getIDsql = "
                select 
                    p.id,p.name,f.name platform 
                from 
                    " . $this -> db -> dbprefix('product') . "  p, 
                    " . $this -> db -> dbprefix('platform') . "  f 
                where 
                    p.product_platform = f.id  and 
                    p.active = 1 
                order by p.id desc;";
        $dwdb = $this -> load -> database('dw', true);
        $getIDsqlResult = $this -> db -> query($getIDsql);
        $todayquery = $dwdb -> query($this -> getsqlsentence($today, $userId));
        $yestadayquery = $dwdb -> query($this -> getsqlsentence($yestoday, $userId));
        $appList = array();
        $flag = 0;
        if ($getIDsqlResult != null && $getIDsqlResult -> num_rows() > 0) {
            foreach ($getIDsqlResult->result() as $row) {
                if (!$this -> isAdmin() && !$this -> isUserHasProductPermission($userId, $row -> id)) {
                    continue;
                }
                $app = array();
                $app['name'] = $row -> name;
                $app['id'] = $row -> id;
                foreach ($todayquery->result() as $todaydata) {
                    foreach ($yestadayquery->result() as $yestodaydata) {
                        if ($row -> name == $todaydata -> product_name && $todaydata -> product_name == $yestodaydata -> product_name) {
                            $app['newuser'] = $todaydata -> newusers . '/' . $yestodaydata -> newusers;
                            $app['startcount'] = $todaydata -> sessions . '/' . $yestodaydata -> sessions;
                            $app['startuser'] = $todaydata -> startusers . '/' . $yestodaydata -> startusers;
                            $app['newUserYestoday'] = $yestodaydata -> newusers;
                            $app['startCountYestoday'] = $yestodaydata -> sessions;
                            $app['startUserYestoday'] = $yestodaydata -> startusers;
                            $app['newUserToday'] = $todaydata -> newusers;
                            $app['startCountToday'] = $todaydata -> sessions;
                            $app['startUserToday'] = $todaydata -> startusers;
                            $app['platform'] = $todaydata -> platform;
                            $app['totaluser'] = $todaydata -> allusers;
                            array_push($appList, $app);
                            $flag = 1;
                            break;
                        }
                    }
                    if ($flag == 1) {
                        break;
                    }
                }
                if ($flag == 0) {
                    $app['newuser'] = '0' . '/' . '0';
                    $app['startcount'] = '0' . '/' . '0';
                    $app['startuser'] = '0' . '/' . '0';
                    $app['newUserYestoday'] = '0' . '/' . '0';
                    $app['startCountYestoday'] = '0' . '/' . '0';
                    $app['startUserYestoday'] = '0' . '/' . '0';
                    $app['newUserToday'] = 0;
                    $app['startCountToday'] = 0;
                    $app['startUserToday'] = 0;
                    $app['platform'] = $row -> platform;
                    $app['totaluser'] = 0;
                    array_push($appList, $app);
                }
                $flag = 0;
            }
        }
        return $appList;
    }

    /**
     * Is admin
     *
     * @return FALSE
     */
    function isAdmin() 
    {
        $userid = $this -> tank_auth -> get_user_id();
        $role = $this -> getUserRoleById($userid);
        if ($role == 3) {
            return true;
        }
        return false;
    }
    
    /**
     * Is checkUserPermissionToProduct
     *
     *@param string $productId productId
     * 
     * @return FALSE
     */
    function checkUserPermissionToProduct($productId)
    {
        $userid = $this -> tank_auth -> get_user_id();
        $role = $this -> getUserRoleById($userid);
        if ($role == 3) {
            return true;
        }
        return $this -> isUserHasProductPermission($userid, $productId);
    }
    /**
     * Ger user role through id    
     *
     * @param int $id id
     *
     * @return $row
     */
    function getUserRoleById($id) 
    {
        if ($id == '') {
            return '2';
        }
            
        $this -> db -> select('roleid');
        $this -> db -> where('userid', $id);
        $query = $this -> db -> get('user2role');
        $row = $query -> first_row();
        if ($query -> num_rows > 0) {
                return $row -> roleid;
        } 
        
        return '2';
    }

    /**
     * Is user has product permission
     *
     * @param int $userId    postday
     * @param int $productId userid
     *
     * @return FALSE
     */
    function isUserHasProductPermission($userId, $productId) 
    {
        $query = $this -> db -> get_where('user2product', array('user_id' => $userId, 'product_id' => $productId));
        if ($query && $query -> num_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Get sql sentence
     *
     * @param date $postDay postday
     * @param int  $userId  userid
     *
     * @return $sql
     */
    function getsqlsentence($postDay, $userId) 
    {
        $productbasicinfo = array();
        $dwdb = $this -> load -> database('dw', true);
        $sql = "
           select
               ppp.product_id,
               ppp.product_name,
               ppp.platform,
               ifnull(allusers,0) allusers,
               ifnull(newusers,0) newusers,
               ifnull(startusers,0) startusers,
               ifnull(sessions,0) sessions 
           from (select 
                     product_id,
                     product_name,
                     platform 
                 from 
                     " . $dwdb -> dbprefix('dim_product') . "
                 group by product_id) ppp 
                 left join (select
                                product_id,
                                max(allusers) allusers
                            from
                                " . $dwdb -> dbprefix('sum_basic_product') . " bp,
                                " . $dwdb -> dbprefix('dim_date') . " dd 
                            where 
                                dd.datevalue='$postDay' and 
                                bp.date_sk<=dd.date_sk 
                            group by product_id) dpp 
           on dpp.product_id=ppp.product_id 
           left join (select 
                          pp.product_id,
                          newusers,
                          startusers,sessions
                      from
                          " . $dwdb -> dbprefix('dim_product') . " p,
                          " . $dwdb -> dbprefix('sum_basic_product') . " pp,
                          " . $dwdb -> dbprefix('dim_date') . " d
                      where
                          d.datevalue='$postDay' and 
                          d.date_sk=pp.date_sk and
                          product_active=1 and channel_active=1 and 
                          version_active=1 and 
                          p.product_id=pp.product_id
                          group by pp.product_id) ff
           on ff.product_id=ppp.product_id 
           group by ppp.product_id";
        return $sql;
    }

    /**
     * Get sql sentence
     *
     * @param int $userId userid
     *
     * @return $query
     */
    function getAllProducts($userId) 
    {
        $sql = "";
        if ($this->isAdmin($userId)) {
            $sql = "select 
                    p.id,
                    p.name,
                    f.name platform 
                from 
                    " . $this->db->dbprefix('product') . "  p,
                    " . $this->db->dbprefix('platform') . "  f
                where 
                    p.product_platform = f.id and 
                    p.active = 1 
                    order by p.id desc;";
        } else {
            $sql = "select 
                        p.id,
                        p.name,
                        f.name platform 
                    from 
                        " . $this -> db -> dbprefix('product') . "  p,  
                        " . $this -> db -> dbprefix('platform') . "  f , 
                        " . $this -> db -> dbprefix('user2product') . "  up  
                    where 
                        p.active = 1 and 
                        p.product_platform = f.id and          
                        p.id=up.product_id and 
                        up.user_id=$userId 
                        order by p.id desc;";
        }
        
        $query = $this -> db -> query($sql);
        return $query;
    }
    
    /**
     * Get report start date
     *
     * @param date $product  product
     * @param date $fromTime starttime
     *
     * @return $fromTime
     */
    function getReportStartDate($product, $fromTime) 
    {
        if (date('Y-m-d', strtotime($product -> date)) > date('Y-m-d', strtotime($fromTime))) {
            return $product -> date;
        } else {
            return $fromTime;
        }
    }

    /**
     * Get report start date through projectid
     *
     * @param int $productId productid
     *
     * @return $toTime
     */
    function getReportStartDateByProjectId($productId) 
    {
        $sql = "
            select 
                min(date) as date 
            from
                " . $this -> db -> dbprefix('channel_product') . "
            where 
                product_id = $productId";
        $query = $this -> db -> query($sql);
        $toTime = date('Y-m-d', time());
        if ($query != null && $query -> num_rows() > 0) {
            $toTime = date('Y-m-d', strtotime($query -> first_row() -> date));
        }
        return $toTime;
    }

    /**
     * Get user start date
     *
     * @param int  $userId   userid
     * @param date $fromTime starttime
     * 
     * @return $fromTime
     */
    function getUserStartDate($userId, $fromTime) 
    {
        $sql = "
            select
                min(date) as date
            from 
                " . $this -> db -> dbprefix('product') . " 
            where 
                user_id = $userId";
        $query = $this -> db -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            if (date('Y-m-d', strtotime($query -> first_row() -> date)) > date('Y-m-d', strtotime($fromTime))) {
                return $query -> first_row() -> date;
            } else {
                return $fromTime;
            }
        }
        return $fromTime;
    }

    /**
     * Get user start date
     *
     * @param int    $userId      userid
     * @param string $appname     appname
     * @param int    $channel     channel
     * @param int    $platform    platform
     * @param int    $category    category
     * @param string $description description
     * 
     * @return $appKey
     */
    function addProduct($userId, $appname, $channel, $platform, $category, $description) 
    {
        $appKey = md5($appname . $platform . $category . time());
        $datetime = date('Y-m-d H:i:s');
        $data = array('name' => $appname, 'description' => $description, 'date' => $datetime, 'user_id' => $userId, 'channel_count' => 1, 
        'product_key' => $appKey, 'product_platform' => $platform, 'category' => $category);
        $this -> db -> insert('product', $data);
        $product_id = $this -> db -> insert_id();
        $chanprod = array('product_id' => $product_id, 'description' => $description, 'date' => $datetime, 'user_id' => $userId, 
        'productkey' => $appKey, 'channel_id' => $channel);
        $this -> db -> insert('channel_product', $chanprod);
        $confi = array('product_id' => $product_id);
        $this -> db -> insert('config', $confi);
        return $appKey;
    }

    /**
     * Add product channel   
     * 
     * @param int $user_id    user
     * @param int $product_id productrid
     * @param int $channel_id channelid
     * 
     * @return void
     */
    function addproductchannel($user_id, $product_id, $channel_id) 
    {
        $isChannelExitSQL = "
                       select 
                           * 
                       from 
                           " . $this -> db -> dbprefix('channel_product') . "
                       where
                           channel_id=$channel_id and
                           user_id=$user_id and
                           product_id=$product_id";
        $result = $this -> db -> query($isChannelExitSQL);
        if ($result == null || $result -> num_rows() == 0) {
            $data = array('product_id' => $product_id, 'date' => date('Y-m-d H:i:s'), 'user_id' => $user_id, 'productkey' => md5($product_id . $channel_id . $user_id . time()), 'channel_id' => $channel_id);
            $this -> db -> insert('channel_product', $data);
            $sql = "update " . $this -> db -> dbprefix('product') . "  set channel_count = channel_count+1 where id = $product_id and user_id = $user_id";
            $this -> db -> query($sql);
        }
    }

    /**
     * Get product information
     * 
     * @param int $product_id productid
     * 
     * @return NULL
     */
    function getproductinfo($product_id) 
    {
        $sql = "
           select 
               pro.* ,p.name as platname 
           from " . $this -> db -> dbprefix('product') . "
           pro inner join " . $this -> db -> dbprefix('platform') . "  p on  pro.product_platform=p.id 
           where 
               pro.id=$product_id ";
        $query = $this -> db -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
        return null;
    }
   
    /**
     * Updateproduct function
     * Update product information through appname,category,
     * description,productid,productkey
     *
     * @param string $appname     appname
     * @param string $category    category
     * @param string $description description
     * @param int    $product_id  productid
     * @param string $productkey  productkey
     *
     * @return query result
     */
    function updateproduct($appname, $category,$description,$product_id, $productkey)
    {
        $data = array('name' => $appname, 'description' => $description,'category' => $category);
        $this->db->where('id', $product_id);
        $this->db->update('product', $data);
        $data2 = array('description' => $description);
        $this->db->where('product_id', $product_id);
        $this->db->where('productkey', $productkey);
        $this->db->update('channel_product', $data2);
    }

    /**
     * GetProductCategory function
     * get product category information
     *
     * @return query result
     */
    function getProductCategory()
    {
        $query = $this->db->get('product_category');
        return $query;
    }

    /**
     * GetProductById function
     * get product information through id
     *
     * @param int $id id
     *
     * @return query result
     */
    function getProductById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('product');
        if ($query != null && $query->num_rows() > 0) {
            return $query->first_row();
        }
    }

    /**
     * DeleteProduct function
     * delete product information through productid,userid
     *
     * @param int $productId productid
     * @param int $userId    userid
     *
     * @return boolean
     */
    function deleteProduct($productId, $userId)
    {
        $sql= "
          update
              " . $this->db->dbprefix('product') . "  
          set 
              active = 0
          where
              id = $productId and
              user_id = $userId";
        $this->db->query($sql);
        $affect = $this->db->affected_rows();
        if ($affect > 0) {
            return true;
        }
        return false;
    }

    /**
     * GetStarterUserCountByTime function
     * get starter user count information through time,$projectid
     *
     * @param string $from      fromtime
     * @param string $to        totime
     * @param int    $projectid $projectid
     *
     * @return query result
     */
    function getStarterUserCountByTime($from, $to, $projectid)
    {
        $dwdb = $this->load->database('dw', true);
        $sql= "
            select
                h.hour,ifnull(sum(startusers),0) startusers,
                ifnull(sum(newusers),0) newusers
            from
                " . $dwdb->dbprefix('dim_date') . "  d
            inner join 
                " . $dwdb->dbprefix('sum_basic_byhour') . " s 
            on
                d.datevalue between '$from' and '$to'and
                d.date_sk = s.date_sk 
            inner join
                " . $dwdb->dbprefix('dim_product') . " p 
            on
                p.product_id = $projectid and
                p.product_sk = s.product_sk and
                p.product_active=1 and
                p.channel_active=1 and
                p.version_active=1 
            right join
                " . $dwdb->dbprefix('hour24') . " h 
            on
                h.hour=s.hour_sk 
            group by
                h.hour 
            order by 
                h.hour";
    
        $query = $dwdb->query($sql);
        if ($query != null && $query->num_rows() > 0) {
            return $query;
        } else {
            return null;
        }
    }

    /**
     * GetBasicInfoByDate function
     * get basic information through productid,date
     *
     * @param int    $productId productid
     * @param string $date      date
     *
     * @return query result
     */
    function getBasicInfoByDate($productId, $date)
    {
        $this->db->query("call p_get_product_basic_info($productId,'$date')");
        $query = $this->db->get('t_basic_info');
        if ($query != null && $query->num_rows() > 0) {
            return $query->first_row();
        }
    }

    /**
     * GetProductChanelById function
     * get product chanel information through id
     *
     * @param int $id id
     *
     * @return query result
     */
    function getProductChanelById($id)
    {
        $sql= "
            select
                c.channel_name,c.channel_id
            from
                " . $this->db->dbprefix('channel_product') . "  cp 
            left join
                " . $this->db->dbprefix('channel') . "  c 
            on
               cp.channel_id = c.channel_id
            where
               cp.product_id = " . $id . " and
               c.active=1";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * GetMarketData function
     * get market data information through market, timePhase, type, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $type      type
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getMarketData($market, $timePhase, $type, $start, $end)
    {
        $ret = array();
        if ($type == 'new') {
            return $this->getNewUserByProductAndChannelAndTime($market, $timePhase, $start, $end);
        }
        if ($type == 'active') {
            return $this->getActiveUserByProductAndChannelAndTime($market, $timePhase, $start, $end);
        } 
        if ($type == 'startcount') {
            return $this->getStartCountByProductAndChannelAndTime($market, $timePhase, $start, $end);
        }
        if ($type == 'average') {
            return $this->getAverageTime($market, $timePhase, $start, $end);
        }
        if ($type == 'weekactive') {
            return $this->getWeeklyActivePercent($market, $timePhase, $start, $end);
        }
        if ($type == 'monthactive') {
            return $this->getMonthlyActivePercent($market, $timePhase, $start, $end);
        }
    }

    /**
     * GetNewUserByProductAndChannelAndTime function
     * get new user information through market, timePhase, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getNewUserByProductAndChannelAndTime($market, $timePhase, $start, $end)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = date('Y-m-d', strtotime("-7 day"));
    
        if ($timePhase == "7day") {
            $title = lang('producttitleinfo_new7days');
            $fromTime = date('Y-m-d', strtotime("-7 day"));
        }
    
        if ($timePhase == "1month") {
            $title = lang('producttitleinfo_newmonth');
            $fromTime = date("Y-m-d", strtotime("-30 day"));
        }
    
        if ($timePhase == "3month") {
            $fromTime = date("Y-m-d", strtotime("-90 day"));
            $title = lang('producttitleinfo_new3month');
    
        }
        if ($timePhase == "all") {
            $title = lang('producttitleinfo_newall');
            $fromTime = 'all';
        }
    
        if ($timePhase == 'any') {
            $title = lang('producttitleinfo_newanytime');
            $fromTime = $start;
            $toTime = $end;
        }
    
        $productId = $currentProduct->id;
        if ($market == 'default') {
            $query = $this->getProductChanelById($productId);
            if ($query != null && $query->num_rows() > 0) {
                $market = $query->first_row()->channel_id;
            } else {
                $market = 0;
            }
        }
        $query= $this->newusermodel->getNewUserByDayAndChannelId($fromTime, $toTime, $currentProduct->id, $market);
        $ret['market'] = $this->getMarketNameById($market);
        $ret["title"] = $title;
        $ret["content"] = $query->result_array();
        $ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime);
        return $ret;
    }

    /**
     * GetMarketNameById function
     * get market name information through marketid
     *
     * @param int $makertId marketid
     *
     * @return query result
     */
    function getMarketNameById($makertId)
    {
    	if($makertId == null)
		 	return "";
    
        $sql= "
            select
                channel_name
            from
                " . $this->db->dbprefix('channel') . "
            where
                channel_id = $makertId";
        $query = $this->db->query($sql);
        if ($query != null && $query->num_rows() > 0) {
            return $query->first_row()->channel_name;
        }
        return "";
    }

    /**
     * GetActiveUserByProductAndChannelAndTime function
     * get active user information through market, timePhase, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getActiveUserByProductAndChannelAndTime($market, $timePhase, $start, $end) 
    {
        $ret = array();
        $currentProduct = $this->common->getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = date('Y-m-d', strtotime("-7 day"));
        if ($timePhase == "7day") {
            $title = lang('producttitleinfo_act7days');
            $fromTime = date('Y-m-d', strtotime("-7 day"));
        }
        if ($timePhase == "1month") {
            $title = lang('producttitleinfo_actmonth');
            $fromTime = date("Y-m-d", strtotime("-30 day"));
        }
        if ($timePhase == "3month") {
            $fromTime = date("Y-m-d", strtotime("-90 day"));
            $title = lang('producttitleinfo_act3month');
        }
        if ($timePhase == "all") {
            $title = lang('producttitleinfo_actall');
            $fromTime = 'all';
        }
        if ($timePhase == 'any') {
            $title = lang('producttitleinfo_actanytime');
            $fromTime = $start;
            $toTime = $end;
        }
        $productId = $currentProduct->id;
        if ($market == 'default') {
            $query = $this->getProductChanelById($productId);
            if ($query != null && $query->num_rows() > 0) {
                $market = $query->first_row()->channel_id;
            } else {
                $market = 0;
            }
        }
        $query= $this->newusermodel->getActiveUsersByDayAndChinnel($fromTime, $toTime, $currentProduct->id, $market);
        $ret['market'] = $this->getMarketNameById($market);
        $ret["title"] = $title;
        $ret["content"] = $query->result_array();
        $ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime);
        return $ret;
    }

    /**
     * GetStartCountByProductAndChannelAndTime function
     * get start count information through market, timePhase, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getStartCountByProductAndChannelAndTime($market, $timePhase, $start, $end) 
    {
        $ret = array();
        $currentProduct = $this->common->getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = date('Y-m-d', strtotime("-7 day"));
        if ($timePhase == "7day") {
            $title = lang('producttitleinfo_start7days');
            $fromTime = date('Y-m-d', strtotime("-7 day"));
        }
        if ($timePhase == "1month") {
            $title = lang('producttitleinfo_startmonth');
            $fromTime = date("Y-m-d", strtotime("-30 day"));
        }
        if ($timePhase == "3month") {
            $fromTime = date("Y-m-d", strtotime("-90 day"));
            $title = lang('producttitleinfo_start3month');
        }
        if ($timePhase == "all") {
            $title = lang('producttitleinfo_startall');
            $fromTime = 'all';
        }
        if ($timePhase == 'any') {
            $title = lang('producttitleinfo_startanytime');
            $fromTime = $start;
            $toTime = $end;
        }
        $productId = $currentProduct->id;
        if ($market == 'default') {
            $query = $this->getProductChanelById($productId);
            if ($query != null && $query->num_rows() > 0) {
                $market = $query->first_row()->channel_id;
            } else {
                $market = 0;
            }
        }
        $query = $this->newusermodel->getTotalStartUserByDayAndChannel($fromTime, $toTime, $currentProduct->id, $market);
        $ret['market'] = $this->getMarketNameById($market);
        $ret["title"] = $title;
        $ret["content"] = $query->result_array();
        return $ret;
    }

    /**
     * GetAverageTime function
     * get average time information through market, timePhase, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getAverageTime($market, $timePhase, $start, $end)
    {
        $ret = array();
        $currentProduct = $this->common->getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = date('Y-m-d', strtotime("-7 day"));
        if ($timePhase == "7day") {
            $title = lang('producttitleinfo_time7days');
            $fromTime = date('Y-m-d', strtotime("-7 day"));
        }
        if ($timePhase == "1month") {
            $title = lang('producttitleinfo_timemonth');
            $fromTime = date("Y-m-d", strtotime("-30 day"));
        }
        if ($timePhase == "3month") {
            $fromTime = date("Y-m-d", strtotime("-90 day"));
            $title = lang('producttitleinfo_time3month');
        }
        if ($timePhase == "all") {
            $title = lang('producttitleinfo_timeall');
            $fromTime = 'all';
        }
        if ($timePhase == 'any') {
            $title = lang('producttitleinfo_timeanytime');
            $fromTime = $start;
            $toTime = $end;
        }
        $productId = $currentProduct->id;
        if ($market == 'default') {
            $query = $this->getProductChanelById($productId);
            if ($query != null && $query->num_rows() > 0) {
                $market = $query->first_row()->channel_id;
            } else {
                $market = 0;
            }
        }
        $query = $this->getAverageUsingTimeByChannelAndTime($fromTime, $toTime, $productId, $market);
        $ret['market'] = $this->getMarketNameById($market);
        $ret["title"] = $title;
        $ret["content"] = $query->result_array();
        return $ret;
    }

    /**
     * GetWeeklyActivePercent function
     * get weekly active percent information through market, timePhase, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getWeeklyActivePercent($market, $timePhase, $start, $end)
    {
        $ret = array();
        $currentProduct = $this->common->getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = date('Y-m-d', strtotime("-7 day"));
        if ($timePhase == "7day") {
            $title = lang('producttitleinfo_percent7days');
            $fromTime = date('Y-m-d', strtotime("-7 day"));
        }
        if ($timePhase == "1month") {
            $title = lang('producttitleinfo_percentmonth');
            $fromTime = date("Y-m-d", strtotime("-30 day"));
        }
        if ($timePhase == "3month") {
            $fromTime = date("Y-m-d", strtotime("-90 day"));
            $title = lang('producttitleinfo_percent3month');
        }
        if ($timePhase == "all") {
            $title = lang('producttitleinfo_percentall');
            $fromTime = 'all';
        }
        if ($timePhase == 'any') {
            $title = lang('producttitleinfo_percentanytime');
            $fromTime = $start;
            $toTime = $end;
        }
        $productId = $currentProduct->id;
        if ($market == 'default') {
            $query = $this->getProductChanelById($productId);
            if ($query != null && $query->num_rows() > 0) {
                $market = $query->first_row()->channel_id;
            } else {
                $market = 0;
            }
        }
        $query = $this->getWeekActiveUserPercent($fromTime, $toTime, $productId, $market);
        $ret['market'] = $this->getMarketNameById($market);
        $ret["title"] = $title;
        $ret["content"] = $query->result_array();
        return $ret;
    }

    /**
     * GetMonthlyActivePercent function
     * get momthly active percent information through market, timePhase, start, end
     *
     * @param string $market    market
     * @param string $timePhase timephase
     * @param string $start     starttime
     * @param string $end       endtime
     *
     * @return query result
     */
    function getMonthlyActivePercent($market, $timePhase, $start, $end)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = date('Y-m-d', strtotime("-7 day"));
        if ($timePhase == "1month" || $timePhase == "7day") {
            $title = lang('producttitleinfo_percentmonth');
            $fromTime = date("Y-m-d", strtotime("-30 day"));
        }
        if ($timePhase == "3month") {
            $fromTime = date("Y-m-d", strtotime("-90 day"));
            $title = lang('producttitleinfo_percent3month');
        }
        if ($timePhase == "all") {
            $title = lang('producttitleinfo_percentall');
            $fromTime = 'all';
        }
        if ($timePhase == 'any') {
            $title = lang('producttitleinfo_percentanytime');
            $fromTime = $start;
            $toTime = $end;
        }
        $productId = $currentProduct->id;
        if ($market == 'default') {
            $query = $this->getProductChanelById($productId);
            if ($query != null && $query->num_rows() > 0) {
                $market = $query->first_row()->channel_id;
            } else {
                $market = 0;
            }
        }
        $query = $this->getMonthActiveUserPercent($fromTime, $toTime, $productId, $market);
        $ret['market'] = $this->getMarketNameById($market);
        $ret["title"] = $title;
        $ret["content"] = $query->result_array();
        return $ret;
    }

    /**
     * GetAverageUsingTimeByChannelAndTime function
     * get average using time information through fromtime, totime, productid,
     * channelid
     *
     * @param string $fromTime  fromtime
     * @param string $toTime    totime
     * @param int    $productId productid
     * @param int    $channelId channelid
     *
     * @return query result
     */
    function getAverageUsingTimeByChannelAndTime($fromTime, $toTime, $productId, $channelId) 
    {
        $dwdb = $this->load->database('dw', true);
        $sql= "
            select
                ddd.datevalue startdate,
                ifnull(ppp.aver,0) totalusers
            from
            (
            select
                dd.datevalue
            from
                " . $dwdb->dbprefix('dim_date_day') . "  dd
            where
                dd.datevalue between '" . $fromTime . "' and '" . $toTime . "') ddd
            left join 
                (
                select
                    d.datevalue,sum(f.duration)/ count(f.session_id) aver
                from
                    " . $dwdb->dbprefix('fact_usinglog_daily') . "  f,
                    " . $dwdb->dbprefix('dim_date_day') . "  d,
                    " . $dwdb->dbprefix('dim_product') . "  p
                where
                    f.date_sk = d.date_sk and
                    d.datevalue between '" . $fromTime . "' and '" . $toTime . "'and
                    f.product_sk = p.product_sk and
                    p.product_id = $productId and
                    p.channel_id = $channelId
                    d.datevalue group by
                    d.datevalue) ppp on
                    ddd.datevalue = ppp.datevalue;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetWeekActiveUserPercent function
     * get week active user percent information through fromtime, totime,
     * productid, channelid
     *
     * @param string $fromTime  fromtime
     * @param string $toTime    totime
     * @param int    $productId productid
     * @param int    $channelId channelid
     *
     * @return query result
     */
    function getWeekActiveUserPercent($fromTime, $toTime, $productId, $channelId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql= "
            Select 
                ds.year, 
                ds.week,
                df.startdate, 
                ifnull(dp.percentage,0) totalusers
            from
            (
            select
                distinct year, week, startdate
            from
                " . $dwdb->dbprefix('dim_date') . "
            where
                startdate between '$fromTime' and '$toTime' and weekday=0) df 
            inner join
                (
                Select distinct 
                    year, 
                    week
                from
                    " . $dwdb->dbprefix('dim_date') . "
                where
                    startdate between '$fromTime' and '$toTime') ds 
                on 
                    df.year=ds.year and
                    df.week = ds.week 
                left join
                    (
                    Select
                        year,
                        week,
                        count(distinct f.deviceidentifier)/(
                        select
                            count(distinct ff.deviceidentifier)
                        from
                            " . $dwdb->dbprefix('fact_activeusers_clientdata') . "  ff,
                            " . $dwdb->dbprefix('dim_date') . " dd,
                            " . $dwdb->dbprefix('dim_product') . "  pp
                        where
                            ff.date_sk = dd.date_sk and
                            ff.product_sk = pp.product_sk and
                            pp.product_id=$productId and
                            pp.channel_id=$channelId and
                            dd.year=d.year and dd.week<=d.week) percentage
                         from
                             " . $dwdb->dbprefix('fact_activeusers_clientdata') . "  f,
                             " . $dwdb->dbprefix('dim_date') . "  d,
                             " . $dwdb->dbprefix('dim_product') . " p
                         where
                             f.date_sk = d.date_sk and
                             f.product_sk = p.product_sk and
                             p.product_id=$productId and
                             p.channel_id=$channelId and
                             d.startdate between '$fromTime'and '$toTime' 
                         group by
                             d.year,d.week) dp on ds.year = dp.year and
                             ds.week = dp.week;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetMonthActiveUserPercent function
     * get month active user percent information through fromtime, totime,
     * productid, channelid
     *
     * @param string $fromTime  fromtime
     * @param string $toTime    totime
     * @param int    $productId productid
     * @param int    $channelId channelid
     *
     * @return query result
     */
    function getMonthActiveUserPercent($fromTime, $toTime, $productId, $channelId) 
    {
        $dwdb = $this->load->database('dw', true);
        $sql= "
            Select 
                ds.year, 
                ds.month,
                df.startdate, 
                ifnull(dp.percentage,0) totalusers
            from
            (
                select distinct 
                    year, 
                    month, 
                    startdate
                from
                    " . $dwdb->dbprefix('dim_date') . "
                where 
                    startdate between '$fromTime' and '$toTime' and 
                    day=1
            ) df 
            inner join
            (
                Select
                    distinct year, month
                from
                    " . $dwdb->dbprefix('dim_date') . "
                where
                    startdate between '$fromTime' and '$toTime'
            ) ds 
            on 
                df.year=ds.year and 
                df.month = ds.month 
            left join 
            (
                Select
                    year,
                    month, 
                    count(distinct f.deviceidentifier)/(
                        select
                            count(distinct ff.deviceidentifier)
                        from
                            " . $dwdb->dbprefix('fact_activeusers_clientdata') . "  ff,
                            " . $dwdb->dbprefix('dim_date') . " dd,
                            " . $dwdb->dbprefix('dim_product') . "  pp
                        where
                            ff.date_sk = dd.date_sk and
                            ff.product_sk = pp.product_sk and
                            pp.product_id=$productId and
                            pp.channel_id=$channelId and
                            dd.year=d.year and
                            dd.month<=d.month) percentage
                        from
                            " . $dwdb->dbprefix('fact_activeusers_clientdata') . "  f,
                            " . $dwdb->dbprefix('dim_date') . " d,
                            " . $dwdb->dbprefix('dim_product') . " p
                        where
                            f.date_sk = d.date_sk and
                            f.product_sk = p.product_sk and
                            p.product_id=$productId and
                            p.channel_id=1 and
                            d.startdate between '$fromTime' and '$toTime' group by d.year,d.month)
                            dp on ds.year = dp.year and
                            ds.month = dp.month;";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetProductName function
     * get product name information through id
     *
     * @param int $id id
     *
     * @return query result
     */
    function getProductName($id)
    {
        $sql= "
            select 
                *
            from
                " . $this->db->dbprefix('product') . "
            where
                id =$id";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * GetRatedate function
     * get rate date information through productid, fromtime, totime,  channelid
     *
     * @param int    $productId productid
     * @param string $fromTime  fromtime
     * @param string $toTime    totime
     * @param string $type      type
     *
     * @return query result
     */
    function getRatedate($productId, $fromTime, $toTime, $type)
    {
        if ($type == 0) {
            $fromTime = "DATE_SUB('" . $fromTime . "',INTERVAL 7 DAY)";
        } else {
            $fromTime = "DATE_SUB('" . $fromTime . "',INTERVAL 1 MONTH)";
        }
        $dwdb = $this->load->database('dw', true);
        $sql= "
            select
                d.datevalue
            from
                " . $dwdb->dbprefix('sum_basic_channel_activeusers') . " ca,
                " . $dwdb->dbprefix('dim_product') . " pp,
                " . $dwdb->dbprefix('dim_date') . " d
            where
                d.datevalue between $fromTime and '$toTime' and
                d.date_sk=ca.date_sk and
                ca.flag=$type and pp.product_id= $productId and
                pp.product_active=1 and
                pp.channel_active=1 and
                pp.version_active=1 and
                ca.product_id=pp.product_id and
                ca.channel_id=pp.channel_id 
            group by
                d.datevalue 
            order by
                d.datevalue asc";
        $query = $dwdb->query($sql);
        if ($query != null && $query->num_rows() > 0) {
            $ret = $query->result_array();
        } else {
            $ret = null;
        }
        return $ret;
    }

    /**
     * GetRateVersion function
     * get rate version information through productid, fromtime, totime, channelid
     *
     * @param int    $productId productid
     * @param string $fromTime  fromtime
     * @param string $toTime    totime
     * @param string $type      type
     *
     * @return query result
     */
    function getRateVersion($productId, $fromTime, $toTime, $type)
    {
        if ($type == 0) {
            $fromTime = "DATE_SUB('" . $fromTime . "',INTERVAL 7 DAY)";
        } else {
            $fromTime = "DATE_SUB('" . $fromTime . "',INTERVAL 1 MONTH)";
        }
        $dwdb = $this->load->database('dw', true);
        $sql= "
            select
                pp.channel_name
            from
                " . $dwdb->dbprefix('sum_basic_channel_activeusers') . " ca,
                " . $dwdb->dbprefix('dim_product') . " pp,
                " . $dwdb->dbprefix('dim_date') . " d
            where
                d.datevalue between $fromTime and '$toTime' and
                d.date_sk=ca.date_sk and
                ca.flag=$type and pp.product_id= $productId and
                pp.product_active=1 and
                pp.channel_active=1 and
                pp.version_active=1 and
                ca.product_id=pp.product_id and
                ca.channel_id=pp.channel_id group by
                d.datevalue,ca.channel_id order by
                d.datevalue asc";
        $query = $dwdb->query($sql);
        if ($query != null && $query->num_rows() > 0) {
            $ret = $query->result_array();
        } else {
            $ret = null;
        }
        return $ret;
    }
}