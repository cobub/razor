<?php
class geterrorbydevicemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getdata($sessionkey,$productid,$fromtime,$totime){

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getErrordata($productid, $fromtime, $totime);
					
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
	
	
	
	function getErrordata( $productid, $fromtime, $totime)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select        
         count(f.stacktrace) errorcount,     
         o.devicebrand_name       
         from  ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_errortitle')." et,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d,
         ".$dwdb->dbprefix('dim_devicebrand')." o
         where    f.product_sk = p.product_sk and f.title_sk = et.title_sk
         and p.product_id = '$productid'  and p.product_active=1 and
          p.channel_active=1 and p.version_active=1 and 
          f.date_sk = d.date_sk and d.datevalue between '$fromtime' 
          and '$totime' and f.deviceidentifier=o.devicebrand_sk 
         group by   o.devicebrand_name order by errorcount desc;";

		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0)
		{
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
				$obj=array(
					
						"device"=>$queryarr[$i]['devicebrand_name'],
						"num"=>$queryarr[$i]['errorcount']
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