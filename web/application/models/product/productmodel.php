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
class ProductModel extends CI_Model
{
	function __construct()
	{
		$this->load->database();
		$this->load->model("product/productanalyzemodel",'productanalyzemodel');
	}

	//	统计今日和昨日的各渠道 的newusers(新用户), startusers（启动用户）, allusers（总用户）
	function getAnalyzeDataByDateAndProductID($date,$product_id){
		return $this->productanalyzemodel->getAllAnalyzeData($date,$product_id);
	}
	//统计活跃数
	function  getActiveUsersNum($startDate,$endDate,$product_id){
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select tt.channel_id,
		tt.channel_name,
		ifnull(t.startusers,0) startusers
		from (
		select
		p.channel_id,
		p.channel_name,
		sum(startusers) startusers
		from   ".$dwdb->dbprefix('sum_basic_all')." s,
		".$dwdb->dbprefix('dim_date')." d,
		".$dwdb->dbprefix('dim_product')." p
		where  d.datevalue between '$startDate' and '$endDate'
		and d.date_sk = s.date_sk
		and p.product_id = $product_id
		and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.channel_active=1
		group by p.channel_id,p.channel_name) t
		right join (
		select distinct
		pp.channel_id,
		pp.channel_name
		from ".$dwdb->dbprefix('dim_product')."
		pp
		where pp.product_id = $product_id and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) tt
		on tt.channel_id = t.channel_id
		order by tt.channel_id;
		";
			
		$query = $dwdb->query($sql);
		return $query;
			
	}
	//	分时统计日期段内的各渠道 的newusers(新用户), startusers（启动用户）, allusers（总用户）,usingtime(使用时长)
	function  getAllMarketData($channel_id,$start,$end,$timePhase){
	$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));
					
		if($timePhase == "7day")
		{
		$title = lang('producttitleinfo_act7days');
		$fromTime = date('Y-m-d',strtotime("-7 day"));
		}
					
		if($timePhase == "1month")
		{
		$title = lang('producttitleinfo_actmonth');
		$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
			
		if($timePhase == "3month")
		{
		$fromTime = date("Y-m-d",strtotime("-90 day"));
		$title = lang('producttitleinfo_act3month');
			
		}
		if($timePhase == "all")
		{
		$title = lang('producttitleinfo_actall');
		$fromTime = 'all';
		}
			
		if($timePhase == 'any')
		{
				$title = lang('producttitleinfo_actanytime');
		$fromTime = $start;
		$toTime = $end;
		}
			
		$productId = $currentProduct->id;
		$fromTime = $this->getReportStartDate($currentProduct,$fromTime);

		$dwdb = $this->load->database ( 'dw', TRUE );
			
			
		$channelname= $this->getMarketNameById($channel_id);
		$sql = "select
		d.datevalue,
		p.channel_id,
		p.channel_name,
		ifnull(sum(startusers),0) startusers,
		ifnull(sum(newusers),0) newusers,
		ifnull(sum(allusers),0) allusers,
		ifnull(sum(usingtime),0) usingtime
		from  (select date_sk,datevalue from ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime') d cross join (
		select distinct
		pp.channel_id,
		pp.channel_name,
		pp.product_sk
		from ".$dwdb->dbprefix('dim_product')."
		pp
		where pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) p
		left join ".$dwdb->dbprefix('sum_basic_all')." s  on d.date_sk = s.date_sk
					
				and s.product_sk = p.product_sk
				group by d.datevalue,p.channel_id,p.channel_name
				order by d.datevalue,p.channel_id;";
				$query = $dwdb->query ( $sql );
				if ($query != null && $query->num_rows > 0) {
					
				$arr = $query->result_array ();
					
				$content_arr = array ();
				for($i = 0; $i < count ( $arr ); $i ++) {
				$row = $arr [$i];
				$channel_name = $row ['channel_name'];
				$allkey = array_keys ( $content_arr );
					if (! in_array ( $channel_name, $allkey ))
					$content_arr [$channel_name] = array ();
					$tmp = array ();
					$tmp ['startusers'] = $row ['startusers'];
					$tmp ['allusers'] = $row ['allusers'];
					$tmp ['newusers'] = $row ['newusers'];
					$tmp['datevalue'] = $row['datevalue'];
					$tmp['usingtime'] = $row['usingtime'];
						
					array_push ( $content_arr [$channel_name], $tmp );

				}
				$all_version_name = array_keys($content_arr);
				$ret['content'] = $content_arr;
					
				}
				$ret ['title'] = $title;

				return $ret;
					
				}
				//	分时统计日期段内的各渠道 的周/月活跃数
				function getActiveNumber($channel_id,$start,$end,$timePhase,$type){
				$currentProduct = $this->common->getCurrentProduct();
				$toTime = date('Y-m-d',time());
				$fromTime = date('Y-m-d',strtotime("-7 day"));

				if($timePhase == "7day")
				{
					$title = lang('producttitleinfo_act7days');
					$fromTime = date('Y-m-d',strtotime("-7 day"));
				}

					if($timePhase == "1month")
					{
					$title = lang('producttitleinfo_actmonth');
					$fromTime = date("Y-m-d",strtotime("-30 day"));
					}

					if($timePhase == "3month")
					{
						$fromTime = date("Y-m-d",strtotime("-90 day"));
						$title = lang('producttitleinfo_act3month');
								
						}
						if($timePhase == "all")
						{
						$title = lang('producttitleinfo_actall');
						$fromTime = 'all';
					}

					if($timePhase == 'any')
					{
							$title = lang('producttitleinfo_actanytime');
							$fromTime = $start;
								$toTime = $end;
							}

							$productId = $currentProduct->id;
							$fromTime = $this->getReportStartDate($currentProduct,$fromTime);
								
							$dwdb = $this->load->database ( 'dw', TRUE );


							$channelname= $this->getMarketNameById($channel_id);
								
							if($type=="weekactive"){
							$day=-6;
}
if($type=="monthactive"){
$day=-30;
}
$sql = "select
d.datevalue,
p.channel_id,
p.channel_name,
(select ifnull(sum(startusers),0) from ".$dwdb->dbprefix('sum_basic_all')."  ss,".$dwdb->dbprefix('dim_date')."  dd where ss.date_sk = dd.date_sk and dd.datevalue between date_add(d.datevalue,interval $day day) and d.datevalue and ss.product_sk= p.product_sk) startusers
from  (select date_sk,datevalue from ".$dwdb->dbprefix('dim_date')."  where datevalue between '$fromTime' and '$toTime') d cross join (
		select distinct
		pp.channel_id,
		pp.channel_name,
		pp.product_sk
		from	".$dwdb->dbprefix('dim_product')."
		pp
		where pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 ) p
		group by d.datevalue,p.channel_id,p.channel_name
		order by d.datevalue,p.channel_id;
		";
			
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows > 0) {
			
		$arr = $query->result_array ();
	
$content_arr = array ();
for($i = 0; $i < count ( $arr ); $i ++) {
$row = $arr [$i];
	$channel_name = $row ['channel_name'];
	$allkey = array_keys ( $content_arr );
	if (! in_array ( $channel_name, $allkey ))
		$content_arr [$channel_name] = array ();
		$tmp = array ();
		$tmp ['startusers'] = $row ['startusers'];
		$tmp['datevalue'] = $row['datevalue'];
		array_push ( $content_arr [$channel_name], $tmp );

	}
	$all_version_name = array_keys($content_arr);
	$ret['content'] = $content_arr;
		
	}
	$ret ['title'] = $title;

	return $ret;
	}




	//根据产品id，产品渠道,和时间  获取今日新增用户
	function getNewUser($productId, $markets,$dataTime) {


	$newUserArray = array();
	foreach ($markets->result() as $row)
	{
	$chanelId = $row->channel_id;

	$count = $this->productanalyzemodel->getNewUsersCountByChannel($productId,$chanelId,$dataTime);
	array_push($newUserArray, $count);

}
return $newUserArray;
}

