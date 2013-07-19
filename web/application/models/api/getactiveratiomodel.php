<?php
class getactiveratiomodel extends CI_Model
{
	function __construct() 
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	
	function getactiveinfo($sessionkey, $productid)
	 {
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid) 
		    {
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify) 
				{
					$basic = $this->getactivedata($productid);
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
	
	function getactivedata($productid)
	 {
	 	$dwdb = $this->load->database ( 'dw', TRUE );
        $sql="select  week_activeuser wusers,
                      month_activeuser musers,
                      week_percent wuserpercent,
                      month_percent muserpercent
		     from 
		     " . $dwdb->dbprefix ( 'sum_basic_activeusers' ) . " 
		     where product_id='$productid' ";
        $query = $dwdb->query ( $sql );
        if($query!=null&&$query->num_rows()>0)
        {
        	$query=$query->result_array();
        }
		return $query;
	}
	
	
}