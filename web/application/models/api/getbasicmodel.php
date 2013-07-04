<?php
class getbasicmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->database ();
	}
	
	function getbasicdata($sessionkey,$productid,$fromtime,$totime)
	{
		try
		{
			$userid=$this->common->getuseridbysessionkey($sessionkey);
			if($userid)
			{
				$verify=$this->common->verifyproductbyproductid($userid,$productid);
				if($verify)
				{
					$basic=$this->getbasicdatainfo($productid,$fromtime,$totime);
					if($basic)
					{
						$productinfo=array(
								'flag'=>2,
								'queryResult'=>$basic
						);
					}
					else
					{
						$productinfo=array(
								'flag'=>-4,
								'msg'=>'No data information'
						);
					}
				}
				else
				{
					$productinfo=array(
							'flag'=>-6,
							'msg'=>'Do not have permission'
					);
				}
				return $productinfo;
			}
			else
			{
				$productinfo=array(
						'flag'=>-2,
						'msg'=>'Sessionkey is invalide '
				);
				return $productinfo;
			}
		}
		catch (Exception $ex )
		{
			$productinfo=array(
					'flag'=>-3,
					'msg'=>'DB Error'
			);
			return $productinfo;
		}	
	}
	
	function getbasicdatainfo($productid,$fromtime,$totime)
	{
		$result=array();
		$ret=$this->getdetaildata($productid, $fromtime);
		if($ret)
		{
			array_push($result, $ret);
		}
		$toret=$this->getdetaildata($productid, $fromtime);
		if($toret)
		{
			array_push($result, $toret);
		}
		if(count($result)>0)
		{
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function getdetaildata($productid,$date)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select ifnull(p.sessions,0) sessions,
	            ifnull(p.startusers,0) activeusers,
	            ifnull(p.newusers,0) newusers,
                ifnull(p.upgradeusers,0) upgradeusers,
		        ifnull(p.usingtime,0) usingtime,
	            ifnull(p.allusers,0) allusers,
	            dd.datevalue date from 
	            " . $dwdb->dbprefix ( 'sum_basic_product' ) . " p 
	            inner join " . $dwdb->dbprefix ( 'dim_date' ) . " dd
	             on p.date_sk=dd.date_sk
	             where dd.datevalue='$date'
	             and p.product_id='$productid'";
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