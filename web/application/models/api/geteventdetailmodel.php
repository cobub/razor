<?php
class geteventdetailmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function geteventdetaildata($sessionkey, $productid, $fromtime, $totime,$eventid,$version)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->geteventdetailinfo($productid, $fromtime, $totime,$eventid,$version);
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

	function geteventdetailinfo($productid, $fromtime, $totime,$eventid,$version)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		if ($version==null)
		{
			$sql="select dd.datevalue,ifnull(ff.count,0) count			
				  from (select date_sk,datevalue
				  from  ".$dwdb->dbprefix('dim_date')."
					where datevalue between '$fromtime' and '$totime') dd
					left join (select d.date_sk,count(*) count
					from ".$dwdb->dbprefix('fact_event')." e,
					".$dwdb->dbprefix('dim_date')." d,
					".$dwdb->dbprefix('dim_event')." dm
					where d.datevalue between '$fromtime' and '$totime' 
					and d.date_sk=e.date_sk and
					e.event_sk='$eventid' and dm.event_sk='$eventid'
					and dm.product_id='$productid'
					group by d.date_sk) ff
					on dd.date_sk = ff.date_sk
					order by dd.date_sk";
		}
		else
		{
			$sql="select dd.datevalue,
				  ifnull(ff.count,0) count			
				  from (select date_sk,datevalue
				  from  ".$dwdb->dbprefix('dim_date')."
				  where datevalue between '$fromtime' and '$totime') dd
				  left join (select d.date_sk,count(*) count
				  from ".$dwdb->dbprefix('fact_event')." e,
				  ".$dwdb->dbprefix('dim_date')." d,
				  ".$dwdb->dbprefix('dim_event')." dm,
				  ".$dwdb->dbprefix('dim_product')." p
				  where d.datevalue between '$fromtime' and
				  '$totime' and d.date_sk=e.date_sk and
				  e.event_sk='$eventid' and
				  dm.event_sk='$eventid' and
				  p.product_id='$productid'
				  and p.product_active=1
				  and p.channel_active=1 and
				  p.version_active=1 and
				  p.version_name = '$version' and
				  p.product_sk=e.product_sk
				  group by d.date_sk) ff
				  on dd.date_sk = ff.date_sk
				 order by dd.date_sk";
			}
		$query = $dwdb->query ( $sql );
		if($query!=null&&$query->num_rows()>0)
		{
			$query=$query->result_array();
			return $query;
		}
		else
		{
			return false;
		}
	}

	
}