<?php
class getretentionmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}
	function getretentiondata($sessionkey,$productid,$fromtime,$totime,$type){

		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getDataOfRetention($productid, $fromtime, $totime,$type);
					
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
	
	function getDataOfRetention($productid,$fromtime,$totime,$type){
		$dwdb = $this->load->database('dw',TRUE);
		if($type=="week"){
			$sql="select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			
			f.usercount,
			f.week1,
			f.week2,
			f.week3,
			f.week4,
			f.week5,
			f.week6,
			f.week7,
			f.week8
			from  ".$dwdb->dbprefix('fact_reserveusers_weekly')."   f,
			".$dwdb->dbprefix('dim_date')."    d1,
			".$dwdb->dbprefix('dim_date')."    d2
			where  f.startdate_sk = d1.date_sk
			and f.enddate_sk = d2.date_sk
			and d1.datevalue >= '$fromtime'
			and d2.datevalue <= '$totime'
			and f.product_id = '$productid'
			order by d1.datevalue;";
		}
		else if ($type=="month")
		{
			$sql="	select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			
			f.usercount,
			f.month1,
			f. month2,
			f.month3,
			f.month4,
			f.month5,
			f.month6,
			f.month7,
			f.month8
			from ".$dwdb->dbprefix('fact_reserveusers_monthly')."   f,
			".$dwdb->dbprefix('dim_date')."    d1,
			".$dwdb->dbprefix('dim_date')."     d2
			where  f.startdate_sk = d1.date_sk
			and f.enddate_sk = d2.date_sk
			and d1.datevalue >= '$fromtime'
			and d2.datevalue <= '$totime'
			and f.product_id = '$productid'
			order by d1.datevalue;";
		}
		  $query = $dwdb->query ( $sql );
		  if($query!=null&&$query->num_rows()>0)
		  {
		  	$query=$query->result_array();
		  	$ret = array();
		  	for($i=0;$i<count($query);$i++)
		  	{
		  		if($type=="week")
		  		{
		  			$obj = array(
		  					'id'=>$i+1,
		  					'startdata'=>$query[$i]['startdate'],
		  					'enddate'=>$query[$i]['enddate'],
		  					'newuser'=>$query[$i]['usercount'],
		  					'1'=>$query[$i]['week1'],
		  					'2'=>$query[$i]['week2'],
		  					'3'=>$query[$i]['week3'],
		  					'4'=>$query[$i]['week4'],
		  					'5'=>$query[$i]['week5'],
		  					'6'=>$query[$i]['week6'],
		  					'7'=>$query[$i]['week7'],
		  					'8'=>$query[$i]['week8']
		  			);
		  		}
		  		if($type=="month")
		  		{
		  			$obj = array(
		  					'id'=>$i+1,
		  					'startdata'=>$query[$i]['startdate'],
		  					'enddate'=>$query[$i]['enddate'],
		  					'newuser'=>$query[$i]['usercount'],
		  					'1'=>$query[$i]['month1'],
		  					'2'=>$query[$i]['month2'],
		  					'3'=>$query[$i]['month3'],
		  					'4'=>$query[$i]['month4'],
		  					'5'=>$query[$i]['month5'],
		  					'6'=>$query[$i]['month6'],
		  					'7'=>$query[$i]['month7'],
		  					'8'=>$query[$i]['month8']
		  			);
		  		}
		  		
		  		array_push($ret, $obj);
		  	}
		  	return $ret;
		  }
		  else
		  {
		  	return false;
		  }
 		
	}
	
	
}