//根据产品id，产品渠道,和时间  获取昨日新增用户
function getNewUserYestoday($productId, $markets,$dataTime) {


$newUserArray = array();
foreach ($markets->result() as $row)
{
$chanelId = $row->channel_id;

$count = $this->productanalyzemodel->getYestodayNewUserCountByChannel($dataTime,$productId,$chanelId);
array_push($newUserArray, $count);

}
return $newUserArray;
}


//根据产品id，产品渠道,和时间  获取活跃用户
function getActiveUser($productId, $markets,$dataTime) {


$activeUserArray = array();
foreach ($markets->result() as $row)
{
$channelId = $row->channel_id;
$count = $this->productanalyzemodel->getUserStartUsersCountByChannel($productId,$channelId,$dataTime);
array_push($activeUserArray,$count);

}
return $activeUserArray;
}

//根据产品id，产品渠道  获取所有用户
function getUserCountByChannel($productId, $markets)
{
$userCountArray = array();
foreach ($markets->result() as $row)
{
$channelId = $row->channel_id;
$count = $this->productanalyzemodel->getTotalUserByChannel($productId,$channelId);
array_push($userCountArray,$count);

}
return $userCountArray;

}



//根据产品id，产品渠道,和时间  获取活跃用户率
function getActiveUserPercent($productId, $markets, $from, $to) {

$dwdb = $this->load->database ( 'dw', TRUE );
$activeUserArray = array ();
foreach ( $markets->result () as $row ) {
$chanelId = $row->channel_id;
$sql = "select   ppp.channel_id,ppp.product_channel,ifnull(t.usercount,0) percentage
from(select   p.channel_id, count(distinct f.deviceidentifier)
/ (select count(distinct ff.deviceidentifier)
from   ".$dwdb->dbprefix('fact_activeusers_clientdata')."   ff,
".$dwdb->dbprefix('dim_date')."  dd, ".$dwdb->dbprefix('dim_product')."  dp
where  ff.date_sk = dd.date_sk
and ff.product_sk = dp.product_sk
and dp.product_id = $productId
and dp.channel_id = p.channel_id) usercount
from   ".$dwdb->dbprefix('fact_activeusers_clientdata')."   f,
".$dwdb->dbprefix('dim_date')."  d,
".$dwdb->dbprefix('dim_product')."  p
		where    f.date_sk = d.date_sk
		and f.product_sk = p.product_sk
		and d.startdate between '" . $from . "' and '" . $to . "'
		and p.product_id = $productId
		group by p.channel_id) t
		right join (select distinct pp.product_channel,
		pp.channel_id
		from   ".$dwdb->dbprefix('dim_product')."  pp
		where pp.channel_id = $chanelId
) ppp
		on ppp.channel_id = t.channel_id
		order by ppp.channel_id;
			
		";
		$query = $dwdb->query ( $sql );
		if ($query->num_rows > 0)
		array_push ( $activeUserArray, $query->first_row ()->percentage );
		else
	array_push ( $activeUserArray, 0 );

}
		return $activeUserArray;
}

		//时段内新增
		function getNewUserByTimePhase($productId, $markets, $from, $to)
		{
		$dwdb = $this->load->database ( 'dw', TRUE );
				$activeUserArray = array ();
				foreach ( $markets->result () as $row ) {
				$chanelId = $row->channel_id;
				$sql = "select ppp.channel_id,



				ppp.product_channel,
				ifnull(t.usercount,0) activeusers
				from (select p.channel_id,
						count(distinct f.deviceidentifier)
						/ (select count(distinct ff.deviceidentifier)
						from ".$dwdb->dbprefix('fact_newusers_clientdata_by_product')."  ff,
						".$dwdb->dbprefix('dim_date')."  dd,
						".$dwdb->dbprefix('dim_product')."   dp
						where ff.date_sk = dd.date_sk
						and ff.product_sk = dp.product_sk
						and dp.product_id = $productId
and dp.channel_id = p.channel_id) usercount
from ".$dwdb->dbprefix('fact_newusers_clientdata_by_product')."  f,
".$dwdb->dbprefix('dim_date')."  d,
".$dwdb->dbprefix('dim_product')." p
where f.date_sk = d.date_sk
and f.product_sk = p.product_sk
and d.startdate between '".$from."' and '".$to."'
and p.product_id = $productId
group by p.channel_id) t
right join (select distinct pp.product_channel,
pp.channel_id


from ".$dwdb->dbprefix('dim_product')."  pp
where pp.channel_id = $chanelId) ppp



on ppp.channel_id = t.channel_id


order by ppp.channel_id;";
$query = $dwdb->query ( $sql );
if ($query->num_rows > 0)
array_push ( $activeUserArray, $query->first_row ()->activeusers );
else
array_push ( $activeUserArray, 0 );

}
return $activeUserArray;
 
}

