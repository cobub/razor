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
        $this -> load -> model('lbs_service/google', 'google');
        $this -> load -> model('lbs_service/ipinfodb', 'ipinfodb');
        $this -> load -> model('service/utility', 'utility');
    }

    function addClientdata($content) {
        $this -> load -> model('servicepublicclass/clientdatapublic', 'clientdatapublic');
        $clientdata = new clientdatapublic();
        $clientdata -> loadclientdata($content);
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
            'useridentifier' => isset($clientdata -> userid) ? $clientdata -> userid : '',
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
                $data["region"] = $regionInfo['region'];
                $data["city"] = $regionInfo['city'];
                $data["street"] = $regionInfo['street'];
                $data["streetno"] = $regionInfo['street_number'];
                $data["postcode"] = $regionInfo['postal_code'];
            }
        }
        if ($choose == 1) {
            require ('IP.class.php');
            $loc = IP::find($ip);
            $data['country'] = $loc[0];
            $data['region']  = $loc[1];
            $data['city']    = $loc[2];
        }

        $this -> db -> insert('clientdata', $data);
    }

}
?>
