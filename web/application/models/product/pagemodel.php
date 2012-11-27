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
class pagemodel extends CI_Model{
	function __construct()
	{
	  parent::__construct ();
	  $this->load->model ( 'common' );
	}
	
	//All versions of basic data
	function getallVersionBasicData($fromDate='',$toDate='',$productId){
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select 'all' version_name,sum(accesscount) accesscount, (select sum(total) from (select ss.product_sk,sum(totaltime)/sum(accesscount) total 
		from ".$dwdb->dbprefix('sum_usinglog_activity')." ss,".$dwdb->dbprefix('dim_product')." pp,".$dwdb->dbprefix('dim_date')." dd, ".$dwdb->dbprefix('dim_activity')." aa where ss.product_sk = pp.product_sk and ss.date_sk = dd.date_sk and ss.activity_sk = aa.activity_sk and dd.datevalue between '$fromDate' and '$toDate' and pp.product_id=$productId 
		group by ss.activity_sk)  ff where ff.product_sk = p.product_sk) avertime,sum(exitcount) exitcount 
		from ".$dwdb->dbprefix('sum_usinglog_activity')." s,".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d,".$dwdb->dbprefix('dim_activity')." a 
		where s.product_sk = p.product_sk and s.date_sk=d.date_sk and s.activity_sk = a.activity_sk and d.datevalue between '$fromDate' and '$toDate' and p.product_id=$productId group by version_name
		union 
		select p.version_name,sum(accesscount) accesscount,(select sum(total) from (select ss.product_sk,sum(totaltime)/sum(accesscount) total 
		from ".$dwdb->dbprefix('sum_usinglog_activity')." ss,".$dwdb->dbprefix('dim_product')." pp,".$dwdb->dbprefix('dim_date')." dd, ".$dwdb->dbprefix('dim_activity')." aa where ss.product_sk = pp.product_sk and ss.date_sk = dd.date_sk and ss.activity_sk = aa.activity_sk and dd.datevalue between '$fromDate' and '$toDate' and pp.product_id=$productId
		group by ss.activity_sk)  ff where ff.product_sk = p.product_sk) avertime,sum(exitcount) exitcount 
		from ".$dwdb->dbprefix('sum_usinglog_activity')." s,".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d,".$dwdb->dbprefix('dim_activity')." a 
		where s.product_sk = p.product_sk and s.date_sk=d.date_sk and s.activity_sk = a.activity_sk and d.datevalue between '$fromDate' and '$toDate' and p.product_id=$productId group by p.version_name order by version_name,accesscount desc;";
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	//For the various versions of the basic data
	function getVersionBasicData($fromTime,$toTime,$productId){
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select 'all' version_name,a.activity_name,sum(accesscount) accesscount,
		sum(totaltime)/sum(accesscount) avertime,sum(exitcount) exitcount 
		from ".$dwdb->dbprefix('sum_usinglog_activity')." s,".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d,".$dwdb->dbprefix('dim_activity')." a  
		where s.product_sk = p.product_sk and s.date_sk=d.date_sk and s.activity_sk = a.activity_sk and d.datevalue between '$fromTime' and '$toTime' and p.product_id=$productId group by a.activity_name
		union
		select p.version_name,a.activity_name,sum(accesscount) accesscount,sum(totaltime)/sum(accesscount) avertime,
		sum(exitcount) exitcount from ".$dwdb->dbprefix('sum_usinglog_activity')." s,".$dwdb->dbprefix('dim_product')." p,".$dwdb->dbprefix('dim_date')." d,".$dwdb->dbprefix('dim_activity')." a 
		where s.product_sk = p.product_sk and s.date_sk=d.date_sk and s.activity_sk = a.activity_sk and d.datevalue between '$fromTime' and '$toTime' and p.product_id=$productId group by p.version_name,a.activity_name order by version_name,accesscount desc;";
		$query = $dwdb->query ( $sql );
		$ret=array();
		$alldata=$this->getallVersionBasicData($fromTime,$toTime,$productId)->result_array ();
		if ($query != null && $query->num_rows > 0) {
						
			$arra = $query->result_array ();
			
			$content_arr = array ();
			$flag='';
		
			
			
			for($i = 0; $i < count ( $arra ); $i ++) {
				$accesscount=0;
				$avertime=0;
				$exitcount=0;
				$row = $arra [$i];
				$versionname = $row ['version_name'];
				$allkey = array_keys ( $content_arr );
				if (! in_array ( $versionname, $allkey )){
					$content_arr [$versionname] = array ();
					$flag='';
				}
				$tmp = array ();
				if($flag==''){
					for($j=0;$j<count($alldata);$j ++){
						$all = $alldata [$j];
						if($all['version_name']!="all"&&$all['version_name']==$versionname){
							$accesscount = $all['accesscount'];
							$avertime = $all['avertime'];
							$exitcount = $all['exitcount'];
							break;
						}else{
							$accesscount = $accesscount+$all['accesscount']/2;
							$avertime =$avertime+ $all['avertime']/2;
							$exitcount =$exitcount+ $all['exitcount']/2;
						}
					}
				}
				if($row ['accesscount']==null){
					$tmp ['accesscount']=0;
				}else{
					$tmp ['accesscount'] = $row ['accesscount']."(".round($row ['accesscount']/$accesscount*100,1)."%)";
				}
			    if($row ['avertime']==null){
					$tmp ['avertime']=0;
				}else{
					$tmp ['avertime'] = round($row ['avertime'],2)."(".round($row ['avertime']/$avertime*100,1)."%)";
				}
				if($row ['exitcount']==null){
					$tmp ['exitcount']=0;
				}else{
					$tmp ['exitcount'] = $row ['exitcount']."(".round($row ['exitcount']/$exitcount*100,1)."%)";
				}
				$tmp ['activity_name'] = $row ['activity_name'];	
				//$tmp ['version_name'] = $row ['version_name'];
				array_push ( $content_arr [$versionname], $tmp );
			
			}
			$ret['content'] = $content_arr;
		}
		return $ret;
	
	}
	
	function getTopLevelData($fromTime,$toTime,$productId)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select 'Entry', e0.activity_name,sum(al0.count) as count,sum(al0.count)/(select sum(sa0.count) as count
				from ".$dwdb->dbprefix('sum_accesslevel')." sa0, ".$dwdb->dbprefix('dim_date')." sd0,
				 ".$dwdb->dbprefix('dim_product')." sp0 where sa0.date_sk = sd0.date_sk and sd0.datevalue 
				between '$fromTime' and '$toTime' and sa0.product_sk = sp0.product_sk and
				 sp0.product_id = $productId and sa0.level = 1 ) percentage from 
				".$dwdb->dbprefix('sum_accesslevel')." al0,".$dwdb->dbprefix('dim_date')." d0, ".$dwdb->dbprefix('dim_product')." p0, 
				".$dwdb->dbprefix('dim_activity')." e0 where al0.date_sk = d0.date_sk and d0.datevalue
			    between '$fromTime' and '$toTime' and al0.product_sk = p0.product_sk 
				and p0.product_id = $productId and al0.fromid = e0.activity_sk and al0.level = 1 group by e0.activity_name ORDER BY SUM( al0.count ) DESC LIMIT 0 , 5";
		return $dwdb->query($sql);
	}
	