function getProductListByPlatform($platformId,$userId,$today,$yestoday)
{
			
		$getIDsql="select p.id,p.name,f.name platform from ".$this->db->dbprefix('product')."  p,  ".$this->db->dbprefix('platform')."  f where p.product_platform = f.id and p.user_id=$userId and p.active = 1 order by p.id desc;";
			
		$dwdb = $this->load->database ( 'dw', TRUE );
		$getProductInfosqltoday = "select p.product_id,p.product_name,
		ifnull(sum(allusers),0) allusers,
				ifnull(sum(newusers),0) newusers,
						ifnull(sum(startusers),0) startusers,
						ifnull(sum(sessions),0) sessions,
						p.platform
						from  ".$dwdb->dbprefix('dim_product')."     p inner join  ".$dwdb->dbprefix('dim_date')."   d on p.userid=$userId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and d.datevalue='$today' left join ".$dwdb->dbprefix('sum_basic_all')."   s on
						p.product_sk = s.product_sk and d.date_sk = s.date_sk
						group by p.product_name,p.platform order by p.product_id desc;
						";
						$getProductInfosqlyeatoday = "select p.product_id,p.product_name,
						ifnull(sum(allusers),0) allusers,
						ifnull(sum(newusers),0) newusers,
						ifnull(sum(startusers),0) startusers,
								ifnull(sum(sessions),0) sessions,
								p.platform
								from  ".$dwdb->dbprefix('dim_product')."     p inner join  ".$dwdb->dbprefix('dim_date')."   d on p.userid=$userId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and d.datevalue='$yestoday' left join   ".$dwdb->dbprefix('sum_basic_all')."  s on
								p.product_sk = s.product_sk and d.date_sk = s.date_sk
								group by p.product_name,p.platform order by p.product_id desc;
								";
									
									
									
								$getIDsqlResult=	$this->db->query($getIDsql);
						$todayquery = $dwdb->query($getProductInfosqltoday);
						$yestadayquery= $dwdb->query($getProductInfosqlyeatoday);
						$appList = array();
						$flag=0;
						if($getIDsqlResult!=null && $getIDsqlResult->num_rows()>0){
						foreach ($getIDsqlResult->result() as $row){
								$app = array();
								$app['name'] = $row->name;
								$app['id']=$row->id;
								foreach ($todayquery->result() as $todaydata){

								foreach ($yestadayquery->result() as $yestodaydata){
										if($row->name==$todaydata->product_name && $todaydata->product_name==$yestodaydata->product_name){
										$app['newuser'] = $todaydata->newusers.'/'.$yestodaydata->newusers;
										$app['startcount'] = $todaydata->startusers.'/'.$yestodaydata->startusers;
										$app['startuser'] = $todaydata->sessions.'/'.$yestodaydata->sessions;
										$app['newUserYestoday'] = $yestodaydata->newusers;
											$app['startCountYestoday'] = $yestodaydata->startusers;
											$app['startUserYestoday'] = $yestodaydata->sessions;
												
											$app['newUserToday'] = $todaydata->newusers;
											$app['startCountToday'] = $todaydata->startusers;
											$app['startUserToday'] = $todaydata->sessions;
											$app['platform']= $todaydata->platform;
											$app['totaluser']=$todaydata->allusers;
											array_push($appList, $app);
											$flag=1;
											break;
											}
												
											}
											if($flag==1){
													break;
											}
												
											}
												
											if($flag==0){
											$app['newuser'] = '0'.'/'.'0';
											$app['startcount'] = '0'.'/'.'0';
											$app['startuser'] = '0'.'/'.'0';
											$app['newUserYestoday'] = '0'.'/'.'0';
											$app['startCountYestoday'] = '0'.'/'.'0';
											$app['startUserYestoday'] = '0'.'/'.'0';
												
											$app['newUserToday'] = 0;
											$app['startCountToday'] = 0;
											$app['startUserToday'] = 0;
											$app['platform']= $row->platform;
											$app['totaluser']=0;
											array_push($appList, $app);
											}
											$flag=0;
												
											}
											}
												
												
											return $appList;
											}

											function getAllProducts($userId)
											{
										$sql="select p.id,p.name,f.name platform from ".$this->db->dbprefix('product')."  p,  ".$this->db->dbprefix('platform')."  f where p.product_platform = f.id and p.user_id=$userId and p.active = 1 order by p.id desc;";
												
										//	$sql = "select * from ".$this->db->dbprefix('product')."  where user_id = $userId and active = 1";
											$query = $this->db->query($sql);
											return $query;
											}

											//根据应用和开始时间判断报表起始时间点，如果起始时间小于产品起始时间，则返回应用的起始时间
											//否则返回传入的起始时间
											function getReportStartDate($product,$fromTime)
											{
											if(date('Y-m-d',strtotime($product->date)) > date('Y-m-d',strtotime($fromTime)) )
												{
													return $product->date;
											}
											else
											{
													return $fromTime;
											}
											}

											function getReportStartDateByProjectId($productId)
											{
											$sql = "select min(date) as date from ".$this->db->dbprefix('channel_product')."  where product_id = $productId";
											$query = $this->db->query($sql);
											$toTime = date('Y-m-d',time());
											if($query!=null && $query->num_rows()>0)
											{
											$toTime = date('Y-m-d',strtotime($query->first_row()->date));
											}
											return $toTime;
											}

											//获取用户项目初始时间的最小值
											function getUserStartDate($userId,$fromTime)
											{
											$sql = "select min(date) as date from ".$this->db->dbprefix('product')."  where user_id = $userId";
											$query = $this->db->query($sql);
											if($query!=null && $query->num_rows()>0)
											{
												if(date('Y-m-d',strtotime($query->first_row()->date)) > date('Y-m-d',strtotime($fromTime)) )
												{
												return $query->first_row()->date;
											}
											else
											{
											return $fromTime;
											}
											}
											return $fromTime;
											}
											//增加产品
											function addProduct($userId,$appname,$channel,$platform,$category,$description)
											{
											//插入product表
											$appKey = md5($appname.$platform.$category.time());
												$data = array(
												'name'=>$appname,
												'description'=>$description,
												'date'=>date('Y-m-d H:i:s'),
												'user_id' => $userId,
												'channel_count'=>1,
												'product_key'=>md5($appname.$platform.$category.time()),
												'product_platform'=>$platform,
												'category'=>$category
												);
												$this->db->insert('product',$data);
													
												//插入channel_product表
												$product_id=$this->db->insert_id();
												$chanprod = array(
												'product_id'=>$product_id,
												'description'=>$description,
												'date'=>date('Y-m-d H:i:s'),
												'user_id' => $userId,
												'productkey'=>md5($appname.$platform.$category.time()),
												'channel_id'=>$channel
												);
												$this->db->insert('channel_product',$chanprod);
												$confi = array(
												'product_id'=>$product_id
												);
												$this->db->insert('config',$confi);
												return $appKey;
												}

												//插入产品渠道
												function addproductchannel($user_id,$product_id,$channel_id)
												{
													$data = array(
															'product_id'=>$product_id,
													'date'=>date('Y-m-d H:i:s'),
													'user_id' => $user_id,
													'productkey'=>md5($product_id.$channel_id.$user_id.time()),
													'channel_id'=>$channel_id
													);
													$this->db->insert('channel_product',$data);
													//更新产品表的渠道数
													$sql = "update ".$this->db->dbprefix('product')."  set channel_count = channel_count+1 where id = $product_id and user_id = $user_id";
													$this->db->query($sql);
												}

												//获得产品信息
												function getproductinfo($product_id)
												{
												$sql = "select pro.* ,p.name as platname from ".$this->db->dbprefix('product')." pro inner join ".$this->db->dbprefix('platform')."  p on  pro.product_platform=p.id where pro.id=$product_id ";
													$query = $this->db->query($sql);
															if($query!=null&&$query->num_rows()>0)
															{
															return $query->row_array();
												}
												return null;
												}
												//更新产品信息
												function updateproduct($appname,$category,$description,$product_id,$productkey)
												{

														$data = array(
																'name'=>$appname,
												'description'=>$description,
												'category'=>$category
												);

												$this->db->where('id', $product_id);
												$this->db->update('product', $data);
														
														
													$sql = "update ".$this->db->dbprefix('channel_product')."  set description ='$description' where product_id = $product_id and productkey = '$productkey'";
													$this->db->query($sql);
														
													}
													function getProductCategory()
													{
													$query = $this->db->get('product_category');
													return $query;
													}

													function getProductById($id)
													{
													$this->db->where('id',$id);
													$query = $this->db->get('product');
													if($query!=null && $query->num_rows()>0)
													{
													return $query->first_row();
													}
													}

													function deleteProduct($productId,$userId)
													{
															$sql = "update ".$this->db->dbprefix('product')."  set active = 0 where id = $productId and user_id = $userId";
															$this->db->query($sql);
																	$affect = $this->db->affected_rows();
																	if($affect>0)
																	{
																	return true;
													}
																	return false;
													}

																	function getStarterUserCountByTime($from,$to,$projectid)
																	{
																	$dwdb = $this->load->database ( 'dw', TRUE );
																	$sql="select h.hour,ifnull(sum(startusers),0) startusers,ifnull(sum(newusers),0) newusers
																	from ".$dwdb->dbprefix('dim_date')."  d inner join ".$dwdb->dbprefix('sum_basic_byhour')."
																	s on d.datevalue between '$from' and '$to' and d.date_sk = s.date_sk inner join ".$dwdb->dbprefix('dim_product')." p on p.product_id = $projectid and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 right join ".$dwdb->dbprefix('hour24')." h on h.hour=s.hour_sk group by h.hour order by h.hour";
																		
																	$query = $dwdb->query($sql);
																	if($query!=null && $query->num_rows()>0)
																	{
																		return $query;
																	}
																	else
																	{
																	return null;
																	}
																	}

																	function getBasicInfoByDate($productId,$date)
																	{
																	$this->db->query("call p_get_product_basic_info($productId,'$date')");
																	$query = $this->db->get('t_basic_info');
																	if($query!=null && $query->num_rows()>0)
																	{
																	return $query->first_row();
																	}
																	}

																	function getProductChanelById($id)
																	{
																	$sql = "select c.channel_name,c.channel_id from ".$this->db->dbprefix('channel_product')."  cp left join ".$this->db->dbprefix('channel')."  c on cp.channel_id = c.channel_id where cp.product_id = ".$id." and c.active=1";
																	$result = $this->db->query($sql);
																	return $result;
																	}

																	function getMarketData($market,$timePhase,$type,$start,$end)
																		{

																		if ($type == 'new')
																		return $this->getNewUserByProductAndChannelAndTime ( $market, $timePhase, $start, $end );
																		if ($type == 'active')
																				return $this->getActiveUserByProductAndChannelAndTime ( $market, $timePhase, $start, $end );
																				if ($type == 'startcount')
																						return $this->getStartCountByProductAndChannelAndTime ( $market, $timePhase, $start, $end );
																						if ($type == 'average')
																							return $this->getAverageTime($market, $timePhase, $start, $end);
																							if ($type == 'weekactive')
																								return $this->getWeeklyActivePercent($market, $timePhase, $start, $end);
																								if ($type == 'monthactive')
																									return $this->getMonthlyActivePercent($market, $timePhase, $start, $end);
}

																								function getNewUserByProductAndChannelAndTime($market,$timePhase,$start,$end)
																								{
																								$currentProduct = $this->common->getCurrentProduct();
																								$toTime = date('Y-m-d',time());
																								$fromTime = date('Y-m-d',strtotime("-7 day"));

																								if($timePhase == "7day")
																								{
																										$title = lang('producttitleinfo_new7days');
																										$fromTime = date('Y-m-d',strtotime("-7 day"));
																										}

																										if($timePhase == "1month")
																										{
																										$title = lang('producttitleinfo_newmonth');
																										$fromTime = date("Y-m-d",strtotime("-30 day"));
																										}

																										if($timePhase == "3month")
																										{
																										$fromTime = date("Y-m-d",strtotime("-90 day"));
																										$title = lang('producttitleinfo_new3month');
																											
																										}
																										if($timePhase == "all")
																										{
																										$title = lang('producttitleinfo_newall');
																										$fromTime = 'all';
																										}

																										if($timePhase == 'any')
																										{
																										$title = lang('producttitleinfo_newanytime');
																										$fromTime = $start;
																										$toTime = $end;
																										}

																										$productId = $currentProduct->id;
																										if ($market == 'default')
																										{
																										$query = $this->getProductChanelById($productId);
																										if($query!=null && $query->num_rows()>0)
																										{
																										$market =  $query->first_row()->channel_id;
																										}
																										else
																										$market = 0;

																										}
																										$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
																										$query = $this->newusermodel->getNewUserByDayAndChannelId($fromTime,$toTime,$currentProduct->id,$market);
																										$ret['market'] = $this->getMarketNameById($market);
																										$ret["title"] = $title;
																										$ret["content"] = $query->result_array();
																										$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
																										return $ret;

																										}


																										function getMarketNameById($makertId)
																											{
																											$sql = "select channel_name from  ".$this->db->dbprefix('channel')." where channel_id = $makertId";
																											$query = $this->db->query($sql);
																											if($query!=null && $query->num_rows()>0)
																											{
																												return $query->first_row()->channel_name;
																											}
																											return "";
																											}

																											function getActiveUserByProductAndChannelAndTime($market,$timePhase,$start,$end)
																											{
																											$currentProduct = $this->common->getCurrentProduct();
																											$toTime = date('Y-m-d',time());
																											$fromTime = date('Y-m-d',strtotime("-7 day"));

																											if($timePhase == "7day")
																											{
																											$title = lang('producttitleinfo_act7days');
																											$fromTime = date('Y-m-d',strtotime("-7 day"));
																											}

																											if($timePhase == "1month")
																						{
																						$title = lang('producttitleinfo_actmonth');
																						$fromTime = date("Y-m-d",strtotime("-30 day"));
																						}

																						if($timePhase == "3month")
																		{
																							$fromTime = date("Y-m-d",strtotime("-90 day"));
																						$title = lang('producttitleinfo_act3month');
																							
																						}
																						if($timePhase == "all")
																						{
																						$title = lang('producttitleinfo_actall');
																						$fromTime = 'all';
																						}

																						if($timePhase == 'any')
																						{
																						$title = lang('producttitleinfo_actanytime');
																						$fromTime = $start;
																						$toTime = $end;
																						}

																						$productId = $currentProduct->id;
																						if ($market == 'default')
																						{
																							
																						$query = $this->getProductChanelById($productId);
																						if($query!=null && $query->num_rows()>0)
																						{
																						$market =  $query->first_row()->channel_id;
																											}
																											else
																												$market = 0;

																										}
																										$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
																										$query = $this->newusermodel->getActiveUsersByDayAndChinnel($fromTime,$toTime,$currentProduct->id,$market);
																										$ret['market'] = $this->getMarketNameById($market);
																												$ret["title"] = $title;
																												$ret["content"] = $query->result_array();
																												$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
																												return $ret;
																										}

																										function getStartCountByProductAndChannelAndTime($market,$timePhase,$start,$end)
																										{
																										$currentProduct = $this->common->getCurrentProduct();
																										$toTime = date('Y-m-d',time());
																										$fromTime = date('Y-m-d',strtotime("-7 day"));

																										if($timePhase == "7day")
																											{
																											$title = lang('producttitleinfo_start7days');
																											$fromTime = date('Y-m-d',strtotime("-7 day"));
																										}

																										if($timePhase == "1month")
																										{
																										$title = lang('producttitleinfo_startmonth');
																										$fromTime = date("Y-m-d",strtotime("-30 day"));
																										}

																										if($timePhase == "3month")
																										{
																											$fromTime = date("Y-m-d",strtotime("-90 day"));
																											$title = lang('producttitleinfo_start3month');
																										}
																										if($timePhase == "all")
																										{
																											$title = lang('producttitleinfo_startall');
																											$fromTime = 'all';
																										}

																										if($timePhase == 'any')
																										{
																										$title = lang('producttitleinfo_startanytime');
																										$fromTime = $start;
																										$toTime = $end;
																										}

																										$productId = $currentProduct->id;
																										if ($market == 'default')
																										{
																											
																										$query = $this->getProductChanelById($productId);
																										if($query!=null && $query->num_rows()>0)
																										{
																										$market =  $query->first_row()->channel_id;
																										}
																										else
																										$market = 0;

																										}
																										$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
																											$query = $this->newusermodel->getTotalStartUserByDayAndChannel($fromTime,$toTime,$currentProduct->id,$market);
																											$ret['market'] = $this->getMarketNameById($market);
																											$ret["title"] = $title;
																											$ret["content"] = $query->result_array();
																											return $ret;
																										}

																										function getAverageTime($market,$timePhase,$start,$end)
																										{
																												$currentProduct = $this->common->getCurrentProduct();

																												$toTime = date('Y-m-d',time());
																												$fromTime = date('Y-m-d',strtotime("-7 day"));

																												if($timePhase == "7day")
																												{
																												$title = lang('producttitleinfo_time7days');
																												$fromTime = date('Y-m-d',strtotime("-7 day"));
																												}

																												if($timePhase == "1month")
																												{
																												$title = lang('producttitleinfo_timemonth');
																												$fromTime = date("Y-m-d",strtotime("-30 day"));
																												}

																												if($timePhase == "3month")
																												{
																												$fromTime = date("Y-m-d",strtotime("-90 day"));
																												$title =lang('producttitleinfo_time3month');
																													
																												}
																												if($timePhase == "all")
																										{
																										$title = lang('producttitleinfo_timeall');
																										$fromTime = 'all';
																												}

																												if($timePhase == 'any')
																												{
																												$title = lang('producttitleinfo_timeanytime');
																												$fromTime = $start;
																												$toTime = $end;
																												}

																												$productId = $currentProduct->id;
																												if ($market == 'default')
																												{
																													
																												$query = $this->getProductChanelById($productId);
																												if($query!=null && $query->num_rows()>0)
																													{
																														$market =  $query->first_row()->channel_id;
																												}
																														else
																														$market = 0;

																												}
																												$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
																														$query = $this->getAverageUsingTimeByChannelAndTime($fromTime, $toTime, $productId, $market);
																														$ret['market'] = $this->getMarketNameById($market);
																														$ret["title"] = $title;
																														$ret["content"] = $query->result_array();
																													return $ret;
																												}


																												function getWeeklyActivePercent($market,$timePhase,$start,$end)
																												{
																												$currentProduct = $this->common->getCurrentProduct();

																												$toTime = date('Y-m-d',time());
																												$fromTime = date('Y-m-d',strtotime("-7 day"));

																												if($timePhase == "7day")
																												{
																													$title = lang('producttitleinfo_percent7days');
																														$fromTime = date('Y-m-d',strtotime("-7 day"));
																												}

		if($timePhase == "1month")
		{
			$title = lang('producttitleinfo_percentmonth');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}

		if($timePhase == "3month")
		{
			$fromTime = date("Y-m-d",strtotime("-90 day"));
			$title =lang('producttitleinfo_percent3month');

		}
		if($timePhase == "all")
		{
			$title = lang('producttitleinfo_percentall');
			$fromTime = 'all';
		}

		if($timePhase == 'any')
		{
			$title = lang('producttitleinfo_percentanytime');
			$fromTime = $start;
			$toTime = $end;
		}

		$productId = $currentProduct->id;
		if ($market == 'default')
		{

			$query = $this->getProductChanelById($productId);
			if($query!=null && $query->num_rows()>0)
			{
				$market =  $query->first_row()->channel_id;
			}
			else
				$market = 0;

		}
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$query = $this->getWeekActiveUserPercent($fromTime, $toTime, $productId, $market);
		$ret['market'] = $this->getMarketNameById($market);
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		return $ret;
	}


	function getMonthlyActivePercent($market,$timePhase,$start,$end)
	{
		$currentProduct = $this->common->getCurrentProduct();

		$toTime = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));

		if($timePhase == "1month" || $timePhase == "7day")
		{
			$title = lang('producttitleinfo_percentmonth');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}

		if($timePhase == "3month")
		{
			$fromTime = date("Y-m-d",strtotime("-90 day"));
			$title =lang('producttitleinfo_percent3month');

		}
		if($timePhase == "all")
		{
			$title = lang('producttitleinfo_percentall');
			$fromTime = 'all';
		}

		if($timePhase == 'any')
		{
			$title = lang('producttitleinfo_percentanytime');
			$fromTime = $start;
			$toTime = $end;
		}

		$productId = $currentProduct->id;
		if ($market == 'default')
		{

			$query = $this->getProductChanelById($productId);
			if($query!=null && $query->num_rows()>0)
			{
				$market =  $query->first_row()->channel_id;
			}
			else
				$market = 0;

		}
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$query = $this->getMonthActiveUserPercent($fromTime, $toTime, $productId, $market);
		$ret['market'] = $this->getMarketNameById($market);
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		return $ret;
	}

	//获取指定时间段内产品平均使用时长
	function getAverageUsingTimeByChannelAndTime($fromTime,$toTime,$productId,$channelId)
     {
     	$dwdb = $this->load->database('dw',TRUE);
		$sql = "select ddd.datevalue startdate,
            ifnull(ppp.aver,0) totalusers from (select dd.datevalue from ".$dwdb->dbprefix('dim_date_day')."  dd
    		where dd.datevalue between '" . $fromTime . "' and '" . $toTime . "') ddd
    		left join (select d.datevalue,sum(f.duration)/ count(f.session_id) aver
    		from ".$dwdb->dbprefix('fact_usinglog_daily')."  f, ".$dwdb->dbprefix('dim_date_day')."  d,".$dwdb->dbprefix('dim_product')."  p
    		where f.date_sk = d.date_sk
        	and d.datevalue between '" . $fromTime . "' and '" . $toTime . "'
        	and f.product_sk = p.product_sk
        	and p.product_id = $productId
       	 	and p.channel_id = $channelId
    		group by d.datevalue
    		order by d.datevalue) ppp
    		on ddd.datevalue = ppp.datevalue;";
			$query = $dwdb->query($sql);
			return $query;
	}


	function getWeekActiveUserPercent($fromTime,$toTime,$productId,$channelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "Select ds.year, ds.week,df.startdate, ifnull(dp.percentage,0) totalusers from
				(select distinct year, week, startdate from ".$dwdb->dbprefix('dim_date')."  where startdate
 				between '$fromTime' and '$toTime' and weekday=0) df inner join
				(Select distinct year, week from ".$dwdb->dbprefix('dim_date')."  where startdate between '$fromTime' and '$toTime')
				ds on df.year=ds.year and df.week = ds.week left join (
				Select year,week, count(distinct f.deviceidentifier)/(select count(distinct ff.deviceidentifier)
				 from ".$dwdb->dbprefix('fact_activeusers_clientdata')."  ff,  ".$dwdb->dbprefix('dim_date')." dd, ".$dwdb->dbprefix('dim_product')."  pp
				 where ff.date_sk = dd.date_sk and ff.product_sk = pp.product_sk and
				 pp.product_id=$productId and pp.channel_id=$channelId and dd.year=d.year and dd.week<=d.week) percentage
				from ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f, ".$dwdb->dbprefix('dim_date')."  d,  ".$dwdb->dbprefix('dim_product')." p
				 where f.date_sk = d.date_sk and f.product_sk = p.product_sk and
				 p.product_id=$productId and p.channel_id=$channelId and d.startdate between '$fromTime'
		 		 and '$toTime' group by d.year,d.week) dp on ds.year = dp.year and ds.week = dp.week;";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getMonthActiveUserPercent($fromTime,$toTime,$productId,$channelId)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "Select ds.year, ds.month,df.startdate, ifnull(dp.percentage,0) totalusers from
				(select distinct year, month, startdate from  ".$dwdb->dbprefix('dim_date')." where startdate
				 between '$fromTime' and '$toTime' and day=1) df inner join
				(Select distinct year, month from ".$dwdb->dbprefix('dim_date')." where startdate between '$fromTime' and '$toTime')
				 ds on df.year=ds.year and df.month = ds.month left join (
				Select year,month, count(distinct f.deviceidentifier)/(select count(distinct ff.deviceidentifier)
 				from ".$dwdb->dbprefix('fact_activeusers_clientdata')."  ff,  ".$dwdb->dbprefix('dim_date')." dd, ".$dwdb->dbprefix('dim_product')."  pp where ff.date_sk = dd.date_sk
 				 and ff.product_sk = pp.product_sk and pp.product_id=$productId and pp.channel_id=$channelId and dd.year=d.year
 				  and dd.month<=d.month) percentage
				from ".$dwdb->dbprefix('fact_activeusers_clientdata')."  f,  ".$dwdb->dbprefix('dim_date')." d,  ".$dwdb->dbprefix('dim_product')." p
				 where f.date_sk = d.date_sk and f.product_sk = p.product_sk and p.product_id=$productId
				 and p.channel_id=1 and d.startdate between '$fromTime' and '$toTime' group by d.year,d.month)
				 dp on ds.year = dp.year and ds.month = dp.month;";
		$query = $dwdb->query($sql);
		return $query;
	}

}