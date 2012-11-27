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

	//get error count and count/sessions data by version
	function geterroralldata($productId,$fromTime,$toTime) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="Select
              ifnull(count(f.id),0) errorcount,
              ifnull(count(f.id)/(select sum(sessions) from ".$dwdb->dbprefix('sum_basic_all')." s,".$dwdb->dbprefix('dim_date')." sd, ".$dwdb->dbprefix('dim_product')." sp where s.date_sk =sd.date_sk and sd.datevalue between '$fromTime' and '$toTime' and s.product_sk = sp.product_sk and sp.product_id='$productId'),0) percentage
              from ".$dwdb->dbprefix('fact_errorlog')." f, ".$dwdb->dbprefix('dim_date')." d, ".$dwdb->dbprefix('dim_product')." p where f.date_sk = d.date_sk and d.datevalue between '$fromTime' and '$toTime' and f.product_sk = p.product_sk and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1";

		$query= $dwdb->query ($sql);
		$ret=array();
		if ($query != null && $query->num_rows() > 0) {

			$array = $query->result_array ();

			$content_arr = array ();
			for($i = 0; $i < count ($array); $i ++) {
				$tmp = array();
				$row = $array[$i];
				$tmp ['count'] = $row ['errorcount'];
				$tmp ['percentage'] = round($row ['percentage'],2);
				array_push ( $content_arr, $tmp );
			}
			$ret['content'] = $content_arr;
		}
		else 
		    $ret['content'] = "";
		return $ret;
	}
	
	function getCompareErrorData($productId,$fromTime,$toTime){
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="SELECT c.datevalue, ifnull( s.errorcount, 0 ) errorcounts , ifnull( s.percentage, 0 ) percent
		from(select d.datevalue,ifnull(count(f.id),0) errorcount,
		ifnull(count(f.id)/(select sum(sessions)
		from ".$dwdb->dbprefix('sum_basic_all')." s,".$dwdb->dbprefix('dim_date')." sd, ".$dwdb->dbprefix('dim_product')." sp 
		where s.date_sk =sd.date_sk and sd.datevalue=d.datevalue and s.product_sk = sp.product_sk and sp.product_id='$productId'),0) percentage,d.datevalue date
		from ".$dwdb->dbprefix('fact_errorlog')." f, ".$dwdb->dbprefix('dim_date')." d, ".$dwdb->dbprefix('dim_product')." p 
		where f.date_sk = d.date_sk and d.datevalue between '$fromTime' and '$toTime' and f.product_sk = p.product_sk and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1
		group by d.datevalue) s right join (select datevalue from ".$dwdb->dbprefix('dim_date')."  
		where datevalue between '$fromTime' and '$toTime') c on s.datevalue = c.datevalue";
		$query= $dwdb->query ($sql);
		$ret=array();
		if ($query != null && $query->num_rows() > 0) {
		
			$array = $query->result_array ();
		
			$content_arr = array ();
			for($i = 0; $i < count ($array); $i ++) {
				$tmp = array();
				$row = $array[$i];
				$tmp ['count'] = $row ['errorcounts'];
				$tmp ['percentage'] = round($row ['percent'],2);
				$tmp ['date'] = substr($row ['datevalue'],0,10);
				array_push ( $content_arr, $tmp );
			}
			$ret['content'] = $content_arr;
		}
		else
			$ret['content'] = "";
		return $ret;
		
	}

	//get error count and count/sessions data by os
	function getErrorAllDataOnOs($productId,$fromTime,$toTime) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="Select
