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
class errormodel extends CI_Model {
	function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->Model ( 'common' );
	}
	
	//获得错误信息的版本
	function geterrorinfoversion() {
		$currentProduct = $this->common->getCurrentProduct ();
		$productid = $currentProduct->id;
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct  product_version as version from ".$dwdb->dbprefix('dim_product')."  where product_id=$productid";
		
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0) {
			
			return $query;
		}
	}
	
	//获得错误趋势的信息
	function geterrorinfodata($productId,$fromTime,$toTime) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select date(d.datevalue) datevalue,
	p.version_name,
       ifnull(count(f.id),0) count,
      ifnull(count(f.id)/(select sum(sessions) from  ".$dwdb->dbprefix('sum_basic_all')."   s where s.date_sk = d.date_sk and s.product_sk = p.product_sk),0) percentage
from   (select datevalue,date_sk
        from   ".$dwdb->dbprefix('dim_date')."
        where  datevalue between '$fromTime' and '$toTime') d
cross join (select product_sk,version_name from ".$dwdb->dbprefix('dim_product')." where product_active=1 and channel_active=1 and version_active=1 and product_id=$productId) p
       left join ".$dwdb->dbprefix('fact_errorlog')." f on
f.date_sk = d.date_sk and f.product_sk = p.product_sk and 
d.datevalue between '$fromTime' and '$toTime' 
group by d.datevalue,p.version_name
order by datevalue, p.version_name desc;";
		$query= $dwdb->query ($sql);	
		$ret=array();
		if ($query != null && $query->num_rows > 0) {
		
			$array = $query->result_array ();
				
			$content_arr = array ();
			for($i = 0; $i < count ($array); $i ++) {
				$row = $array [$i];
				$versionname = $row ['version_name'];
				$allkey = array_keys ( $content_arr );
				if (! in_array ( $versionname, $allkey )){
					$content_arr [$versionname] = array ();
				}
				$tmp = array ();
				$tmp ['datevalue'] = $row ['datevalue'];
				$tmp ['count'] = $row ['count'];
				$tmp ['percentage'] = round($row ['percentage'],2)."%";
				$tmp ['version_name'] = $row ['version_name'];
				array_push ( $content_arr [$versionname], $tmp );
					
			}
			$ret['content'] = $content_arr;
		}
		
		return $ret;
	}                                
	
	//通过时间与版本 获得错误信息的个数
	function geterrornumbyvertime($fromTime, $toTime, $productid, $version) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select dd.datevalue, ifnull(ff.count,0) count 
		  from   (select datevalue from   ".$dwdb->dbprefix('dim_date_day')."   
         where  datevalue between '$fromTime' and '$toTime') dd
         left join (select   d.datevalue,count(f.deviceidentifier) count
                  from  ".$dwdb->dbprefix('fact_errorlog')."       f,  ".$dwdb->dbprefix('dim_date_day')."    d, ".$dwdb->dbprefix('dim_product')."    p
                  where    f.date_sk = d.date_sk and d.datevalue between '$fromTime' and '$toTime'
                           and f.product_sk = p.product_sk and p.product_id = $productid and p.product_version='$version'
                  group by d.datevalue) ff on dd.datevalue = ff.datevalue";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}	
	//通过时间与版本 获得 错误/启动 信息的个数
	function geterrorstartbyversion($fromTime, $toTime, $productid, $version) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select dd.datevalue,ifnull(ff.count,0) count from   (select datevalue
        from   ".$dwdb->dbprefix('dim_date_day')."     where  datevalue between '$fromTime' and '$toTime') dd
       left join (select   d.datevalue,count(f.deviceidentifier)/(select count(ft.deviceidentifier)
        from   ".$dwdb->dbprefix('fact_activeusers_clientdata')."    ft,  ".$dwdb->dbprefix('dim_product')."   pt,  ".$dwdb->dbprefix('dim_date')."   dt
        where  ft.product_sk = pt.product_sk and pt.product_id =$productid and pt.product_version='$version'
        and ft.date_sk = dt.date_sk and dt.startdate = d.datevalue) count
                  from   ".$dwdb->dbprefix('fact_errorlog')."      f, ".$dwdb->dbprefix('dim_date_day')."   d, ".$dwdb->dbprefix('dim_product')."   p
                  where    f.date_sk = d.date_sk and d.datevalue between '$fromTime' and '$toTime'
                           and f.product_sk = p.product_sk and p.product_id =$productid and p.product_version='$version'
                  group by d.datevalue) ff on dd.datevalue = ff.datevalue";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}	
	
	//获得错误信息列表  *****************
	function geterrorlist($productid, $isfix = 0,$devicebrandname="") {
		$dwdb = $this->load->database ( 'dw', TRUE );
	   if($devicebrandname=="")
		{$sql = "select  f.title,f.title_sk,p.product_sk,count(f.stacktrace) errorcount,
         p.version_name,f.time 
         from  ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_product')." p ,".$dwdb->dbprefix('dim_devicebrand')."  o
         where    f.product_sk = p.product_sk and f.deviceidentifier = o.devicebrand_sk 
         and p.product_id = $productid and f.isfix=$isfix and p.product_active=1 and p.channel_active=1 and p.version_active=1
		 group by f.title,p.version_name, p.product_sk,f.title_sk;";
		}
		else 
		{
			$sql = "select  f.title,f.title_sk,p.product_sk,count(f.stacktrace) errorcount,
         p.version_name,f.time 
         from  ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_product')." p ,".$dwdb->dbprefix('dim_devicebrand')."  o
         where    f.product_sk = p.product_sk and f.deviceidentifier = o.devicebrand_sk 
         and p.product_id = $productid and f.isfix=$isfix and p.product_active=1 and p.channel_active=1 and p.version_active=1 and o.devicebrand_name= '$devicebrandname'
		group by f.title,p.version_name, p.product_sk,f.title_sk ;";
		}
		$query = $dwdb->query ( $sql );
		return $query;
	}
	//获得错误信息分页信息
	function getpageerrorlist ($productid,$isfix=0,$pagenum=0,$devicebrandname="", $count = REPORT_TOP_TEN)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		if($devicebrandname=="")
		{$sql = "select  f.title,f.title_sk,p.product_sk,count(f.stacktrace) errorcount,
         p.version_name,f.time 
         from  ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_product')." p ,".$dwdb->dbprefix('dim_devicebrand')."  o
         where    f.product_sk = p.product_sk and f.deviceidentifier = o.devicebrand_sk 
         and p.product_id = $productid and f.isfix=$isfix and p.product_active=1 and p.channel_active=1 and p.version_active=1
		 group by f.title,p.version_name, p.product_sk,f.title_sk limit $pagenum,$count;";
		}
		else 
		{
			$sql = "select  f.title,f.title_sk,p.product_sk,count(f.stacktrace) errorcount,
         p.version_name,f.time 
         from  ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_product')." p ,".$dwdb->dbprefix('dim_devicebrand')."  o
         where    f.product_sk = p.product_sk and f.deviceidentifier = o.devicebrand_sk 
         and p.product_id = $productid and f.isfix=$isfix and p.product_active=1 and p.channel_active=1 and p.version_active=1 and o.devicebrand_name= '$devicebrandname'
		group by f.title,p.version_name, p.product_sk,f.title_sk limit $pagenum,$count;";
		}
		$query = $dwdb->query ( $sql );
		return $query;
	}
	//根据页数获得错误的详细信息
	function geterrorlistbypagenum($productid, $isfix = 0,$pageIndex=0,$pageNums=RECORD_NUM)
	{
		$from = ($pageIndex*$pageNums);
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select  f.title,f.title_sk,p.product_sk,count(f.stacktrace) errorcount,
         p.version_name,f.time 
         from  ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_product')." p 
         where    f.product_sk = p.product_sk 
         and p.product_id = $productid and f.isfix=$isfix and p.product_active=1 and p.channel_active=1 and p.version_active=1 
		group by f.title,p.version_name, p.product_sk,f.title_sk limit $from,$pageNums;";
		$query = $dwdb->query ( $sql );
		return $query;
	}
	//获取错误的详细描述信息
	function getdetailstacktrace($productsk,$titlesk,$isfix)
		{   
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select f.stacktrace from  ".$dwdb->dbprefix('fact_errorlog')."   f
          where   f.title_sk =$titlesk
         and product_sk = $productsk and f.isfix = $isfix order by f.time desc";
		
	
		$query = $dwdb->query ( $sql );
		$rel=$query->first_row();
		return $rel;
	}
	
	
	//根据版本号,appkey标记错误信息
	function markfixerrorinfo($productid, $product_version,$titlesk,$titles,$product_sk, $fix){
		$sql = "update  ".$this->db->dbprefix('errorlog')."   set isfix=$fix where appkey in 
		(select distinct   productkey from  ".$this->db->dbprefix('channel_product')."  where product_id=$productid) and title='$titles' and version='$product_version' ";
		$this->db->query ( $sql );
		
		$dwdb = $this->load->database ( 'dw', TRUE );		
	//	$dsql="update fact_errorlog set isfix = $fix where product_sk =$product_sk and title_sk=$titlesk and title='$titles' ";
		$dsql="update ".$dwdb->dbprefix('fact_errorlog')." 
set    isfix = $fix
where  title_sk = $titlesk
       and product_sk = $productid and isfix = 0;
		";
		$dwdb->query ( $dsql );
	
	}
	//根据appkey标记所有错误信息
	function markfixallversion($productid,$fix) {
		$sql = "update  ".$this->db->dbprefix('errorlog')."   set isfix=$fix where appkey in 
		(select distinct productkey from  ".$dwdb->dbprefix('channel_product')."   where product_id=$productid)";
		$this->db->query ( $sql );
		
		$dwdb = $this->load->database ( 'dw', TRUE );
		$dsql="update  ".$dwdb->dbprefix('fact_errorlog')."   set isfix=$fix where product_sk in 
		(select distinct product_sk from  ".$dwdb->dbprefix('dim_product')."   where product_id=$productid)";
		 $dwdb->query ( $dsql );
	}
	//根据获得设备类型
	function geterrordevicename($device) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct devicebrand_name from  ".$dwdb->dbprefix('dim_devicebrand')."   where devicebrand_name like '%$device%'";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	//获得错误的明细
	function geterrordetail($titlesk,$productsk,$isfix)
	{
// 		$sql="select   f.time,o.deviceos_name,b.devicebrand_name,f.stacktrace from fact_errorlog f,
//          dim_deviceos o,dim_devicebrand b where  f.osversion_sk = o.deviceos_sk and f.deviceidentifier = b.devicebrand_sk
//          and f.title_sk = $titlesk and product_sk =$productsk and f.isfix =$isfix order by f.time desc";
		
//		$sql = " select   f.time, o.deviceos_name,f.deviceidentifier, f.stacktrace
//             from  fact_errorlog f, dim_deviceos o where f.osversion_sk = o.deviceos_sk
//         and f.title_sk =$titlesk and product_sk = $productsk and f.isfix =$isfix order by f.time desc";		
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   f.time,
		o.deviceos_name,
		b.devicebrand_name,
		f.stacktrace
		from   ".$dwdb->dbprefix('fact_errorlog')."      f,
		".$dwdb->dbprefix('dim_deviceos')."      o,
		".$dwdb->dbprefix('dim_devicebrand')."      b
		where    f.osversion_sk = o.deviceos_sk
		and f.deviceidentifier = b.devicebrand_sk
		and f.title_sk = $titlesk
		and product_sk = $productsk
		and f.isfix = $isfix
		order by f.time desc;
		";
		$query = $dwdb->query ( $sql );
		return $query;
		
	}
	//设备分布情况
	function deviceinfo($titlesk,$productsk,$isfix)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   o.devicebrand_name,
         count(* ) count,
         count(f.osversion_sk)
           / (select count(osversion_sk)
              from  ".$dwdb->dbprefix('fact_errorlog')."  ff, 
                   ".$dwdb->dbprefix('dim_devicebrand')."    oo
              where  title_sk = $titlesk
                     and ff.deviceidentifier = oo.devicebrand_sk
                     and product_sk = $productsk
                     and isfix = $isfix) percentage
