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
class Clientdata extends CI_Model {
    function Clientdata() {
        parent::__construct();
        $this -> load -> model('utility');
        $this -> load -> database();
        $this -> load -> helper("date");
        $this -> load -> library('redis');
        $this -> load -> model('lbs_service/google', 'google');
        $this -> load -> model('lbs_service/ipinfodb', 'ipinfodb');
        $this -> load -> model('redis_service/utility', 'utility');
    }

    function addClientdata($clientdata) {
    	$productId = $this -> utility -> getProductIdByKey($clientdata -> appkey);
    	//For realtime User sessions
    	$key = "razor_r_u_p_" . $productId . "_" . date('Y-m-d-H-i', time());
    	$this -> redis -> hset($key, array($data["deviceid"] => $productId));
    	$this -> redis -> expire($key, 30 * 60);
    	
        $ip = $this -> utility -> getOnlineIP();
        $nowtime = date('Y-m-d H:i:s');
        if (isset($clientdata -> time)) {
            $nowtime = $clientdata -> time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $data = array(
            'productkey' => $clientdata -> appkey,
            'platform' => $clientdata -> platform,
            'osversion' => $clientdata -> os_version,
            'language' => $clientdata -> language,
            'deviceid' => $clientdata -> deviceid,
            'resolution' => $clientdata -> resolution,
            'ismobiledevice' => isset($clientdata -> ismobiledevice) ? $clientdata -> ismobiledevice : '',
            'devicename' => isset($clientdata -> devicename) ? $clientdata -> devicename : 'unknown',
            'defaultbrowser' => isset($clientdata -> defaultbrowser) ? $clientdata -> defaultbrowser : '',
            'javasupport' => isset($clientdata -> javasupport) ? $clientdata -> javasupport : '',
            'flashversion' => isset($clientdata -> flashversion) ? $clientdata -> flashversion : '',
            'modulename' => isset($clientdata -> modulename) ? $clientdata -> modulename : '',
            'imei' => isset($clientdata -> imei) ? $clientdata -> imei : '',
            'imsi' => isset($clientdata -> imsi) ? $clientdata -> imsi : '',
            'havegps' => isset($clientdata -> havegps) ? $clientdata -> havegps : '',
            'havebt' => isset($clientdata -> havebt) ? $clientdata -> havebt : '',
            'havewifi' => isset($clientdata -> havewifi) ? $clientdata -> havewifi : '',
            'havegravity' => isset($clientdata -> havegravity) ? $clientdata -> havegravity : '',
            'wifimac' => isset($clientdata -> wifimac) ? $clientdata -> wifimac : '',
            'version' => isset($clientdata -> version) ? $clientdata -> version : '',
            'network' => isset($clientdata -> network) ? $clientdata -> network : '',
            'latitude' => isset($clientdata -> latitude) ? $clientdata -> latitude : '',
            'longitude' => isset($clientdata -> longitude) ? $clientdata -> longitude : '',
            'isjailbroken' => isset($clientdata -> isjailbroken) ? $clientdata -> isjailbroken : 0,
            'date' => $nowtime,
            'service_supplier' => isset($clientdata -> mccmnc) ? $clientdata -> mccmnc : '0',
            'clientip' => $ip
        );
        $latitude = isset($clientdata -> latitude) ? $clientdata -> latitude : '';
        $choose = $this -> config -> item('get_geographical');
        $data["country"] = 'unknown';
        $data["region"] = 'unknown';
        $data["city"] = 'unknown';
        $data["street"] = '';
        $data["streetno"] = '';
        $data["postcode"] = '';
        if ($choose == 2) {
            if ($latitude != '') {
                $latitude = $clientdata -> latitude;
                $longitude = $clientdata -> longitude;
                $regionInfo = $this -> google -> getregioninfo($latitude, $longitude);
            } else {
                $regionInfo = $this -> ipinfodb -> getregioninfobyip($ip);
            }

            
            if (!empty($regionInfo)) {
                $data["country"] = $regionInfo['country'];
                if ($regionInfo['country'] == null || $regionInfo['country'] == "") {
                    $data["country"] = "Unknown";
                }
                $data["region"] = $regionInfo['region'];
                $data["city"] = $regionInfo['city'];
                $data["street"] = $regionInfo['street'];
                $data["streetno"] = $regionInfo['street_number'];
                $data["postcode"] = $regionInfo['postal_code'];
            }
        }
        if ($choose == 1) {
            require ("geoip.inc");
            require ("geoipcity.inc");
            require ("geoipregionvars.php");
            $gi = geoip_open("GeoLiteCity.dat", GEOIP_STANDARD);
            $record = geoip_record_by_addr($gi, $ip);
            if (!empty($record)) {

                if ($record -> country_name != '') {
                    $data["country"] = $record -> country_name;
                } else {
                    $data["country"] = "unknown";
                }
                if ($record -> region != '') {
                    $data["region"] = $GEOIP_REGION_NAME[$record -> country_code][$record -> region];
                } else {
                    $data["region"] = "unknown";
                }
                if ($record -> city != '') {
                    $data["city"] = $record -> city;
                } else {
                    $data["city"] = "unknown";
                }
                $data["region"] = mb_convert_encoding($data["region"], "UTF-8", "UTF-8");
                $data["city"] = mb_convert_encoding($data["city"], "UTF-8", "UTF-8");
            } else {
                $data["country"] = "unknown";
                $data["region"] = "unknown";
                $data["city"] = "unknown";

            }
        }
        //For realtime areas
        $key = "razor_r_arc_p_" . $productId . "_c_" . $data["country"] . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($key, array($data["country"] => $productId));
        $this -> redis -> expire($key, 30 * 60);

        $regionKey = "razor_r_arrd_p_" . $productId . "_c_" . $data["country"] . "_r_" . $data["region"] . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($regionKey, array("regionname" => $data["region"]));
        $this -> redis -> expire($regionKey, 30 * 60);

        $regionKey = "razor_r_arr_p_" . $productId . "_c_" . $data["country"] . "_r_" . $data["region"] . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($regionKey, array($data["deviceid"] => $productId));
        $this -> redis -> expire($regionKey, 30 * 60);

        //$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
        //$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
        $this -> redis -> lpush("razor_clientdata", serialize($data));

        

        $this -> processor -> process();
    }

}
?>
