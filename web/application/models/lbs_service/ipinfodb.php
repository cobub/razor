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
class ipinfodb extends CI_Model {
	function google() {
		parent::__construct ();
		$this->load->database ();
		$this->load->helper ( 'array' );
		$this->load->model ( 'service/utility', 'utility' );
	}
	// By ipinfodb - ip query location
	function getregioninfobyip($ip) {
		if ($ip == '')
			return FALSE;
		$key = 'b2dff8fe622f22b7db124cb5a7925779b21bad03f7be12f4fb36cae4c4118e92';
		$url = "http://api.ipinfodb.com/v3/ip-city/?key=" . $key . "&ip=" . $ip;
		$client = $this->utility->Post2 ( $url );
		
		if (! isset ( $client )) {
			$ret = array (
					'flag' => - 8,
					'msg' => 'Invalid regioninfo' 
			);
			// echo json_encode($ret);
			return FALSE;
		} else {
			$arr = explode ( ";", $client );
			if ($arr [0] != "OK") {
				$ret = array (
						'flag' => - 9,
						'msg' => 'Invalid IP' 
				);
				return FALSE;
			} else {
				$arr = $this->result2array ( $arr );
				return $arr;
			}
		}
	}
	
	function result2array($result) {
		if ($result == '') {
			return;
		}
		$data = array ();
		$data ["postal_code"] = '';
		$data ["country"] = $result [4];
		$data ["region"] = $result [5];
		$data ["city"] = $result [6];
		$data ["street"] = '';
		$data ["street_number"] = '';
		return $data;
	}
}

?>