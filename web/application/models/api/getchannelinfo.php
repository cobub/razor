<?php
class getchannelinfo extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
		$this->load->model('api/common','common');
		$this->load->database ();
	}
	
	function getchannel($sessionkey,$productid)
	{
		try
		{
			$userid=$this->common->getuseridbysessionkey($sessionkey);
			if($userid)
			{
				$verify=$this->common->verifyproductbyproductid($userid,$productid);
				if($verify)
				{
					$channel=$this->getchannelinfo($productid);
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
								'msg'=>'No channel information'
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
	
	function getchannelinfo($productid)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select channel_id,channel_name
		from " . $dwdb->dbprefix ( 'dim_product' ) . "
		where product_id='$productid'
		and product_active=1 and
		channel_active=1
		and version_active=1";		
		$query = $dwdb->query ( $sql );
		$query = $dwdb->query ( $sql );
		if($query && $query->num_rows()>0)
		{
			$ret=$query->result_array();
			return $ret;
		}
		else
		{
			return false;
		}
	}
}