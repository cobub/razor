<?php
class getfrequencymodel extends CI_Model
{
		function __construct()
		{
			parent::__construct ();
			$this->load->model ( 'api/common', 'common' );
			$this->load->database ();
		}
	
		function getfrequencyinfo($sessionkey, $productid, $fromtime, $totime)
		{
			try {
				$userid = $this->common->getuseridbysessionkey ( $sessionkey );
				if ($userid)
				{
					$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
					if ($verify)
					{
						$basic = $this->getfrequencydata($productid, $fromtime, $totime);
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
	
	function getfrequencydata($productid, $fromtime, $totime)
	{
		$dwdb = $this->load->database('dw',TRUE);		
		$sql = "select s.segment_sk,s.segment_name,
		ifnull(sum(f.accesscount),0) access,
		ifnull(sum(f.accesscount),0)
		/ (select sum(ifnull(ff.accesscount,0))
		from   " . $dwdb->dbprefix ( 'fact_launch_daily' ) . " ff,"
		.$dwdb->dbprefix('dim_date'). " dd,  "
		. $dwdb->dbprefix ( 'dim_product' ) . " pp
		where
		ff.date_sk = dd.date_sk and dd.datevalue
		 between '$fromtime' and '$totime' and
		ff.product_sk = pp.product_sk
		and pp.product_id = '$productid'
		and pp.product_active=1
		and pp.channel_active=1
		and pp.version_active=1) percentage
		from   " . $dwdb->dbprefix ( 'fact_launch_daily' ) . " f
		inner join " . $dwdb->dbprefix ( 'dim_product' ) . " p
		on f.product_sk = p.product_sk
		inner join ". $dwdb->dbprefix('dim_date') . " d
		on f.date_sk = d.date_sk
		and d.datevalue between '$fromtime' and '$totime'
		and p.product_id = '$productid'
		and p.product_active=1
		and p.channel_active=1
		and p.version_active=1
		right join " . $dwdb->dbprefix ( 'dim_segment_launch' ) . " s
				on f.segment_sk = s.segment_sk
				group by s.segment_sk
				order by s.segment_sk;";		
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0)
		{
			$query = $query->result_array ();
			return $query;
		}
		else
		{
			return false;
		}		
	}
}