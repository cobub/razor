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
 * Clientdata class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Clientdata extends CI_Model
{
    /**
     * Construct
     *
     * @return void
     */
    function Clientdata()
    {
        parent::__construct();
        $this->load->model('utility');
        $this->load->database();
        $this->load->helper("date");
        $this->load->model('lbs_service/google', 'google');
        $this->load->model('lbs_service/ipinfodb', 'ipinfodb');
        $this->load->model('service/utility', 'utility');
        $this->load->library('iplibrary');
    }

    /**
     * Add clientdata
     *
     * @param array $content json data
     *
     * @return void
     */
    function addClientdata($content)
    {
        $this->load->model('servicepublicclass/clientdatapublic', 'clientdatapublic');
        $clientdata = new clientdatapublic();
        $clientdata->loadclientdata($content);
        $ip = $this->utility->getOnlineIP();

        $nowtime = date('Y-m-d H:i:s');
        if (isset($clientdata->time)) {
            $nowtime = $clientdata->time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
		
        $insertdate = date('Y-m-d H:i:s');
        $data = array(
            'productkey' => $clientdata->appkey,
            'platform' => $clientdata->platform,
            'osversion' => $clientdata->os_version,
            'language' => $clientdata->language,
            'deviceid' => $clientdata->deviceid,
            'resolution' => $clientdata->resolution,
            'ismobiledevice' => $clientdata->ismobiledevice,
            'devicename' => $clientdata->devicename,
            'defaultbrowser' => $clientdata->defaultbrowser,
            'javasupport' => $clientdata->javasupport,
            'flashversion' => $clientdata->flashversion,
            'modulename' => $clientdata->modulename,
            'imei' => $clientdata->imei,
            'imsi' => $clientdata->imsi,
            'salt' => isset($clientdata->salt) ? $clientdata->salt : '',
            'havegps' => $clientdata->havegps,
            'havebt' => $clientdata->havebt,
            'havewifi' => $clientdata->havewifi,
            'havegravity' => $clientdata->havegravity,
            'wifimac' => $clientdata->wifimac,
            'version' => $clientdata->version,
            'network' => $clientdata->network,
            'latitude' => $clientdata->latitude,
            'longitude' => $clientdata->longitude,
            'isjailbroken' => $clientdata->isjailbroken,
            'useridentifier' => $clientdata->useridentifier,
            'date' => $nowtime,
            'service_supplier' => $clientdata->mccmnc,
            'clientip' => $ip,
            'insertdate' => $insertdate,
            'salt' => $clientdata->salt,
            'session_id' => $clientdata->session_id,
            'lib_version' => $clientdata->lib_version
        );
        $latitude = isset($clientdata->latitude) ? $clientdata->latitude : '';
        $choose = $this->config->item('get_geographical');
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
		
		$data = $this->db->escape_str($data);
		
        $this->db->insert('clientdata', $data);
    }
}
?>
