<?php
class getdatabyhourmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function getdatabyhour($sessionkey, $productid, $fromtime, $totime)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getdatabyhourinfo($productid, $fromtime, $totime);
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

	function getdatabyhourinfo($productid, $fromtime, $totime)
	{
		$ret=$this->getdetaildata($productid, $fromtime, $totime);
		$content=array();
		if($ret)
		{
			$content ['activeuser'] = array ();
			$content ['newuser'] = array ();
			$content ['sessions'] = array ();
			for($i=0;$i<count($ret);$i++)
			{

				$active=array(
						$ret[$i]['hour'].":00"=>$ret[$i]['activeuser']
				);
				
				$newuser=array(
						$ret[$i]['hour'].":00"=>$ret[$i]['newuser']
				);
				
				$sessions=array(
						$ret[$i]['hour'].":00"=>$ret[$i]['sessions']
				);							
				array_push($content ['activeuser'], $active);					
				array_push($content ['newuser'], $newuser);					
				array_push($content ['sessions'], $sessions);
			}
			
			return $content;
		}
		else
		{
			return false;
		}
	}
	
   function getdetaildata($productid, $fromtime, $totime)
   {
	   	$dwdb = $this->load->database('dw',TRUE);
	   	$sql = "select h.hour,
	   	ifnull(sum(startusers),0) activeuser,
	   	ifnull(sum(newusers),0) newuser,
	   	ifnull(sum(sessions),0) sessions
	   	from " . $dwdb->dbprefix ( 'dim_date' ) . "  d
	   	inner join " . $dwdb->dbprefix ( 'sum_basic_byhour' ) . " s
	   	on d.datevalue between '$fromtime' and '$totime'
	   	and d.date_sk = s.date_sk
	   	inner join " . $dwdb->dbprefix ( 'dim_product' ) . " p
	   			on p.product_id = '$productid' and
	   			p.product_sk = s.product_sk
	   			and p.product_active=1 and p.channel_active=1
	   			and p.version_active=1
	   			right join " . $dwdb->dbprefix ( 'hour24' ) . " h
	   			on h.hour=s.hour_sk group by h.hour order by h.hour";
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