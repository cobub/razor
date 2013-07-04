<?php
class getbasicchannelactive extends CI_Model
{
	function __construct() 
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	
	function getchannelactive($sessionkey, $productid, $fromtime, $totime, $type)
	 {
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid) 
		    {
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify) 
				{
					$basic = $this->getchanneldatainfo ( $productid, $fromtime, $totime, $type );
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
	
	function getchanneldatainfo($productid, $fromtime, $totime, $type)
	 {
	 	$content=array();
		if ($type == "week") 
		{
			$flag = 0;
		} 
		else
		{
			$flag = 1;
		}
		$channelid=$this->getchannelidbyproductid($productid, $flag);
		$fromret=$this->getdetailchanneldata($fromtime,$totime, $flag, $productid);		
		if ($channelid) 
		{
			for($i = 0; $i < count ( $channelid ); $i ++)
			 {
				$content [$channelid [$i] ['channel_id']] = array ();
				if ($fromret) 
				{
					for($j = 0; $j < count ( $fromret ); $j ++)
					 {
						if ($channelid [$i] ['channel_id'] == $fromret [$j] ['channel_id'])
						 {
							$obj = array (
									'datevalue' => $fromret [$j] ['datevalue'],
									'activeusers' => $fromret [$j] ['activeuser'],
									'rate' => round($fromret [$j] ['percent'],2)									 
							);
							array_push ( $content [$channelid [$i] ['channel_id']], $obj );						
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
	
	function getdetailchanneldata($fromtime, $totime, $flag, $productid)
	 {
		
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select c.channel_id, ifnull( c.percent, 0 ) percent, c.activeuser, d.datevalue
              from  " . $dwdb->dbprefix ( 'sum_basic_channel_activeusers' ) . " c
              inner join " . $dwdb->dbprefix ( 'dim_date' ) . " d 
              on c.date_sk = d.date_sk
              where c.flag ='$flag'
              and c.product_id ='$productid'
              and d.datevalue between '$fromtime' and '$totime'";
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
	
	function getchannelidbyproductid($productid, $flag) 
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct channel_id
		from " . $dwdb->dbprefix ( 'sum_basic_channel_activeusers' ) . "
		where product_id='$productid' and flag='$flag'";
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