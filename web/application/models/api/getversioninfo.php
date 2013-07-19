<?php
class getversioninfo extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('api/common','common');
		$this->load->database ();
	}
	
	function getversion($sessionkey,$productid)
	{
		try
		{
			$userid=$this->common->getuseridbysessionkey($sessionkey);
			if($userid)
			{
				$verify=$this->common->verifyproductbyproductid($userid,$productid);
				if($verify)
				{
					$version=$this->getversioninfo($productid);
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
								'msg'=>'No version information'
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
	
	function getversioninfo($productid)
	{		
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select distinct version_name
		from ".$dwdb->dbprefix('dim_product')." 
		where product_id = '$productid'
		and product_active=1 and
		channel_active=1
		and version_active=1";
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