from  ".$dwdb->dbprefix('fact_errorlog')."     f,
      ".$dwdb->dbprefix('dim_devicebrand')."     o
where    f.title_sk = $titlesk
         and f.deviceidentifier = o.devicebrand_sk
         and product_sk = $productsk
         and f.isfix = $isfix
group by o.devicebrand_name
order by count desc limit 0,15;

		";
		$query = $dwdb->query ( $sql );		
		$ret  = $query->result_array();
		return $ret;	
		
	}
	 
	//操作系统分布情况
	function operationinfo($titlesk,$productsk,$isfix)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   o.deviceos_name,
         count(* ) count,
         count(f.osversion_sk)
           / (select count(osversion_sk)
              from ".$dwdb->dbprefix('fact_errorlog')."   ff,
                   ".$dwdb->dbprefix('dim_deviceos')."      oo
              where  title_sk = $titlesk
                     and ff.osversion_sk = oo.deviceos_sk
                     and product_sk = $productsk
                     and isfix = $isfix) percentage
from  ".$dwdb->dbprefix('fact_errorlog')."       f,
       ".$dwdb->dbprefix('dim_deviceos')."      o
where    f.title_sk = $titlesk
         and f.osversion_sk = o.deviceos_sk
         and product_sk = $productsk
         and f.isfix = $isfix
group by o.deviceos_name
order by count desc;
		";
		$query = $dwdb->query ( $sql );		
		$ret = $query->result_array();
		return $ret;
		
	}
	

}
