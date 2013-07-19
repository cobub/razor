<?php
class geterrordetaillistbydevicemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getdata($sessionkey,$productid,$fromtime,$totime,$erroridentifier)
	{

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getversiondata($productid, $fromtime, $totime,$erroridentifier);
					
					if ($basic)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' =>$basic
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
	
	
	
	function getversiondata( $productid, $fromtime, $totime,$erroridentifier)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   f.title,f.stacktrace,d.datevalue,dos.deviceos_name,
         f.title_sk,et.isfix,
         count(f.stacktrace) errorcount,
         p.version_name,
         max(f.time) time
         from    ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('dim_product')." p,
          ".$dwdb->dbprefix('dim_deviceos')." dos,
         ".$dwdb->dbprefix('dim_date')." d
         where  f.product_sk = p.product_sk and f.title_sk = et.title_sk 
         and p.product_id = '$productid'  
         and dos.deviceos_sk=f.deviceidentifier
         and p.product_active=1 and p.channel_active=1 and f.title_sk='$erroridentifier'
         and p.version_active=1 and f.date_sk = d.date_sk 
         and d.datevalue between '$fromtime' and '$totime'
         group by p.version_name,f.title_sk 
         order by version_name desc, f.time desc;
		";        
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0)
		{
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
				$obj=array(
						"datetime"=>$queryarr[$i]['time'],
						"version"=> $queryarr[$i]['version_name'],
						"osversion"=>$queryarr[$i]['deviceos_name'],
						"stacktrace"=>$queryarr[$i]['stacktrace']
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