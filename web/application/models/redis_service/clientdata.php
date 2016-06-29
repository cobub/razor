<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Clientdata Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Clientdata extends CI_Model
{


    /** 
     * Clientdata load 
     * Clientdata function 
     * 
     * @return void 
     */
    function Clientdata()
    {
        parent::__construct();
        $this -> load -> model('utility');
        $this -> load -> database();
        $this -> load -> helper("date");
        $this -> load -> model("redis_service/processor");
        $this -> load -> library('redis');
        $this -> load -> model('lbs_service/google', 'google');
        $this -> load -> model('lbs_service/ipinfodb', 'ipinfodb');
        $this -> load -> model('redis_service/utility', 'utility');
        $this -> load -> library('iplibrary');
    }
    
    /** 
     * Add client data 
     * AddClientdata function 
     * 
     * @param string $clientdata clientdata 
     * 
     * @return void 
     */
    function addClientdata($clientdata)
    {
        $productId = $this -> utility -> getProductIdByKey($clientdata -> appkey);
        
        
        $ip = $this -> utility -> getOnlineIP();
        $nowtime = date('Y-m-d H:i:s');
        if (isset($clientdata -> time)) {
            $nowtime = $clientdata -> time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        
        $data = array('productkey' => $clientdata -> appkey,
        'platform' => $clientdata -> platform, 
        'osversion' => $clientdata -> os_version, 
        'language' => $clientdata -> language, 
        'deviceid'=>$clientdata->deviceid, 
        'resolution' => $clientdata -> resolution,
        'ismobiledevice' => isset($clientdata -> ismobiledevice) ? $clientdata -> ismobiledevice : '',
        'devicename' => isset($clientdata -> devicename) ? $clientdata -> devicename:'unknown',
        'defaultbrowser' =>isset($clientdata -> defaultbrowser) ? $clientdata -> defaultbrowser : '', 
        'javasupport' => isset($clientdata -> javasupport) ? $clientdata -> javasupport : '',
        'flashversion' =>isset($clientdata -> flashversion) ? $clientdata -> flashversion : '', 
        'modulename' => isset($clientdata -> modulename) ? $clientdata -> modulename : '', 
        'imei' => isset($clientdata -> imei) ? $clientdata -> imei : '', 
        'imsi' => isset($clientdata -> imsi) ? $clientdata -> imsi : '', 
        'havegps' => isset($clientdata -> havegps) ? $clientdata -> havegps : '',
        'havebt' =>isset($clientdata -> havebt) ? $clientdata -> havebt : '', 
        'havewifi' => isset($clientdata -> havewifi) ? $clientdata -> havewifi : '',
        'havegravity' =>isset($clientdata -> havegravity) ? $clientdata -> havegravity : '',
        'wifimac' =>isset($clientdata -> wifimac) ? $clientdata -> wifimac : '', 
        'version' => isset($clientdata -> version) ? $clientdata -> version : '', 
        'network' => isset($clientdata -> network) ? $clientdata -> network : '', 
        'latitude' => isset($clientdata -> latitude) ? $clientdata -> latitude : '', 
        'longitude' => isset($clientdata -> longitude) ? $clientdata -> longitude : '', 
        'isjailbroken' => isset($clientdata -> isjailbroken) ? $clientdata -> isjailbroken : 0, 
        'date' => $nowtime, 
        'service_supplier' => isset($clientdata -> mccmnc) ? $clientdata -> mccmnc : '0', 
        'clientip' => $ip);
        
        $latitude = isset($clientdata -> latitude) ? $clientdata -> latitude : '';
        $choose = $this -> config -> item('get_geographical');
        $data["country"] = 'unknown';
        $data["region"] = 'unknown';
        $data["city"] = 'unknown';
        $data["street"] = '';
        $data["streetno"] = '';
        $data["postcode"] = '';
        if ($choose == 2) {
            $this->iplibrary->setLibrary('GeoIpLibrary', $ip);
            $data['country'] = $this->iplibrary->getCountry();
            $data['region'] = $this->iplibrary->getRegion();
            $data['city'] = $this->iplibrary->getCity();
        }
        if ($choose == 1) {
            $this->iplibrary->setLibrary('IpIpLibrary', $ip);

            $data['country'] = $this->iplibrary->getCountry();
            $data['region'] = $this->iplibrary->getRegion();
            $data['city'] = $this->iplibrary->getCity();
        }
        
        $this -> redis -> lpush("razor_clientdata", serialize($data));
        
		//For realtime User sessions
        $key = "razor_r_u_p_" . $productId . "_" . date('Y-m-d-H-i', time());
		$this -> redis -> hset($key, array($data["deviceid"] => $productId));
        $this -> redis -> expire($key, 30 * 60);

      
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
        
        $this -> processor -> process();
    }

}
?>
