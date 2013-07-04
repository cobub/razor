<?php
class geterrordetailbyversionmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function geterrordetailbyversion($sessionkey, $productid, $fromtime, $totime)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
				    $basic = $this->geterrordetaildata($productid, $fromtime, $totime);
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

	function geterrordetaildata($productid, $fromtime, $totime)
	{		
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   f.title_sk errorid,
		         f.title errortitle, 	        
		         p.version_name version,
		         max(f.time) lastdate,
		         et.isfix,
		         count(f.stacktrace) num
		         from    ".$dwdb->dbprefix('fact_errorlog')." f,	
		         ".$dwdb->dbprefix('dim_errortitle')." et,	         
		         ".$dwdb->dbprefix('dim_product')." p,
		         ".$dwdb->dbprefix('dim_date')." d
		         where  f.product_sk = p.product_sk 
		         and f.title_sk = et.title_sk		         
		         and p.product_id = '$productid' 
		         and p.product_active=1 and p.channel_active=1 
		         and p.version_active=1 and f.date_sk = d.date_sk 
		         and d.datevalue between '$fromtime' and '$totime'
		         group by p.version_name,f.title_sk 
		         order by version_name desc, f.time desc;";			
		$query= $dwdb->query ($sql);
		if ($query != null && $query->num_rows() > 0)
		{
			$result=array();
			$ret= $query->result_array();
			for($i=0;$i<count($ret);$i++)
			{
				if($ret[$i]['isfix']==1)
				{
					$fix="true";
				}
				else
				{
					$fix="false";
				}
				$obj=array(
						"errorid"=>$ret[$i]['errorid'],
						"errortitle"=>$ret[$i]['errortitle'],
						"version"=>$ret[$i]['version'],
						"lastdate"=>$ret[$i]['lastdate'],
						"num"=>$ret[$i]['num'],
						"isfix"=>$fix
						 );
				array_push($result, $obj);
			}	
			return $result;
		}
		else
		{
			return false;
		}
	}


}