o.deviceos_name,
ifnull(count(f.id),0) errorcount,      
ifnull(count(f.id)/(select count(*) from  ".$dwdb->dbprefix('fact_clientdata')."  s,".$dwdb->dbprefix('dim_date')." sd, ".$dwdb->dbprefix('dim_product')." sp  where s.date_sk =sd.date_sk and sd.datevalue between '$fromTime' and '$toTime' and s.product_sk = sp.product_sk and sp.product_id=1 and s.deviceos_sk=o.deviceos_sk),0) percentage
from ".$dwdb->dbprefix('fact_errorlog')."  f, ".$dwdb->dbprefix('dim_date')." d, ".$dwdb->dbprefix('dim_product')." p, ".$dwdb->dbprefix('dim_deviceos')." o where f.date_sk = d.date_sk and d.datevalue between '$fromTime' and '$toTime' and f.product_sk = p.product_sk and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.osversion_sk = o.deviceos_sk group by o.deviceos_name order by o.deviceos_name
		";

		$query= $dwdb->query ($sql);
		$ret=array();
		if ($query != null && $query->num_rows() > 0) {

			$array = $query->result_array ();

			$content_arr = array ();
			for($i = 0; $i < count ($array); $i ++) {
				$tmp = array();
				$row = $array[$i];
				$tmp ['count'] = $row ['errorcount'];
				$tmp ['percentage'] = round($row ['percentage'],2);
				$tmp ['deviceos_name'] = $row ['deviceos_name'];
				array_push ( $content_arr, $tmp );
			}
			$ret['content'] = $content_arr;
		}
		else 
		    $ret['content'] = "";
		return $ret;
	}

	//get error count and count/sessions data by device
	function getErrorAllDataOnDevice($productId,$fromTime,$toTime) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="Select
