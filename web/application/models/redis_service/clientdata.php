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
        $ip = $this -> utility -> getOnlineIP();
        $i = isset($clientdata -> mccmnc) ? $clientdata -> mccmnc : '';
        $query = $this -> db -> query("select name from " . $this -> db -> dbprefix('mccmnc') . " where value = '$i'");
        if ($query != null && $query -> num_rows() > 0) {
            $service_supplier = $query -> first_row() -> name;
        } else {
            $service_supplier = "Unknown";
        }
        $nowtime = date('Y-m-d H:i:s');
        if (isset($clientdata -> time)) {
            $nowtime = $clientdata -> time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $data = array('productkey' => $clientdata -> appkey, 'platform' => $clientdata -> platform, 'osversion' => $clientdata -> os_version, 'language' => $clientdata -> language, 'deviceid' => $clientdata -> deviceid, 'resolution' => $clientdata -> resolution, 'ismobiledevice' => isset($clientdata -> ismobiledevice) ? $clientdata -> ismobiledevice : '', 'devicename' => isset($clientdata -> devicename) ? $clientdata -> devicename : '', 'defaultbrowser' => isset($clientdata -> defaultbrowser) ? $clientdata -> defaultbrowser : '', 'javasupport' => isset($clientdata -> javasupport) ? $clientdata -> javasupport : '', 'flashversion' => isset($clientdata -> flashversion) ? $clientdata -> flashversion : '', 'modulename' => isset($clientdata -> modulename) ? $clientdata -> modulename : '', 'imei' => isset($clientdata -> imei) ? $clientdata -> imei : '', 'imsi' => isset($clientdata -> imsi) ? $clientdata -> imsi : '', 'havegps' => isset($clientdata -> havegps) ? $clientdata -> havegps : '', 'havebt' => isset($clientdata -> havebt) ? $clientdata -> havebt : '', 'havewifi' => isset($clientdata -> havewifi) ? $clientdata -> havewifi : '', 'havegravity' => isset($clientdata -> havegravity) ? $clientdata -> havegravity : '', 'wifimac' => isset($clientdata -> wifimac) ? $clientdata -> wifimac : '', 'version' => isset($clientdata -> version) ? $clientdata -> version : '', 'network' => isset($clientdata -> network) ? $clientdata -> network : '', 'latitude' => isset($clientdata -> latitude) ? $clientdata -> latitude : '', 'longitude' => isset($clientdata -> longitude) ? $clientdata -> longitude : '', 'isjailbroken' => isset($clientdata -> isjailbroken) ? $clientdata -> isjailbroken : '', 'date' => $nowtime, 'service_supplier' => $service_supplier, 'clientip' => $ip);
        $latitude = isset($clientdata -> latitude) ? $clientdata -> latitude : '';
        $choose = $this -> config -> item('get_geographical');
        $data["country"] = '';
        $data["region"] = '';
        $data["city"] = '';
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

            $productId = $this -> utility -> getProductIdByKey($clientdata -> appkey);
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
                $data["country"] = $record -> country_name;
                if ($record -> region != '') {
                    $data["region"] = $GEOIP_REGION_NAME[$record -> country_code][$record -> region];
                }
                $data["city"] = $record -> city;
                $data["postcode"] = $record -> postal_code;
            }
        }

        $this -> redis -> lpush("razor_clientdata", serialize($data));
        $this -> processor -> process();
    }

}
?>
