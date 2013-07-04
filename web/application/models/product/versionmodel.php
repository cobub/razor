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
class versionmodel extends CI_Model {
	private $count;
	function __construct() {
		parent::__construct ();
		$this->load->model("common");
		$this->load->model('product/productmodel','product');
		 
	}
    //get basic info
	function getBasicVersionInfo($productId,$date)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select tt.version_name, 
			ifnull(t.sessions,0) sessions, 
			ifnull(t.startusers,0) startusers, 
			ifnull(t.newusers,0) newusers,
			ifnull(t.upgradeusers,0) upgradeusers,
			(select ifnull(max(allusers),0) from
			 ".$dwdb->dbprefix('dim_date')." da,
			 ".$dwdb->dbprefix('sum_basic_product_version')." pv 
			 where da.datevalue='$date' and
			  pv.date_sk<=da.date_sk and pv.product_id=$productId 
			  and pv.version_name=tt.version_name) allusers 
			from(select p.version_name,sessions,
			startusers,newusers, upgradeusers 
			from ".$dwdb->dbprefix('sum_basic_product_version')." s,
			".$dwdb->dbprefix('dim_date')." d,
			".$dwdb->dbprefix('dim_product')." p 
			where d.datevalue='$date' 
			and d.date_sk = s.date_sk 
			and s.product_id = $productId and
			 p.product_id = s.product_id 
			 and p.product_active=1 
			and p.channel_active=1 
			and p.version_active=1 
			and p.version_name=s.version_name
			 group by p.version_name) t
			  right join 
			( select distinct pp.version_name
			 from ".$dwdb->dbprefix('dim_product')." pp 
			where pp.product_id = $productId
			and pp.product_active=1 and
			 pp.channel_active=1 
			and pp.version_active=1) tt 
		on tt.version_name = t.version_name";	
		$query = $dwdb->query ( $sql );
		$basicRet = $query->result();		
		$ret = array();
		$totalusers = 0;
		$activeUsers = 0;
		if($basicRet!=null && count($basicRet)>0)
		{
			
			for($i=0;$i<count($basicRet);$i++)
			{
				$record = array();
				$record["version"] = $basicRet[$i]->version_name;
				$record["total"] = $basicRet[$i]->allusers;
				$record["new"] = $basicRet[$i]->newusers;
				$record["update"] = $basicRet[$i]->upgradeusers;
				$record["active"] = $basicRet[$i]->startusers;
				$record["start"] = $basicRet[$i]->sessions;

				array_push($ret, $record);
			}
		}
		return $ret;
	}
	//Get the report data
	function getVersionData($fromTime,$toTime,$productid)
	{  
		$ret=array();
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		//$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select d.datevalue,p.version_name,
				ifnull(startusers,0) startusers,
				ifnull(newusers,0) newusers			
				from (select date_sk,datevalue 
				from ".$dwdb->dbprefix('dim_date')."  where
				datevalue between '$fromTime' and '$toTime')  d 
				cross join 
				(select pp.version_name 
				from ".$dwdb->dbprefix('dim_product')." pp
				where pp.product_id =$productid and
				pp.product_active=1 and pp.channel_active=1
				 and pp.version_active=1 
				 group by pp.version_name) p
				left join (select * from 
				".$dwdb->dbprefix('sum_basic_product_version')." 
				where product_id=$productid) s  
				on d.date_sk = s.date_sk 
				and s.version_name = p.version_name	
				group by datevalue,p.version_name
		";				
		$query = $dwdb->query ( $sql );
	 if ($query != null && $query->num_rows > 0) {
			
			$arr = $query->result_array ();
			
			$content_arr = array ();
			for($i = 0; $i < count ( $arr ); $i ++) {
				$row = $arr [$i];
				$versionname = $row ['version_name'];
				$allkey = array_keys ( $content_arr );
				if (! in_array ( $versionname, $allkey ))
					$content_arr [$versionname] = array ();
				$tmp = array ();
				$tmp ['startusers'] = $row ['startusers'];
				$tmp ['datevalue'] = substr($row['datevalue'],0,10);
				$tmp ['newusers'] = $row ['newusers'];
				$tmp ['version_name'] = $row ['version_name'];
				array_push ( $content_arr [$versionname], $tmp );
			
			}
			$all_version_name = array_keys($content_arr);
		    $ret['content'] = $content_arr;
		
		}
	//	$ret ['title'] = $title;
		
		return $ret;
	
	}
	
	function getVersionContrast($productId,$from,$to,$version)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		if($version!=100)
		{
			$sql = "select 
            d.version_name,
	       ifnull(startusers,0) startusers,
	       ifnull(newusers,0) newusers
		  from
		( select version_name, product_id,
		  sum(startusers) startusers,
		  sum(newusers) newusers
		  from ".$dwdb->dbprefix('sum_basic_product_version')." v 
		  inner join ".$dwdb->dbprefix('dim_date')." d
	      on v.date_sk =d.date_sk and 
	      d.datevalue between '$from' and '$to'
	       where v.product_id=$productId
	        group by v.version_name
		     ) d 
		left join ".$dwdb->dbprefix('dim_product')." p 
		on d.version_name=p.version_name 
		and d.product_id = p.product_id
		where p.product_id = $productId
		 and p.product_active=1 
		and p.channel_active=1 and p.version_active=1 
		group by d.version_name
		order by startusers desc,newusers desc limit $version ";			
		}
		else
		{
			$sql = "select 
	            d.version_name,
		       ifnull(startusers,0) startusers,
		       ifnull(newusers,0) newusers
			  from
			( select version_name, product_id,
			  sum(startusers) startusers,
			  sum(newusers) newusers
			  from ".$dwdb->dbprefix('sum_basic_product_version')." v 
			  inner join ".$dwdb->dbprefix('dim_date')." d
		      on v.date_sk =d.date_sk and 
		      d.datevalue between '$from' and '$to'
		       where v.product_id=$productId
		        group by v.version_name
			     ) d 
			left join ".$dwdb->dbprefix('dim_product')." p 
			on d.version_name=p.version_name 
			and d.product_id = p.product_id
			where p.product_id = $productId
			 and p.product_active=1 
			and p.channel_active=1 and p.version_active=1 
			group by d.version_name
			order by startusers desc,newusers desc ";
		}		
		$query = $dwdb->query ( $sql );
		return $query;
		
	}
	
	function getNewAndActiveAllCount($productId,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
	    $sql=" select 
	    ifnull(sum(vv.startusers),0) startusers ,
	    ifnull(sum(vv.newusers),0) newusers
        from(
	       select 
	       ifnull(startusers,0) startusers,
	       ifnull(newusers,0) newusers
		  from
		( select version_name, product_id,
		  sum(startusers) startusers,
		  sum(newusers) newusers
		  from ".$dwdb->dbprefix('sum_basic_product_version')." v 
		  inner join ".$dwdb->dbprefix('dim_date')." d
	      on v.date_sk =d.date_sk and 
	      d.datevalue between '$from' and '$to'
	       where v.product_id=$productId
	        group by v.version_name
		     ) d 
		left join ".$dwdb->dbprefix('dim_product')." p 
		on d.version_name=p.version_name 
		and d.product_id = p.product_id
		where p.product_id = $productId
		 and p.product_active=1 
		and p.channel_active=1 and p.version_active=1 
		group by d.version_name	) vv";
		$query = $dwdb->query ( $sql );
		return $query->result_array();
	}
	
}

?>