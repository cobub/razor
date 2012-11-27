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
		$sql = "select 
	tt.version_name,
    ifnull(t.sessions,0) sessions,
	ifnull(t.startusers,0) startusers,
	ifnull(t.newusers,0) newusers,
    ifnull(t.upgradeusers,0) upgradeusers,
	ifnull(t.allusers,0) allusers
from (
	select 
p.version_name,
sum(sessions) sessions,
       sum(startusers) startusers,
       sum(newusers) newusers,
       sum(upgradeusers) upgradeusers,
       sum(allusers) allusers
	from ".$dwdb->dbprefix('sum_basic_all')."   s,
      ".$dwdb->dbprefix('dim_date')."   d,
       ".$dwdb->dbprefix('dim_product')."  p
	where  d.datevalue='$date'
       and d.date_sk = s.date_sk
       and p.product_id = $productId
       and p.product_sk = s.product_sk and p.product_active=1 and p.channel_active=1 and p.channel_active=1
	group by p.version_name) t
		right join (
			select distinct
                pp.version_name
           	from
				".$dwdb->dbprefix('dim_product')."  pp
            where pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) tt
        on tt.version_name = t.version_name
		order by tt.version_name desc;
		";
		
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
		$sql = "select d.datevalue,p.version_name,ifnull(sum(startusers),0) startusers,
       ifnull(sum(newusers),0) newusers	from  
       (select date_sk,datevalue from ".$dwdb->dbprefix('dim_date')." where datevalue
        between '$fromTime' and '$toTime') d cross join (
			select distinct pp.version_name, pp.product_sk
           	from ".$dwdb->dbprefix('dim_product')." pp where pp.product_id = $productid and
           	 pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) p
         left join ".$dwdb->dbprefix('sum_basic_all')." s  on d.date_sk = s.date_sk 
        and s.product_sk = p.product_sk group by d.datevalue, p.version_name
		order by d.datevalue asc,p.version_name desc";		
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
			p.version_name,
			ifnull(sum(startusers),0) startusers,
			ifnull(sum(newusers),0) newusers
			from ".$dwdb->dbprefix('dim_date')."   d inner join ".$dwdb->dbprefix('sum_basic_all')."  s on d.date_sk = s.date_sk and d.datevalue between '$from' and '$to'  right join (
			select distinct
			pp.version_name,
			pp.product_sk
			from ".$dwdb->dbprefix('dim_product')."
			pp
			where pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) p on
			s.product_sk = p.product_sk
			group by p.version_name
			order by p.version_name desc limit $version ";			
		}
		else
		{
			$sql = "select
			p.version_name,
			ifnull(sum(startusers),0) startusers,
			ifnull(sum(newusers),0) newusers
			from ".$dwdb->dbprefix('dim_date')."   d inner join ".$dwdb->dbprefix('sum_basic_all')."  s on d.date_sk = s.date_sk and d.datevalue between '$from' and '$to'  right join (
			select distinct
			pp.version_name,
			pp.product_sk
			from ".$dwdb->dbprefix('dim_product')."
					pp
					where pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) p on
					s.product_sk = p.product_sk
					group by p.version_name
					order by p.version_name desc";
		}		
		$query = $dwdb->query ( $sql );
		return $query;
		
	}
	
	function getNewAndActiveAllCount($productId,$from,$to)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select 
       ifnull(sum(startusers),0) startusers,
       ifnull(sum(newusers),0) newusers
	from ".$dwdb->dbprefix('dim_date')."   d,".$dwdb->dbprefix('sum_basic_all')."  s,".$dwdb->dbprefix('dim_product')."  p where  d.date_sk = s.date_sk and d.datevalue between '$from' and '$to'  and p.product_id = $productId and p.product_active=1 and p.channel_active=1 and p.version_active=1 and s.product_sk = p.product_sk;
		";
		
		$query = $dwdb->query ( $sql );
		return $query->result_array();
	}
	
}

?>