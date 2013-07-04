<?php
class getusingtimemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function getusingtime($sessionkey, $productid, $fromtime, $totime)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getusingtimedata($productid, $fromtime, $totime);
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

	function getusingtimedata($productid, $fromtime, $totime)
	{		
		$dwdb = $this->load->database('dw',TRUE);
		$sql = "select   s.segment_sk,
                s.segment_name,
                count(fs.segment_sk) numbers,
                count(fs.segment_sk) / (select count(* )
                from   ".$dwdb->dbprefix('fact_usinglog_daily')." ff,
              	".$dwdb->dbprefix('dim_date') ." dd , 
                ".$dwdb->dbprefix('dim_product')."   pp
              where  dd.date_sk = ff.date_sk and dd.datevalue
               between '$fromtime' and '$totime' and
              		 pp.product_id = '$productid'
                     and pp.product_sk = ff.product_sk 
                     and pp.product_active=1 and pp.channel_active=1 
                     and pp.version_active=1) percentage
              from    ".$dwdb->dbprefix('dim_segment_usinglog')." s
              left join (select f.segment_sk
                    from  ".$dwdb->dbprefix('fact_usinglog_daily')."     f,
                          ".$dwdb->dbprefix('dim_product')."     p,
                          ".$dwdb->dbprefix('dim_date')." d 
                    where  d.date_sk = f.date_sk and d.datevalue 
                    between '$fromtime' and '$totime' 
                    and p.product_id = '$productid'
                    and p.product_sk = f.product_sk) fs
                     on fs.segment_sk = s.segment_sk
                    group by s.segment_sk,s.segment_name
                  order by s.segment_sk;";
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