	function getFlowData($fromTime,$toTime,$productId)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "Select e1.activity_name as activity_from ,ifnull(e2.activity_name,'Exit') as activity_to,level, 
				sum(al.count) as count , sum(al.count)/(select sum(count) from ".$dwdb->dbprefix('sum_accesslevel')." sa,
				 ".$dwdb->dbprefix('dim_date')." sd, ".$dwdb->dbprefix('dim_product')." sp where sa.date_sk = 
				sd.date_sk and sd.datevalue between '$fromTime' and '$toTime' and sa.product_sk = sp.product_sk and
				 sp.product_id = $productId and sa.fromid = al.fromid and sa.level = al.level) percentage 
				from ".$dwdb->dbprefix('sum_accesslevel')." al inner join ".$dwdb->dbprefix('dim_date')." d on al.date_sk = d.date_sk
				 and d.datevalue between '$fromTime' and '$toTime'  
				inner join ".$dwdb->dbprefix('dim_product')." p on al.product_sk = p.product_sk and p.product_id = $productId
				left join ".$dwdb->dbprefix('dim_activity')." e1 on al.fromid = e1.activity_sk
				left join ".$dwdb->dbprefix('dim_activity')." e2 on al.toid = e2.activity_sk
				group by e1.activity_sk,e2.activity_sk,level
				order by e1.activity_name,level asc, count desc";
		return $dwdb->query($sql);
	}
	
}