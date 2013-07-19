<?php
class geterrordetailbydevicemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getdata($sessionkey,$productid,$fromtime,$totime){

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getErrordata($productid, $fromtime, $totime);
					
					if ($basic)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' =>$basic
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
	
	
	
	function getErrordata( $productid, $fromtime, $totime)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql="select   f.time,et.isfix, ifnull(count(f.id),0) errorcount,
         o.deviceos_name,
         p.version_name,
         f.title,f.title_sk
		from     
		         ".$dwdb->dbprefix('dim_errortitle')." et,
		         ".$dwdb->dbprefix('fact_errorlog')." f,
		         ".$dwdb->dbprefix('dim_deviceos')." o,
		         ".$dwdb->dbprefix('dim_product')." p,
		         ".$dwdb->dbprefix('dim_date')." d
		where    f.osversion_sk = o.deviceos_sk
		         and f.title_sk = et.title_sk
		         and f.product_sk = p.product_sk
		          and p.product_id = '$productid'
		          and f.date_sk = d.date_sk and d.datevalue between '$fromtime' and '$totime'
		            group by p.version_name 
		ORDER BY f.time desc;";
		
		
		$query = $dwdb->query ( $sql );
		if ($query != null && $query->num_rows () > 0)
		{
			$ret=array();
			$queryarr  = $query->result_array();
			for($i=0;$i<count($queryarr);$i++)
			{
				$obj=array(
						"errorid"=>$queryarr[$i]['title_sk'],
						"errortitle"=> $queryarr[$i]['title'],
						"device"=>$queryarr[$i]['deviceos_name'],
						"num"=>$queryarr[$i]['errorcount'],
						"isfix"=>$queryarr[$i]['isfix']==0?false:true,
						"lastdate"=>$queryarr[$i]['time']
				);
			array_push($ret, $obj);
			}
			return $ret;
		}
		else
		{
			return  false;
		}
	}

}