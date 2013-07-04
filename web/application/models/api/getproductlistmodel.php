<?php
class getproductlistmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database ();
		$this->load->model('api/common','common');
	}
	
	function getproductinfo($sessionkey)
	{
		try
		{
			$userId=$this->common->getuseridbysessionkey($sessionkey);
			if($userId)
			{				
				$productdata=$this->getproductlistinfo($userId);
				if($productdata)
				{
					if(count($productdata)>0)
					{
						$productinfo=array(
								'flag'=>1,
								'queryresult'=>$productdata
						);
					}
					else 
					{
						$productinfo=array(
								'flag'=>-6,
								'msg'=>'Do not have permission'
						);
					}
					
				}
				else
				{
					$productinfo=array(
							'flag'=>-4,
							'msg'=>'No product information'
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
	function getproductlistinfo($userId)
	{			
		$getidsql = "select p.id,p.name,f.name platform
		from " . $this->db->dbprefix ( 'product' ) . "  p,
		" . $this->db->dbprefix ( 'platform' ) . "  f
		where p.product_platform = f.id and
		p.user_id='$userId' and p.active = 1
		order by p.id desc;";
		$result=$this->db->query($getidsql);
		if($result && $result->num_rows()>0)
		{
			$listinfo = array();
			$productinfo=$result->result_array();
			$ret=$this->common->compareproductid($productinfo,$userId);
			if($ret)
			{
				for($i=0;$i<count($ret);$i++)
				{
					for($j=0;$j<count($productinfo);$j++)
					{
						if($ret[$i]['id']==$productinfo[$j]['id'])
						{
							array_push($listinfo, $productinfo[$j]);
						}
					}
				}
			}	
			return $listinfo;			
		}
		else 
		{
			return false;
		}		
		
	}
	
	
}