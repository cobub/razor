<?php
class getpagedatamodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function getdatapagedata($sessionkey, $productid, $fromtime, $totime,$version)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getpagedata($productid, $fromtime, $totime,$version);
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

	function getpagedata($productid, $fromtime, $totime,$version)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		if($version==null)
		{
			$sql="select 'all' version_name,
			a.activity_name  activity,
			sum(s.accesscount) count,
			sum(s.totaltime)/sum(s.accesscount) duration,
			sum(s.exitcount) exitcount
			from ".$dwdb->dbprefix('sum_usinglog_activity')." s,
			".$dwdb->dbprefix('dim_product')." p,
			".$dwdb->dbprefix('dim_date')." d,
			".$dwdb->dbprefix('dim_activity')." a
			where s.product_sk = p.product_sk and
			s.date_sk=d.date_sk and s.activity_sk = a.activity_sk
			and d.datevalue between '$fromtime' and '$totime'
			and p.product_id='$productid' 
			group by a.activity_name			     
			order by p.version_name,s.accesscount desc;";
		}	
		else
		{
			$sql="select a.activity_name  activity,
			sum(s.accesscount) count,
			sum(s.totaltime)/sum(s.accesscount) duration,
			sum(s.exitcount) exitcount
			from ".$dwdb->dbprefix('sum_usinglog_activity')." s,
			".$dwdb->dbprefix('dim_product')." p,
			".$dwdb->dbprefix('dim_date')." d,
			".$dwdb->dbprefix('dim_activity')." a
			where s.product_sk = p.product_sk and
			s.date_sk=d.date_sk and s.activity_sk = a.activity_sk
			and d.datevalue between '$fromtime' and '$totime'
			and p.product_id='$productid' 
			and p.version_name='$version' 
			group by a.activity_name			
			order by s.accesscount desc;";
		}	
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0) 
		{
			$ret=array();
			$query = $query->result_array ();
			for($i=0;$i<count($query);$i++)
			{
				$obj=array(
						"activity"=> $query[$i]['activity'],
						"exitcount"=> $query[$i]['exitcount'],
						"count"=> $query[$i]['count'],
						"duration"=>$query[$i]['duration']
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

}