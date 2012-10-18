<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class networkmodel extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	// Get active user percent by carries
	function getActiveUserNetWorkType($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   n.networkname,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct nn.network_sk,ff.deviceidentifier)
						 from  " . $dwdb->dbprefix ( 'fact_clientdata' ) . "     ff,
									 " . $dwdb->dbprefix ( 'dim_product' ) . "  	 pp,
									 " . $dwdb->dbprefix ( 'dim_date' ) . "  	 dd,
									 " . $dwdb->dbprefix ( 'dim_network' ) . "  	 nn
						 where  ff.date_sk = dd.date_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and ff.network_sk = nn.network_sk and ff.product_sk = pp.product_sk
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   " . $dwdb->dbprefix ( 'fact_clientdata' ) . "      f,
		 " . $dwdb->dbprefix ( 'dim_product' ) . "  		  p,
			 " . $dwdb->dbprefix ( 'dim_date' ) . "  	  d,
			 " . $dwdb->dbprefix ( 'dim_network' ) . "  	  n
where    f.date_sk = d.date_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and f.product_sk = p.product_sk
				 and p.product_id = $productId
				 and f.network_sk = n.network_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by n.networkname
order by percentage desc limit 10;
		
                           
		";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	/*
	 * Get new user percent by carries
	 */
	function getNewUserNetWorkType($fromTime, $toTime, $productId, $count = REPORT_TOP_TEN) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   n.networkname,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct nn.network_sk,ff.deviceidentifier)
						 from " . $dwdb->dbprefix ( 'fact_clientdata' ) . "      ff,
								 " . $dwdb->dbprefix ( 'dim_product' ) . "  		 pp,
								 " . $dwdb->dbprefix ( 'dim_date' ) . "  		 dd,
								 " . $dwdb->dbprefix ( 'dim_network' ) . "  		 nn
						 where  ff.date_sk = dd.date_sk
										and dd.datevalue between '$fromTime' and '$toTime'
										and ff.network_sk = nn.network_sk and ff.product_sk = pp.product_sk
										and pp.product_id = $productId and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) percentage
from   " . $dwdb->dbprefix ( 'fact_clientdata' ) . "      f,
		 " . $dwdb->dbprefix ( 'dim_product' ) . "  		  p,
		 " . $dwdb->dbprefix ( 'dim_date' ) . "  		  d,
		 " . $dwdb->dbprefix ( 'dim_network' ) . "  		  n
where    f.date_sk = d.date_sk
				 and d.datevalue between '$fromTime' and '$toTime'
				 and f.product_sk = p.product_sk
				 and p.product_id = $productId
				 and f.network_sk = n.network_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1 and f.isnew=1
group by n.networkname
order by percentage desc limit 10;
		";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
	
	/*
	 * Get all network data
	 */
	function getALlNetWorkData($productid, $fromTime, $toTime) {
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select   n.networkname,
				 count(distinct f.deviceidentifier)
					/ (select count(distinct nn.network_sk,ff.deviceidentifier)
						 from  " . $dwdb->dbprefix ( 'fact_clientdata' ) . "     ff,
								 " . $dwdb->dbprefix ( 'dim_product' ) . "  		 pp,
								 		" . $dwdb->dbprefix ( 'dim_date' ) . "  		 dd,
								 " . $dwdb->dbprefix ( 'dim_network' ) . "  		 nn
						 where  ff.date_sk = dd.date_sk
										and dd.datevalue between '$fromTime' and '$toTime' and
ff.network_sk = nn.network_sk and ff.product_sk = pp.product_sk
										and pp.product_id = $productid and pp.product_active=1 and pp.channel_active=1 and pp.version_active=1) percentage
from   " . $dwdb->dbprefix ( 'fact_clientdata' ) . "      f,
		 " . $dwdb->dbprefix ( 'dim_product' ) . "  		  p,
		 		" . $dwdb->dbprefix ( 'dim_date' ) . "  		  d,
		 " . $dwdb->dbprefix ( 'dim_network' ) . "  		  n
where   f.date_sk = d.date_sk
				 and d.datevalue between '$fromTime' and '$toTime' and  f.product_sk = p.product_sk
				 and p.product_id = $productid
				 and f.network_sk = n.network_sk and p.product_active=1 and p.channel_active=1 and p.version_active=1
group by n.networkname
order by percentage desc;";
		
		$query = $dwdb->query ( $sql );
		return $query;
	}
}



?>