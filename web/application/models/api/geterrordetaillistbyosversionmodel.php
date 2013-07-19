<?php
class geterrordetaillistbyosversionmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function geterrorosdetaillist($sessionkey, $productid, $fromtime, $totime,$titleid,$deviceosid)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->geterrorosdetaillistdata($productid, $fromtime, $totime,$titleid,$deviceosid);
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

	function geterrorosdetaillistdata($productid, $fromtime, $totime,$titleid,$deviceosid)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select f.stacktrace,
		      p.version_name  version,		      
		      f.time  datetime,
              b.devicebrand_name device
         from    
         ".$dwdb->dbprefix('fact_errorlog')." f,
         ".$dwdb->dbprefix('dim_deviceos')." o,
         ".$dwdb->dbprefix('dim_devicebrand')." b,
         ".$dwdb->dbprefix('dim_product')." p,
         ".$dwdb->dbprefix('dim_date')." d
         where    f.osversion_sk = o.deviceos_sk        
         and f.deviceidentifier = b.devicebrand_sk
         and f.product_sk = p.product_sk
         and f.title_sk = '$titleid'
         and o.deviceos_sk = '$deviceosid' and p.product_id = '$productid'
         and f.date_sk = d.date_sk and d.datevalue between '$fromtime' and '$totime'
         order by datetime desc;";
		$query = $dwdb->query ( $sql );
			if ($query != null && $query->num_rows() > 0)
			{
				$ret=$query->result_array();
				return $ret;
			}
			else
			{
			   return false;
			}
		}


	}