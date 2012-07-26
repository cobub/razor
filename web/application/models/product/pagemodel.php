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
	
	//获得所有版本的基本数据
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
	
	//获取各个版本的基本数据
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
			$accesscount=1;
			$avertime=1;
			$exitcount=1;
			for($i = 0; $i < count ( $arra ); $i ++) {
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
						if($all['version_name']==$versionname){
							$accesscount = $all['accesscount'];
							$avertime = $all['avertime'];
							$exitcount = $all['exitcount'];
							break;
						}
					}
				}
				if($row ['accesscount']==null){
					$tmp ['accesscount']=0;
				}else{
					$tmp ['accesscount'] = $row ['accesscount']."(".round($row ['accesscount']/$accesscount*100,2)."%)";
				}
			    if($row ['avertime']==null){
					$tmp ['avertime']=0;
				}else{
					$tmp ['avertime'] = round($row ['avertime'],2)."(".round($row ['avertime']/$avertime*100,2)."%)";
				}
				if($row ['exitcount']==null){
					$tmp ['exitcount']=0;
				}else{
					$tmp ['exitcount'] = $row ['exitcount']."(".round($row ['exitcount']/$exitcount*100,2)."%)";
				}
				$tmp ['activity_name'] = $row ['activity_name'];	
				//$tmp ['version_name'] = $row ['version_name'];
				array_push ( $content_arr [$versionname], $tmp );
			
			}
			$ret['content'] = $content_arr;
		}
		
		return $ret;
	
	}
	
}