<?php
class getbasicversion extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->database ();
	}

	function getversiondata($sessionkey,$productid,$fromtime,$totime)
	{
		try
		{
			$userid=$this->common->getuseridbysessionkey($sessionkey);
			if($userid)
			{
				$verify=$this->common->verifyproductbyproductid($userid,$productid);
				if($verify)
				{
					$version=$this->getversioninfo($productid,$fromtime,$totime);
					if($version)
					{
						$productinfo=array(
								'flag'=>2,
								'queryResult'=>$version
						);
					}
					else
					{
						$productinfo=array(
								'flag'=>-4,
								'msg'=>'No version data information'
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

	function getversioninfo($productid,$fromtime,$totime)
	{
		$versionname=$this->getversionbyproductid($productid);
		$content=array();
		$fromret = $this->getdetailversion($productid, $fromtime);
		$toret   = $this->getdetailversion($productid, $totime);
		if($versionname)
		{
			for($i=0;$i<count($versionname);$i++)
			{
				$content[$versionname[$i]['version_name']] = array ();
				if($fromret)
				{
					for($j=0;$j<count($fromret);$j++)
					{
						if($versionname[$i]['version_name']==$fromret[$j]['version_name'])
						{
							$obj=array(  'datevalue'=>$fromret[$j]['datevalue'],
									'activeusers'=>$fromret[$j]['activeusers'],
									'newusers'=>$fromret[$j]['newusers'],
									'sessions'=>$fromret[$j]['sessions'],
									'upgradeusers'=>$fromret[$j]['upgradeusers'],
									'allusers'=>$fromret[$j]['allusers'],
									'allsessions'=>$fromret[$j]['allsessions'],
									'usingtime'=>$fromret[$j]['usingtime']
							);
							array_push($content[$versionname[$i]['version_name']], $obj);
							break;
						}
					}
				}
				if($toret)
				{
					for($j=0;$j<count($toret);$j++)
					{
						if($versionname[$i]['version_name']==$toret[$j]['version_name'])
						{
							$obj=array(  'datevalue'=>$toret[$j]['datevalue'],
									'activeusers'=>$toret[$j]['activeusers'],
									'newusers'=>$toret[$j]['newusers'],
									'sessions'=>$toret[$j]['sessions'],
									'upgradeusers'=>$toret[$j]['upgradeusers'],
									'allusers'=>$toret[$j]['allusers'],
									'allsessions'=>$toret[$j]['allsessions'],
									'usingtime'=>$toret[$j]['usingtime']
							);
							array_push($content[$versionname[$i]['version_name']], $obj);
							break;
						}
					}
				}

			}
			return $content;

		}
		else
		{
			return false;
		}
			
	}
	function getdetailversion($productid,$date)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select c.version_name, ifnull(c.sessions,0) sessions,
		ifnull(c.startusers,0) activeusers,
		ifnull(c.newusers,0) newusers,
		ifnull(c.upgradeusers,0) upgradeusers,
		ifnull(c.usingtime,0) usingtime,
		ifnull(c.allusers,0) allusers,
		ifnull(c.allsessions,0) allsessions,
		dd.datevalue datevalue from
		" . $dwdb->dbprefix ( 'sum_basic_product_version' ) . " c
		inner join " . $dwdb->dbprefix ( 'dim_date' ) . " dd
		on c.date_sk=dd.date_sk
		where dd.datevalue='$date'
		and c.product_id='$productid'";
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
	function getversionbyproductid($productid)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct version_name
		from " . $dwdb->dbprefix ( 'sum_basic_product_version' ) . "
		where product_id='$productid'";
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