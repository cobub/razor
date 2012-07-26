<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class productanalyzemodel extends CI_Model {
	
	function __construct() {
		$this->load->database ();
	}
	
	function getAllAnalyzeData($date,$product_id){
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select tt.channel_id,
	tt.channel_name,
	ifnull(t.startusers,0) startusers,
	ifnull(t.newusers,0) newusers,
	ifnull(t.allusers,0) allusers
from (
	select 
p.channel_id,
p.channel_name,
       sum(startusers) startusers,
       sum(newusers) newusers,
       sum(allusers) allusers
	from 
	  ".$dwdb->dbprefix('sum_basic_all')."    s,
       ".$dwdb->dbprefix('dim_date')."    d,
       ".$dwdb->dbprefix('dim_product')."    p
	where  d.datevalue='$date'
       and d.date_sk = s.date_sk
       and p.product_id = $product_id
       and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.channel_active=1
	group by p.channel_id,p.channel_name) t
		right join (
			select distinct
				pp.channel_id,
                pp.channel_name
           	from  ".$dwdb->dbprefix('dim_product')." 
				 pp
            where pp.product_id = $product_id ) tt
        on tt.channel_id = t.channel_id
		order by tt.channel_id;
	";
		
		$query = $dwdb->query($sql);
		return $query;
	}
	
	
	
	// 获得今日的数据
	function getTodayInfo($productId, $date) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select ifnull(sum(sessions),0) sessions,ifnull(sum(startusers),0) startusers,
       ifnull(sum(newusers),0) newusers,ifnull(sum(upgradeusers),0) upgradeusers,ifnull(sum(usingtime),0) usingtime,
       ifnull(sum(allusers),0) allusers,ifnull(sum(allsessions),0) allsessions from   ".$dwdb->dbprefix('sum_basic_all')."  s,
       ".$dwdb->dbprefix('dim_date')."    d,  ".$dwdb->dbprefix('dim_product')."  p where  d.datevalue='$date' and d.date_sk = s.date_sk and p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1;";
		
		$query = $dwdb->query ( $sql );
		// $data['date'] = $id;
		// $date);
		// $data['newuser'] = $this->getNewUsersCount($productId, $date);
		// $data['updateuser'] = $this->getUpdateUsersCount($productId, $date);
		// $data['avertime'] =
		// $this->getAverageUsingTimeByProductAtRealTime($date, $productId);
		// $data['sessions'] = $query->first_row()->sessions;
		// $data['newusers'] = $this->getNewUsersCount($productId, $date);
		// $data['upgradeusers'] = $this->getUpdateUsersCount($productId,
		// $date);
		// $data['usingtime'] =
		// $this->getAverageUsingTimeByProductAtRealTime($date, $productId);
		return $query->first_row ();
	}
	
	// function getYestodayInfo($productId, $date) {
	// $data = array ();
	// $data ['start'] = $this->getYestodayStartCount ( $date, $productId );
	// $data ['startuser'] = $this->getActiveUserCount ( $date, $productId );
	// $data ['newuser'] = $this->getYestodayNewUserCount ( $date, $productId );
	// $data ['updateuser'] = $this->getYestodayUpdateUser ( $productId, $date
	// );
	// $data ['avertime'] = $this->getAverageUsingTimeByProduct ( $date,
	// $productId );
	// return $data;
	// }
	
	function getYestodayUpdateUser($productId, $date) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "Select count(distinct f.deviceidentifier) as usercount from   ".$dwdb->dbprefix('fact_clientdata')."  f, 
		  ".$dwdb->dbprefix('dim_product')."  p,  ".$dwdb->dbprefix('dim_date')."   d where f.product_sk=p.product_sk and f.date_sk = d.date_sk and
		 p.product_id=$productId and d.datevalue='$date';";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 获得总体概况
	function getOverallInfo($productId) {
		$data = array ();
		$toTime = date ( 'Y-m-d', time () );
		$day7 = date ( "Y-m-d", strtotime ( "-7 day" ) );
		$day30 = date ( "Y-m-d", strtotime ( "-30 day" ) );
		
		$data ['7dayactive'] = $this->getActiveUserByPeriod ( $day7, $toTime, $productId );
		$data ['1month'] = $this->getActiveUserByPeriod ( $day30, $toTime, $productId );
		
		return $data;
	}
	
	// 根据时间获取累计启动次数
	function getTotalStartCount($productId) {
		$sql = "select count(*) count from   ".$this->db->dbprefix('clientdata')."  where productkey
		in (select productkey from   ". $this->db->dbprefix('channel_product')."  where product_id = $productId);";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 根据时间获取累计用户
	function getTotalUsers($productId) {
		$sql = "select count(distinct deviceid) count from   ". $this->db->dbprefix('clientdata')."  where productkey
		in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId);";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 查看该用户的所有产品新用户数
	function getTotalNewUsersCountByUserId($userId, $dateTime) {
		$sql = "select count(distinct deviceid) count from  ". $this->db->dbprefix('clientdata')." where
		  date(date) = '$dateTime' and productkey 
	in (select productkey from  ". $this->db->dbprefix('channel_product')." where user_id = $userId) 
	and deviceid not in (select distinct deviceid from  ". $this->db->dbprefix('clientdata')."
		 where date < '$dateTime' and productkey in (select productkey from  ". $this->db->dbprefix('channel_product')." where user_id = $userId))";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 查看该用户所有产品的启动用户数
	function getStartUserCountByUserId($userId, $dateTime) {
		$sql = "select count(distinct deviceid) count from  ". $this->db->dbprefix('clientdata')." where productkey in
		 (select productkey from  ". $this->db->dbprefix('channel_product')." where user_id = $userId) and date(date) = '$dateTime' ";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 实时数据
	// 根据时间获取今日启动次数
	function getUserStartCount($productId, $dataTime) {
		$sql = "select count(*) count from  ". $this->db->dbprefix('clientdata')." where  date(date) = '$dataTime' and productkey 
		 in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId);";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 获取今日起动用户，即活跃用户
	function getUserStartUsersCount($productId, $dataTime) {
		$sql = "select count(distinct deviceid) count from  ". $this->db->dbprefix('clientdata')." where  date(date) = '$dataTime' and productkey
		in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId);";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 获取今日起动用户，即活跃用户(根据渠道)
	function getUserStartUsersCountByChannel($productId, $chanelId, $dataTime) {
		$sql = "select count(distinct deviceid) count from  ". $this->db->dbprefix('clientdata')." where  date(date) = '$dataTime' and productkey
		in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId and channel_id = $chanelId);";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	
	}
	
	// 获取今日新用户
	function getNewUsersCount($productId, $dataTime) {
		$sql = "select count(distinct deviceid) count from  ". $this->db->dbprefix('clientdata')." where
		  date(date) = '$dataTime' and productkey 
	in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId) 
	and deviceid not in (select distinct deviceid from  ". $this->db->dbprefix('clientdata')."
		 where date < '$dataTime' and productkey in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId))";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 获取今日新用户(根据渠道)
	function getNewUsersCountByChannel($productId, $chanelId, $dataTime) {
		$sql = "select count(distinct deviceid) count from  ". $this->db->dbprefix('clientdata')." where
		  date(date) = '$dataTime' and productkey 
	in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId and channel_id = $chanelId) 
	and deviceid not in (select distinct deviceid from ". $this->db->dbprefix('clientdata')." 
		 where date < '$dataTime' and productkey in (select productkey from  ". $this->db->dbprefix('channel_product')." where product_id = $productId and channel_id = $chanelId))";
		// echo $sql."<br>"."<br>";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->count;
	}
	
	// 根据时间，产品ID获取昨日新用户数(根据渠道)
	function getYestodayNewUserCountByChannel($dateTime, $productId, $chanelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from  ". $dwdb->dbprefix('fact_clientdata')."
		f, ". $dwdb->dbprefix('dim_date')."  d,  ". $dwdb->dbprefix('dim_product')." p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and
		d.year=year('$dateTime') and d.month=month('$dateTime') and d.day=day('$dateTime') and p.product_id=$productId and channel_id = $chanelId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 获取升级用户数量
	function getUpdateUsersCount($productId, $dataTime) {
		return 0;
		// $sql = "select count(*) from clientdata c,product_version pv on
		// c.version = pv.version where date = '$dataTime' and ";
		// $query = $this->db->query($sql);
		// return $query->first_row()->newusercount;
	}
	
	// 根据时间，产品ID，渠道ID获取昨日新用户数
	// function
	// getYestodayNewUserCountByChannel($dateTime,$productId,$channelId)
	// {
	// $sql = "select count(distinct f.deviceidentifier) usercount from
	// fact_clientdata
	// f, dim_date d, dim_product p where f.date_sk=d.date_sk and
	// f.product_sk=p.product_sk and
	// d.year=year('$dateTime') and d.month=month('$dateTime') and
	// d.day=day('$dateTime') and p.product_id=$productId and
	// p.channel_id=$channelId;";
	// $dwdb = $this->load->database('dw',TRUE);
	// $query = $dwdb->query($sql);
	// return $query->first_row()->usercount;
	// }
	
	// 根据时间，产品ID获取昨日新用户数
	function getYestodayNewUserCount($dateTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from  ". $dwdb->dbprefix('fact_clientdata')."
		f,  ". $dwdb->dbprefix('dim_date')." d,  ". $dwdb->dbprefix('dim_product')." p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and
		d.year=year('$dateTime') and d.month=month('$dateTime') and d.day=day('$dateTime') and p.product_id=$productId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据时间，产品ID，渠道ID获取昨日启动次数
	function getYestodayStartCountByChannelId($dateTime, $productId, $channelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(*) starttimes from  ". $dwdb->dbprefix('fact_clientdata')." f,  ". $dwdb->dbprefix('dim_date')." d,  ". $dwdb->dbprefix('dim_product')."
		 p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and d.year=year('$dateTime') and
		 d.month=month('$dateTime') and d.day=day('$dateTime') and p.product_id=$productId and p.channel_id=$channelId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->starttimes;
	}
	
	// 根据时间，产品ID获取昨日启动次数
	function getYestodayStartCount($dateTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(*) starttimes from  ". $dwdb->dbprefix('fact_clientdata')." f, ". $dwdb->dbprefix('dim_date')."  d,  ". $dwdb->dbprefix('dim_product')."
		p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and d.year=year('$dateTime') and
		d.month=month('$dateTime') and d.day=day('$dateTime') and p.product_id=$productId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->starttimes;
	}
	
	// 根据时间，产品ID，渠道ID获取昨日活跃用户数
	function getActiveUserCountByChannelId($dateTime, $productId, $channelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from  ". $dwdb->dbprefix('fact_clientdata')."
		 f, ". $dwdb->dbprefix('dim_date')." d, ". $dwdb->dbprefix('dim_product')." p where f.date_sk=d.date_sk and
		 f.product_sk=p.product_sk and d.year=year('$dateTime') and d.month=month('$dateTime') and d.day=day('$dateTime') and
		 p.product_id=$productId and p.channel_id=$channelId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据时间，产品ID 获取昨日活跃用户数
	function getActiveUserCount($dateTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from  ". $dwdb->dbprefix('fact_clientdata')." 
		f,   ". $dwdb->dbprefix('dim_date')."  d,   ". $dwdb->dbprefix('dim_product')."  p where f.date_sk=d.date_sk and
		f.product_sk=p.product_sk and d.year=year('$dateTime') and d.month=month('$dateTime') and d.day=day('$dateTime') and
		p.product_id=$productId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据产品，渠道ID获取总用户数
	function getTotalUserByChannel($productId, $channelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from 
		  ". $dwdb->dbprefix('fact_activeusers_clientdata')."  f,   ". $dwdb->dbprefix('dim_product')."  p where f.product_sk=p.product_sk and p.product_id=$productId and p.channel_id=$channelId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据产品获取总用户数
	function getTotalUserByProductId($productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from
		  ". $dwdb->dbprefix('fact_activeusers_clientdata')."  f,   ". $dwdb->dbprefix('dim_product')."  p where f.product_sk=p.product_sk and p.product_id=$productId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据产品，渠道ID获取累计启动次数
	function getTotalStartUserCountByChannel($productId, $channelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(1) usercount from   ". $dwdb->dbprefix('fact_activeusers_clientdata')."  f,   ". $dwdb->dbprefix('dim_product')."  p where f.product_sk=p.product_sk and p.product_id=$productId and p.channel_id=$channelId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据产品，渠道ID获取累计启动次数
	function getTotalStartUserCountByProductId($productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(1) usercount from   ". $dwdb->dbprefix('fact_activeusers_clientdata')."  f,  ". $dwdb->dbprefix('dim_product')."   p where f.product_sk=p.product_sk and p.product_id=$productId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据时间段,产品ID，渠道ID获取活跃用户数，可传入7天，14天获取7天，14天的活跃用户数
	function getActiveUserByPeriodAndChannel($fromTime, $toTime, $productID, $channelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select count(distinct f.deviceidentifier) usercount from   ". $dwdb->dbprefix('fact_clientdata')."  f, 
	  ". $dwdb->dbprefix('dim_date')." 	 d,   ". $dwdb->dbprefix('dim_product')."  p where f.date_sk=d.date_sk and f.product_sk=p.product_sk and d.datevalue
		 between '$fromTime' and '$toTime' and p.product_id = $productID and p.channel_id = $channelId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->usercount;
	}
	
	// 根据时间段,产品ID获取活跃用户数，可传入7天，30天获取7天，30天的活跃用户数
	function getActiveUserByPeriod($fromTime, $toTime, $productID) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select ifnull(sum(newusers),0) newusers from    ". $dwdb->dbprefix('sum_basic_all')."  s,
      ". $dwdb->dbprefix('dim_date')."     d,
      ". $dwdb->dbprefix('dim_product')."     p 
       where  d.datevalue between '$fromTime' and '$toTime'
       and d.date_sk = s.date_sk
       and p.product_id = $productID
       and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->newusers;
	}
	
	// 返回指定日期内产品，渠道平均使用时常
	function getAverageUsingTimeByChannel($dateTime, $productId, $channelId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "Select sum(u.duration)/count(distinct session_id) from   ". $dwdb->dbprefix('fact_usinglog')." 
		 u,  ". $dwdb->dbprefix('dim_product')."   p,   ". $dwdb->dbprefix('dim_date')."  d where u.product_sk = p.product_sk
		 and u.date_sk=d.date_sk and d.datevalue='$dateTime' and p.product_id = $productId and p.channel_id = $channelId;";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	// 返回指定日期内产品平均使用时常
	function getAverageUsingTimeByProduct($dateTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "Select sum(f.duration)/count(f.session_id) as avertime from  ". $dwdb->dbprefix('fact_usinglog_daily')."   f,   ". $dwdb->dbprefix('dim_product')."  p,   ". $dwdb->dbprefix('dim_date')."  d 
		where f.date_sk=d.date_sk and f.product_sk = p.product_sk and
		 d.datevalue='$dateTime' and p.product_id=$productId;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->avertime;
	}
	
	// 获取实时数据平均使用时常(今日）
	function getAverageUsingTimeByProductAtRealTime($dateTime, $productId) {
		$sql = "Select sum(u.duration)/count(distinct session_id) avertime from   ". $this->db->dbprefix('clientusinglog')."  u
  join   ". $this->db->dbprefix('channel_product')."   cp on u.appkey = cp.productkey
 where date(u.start_millis) = '$dateTime' and cp.product_id = $productId ";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->avertime;
	}
	
	// 获取实时数据平均使用时常(今日）
	function getAverageUsingTimeByProductAndChannelAtRealTime($dateTime, $channelId) {
		$sql = "Select sum(u.duration)/count(distinct session_id) avertime from   ". $this->db->dbprefix('clientusinglog')."  u
		join    ". $this->db->dbprefix('channel_product')."  cp on u.appkey = cp.productkey
		where date(u.start_millis) = '$dateTime' and cp.cp_id = $channelId ";
		$query = $this->db->query ( $sql );
		return $query->first_row ()->avertime;
	}
	
	// 获取指定时间段内产品平均使用时长
	function getAverageUsingTimeByPeriod($fromTime, $toTime, $productId) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "Select d.datevalue,sum(u.duration)/count(distinct session_id) avertime
		 from   ". $dwdb->dbprefix('fact_usinglog')."  u,   ". $dwdb->dbprefix('dim_product')."  p,   ". $dwdb->dbprefix('dim_date')."  d where u.product_sk = p.product_sk
		 and u.date_sk=d.date_sk and d.datevalue between '$fromTime' and '$toTime' and p.product_id = $productId 
		 group by d.datevalue order by d.datevalue;";
		
		$query = $dwdb->query ( $sql );
		return $query->first_row ()->avertime;
	}

}

?>