o.devicebrand_name,
ifnull(count(f.id),0) errorcount,
ifnull(count(f.id)/(select count(*) from ".$dwdb->dbprefix('fact_clientdata')."  s,".$dwdb->dbprefix('dim_date')." sd, ".$dwdb->dbprefix('dim_product')." sp  where s.date_sk =sd.date_sk and sd.datevalue between '$fromTime' and '$toTime' and s.product_sk = sp.product_sk and sp.product_id=$productId and s.devicebrand_sk=o.devicebrand_sk),0) percentage
from ".$dwdb->dbprefix('fact_errorlog')." f, ".$dwdb->dbprefix('dim_date')." d, ".$dwdb->dbprefix('dim_product')." p, ".$dwdb->dbprefix('dim_devicebrand')." o where f.date_sk = d.date_sk and d.datevalue between '$fromTime' and '$toTime' and f.product_sk = p.product_sk and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.deviceidentifier = o.devicebrand_sk group by o.devicebrand_name order by errorcount desc
		limit 0,".REPORT_TOP_TEN;

		$query= $dwdb->query ($sql);
		$ret=array();
		if ($query != null && $query->num_rows() > 0) {

			$array = $query->result_array ();

			$content_arr = array ();
			for($i = 0; $i < count ($array); $i ++) {
				$tmp = array();
				$row = $array[$i];
				$tmp ['count'] = $row ['errorcount'];
				$tmp ['percentage'] = round($row ['percentage'],2);
				$tmp ['devicebrand_name'] = $row ['devicebrand_name'];
				array_push ( $content_arr, $tmp );
			}
			$ret['content'] = $content_arr;
		}
		else 
		    $ret['content'] = "";
		return $ret;
	}

	//Error message list  of version*****************
	function geterrorlist($productid,$from,$to) {
		$dwdb = $this->load->database ( 'dw', TRUE );

		$sql = "select   f.title,
         f.title_sk,et.isfix,
         count(f.stacktrace) errorcount,
         p.version_name,
         max(f.time) time
from    ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d
where    f.product_sk = p.product_sk and f.title_sk = et.title_sk 
         and p.product_id = $productid  and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.date_sk = d.date_sk and d.datevalue between '$from' and '$to'
group by p.version_name,f.title_sk order by version_name desc, f.time desc;
		";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;
	}

	//Error message list  on os*****************
	function getErrorlistOnOs($productid,$from,$to) {
		$dwdb = $this->load->database ( 'dw', TRUE );

		$sql = "select   f.title,
         f.title_sk,et.isfix,
         count(f.stacktrace) errorcount,
         o.deviceos_sk,
         o.deviceos_name,
         max(f.time) time
from     ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d,".$dwdb->dbprefix('dim_deviceos')." o
where    f.product_sk = p.product_sk and f.title_sk = et.title_sk
         and p.product_id = $productid  and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.date_sk = d.date_sk and d.datevalue between '$from' and '$to' and f.osversion_sk=o.deviceos_sk 
group by o.deviceos_sk,f.title_sk order by o.deviceos_name desc,f.time desc;
		";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;
	}


	//Error message list  on device*****************
	function getErrorlistOnDevice($productid,$from,$to) {
		$dwdb = $this->load->database ( 'dw', TRUE );

		$sql = "select   f.title,
         f.title_sk, et.isfix,
         count(f.stacktrace) errorcount,
         o.devicebrand_sk,
         o.devicebrand_name,
         max(f.time) time
from  ".$dwdb->dbprefix('fact_errorlog')." f,".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d,".$dwdb->dbprefix('dim_devicebrand')." o
where    f.product_sk = p.product_sk and f.title_sk = et.title_sk
         and p.product_id = $productid  and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.date_sk = d.date_sk and d.datevalue between '$from' and '$to' and f.deviceidentifier=o.devicebrand_sk 
group by o.devicebrand_sk,f.title_sk order by count(f.stacktrace) desc,f.time desc;
		";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;
	}

	

	//error Detail of version
	function geterrordetail($title_sk,$version_name,$product_id,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   f.time, et.isfix,
         o.deviceos_name,
         b.devicebrand_name,
         f.stacktrace
         from     
         ".$dwdb->dbprefix('dim_errortitle')." et,
        ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_deviceos')." o,
         ".$dwdb->dbprefix('dim_devicebrand')." b,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
         where    
         f.title_sk = et.title_sk
         and f.osversion_sk = o.deviceos_sk
         and f.deviceidentifier = b.devicebrand_sk
         and f.product_sk = p.product_sk
         and f.title_sk = '$title_sk'
         and p.version_name='$version_name' and p.product_id = '$product_id'
         and f.date_sk = d.date_sk and d.datevalue between '$from' and '$to'
         ORDER BY f.time desc;
		";
		
		$query = $dwdb->query ( $sql );
		return $query;

	}

	//error Detail on os
	function getErrorDetailOnOs($title_sk,$deviceos_sk,$product_id,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select  f.time,et.isfix,
         b.devicebrand_name,
         p.version_name,
         f.stacktrace
from     ".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_deviceos')." o,
         ".$dwdb->dbprefix('dim_devicebrand')." b,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
where    f.osversion_sk = o.deviceos_sk
         and f.title_sk = et.title_sk
         and f.deviceidentifier = b.devicebrand_sk
         and f.product_sk = p.product_sk
         and f.title_sk = '$title_sk'
         and o.deviceos_sk = '$deviceos_sk' and p.product_id = '$product_id'
         and f.date_sk = d.date_sk and d.datevalue between '$from' and '$to'
ORDER BY f.time desc;
		";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;

	}

	//error Detail on device
	function getErrorDetailOnDevice($title_sk,$devicebrand_sk,$product_id,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   f.time,et.isfix,
         o.deviceos_name,
         p.version_name,
         f.stacktrace
from     
         ".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_deviceos')." o,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
where    f.osversion_sk = o.deviceos_sk
         and f.title_sk = et.title_sk
         and f.product_sk = p.product_sk
         and f.title_sk = $title_sk
         and f.deviceidentifier = $devicebrand_sk
          and p.product_id = $product_id
          and f.date_sk = d.date_sk and d.datevalue between '$from' and '$to'
ORDER BY f.time desc
		;
		";
		//echo $sql;
		$query = $dwdb->query ( $sql );
		return $query;

	}




	//device distribution of version
	function getDeviceInfoOfVersion($titlesk,$productid,$version_name,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   o.devicebrand_name,
         count(* ) count,
         count(f.deviceidentifier)
           / (select count(deviceidentifier)
              from   ".$dwdb->dbprefix('fact_errorlog')." ff,
                     ".$dwdb->dbprefix('dim_devicebrand')." oo,
                     ".$dwdb->dbprefix('dim_product')." pp,
                     ".$dwdb->dbprefix('dim_date')." dd
              where  ff.product_sk = pp.product_sk
                     and title_sk = $titlesk
                     and ff.deviceidentifier = oo.devicebrand_sk
                     and pp.product_id = $productid
                     and pp.version_name = $version_name
                     and ff.date_sk = dd.date_sk 
                     and dd.datevalue between '$from' and '$to' ) percentage
              from   ".$dwdb->dbprefix('fact_errorlog')." f,
                     ".$dwdb->dbprefix('dim_devicebrand')." o,
                     ".$dwdb->dbprefix('dim_product')." p,
                     ".$dwdb->dbprefix('dim_date')." d
              where  f.product_sk = p.product_sk 
                     and f.title_sk = $titlesk
                     and f.deviceidentifier = o.devicebrand_sk
                     and p.product_id = $productid
                     and p.version_name= $version_name
                     and f.date_sk = d.date_sk
                     and d.datevalue between '$from' and '$to'
                     group by o.devicebrand_sk
                     order by count desc;";

		$query = $dwdb->query ( $sql );
		$ret  = $query->result_array();
		return $ret;
	}

	//device distribution on os
	function getDeviceInfoOnOs($titlesk,$productid,$deviceos_sk,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   o.devicebrand_name,
         count(* ) count,
         count(f.deviceidentifier)
           / (select count(deviceidentifier)
              from   ".$dwdb->dbprefix('fact_errorlog')." ff,
                     ".$dwdb->dbprefix('dim_devicebrand')." oo,
                     ".$dwdb->dbprefix('dim_product')." pp,
                     ".$dwdb->dbprefix('dim_date')." dd
              where  ff.product_sk = pp.product_sk
                     and title_sk = $titlesk
                     and ff.deviceidentifier = oo.devicebrand_sk
                     and pp.product_id = $productid
                     and ff.osversion_sk = $deviceos_sk
                     
                     and ff.date_sk = dd.date_sk 
                     and dd.datevalue between '$from' and '$to' ) percentage
from     ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_devicebrand')." o,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
where    f.product_sk = p.product_sk 
         and f.title_sk = $titlesk
         and f.deviceidentifier = o.devicebrand_sk
         and p.product_id = $productid
         and f.osversion_sk = $deviceos_sk
         and f.date_sk = d.date_sk
         and d.datevalue between '$from' and '$to'
group by o.devicebrand_sk
order by count desc;
		";

		$query = $dwdb->query ( $sql );
		$ret  = $query->result_array();
		return $ret;
	}


	//app version distribution on device
	function getVersionInfoOnDevice($titlesk,$productid,$devicebrand_sk,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   p.version_name,
         count(* ) count,
         count(*)
           / (select count(deviceidentifier)
              from   ".$dwdb->dbprefix('fact_errorlog')." ff,
                     ".$dwdb->dbprefix('dim_product')." pp,
                     ".$dwdb->dbprefix('dim_date')." dd
              where  ff.product_sk = pp.product_sk
                     and title_sk = $titlesk
                     and pp.product_id = $productid
                     and ff.deviceidentifier = $devicebrand_sk
                     and ff.date_sk = dd.date_sk 
                     and dd.datevalue between '$from' and '$to' ) percentage
from     ".$dwdb->dbprefix('fact_errorlog')." f,
          ".$dwdb->dbprefix('dim_product')." p,
          ".$dwdb->dbprefix('dim_date')." d
where    f.product_sk = p.product_sk 
         and f.title_sk = $titlesk
         and p.product_id = $productid
         and f.deviceidentifier = $devicebrand_sk
         and f.date_sk = d.date_sk
         and d.datevalue between '$from' and '$to'
group by p.version_name
order by count desc;";

		$query = $dwdb->query ( $sql );
		$ret  = $query->result_array();
		return $ret;
	}

	//Os distribution of version
	function getOsOfVersion($titlesk,$productid,$version_name,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   o.deviceos_name,
         count(* ) count,
         count(f.osversion_sk)
           / (select count(osversion_sk)
              from   ".$dwdb->dbprefix('fact_errorlog')." ff,
                     ".$dwdb->dbprefix('dim_deviceos')." oo,
                     ".$dwdb->dbprefix('dim_product')." pp,
                     ".$dwdb->dbprefix('dim_date')." dd
              where  ff.product_sk = pp.product_sk
                     and title_sk = $titlesk
                     and ff.osversion_sk = oo.deviceos_sk
                     and pp.product_id = $productid
                     and pp.version_name = $version_name
                     and ff.date_sk = dd.date_sk
                     and dd.datevalue between '$from' and '$to') percentage
from     ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_deviceos')." o,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
where    f.product_sk = p.product_sk 
         and f.title_sk = $titlesk
         and f.osversion_sk = o.deviceos_sk
         and p.product_id = $productid
         and p.version_name=$version_name
         and f.date_sk = d.date_sk
         and d.datevalue between '$from' and '$to'
group by o.deviceos_sk
order by count desc;
		";
		$query = $dwdb->query ( $sql );
		$ret = $query->result_array();
		return $ret;

	}

	//Operating system distribution on Os
	function getAppVersionOnOs($titlesk,$productid,$deviceos_sk,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   p.version_name,
         count(* ) count,
         count(f.deviceidentifier)
           / (select count(deviceidentifier)
              from  ".$dwdb->dbprefix('fact_errorlog')." ff,
                     ".$dwdb->dbprefix('dim_product')." pp,
                     ".$dwdb->dbprefix('dim_date')." dd
              where  ff.product_sk = pp.product_sk
                     and title_sk = $titlesk
                     and pp.product_id = $productid 
                     and ff.osversion_sk = $deviceos_sk
                     and ff.date_sk = dd.date_sk 
                     and dd.datevalue between '$from' and '$to' ) percentage
from     ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
where    f.product_sk = p.product_sk 
         and f.title_sk = $titlesk
         and p.product_id = $productid
         and f.osversion_sk = $deviceos_sk
         and f.date_sk = d.date_sk
         and d.datevalue between '$from' and '$to'
group by p.version_name
order by count desc;
		";
		$query = $dwdb->query ( $sql );
		$ret = $query->result_array();
		return $ret;

	}

	//Operating system distribution on Device
	function getOsInfoOnDevice($titlesk,$productid,$devicebrand_sk,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   o.deviceos_name,
         count(* ) count,
         count(f.osversion_sk)
           / (select count(deviceidentifier)
              from   ".$dwdb->dbprefix('fact_errorlog')." ff,
                     ".$dwdb->dbprefix('dim_deviceos')." oo,
                     ".$dwdb->dbprefix('dim_product')." pp,
                     ".$dwdb->dbprefix('dim_date')." dd
              where  ff.product_sk = pp.product_sk
                     and title_sk = $titlesk
                     and ff.osversion_sk = oo.deviceos_sk
                     and pp.product_id = $productid 
                     and ff.deviceidentifier = $devicebrand_sk
                     and ff.date_sk = dd.date_sk 
                     and dd.datevalue between '$from' and '$to' ) percentage
from     ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_deviceos')." o,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
where    f.product_sk = p.product_sk 
         and f.title_sk = $titlesk
         and f.osversion_sk = o.deviceos_sk
         and p.product_id = $productid
         and f.deviceidentifier = $devicebrand_sk
         and f.date_sk = d.date_sk
         and d.datevalue between '$from' and '$to'
group by o.deviceos_sk
order by count desc;
		";
		$query = $dwdb->query ( $sql );
		$ret = $query->result_array();
		return $ret;

	}
	

	//Mark all error messages according appkey
	function changeErrorStatus($title_sk, $fix) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "update ".$dwdb->dbprefix('dim_errortitle')."
                set isfix = $fix
                where title_sk = $title_sk;
		";
		$dwdb->query ( $sql );
	}

}
