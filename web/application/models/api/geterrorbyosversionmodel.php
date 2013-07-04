<?php
class geterrorbyosversionmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function geterrorbyosversions($sessionkey, $productid, $fromtime, $totime)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->geterrorosversionsdata($productid, $fromtime, $totime);
					if ($basic)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' => $basic
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

	function geterrorosversionsdata($productid, $fromtime, $totime)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select
              o.deviceos_name osversion,
              ifnull(count(f.id),0) num             
               from ".$dwdb->dbprefix('fact_errorlog')."  f, 
               ".$dwdb->dbprefix('dim_date')." d,
                ".$dwdb->dbprefix('dim_product')." p, 
                ".$dwdb->dbprefix('dim_deviceos')." o 
                where f.date_sk = d.date_sk and 
                d.datevalue between '$fromtime' and '$totime' 
                and f.product_sk = p.product_sk and
                 p.product_id = '$productid' and p.product_active=1
                  and p.channel_active=1 and p.version_active=1 
                  and f.osversion_sk = o.deviceos_sk 
                  group by osversion 
                  order by osversion";
		$query= $dwdb->query ($sql);
		if ($query != null && $query->num_rows() > 0)
		{
			$ret= $query->result_array();
			return $ret;
		}
		else
		{
			return false;
		}
	}


}