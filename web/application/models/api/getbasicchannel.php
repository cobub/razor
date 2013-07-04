<?php
class getbasicchannel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->database ();
	}
	
	function getchanneldata($sessionkey,$productid,$fromtime,$totime)
	{
		try
		{
			$userid=$this->common->getuseridbysessionkey($sessionkey);			
			if($userid)
			{
				$verify=$this->common->verifyproductbyproductid($userid,$productid);
				if($verify)
				{
					$channel=$this->getbasicchannelinfo($productid,$fromtime,$totime);
					if($channel)
					{
						$productinfo=array(
								'flag'=>2,
								'queryResult'=>$channel
						);
					}
					else
					{
						$productinfo=array(
								'flag'=>-4,
								'msg'=>'No channel data information'
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
	
	function getbasicchannelinfo($productid,$fromtime,$totime)
	{
		$channelid=$this->getchannelidbyproductid($productid);
		$content=array();
		$fromret = $this->getdetailchannel($productid, $fromtime);
		$toret   = $this->getdetailchannel($productid, $totime);
		if($channelid)
		{
			for($i=0;$i<count($channelid);$i++)
			{
				$content[$channelid[$i]['channel_id']] = array ();
				if($fromret)
				{
					for($j=0;$j<count($fromret);$j++)
					{
						if($channelid[$i]['channel_id']==$fromret[$j]['channel_id'])
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
							array_push($content[$channelid[$i]['channel_id']], $obj);
							break;
						}
					}
				}
				if($toret)
				{
					for($j=0;$j<count($toret);$j++)
					{
						if($channelid[$i]['channel_id']==$toret[$j]['channel_id'])
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
							array_push($content[$channelid[$i]['channel_id']], $obj);
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
	function getdetailchannel($productid,$date)
	{		
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select c.channel_id, ifnull(c.sessions,0) sessions,
		ifnull(c.startusers,0) activeusers,
		ifnull(c.newusers,0) newusers,
		ifnull(c.upgradeusers,0) upgradeusers,
		ifnull(c.usingtime,0) usingtime,
		ifnull(c.allusers,0) allusers,
		ifnull(c.allsessions,0) allsessions,
		dd.datevalue datevalue from
		" . $dwdb->dbprefix ( 'sum_basic_channel' ) . " c
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
	function getchannelidbyproductid($productid)	
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct channel_id
		from " . $dwdb->dbprefix ( 'sum_basic_channel' ) . "
		where product_id=$productid";
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