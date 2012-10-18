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
class google extends CI_Model {
	function google() {
		parent::__construct ();
		$this->load->database ();
		$this->load->helper ( 'array' );
		$this->load->model ( 'service/utility', 'utility' );
	}
	// google services - by latitude and longitude location
	function getregioninfo($latitude, $longitude) {
		$configlanguage = $this->config->item ( 'language' );
		if ($configlanguage == "en_US") {
			$configlanguage = "EN";
		} elseif ($configlanguage == "zh_CN") {
			$configlanguage = "CN";
		}
		$preurl = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=true';
		if ($configlanguage != '') {
			$preurl = $preurl . '&language=' . $configlanguage;
		}
		
		$client = $this->utility->Post2 ( $preurl );
		
		if (! isset ( $client )) {
			$ret = array (
					'flag' => - 8,
					'msg' => 'Invalid regioninfo' 
			);
			// echo json_encode($ret);
			return FALSE;
		} else {
			$arr = json_decode ( $client );
			$arr = $this->result2array ( $arr );
			return $arr;
		}
	}
	function result2array($result) {
		if ($result == '') {
			return;
		}
		$data = array ();
		$result = $result->results;
		$result = $result [0];
		$result = $result->address_components;
		$length = sizeof ( $result );
		$data ["postal_code"] = '';
		$data ["country"] = '';
		$data ["region"] = '';
		$data ["city"] = '';
		$data ["street"] = '';
		$data ["street_number"] = '';
		for($i = 0; $i < sizeof ( $result ); $i ++) {
			$geoname = $result [$i]->long_name;
			$geotype = $result [$i]->types;
			$geotype = $geotype [0];
			if ($geotype == "postal_code") {
				$data ["postal_code"] = $geoname;
			} elseif ($geotype == "country") {
				$data ["country"] = $geoname;
			} elseif ($geotype == "administrative_area_level_1") {
				$data ["region"] = $geoname;
			} elseif ($geotype == "locality") {
				$data ["city"] = $geoname;
			} elseif ($geotype == "route") {
				$data ["street"] = $geoname;
			} elseif ($geotype == "street_number") {
				$data ["street_number"] = $geoname;
			}
		}
		return $data;
	}
}

?>