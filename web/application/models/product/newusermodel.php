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
		$this->load->model('product/productmodel','product');
		$this->load->model('product/usinganalyzemodel','usinganalyzemodel');
	}
	
	//根据时间段，应用ID获取用户总数
	function getSumUsersByDay($fromTime,$toTime,$productId)
	{
			
	}
	//获取访问趋势里的所有数据
	function getAlldataofVisittrends($fromtime,$totime,$userId){
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select date(d.datevalue) date,
					ifnull(sum(newusers),0) newusers,
					ifnull(sum(startusers),0) startusers,
					ifnull(sum(sessions),0) sessions
				from  (select date_sk,datevalue from  ".$dwdb->dbprefix('dim_date')."   where datevalue between '$fromtime' and '$totime') d left join ".$dwdb->dbprefix('dim_product')." p on p.userid=$userId and p.product_active=1 and p.channel_active=1 and p.version_active=1 left join  ".$dwdb->dbprefix('sum_basic_all')." s on p.product_sk = s.product_sk and d.date_sk = s.date_sk
						group by d.datevalue
						order by d.datevalue;";
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
	
	
	//根据时间段，应用ID获取活跃用户总数
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
	
	//根据时间段，应用ID获取累计用户总数
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
	
	
	//根据时间段，用户ID获取活跃用户数
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
	
	
	//根据时间段，应用ID,渠道ID获取活跃用户总数
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
	
	//根据时间段，产品ID获取时间段内新用户启动次数，精确到每天
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
	
	
	//根据时间段，用户ID获取时间段内用户启动次数，精确到每天
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
	
	//根据时间段，产品ID,渠道ID获取时间段内用户启动次数，精确到每天
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
	
	//根据时间段，产品ID获取时间段内新用户数量，精确到每天
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
	
	//根据时间段，用户ID获取该用户时间段内新用户数量，精确到每天
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
	
	//根据时间段，产品ID，渠道ID获取时间段内新用户数量，精确到每天
	function getNewUserByDayAndChannelId($fromTime,$toTime,$productId,$chinnelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.startdate, ifnull(sum(h.newusers),0) totalusers from (select distinct startdate from    ".$dwdb->dbprefix('dim_date')."  where startdate between '$fromTime' and '$toTime') d left join   ".$dwdb->dbprefix('history_newusers_day_hour')."   h
		on h.newdate = d.startdate and h.product_id=$productId and h.channel_id=$chinnelId group by d.startdate;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	
	//根据时间段和产品ID获取24小时分段新用户统计数据
	function getNewUserHistoryBy24Hour($fromTime,$toTime,$productId)
	{
		$dwdb = $this->load->database('dw',TRUE);   
		$sql = "select h.hour,ifnull(sum(newusers),0) count from   ".$dwdb->dbprefix('dim_date')."   d inner join 
	  ".$dwdb->dbprefix('sum_basic_byhour')." 	 s on d.datevalue between '$fromTime' and '$toTime' and d.date_sk = s.date_sk inner join ".$dwdb->dbprefix('dim_product')." p on p.product_id = $productId 
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join ".$dwdb->dbprefix('hour24')." h on h.hour=s.hour_sk group by h.hour order by h.hour;";
		$query = $dwdb->query($sql);  
		return $query;
	}
	
	//根据时间段，产品ID，渠道ID获取24小时分段用户统计数据
	function getNewUserHistoryBy24HourAndChannel($fromTime,$toTime,$productId,$channelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select hour24.hour,ifnull(sum(newusers),0) totalusers from   ".$dwdb->dbprefix('history_newusers_day_hour')."    h right join
	  ".$dwdb->dbprefix('hour24')." 	 on   ".$dwdb->dbprefix('hour24').".hour=h.hour and h.newdate between '$fromTime' and '$toTime' and h.product_id=$productId and h.channel_id= $channelId
		group  by   ".$dwdb->dbprefix('hour24').".hour order by   ".$dwdb->dbprefix('hour24').".hour;";
		$query = $dwdb->query($sql);
		return $query;
	}
	
	//获得明细数据
	function getallUserData($fromTime,$toTime,$productId,$pageIndex=0,$pageNums=RECORD_NUM){
		$from = $pageIndex*$pageNums;
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select d.datevalue,ifnull(sum(sessions),0) sessions,ifnull(sum(startusers),0) startusers,
		ifnull(sum(newusers),0) newusers,ifnull(sum(usingtime),0) usingtime,ifnull(sum(allusers),0) allusers
		from   ".$dwdb->dbprefix('sum_basic_all')."  s inner join  ".$dwdb->dbprefix('dim_product')."   p on  p.product_id = $productId
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join (
		select date_sk, datevalue from   ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime' order by date_sk) d on s.date_sk = d.date_sk group by d.datevalue limit $from,$pageNums ;";
		$query = $dwdb->query($sql);
		return $query;
		
	}
	
	
	//根据页数 获得详细数据
	function getDetailUserDataByDay($currentProduct,$pageIndex)
	{
		$list = array();
		$fromTime = $currentProduct->date;		
		$productId=$currentProduct->id;
		//$fromTime = date("Y-m-d",strtotime("-90 day"));
		$toTime = date("Y-m-d",strtotime("-1 day"));
		$query = $this->getallUserData($fromTime, $toTime, $productId, $pageIndex,PAGE_NUMS);
		$activeUserRow = $query->first_row();		 
		for($i=0;$i<$query->num_rows();$i++)
		{		   
		 	$fRow = array();
		 	$fRow["date"] = substr($activeUserRow->datevalue,0,10);
		 	$fRow['active'] = $activeUserRow->startusers;
		 	$fRow['start'] = $activeUserRow->sessions;
		 	$fRow['new'] = $activeUserRow->newusers;
		 	$fRow['total'] = $activeUserRow->allusers;
		 	$fRow['aver'] = $activeUserRow->usingtime; 
		 	$activeUserRow = $query->next_row();
		 	array_push($list,$fRow);
		}
		return $list;
	}
	//导出报表中的详细数据(新)
	function getexportdetaildatas($currentProduct){
		$pageIndex=0;
		$list = array();
		$fromTime = $currentProduct->date;
		$productId=$currentProduct->id;
		//$fromTime = date("Y-m-d",strtotime("-90 day"));
		$toTime = date("Y-m-d",strtotime("-1 day"));
		$query = $this->getallUserData($fromTime, $toTime, $productId, $pageIndex,RECORD_NUM);
		$activeUserRow = $query->first_row();
		for($i=0;$i<$query->num_rows();$i++)
		{
		 	$fRow = array();
		 	$fRow["date"] = substr($activeUserRow->datevalue,0,10);
		 	$fRow['new'] = $activeUserRow->newusers;
		 	$fRow['total'] = $activeUserRow->allusers;
		 	$fRow['active'] = $activeUserRow->startusers;
		 	$fRow['start'] = $activeUserRow->sessions;
		 	$fRow['aver'] = $activeUserRow->usingtime; 
		 	$activeUserRow = $query->next_row();
		 	array_push($list,$fRow);
		}
		return $list;
	}
    //导出报表中的详细数据
    function getexportdetaildata($productId)
    { 
    	$pageIndex=0;
    	$list = array();
		$fromTime = $this->product->getReportStartDateByProjectId($productId);
		$fromTime = date("Y-m-d",strtotime("-90 day"));
		$toTime = date("Y-m-d",strtotime("-1 day"));		
        $queryActiveUser = $this->getActiveUsersByDay($fromTime, $toTime, $productId,$pageIndex,RECORD_NUM,'DESC');
		$queryTotalStart = $this->getTotalStartUserByDay($fromTime, $toTime, $productId,$pageIndex,RECORD_NUM,'DESC');
		$queryTotalUsers = $this->getTotalUsersByDay($fromTime, $toTime, $productId,$pageIndex,RECORD_NUM,'DESC');
		$queryNewUser = $this->getNewUserByDay($fromTime, $toTime, $productId,$pageIndex,RECORD_NUM,'DESC');
		$queryAverUsingTime = $this->usinganalyzemodel->getUsingTimeByDay($fromTime, $toTime, $productId,$pageIndex,RECORD_NUM,'DESC');
		for($i=0;$i<$queryActiveUser->num_rows();$i++)
		{
		 	$fRow = array();
		 	$activeUserRow = $queryActiveUser->next_row();
		 	$totalStartRow = $queryTotalStart->next_row();
		 	$newUserRow = $queryNewUser->next_row();
		 	$averTimeRow = $queryAverUsingTime->next_row();
		 	$totalUserRow = $queryTotalUsers[$i];
		 	
		 	$fRow["date"] = $activeUserRow->startdate;
		 	$fRow['new'] = $newUserRow->totalaccess;
		 	$fRow['total'] = $totalUserRow['totalaccess'];
		 	$fRow['active'] = $activeUserRow->totalaccess;
		 	$fRow['start'] = $totalStartRow->totalaccess;		 	
		 	$fRow['aver'] = round($averTimeRow->totalaccess,2);
		 	array_push($list,$fRow);
		}    	
		return $list;
    } 
}
?>