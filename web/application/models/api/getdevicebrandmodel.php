<?php
class getdevicebrandmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getdataofdeviceBrand($sessionkey,$productid,$fromtime,$totime,$limit){

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basicAct = $this->getdeviceBrandActivedata($productid, $fromtime, $totime,$limit);
					$basicNew = $this->getdeviceBrandNewData($productid,$fromtime,$totime,$limit);
					
					if ($basicAct)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' =>array(
										'newusers'=>$basicNew,
										'activeusers'=>$basicAct
										)
						);
					}
					else
					{
						$productinfo = array (
								'flag' => - 4,
								'msg' => 'No data information'
						);
					}
				}
				else
				{
					$productinfo = array (
							'flag' => - 6,
							'msg' => 'Do not have permission'
					);
				}
				return $productinfo;
			}
			else
			{
				$productinfo = array (
						'flag' => - 2,
						'msg' => 'Sessionkey is invalide '
				);
				return $productinfo;
			}
		}
		catch ( Exception $ex )
		{
			$productinfo = array (
					'flag' => - 3,
					'msg' => 'DB Error'
			);
			return $productinfo;
		}
		
	}
	
	function getdeviceBrandNewData($productid,$fromtime,$totime,$limit)
	{
		$dwdb = $this->load->database('dw',TRUE);
		if($limit==null){
			$sql = "select   b.devicebrand_name,
			count(distinct f.deviceidentifier)  access
			from   ".$dwdb->dbprefix('fact_clientdata')."      f,
			".$dwdb->dbprefix('dim_date')."  		  d,
			".$dwdb->dbprefix('dim_product')."  		  p,
			".$dwdb->dbprefix('dim_devicebrand')."  		  b
			where    f.date_sk = d.date_sk
			and f.product_sk = p.product_sk
			and f.devicebrand_sk = b.devicebrand_sk
			and d.datevalue between '$fromtime' and '$totime'
			and p.product_id = '$productid' and p.product_active=1 
			and p.channel_active=1 and p.version_active=1 and f.isnew=1
			group by b.devicebrand_name
			order by access desc;
			";
		}else{
			$sql = "select   b.devicebrand_name,
			count(distinct f.deviceidentifier)  access
			from   ".$dwdb->dbprefix('fact_clientdata')."      f,
			".$dwdb->dbprefix('dim_date')."  		  d,
			".$dwdb->dbprefix('dim_product')."  		  p,
			".$dwdb->dbprefix('dim_devicebrand')."  		  b
			where    f.date_sk = d.date_sk
			and f.product_sk = p.product_sk
			and f.devicebrand_sk = b.devicebrand_sk
			and d.datevalue between '$fromtime' and '$totime'
			and p.product_id = '$productid' and p.product_active=1 
			and p.channel_active=1 and p.version_active=1 and f.isnew=1
			group by b.devicebrand_name
			order by access desc limit 0,$limit;
			";
		}
		
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0){
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
			$obj=array(
					"id"=>$i+1,
					"name"=> $queryarr[$i]['devicebrand_name'],
					"num"=> $queryarr[$i]['access']
			);
					array_push($ret, $obj);
			}
			return $ret;
		}else{
			return false;
		}
	}
	
	
	function getdeviceBrandActivedata( $productid, $fromtime, $totime,$limit)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		if($limit==null)
		{
			$sql = "select   b.devicebrand_name , count(distinct f.deviceidentifier) access
			from   ".$dwdb->dbprefix('fact_clientdata')."      f,
			".$dwdb->dbprefix('dim_date')."  		  d,
			".$dwdb->dbprefix('dim_product')."  		  p,
			".$dwdb->dbprefix('dim_devicebrand')."  		  b
			where    f.date_sk = d.date_sk
			and f.product_sk = p.product_sk
			and f.devicebrand_sk = b.devicebrand_sk
			and d.datevalue between '$fromtime' and '$totime'
			and p.product_id = '$productid' and p.product_active=1 
			and p.channel_active=1 and p.version_active=1
			group by b.devicebrand_name order by access desc;";
		}
		else
		{
			$sql = "select   b.devicebrand_name, count(distinct f.deviceidentifier) access
			from   ".$dwdb->dbprefix('fact_clientdata')."      f,
			".$dwdb->dbprefix('dim_date')."  		  d,
			".$dwdb->dbprefix('dim_product')."  		  p,
			".$dwdb->dbprefix('dim_devicebrand')."  		  b
			where    f.date_sk = d.date_sk
			and f.product_sk = p.product_sk
			and f.devicebrand_sk = b.devicebrand_sk
			and d.datevalue between '$fromtime' and '$totime'
			and p.product_id = '$productid' and p.product_active=1 and p.channel_active=1 and p.version_active=1
			group by b.devicebrand_name order by access desc  limit 0,$limit;";
		}
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0)
		{
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
				$obj=array(
						"id"=>$i+1,
						"name"=> $queryarr[$i]['devicebrand_name'],
						"num"=> $queryarr[$i]['access']
				);
			array_push($ret, $obj);
			}
			return $ret;
		}
		else
		{
			return  false;
		}
	}

}