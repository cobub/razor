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
class newusermodel extends CI_Model{
	function __construct()
	{
		$this->load->model("common");
		$this->load->model('product/productmodel','product');
		$this->load->model('product/usinganalyzemodel','usinganalyzemodel');
	}
	
	//According to the time period, the application ID to get the total number of users
	function getSumUsersByDay($fromTime,$toTime,$productId)
	{
			
	}
	//Get all data access trend
	function getAlldataofVisittrends($fromtime,$totime,$userId){
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select date(d.datevalue) date,
					ifnull(sum(newusers),0) newusers,
					ifnull(sum(startusers),0) startusers,
					ifnull(sum(sessions),0) sessions
				from  (select date_sk,datevalue from  ".$dwdb->dbprefix('dim_date')."   
				where datevalue between '$fromtime' and '$totime') d left join ".$dwdb->dbprefix('dim_product')." p 
				on p.userid=$userId and p.product_active=1 and p.channel_active=1 and p.version_active=1
				 left join  ".$dwdb->dbprefix('sum_basic_all')." s on p.product_sk = s.product_sk and d.date_sk = s.date_sk
						group by d.datevalue order by d.datevalue;";
		$query = $dwdb->query($sql);
		$ret = array();
		if($query!=null&& $query->num_rows()>0){
			
			foreach($query->result() as $row)
			{
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
	
	
	//According to the time period, the application ID to get the total number of active users
	function getActiveUsersByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order="ASC")
	{
		$from = ($pageIndex*$pageNums);
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(startusers),0) totalaccess 
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join   ".$dwdb->dbprefix('dim_product')."   p on  p.product_id = $productId 
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from    ".$dwdb->dbprefix('dim_date')."   where datevalue between '$fromTime' and '$toTime' order by date_sk $order) d on s.date_sk = d.date_sk group by d.datevalue limit $from,$pageNums;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//According to the time period, the application ID to obtain the cumulative total number of users
	function getTotalUsersByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order="ASC")
	{
		$from = ($pageIndex*$pageNums);
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(allusers),0) totalaccess
		from    ".$dwdb->dbprefix('sum_basic_all')."  s inner join    ".$dwdb->dbprefix('dim_product')."  p on  p.product_id = $productId 
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."   where datevalue between '$fromTime' and '$toTime' order by date_sk $order) d on s.date_sk = d.date_sk group by d.datevalue limit $from,$pageNums;";
		$query = $dwdb->query($sql);
		$ret = array();
		if($query!=null && $query->num_rows()>0)
		{
			$preTotal = 0;
			foreach($query->result() as $row)
			{
				$record = array();
				$record["datevalue"] = $row->datevalue;
				if($row->totalaccess == null || $row->totalaccess == 'null')
				{
					$record["totalaccess"] = $preTotal;
				}
				else 
				{
					$preTotal = $row->totalaccess;
					$record["totalaccess"] = $preTotal;
				}
				
				array_push($ret, $record);
			}
		}
		return $ret;
	}
	
	
	//According to the time period, the user ID for the number of active users
	function getActiveUsersByUserID($fromTime,$toTime,$userId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select dd.startdate,ifnull(totalaccess,0) totalusers from (select distinct startdate
		from   ".$dwdb->dbprefix('dim_date')."   where startdate between '$fromTime' and '$toTime') dd left join
		(select d.startdate,count(distinct(deviceidentifier)) totalaccess from    ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,    ".$dwdb->dbprefix('dim_date')."  d,    ".$dwdb->dbprefix('dim_product')."  p
		where d.startdate between '$fromTime' and '$toTime' and d.date_sk=f.date_sk and p.product_sk=f.product_sk
		and p.product_id in (select product_id from    ".$dwdb->dbprefix('dim_product')."  where product_userid = $userId) group by d.startdate order by d.startdate) ddd on ddd.startdate = dd.startdate;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	
	//According to the time period, the application ID, channel ID to get the total number of active users
	function getActiveUsersByDayAndChinnel($fromTime,$toTime,$productId,$channelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select dd.startdate,ifnull(totalaccess,0) as totalusers from (select distinct startdate 
		 from    ".$dwdb->dbprefix('dim_date')."  where startdate between '$fromTime' and '$toTime') dd left join
 		(select d.startdate,count(distinct(deviceidentifier)) totalaccess from    ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,   ".$dwdb->dbprefix('dim_date')."  d,    ".$dwdb->dbprefix('dim_product')."  p
 		 where d.startdate between '$fromTime' and '$toTime' and d.date_sk=f.date_sk and p.product_sk=f.product_sk 
 		 and p.product_id=$productId and p.channel_id=$channelId group by d.startdate order by d.startdate) ddd on ddd.startdate = dd.startdate;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//According to the time period, the product ID for a period of time new users start times accurate to daily
	function getTotalStartUserByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order='ASC')
	{
		$from = $pageIndex*$pageNums;
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(sessions),0) totalaccess
		from   ".$dwdb->dbprefix('sum_basic_all')."   s inner join    ".$dwdb->dbprefix('dim_product')."  p on  p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."   where datevalue between '$fromTime' and '$toTime' order by date_sk $order) d on s.date_sk = d.date_sk group by d.datevalue limit $from,$pageNums;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	
	//According to the time period, the user ID to obtain the number of the period of time the user starts accurate to daily
	function getTotalStartUserByUserId($fromTime,$toTime,$userId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select dd.startdate,ifnull(totalaccess,0) totalusers from (select distinct startdate
		from   ".$dwdb->dbprefix('dim_date')."  where startdate between '$fromTime' and '$toTime') dd left join
		(select d.startdate,count(*) totalaccess from    ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,  ".$dwdb->dbprefix('dim_date')."  d,   ".$dwdb->dbprefix('dim_product')."  p
		where d.startdate between '$fromTime' and '$toTime' and d.date_sk=f.date_sk and p.product_sk=f.product_sk
		and p.product_id in (select product_id from  ".$dwdb->dbprefix('dim_product')."   where product_userid = $userId) group by d.startdate order by d.startdate) ddd on ddd.startdate = dd.startdate;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//According to the time period, the product ID, channel ID to obtain the number of the period of time the user starts and accurate to the daily
	function getTotalStartUserByDayAndChannel($fromTime,$toTime,$productId,$channelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select dd.startdate,ifnull(totalaccess,0) as totalusers from (select distinct startdate 
		 from   ".$dwdb->dbprefix('dim_date')."  where startdate between '$fromTime' and '$toTime') dd left join
 		(select d.startdate,count(*) totalaccess from    ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,  ".$dwdb->dbprefix('dim_date')."  d,   ".$dwdb->dbprefix('dim_product')."  p
 		 where d.startdate between '$fromTime' and '$toTime' and d.date_sk=f.date_sk and p.product_sk=f.product_sk 
 		 and p.product_id=$productId and p.channel_id=$channelId group by d.startdate order by d.startdate) ddd on ddd.startdate = dd.startdate;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//Get time period the number of new users according to the time period, the product ID, accurate to daily
	function getNewUserByDay($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM,$order='ASC')
	{
		$from = $pageIndex*$pageNums;
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(newusers),0) totalaccess 
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join   ".$dwdb->dbprefix('dim_product')."  p on  p.product_id = $productId 
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."   where datevalue between '$fromTime' and '$toTime' order by date_sk $order) d on s.date_sk = d.date_sk group by d.datevalue limit $from,$pageNums;";
		log_message('error',$sql);
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//Get period the number of new users in the user according to the time period, the user ID, accurate to daily
	function getNewUsersByUserId($fromTime,$toTime,$userId)
	{	
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.startdate, ifnull(sum(h.newusers),0) totalusers
 				from (select distinct startdate from    ".$dwdb->dbprefix('dim_date')."  where startdate
 				between '$fromTime' and '$toTime') d left join ".$dwdb->dbprefix('history_newusers_day_hour')." h
 				on h.newdate = d.startdate and h.product_id
				in (select product_id from   ".$dwdb->dbprefix('dim_product')."   where product_userid = $userId) group by d.startdate;";
		log_message('error',$sql);
		$query = $dwdb->query($sql);
		return $query; 
	}
	
	//According to the time period, the product ID, channel ID to obtain a period of time the number of new users, accurate to daily
	function getNewUserByDayAndChannelId($fromTime,$toTime,$productId,$chinnelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.startdate, ifnull(sum(h.newusers),0) totalusers from (select distinct startdate from    ".$dwdb->dbprefix('dim_date')."  where startdate between '$fromTime' and '$toTime') d left join   ".$dwdb->dbprefix('history_newusers_day_hour')."   h
		on h.newdate = d.startdate and h.product_id=$productId and h.channel_id=$chinnelId group by d.startdate;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	
	//Get 24-hour segments new user statistics based on the time period and the product ID
	function getNewUserHistoryBy24Hour($fromTime,$toTime,$productId)
	{
		$dwdb = $this->load->database('dw',TRUE);   
		$sql = "select h.hour,ifnull(sum(newusers),0) count from   ".$dwdb->dbprefix('dim_date')."   d inner join 
	  ".$dwdb->dbprefix('sum_basic_byhour')." 	 s on d.datevalue between '$fromTime' and '$toTime' and d.date_sk = s.date_sk inner join ".$dwdb->dbprefix('dim_product')." p on p.product_id = $productId 
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join ".$dwdb->dbprefix('hour24')." h on h.hour=s.hour_sk group by h.hour order by h.hour;";
		$query = $dwdb->query($sql);  
		return $query;
	}
	
	//For 24 hours according to the time period, the product ID, channel ID segmented user statistics
	function getNewUserHistoryBy24HourAndChannel($fromTime,$toTime,$productId,$channelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select hour24.hour,ifnull(sum(newusers),0) totalusers from   ".$dwdb->dbprefix('history_newusers_day_hour')."    h right join
	  ".$dwdb->dbprefix('hour24')." 	 on   ".$dwdb->dbprefix('hour24').".hour=h.hour and h.newdate between '$fromTime' and '$toTime' and h.product_id=$productId and h.channel_id= $channelId
		group  by   ".$dwdb->dbprefix('hour24').".hour order by   ".$dwdb->dbprefix('hour24').".hour;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//get report Detail data
	function getallUserData($fromTime,$toTime){
		
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		//$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(sessions),0) sessions,ifnull(sum(startusers),0) startusers,
		ifnull(sum(newusers),0) newusers,ifnull(sum(usingtime),0) usingtime,ifnull(sum(allusers),0) allusers
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join  ".$dwdb->dbprefix('dim_product')."   p on  p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime' 
		) d on s.date_sk = d.date_sk group by d.datevalue order by d.datevalue ASC;";	
			
		$query = $dwdb->query($sql);
		return $query;
		
	}
	
	function getallUserDataBy($fromTime,$toTime,$productid){
	
		$productId= $productid;
		//$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
	
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(sessions),0) sessions,ifnull(sum(startusers),0) startusers,
		ifnull(sum(newusers),0) newusers,ifnull(sum(usingtime),0) usingtime,ifnull(sum(allusers),0) allusers
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join  ".$dwdb->dbprefix('dim_product')."   p on  p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime'
		) d on s.date_sk = d.date_sk group by d.datevalue order by d.datevalue ASC;";
					
		$query = $dwdb->query($sql);
		return $query;
	
	}
	
	function getallUserDataByPid($fromTime,$toTime,$productId){
		//$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(sessions),0) sessions,ifnull(sum(startusers),0) startusers,
		ifnull(sum(newusers),0) newusers,ifnull(sum(usingtime),0) usingtime,ifnull(sum(allusers),0) allusers
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join  ".$dwdb->dbprefix('dim_product')."   p on  p.product_id = $productId
			and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
			select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime'
			) d on s.date_sk = d.date_sk group by d.datevalue order by d.datevalue ASC;";
				
			$query = $dwdb->query($sql);
			return $query;
	
	}
	
	// get detailed data
	function getDetailUserData($fromTime,$toTime){
	
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		//$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
	
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(sessions),0) sessions,ifnull(sum(startusers),0) startusers,
		ifnull(sum(newusers),0) newusers,ifnull(sum(usingtime),0) usingtime,ifnull(sum(allusers),0) allusers
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join  ".$dwdb->dbprefix('dim_product')."   p on  p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime'
		) d on s.date_sk = d.date_sk group by d.datevalue order by d.datevalue 	DESC;";
					
		$query = $dwdb->query($sql);
		return $query;
	
	}
	
	//Pages get detailed data
	function getDetailUserDataByDay($fromTime,$toTime)
	{			
		$list = array();	
		$query = $this->getDetailUserData($fromTime, $toTime);
		$activeUserRow = $query->first_row();		 
		for($i=0;$i<$query->num_rows();$i++)
		{			   
		 	$fRow = array();
		 	$fRow["date"] = substr($activeUserRow->datevalue,0,10);
		 	$fRow['active'] = $activeUserRow->startusers;
		 	$fRow['start'] = $activeUserRow->sessions;
		 	$fRow['newuser'] = $activeUserRow->newusers;
		 	$fRow['total'] = $activeUserRow->allusers;
		 	$fRow['aver'] = $activeUserRow->usingtime; 
		 	$activeUserRow = $query->next_row();
		 	array_push($list,$fRow);
		}
		return $list;
	}
}
?>