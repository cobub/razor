<?php
class geterrordistributionbydevicemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getdata($sessionkey,$productid,$fromtime,$totime,$erroridentifier){

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basicAppOS = $this->getversiondata($productid, $fromtime, $totime,$erroridentifier);
					$basicDeviceOS = $this->getosversionData($productid,$fromtime,$totime,$erroridentifier);
					
					if ($basicDeviceOS)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' =>array(
										'versiondistribution'=>$basicAppOS,
										'osversiondistribution'=>$basicDeviceOS
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
	
	function getosversionData($productid,$fromtime,$totime,$erroridentifier)
	{
		$dwdb = $this->load->database('dw',TRUE);
		$sql="select  f.stacktrace,d.datevalue,
		o.deviceos_name,
		ifnull(count(f.id),0) errorcount
		from ".$dwdb->dbprefix('fact_errorlog')."  f,
		".$dwdb->dbprefix('dim_date')." d,
		".$dwdb->dbprefix('dim_product')." p,
				".$dwdb->dbprefix('dim_deviceos')." o
				where f.date_sk = d.date_sk and
				d.datevalue between '$fromtime' and '$totime'
				and f.product_sk = p.product_sk and
				p.product_id = '$productid' and p.product_active=1 and f.title_sk='$erroridentifier'
				and p.channel_active=1 and p.version_active=1
				and f.osversion_sk = o.deviceos_sk
				group by o.deviceos_name
				order by o.deviceos_name";		
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0){
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
			$obj=array(
					"osversion"=>$queryarr[$i]['deviceos_name'],
					"datetime"=> $queryarr[$i]['datevalue'],
					"stracktrace"=> $queryarr[$i]['stacktrace'],
					"num"=>$queryarr[$i]['errorcount']
			);
					array_push($ret, $obj);
			}
			return $ret;
		}
		else
		{
			return false;
		}
	}
	
	
	function getversiondata( $productid, $fromtime, $totime,$erroridentifier)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   f.title,f.stacktrace,d.datevalue,
         f.title_sk,et.isfix,
         count(f.stacktrace) errorcount,
         p.version_name,
         max(f.time) time
         from    ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
         where  f.product_sk = p.product_sk and f.title_sk = et.title_sk 
         and p.product_id = '$productid'  
         and p.product_active=1 and p.channel_active=1 and f.title_sk='$erroridentifier'
         and p.version_active=1 and f.date_sk = d.date_sk 
         and d.datevalue between '$fromtime' and '$totime'
         group by p.version_name,f.title_sk 
         order by version_name desc, f.time desc;
		";	
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0){
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
				$obj=array(
						"datetime"=>$queryarr[$i]['datevalue'],
						"version"=> $queryarr[$i]['version_name'],
						"num"=> $queryarr[$i]['errorcount'],
						"stacktrace"=>$queryarr[$i]['stacktrace']
				);
			array_push($ret, $obj);
			}
			return $ret;
		}else{
			return  false;
		}
	}

}