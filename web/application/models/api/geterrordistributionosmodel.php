<?php
class geterrordistributionosmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function geterrorosdistribution($sessionkey, $productid, $fromtime, $totime,$titleid,$version)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->geterrorosdistributiondata($productid, $fromtime, $totime,$titleid,$version);
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

	function geterrorosdistributiondata($productid, $fromtime, $totime,$titleid,$version)
	{
		$device=$this->getdeviceinfoversion($productid, $fromtime, $totime, $titleid, $version);
		$osinfo=$this->getosinfoversion($productid, $fromtime, $totime, $titleid, $version);
		$content=array();
		$content['versiondis']=array();
		$content['devicedis']=array();
		if($device)
		{
			for($i=0;$i<count($device);$i++)
			{
			$obj=array(
					'device'=>$device[$i]['device'],
					'num'=>$device[$i]['num']
			);
			array_push($content['devicedis'], $obj);
			}
			}
			if($osinfo)
			{
			for($i=0;$i<count($osinfo);$i++)
			{
			$obj=array(
			'version'=>$osinfo[$i]['version'],
				'num'=>$osinfo[$i]['num']
				);
				array_push($content['versiondis'], $obj);
			}
			}
			if(count($content)>0)
			{
			return  $content;
			}
			else
			{
			return false;
}

}

function getdeviceinfoversion($productid,$fromtime,$totime,$titleid, $deviceosid)
	{
	$dwdb = $this->load->database ( 'dw', TRUE );
	$sql="select   o.devicebrand_name device,
          count(* ) num         
		  from     ".$dwdb->dbprefix('fact_errorlog')." f,
		         ".$dwdb->dbprefix('dim_devicebrand')." o,
		         ".$dwdb->dbprefix('dim_product')." p,
		         ".$dwdb->dbprefix('dim_date')." d
		  where    f.product_sk = p.product_sk 
		         and f.title_sk = '$titleid'
		         and f.deviceidentifier = o.devicebrand_sk
		         and p.product_id = '$productid'
		         and f.osversion_sk = '$deviceosid'
		         and f.date_sk = d.date_sk
		         and d.datevalue between '$fromtime' and '$totime'
		   group by device  order by num desc;";
	       $query = $dwdb->query ( $sql );
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
function getosinfoversion($productid,$fromtime,$totime,$titleid, $deviceosid)
{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   p.version_name version,
		      count(* ) num		         
			  from     ".$dwdb->dbprefix('fact_errorlog')." f,
				         ".$dwdb->dbprefix('dim_product')." p,
				         ".$dwdb->dbprefix('dim_date')." d
			  where    f.product_sk = p.product_sk 
				         and f.title_sk = '$titleid'
				         and p.product_id = '$productid'
				         and f.osversion_sk = '$deviceosid'
				         and f.date_sk = d.date_sk
				         and d.datevalue between '$fromtime' and '$totime'
			  group by version
			  order by num desc;";
		$query = $dwdb->query ( $sql );
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