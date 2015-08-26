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
 * Google Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Google extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function google()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('array');
        $this->load->model('service/utility', 'utility');
    }
    
    /**
     * getregioninfo function
     * Get google services by latitude and longitude location
     *
     * @param string $latitude  latitude
     * @param string $longitude longitude
     *
     * @return array arr
     */
    function getregioninfo($latitude, $longitude)
    {
        $configlanguage = $this->config->item('language');
        if ($configlanguage == "en_US") {
            $configlanguage = "EN";
        } elseif ($configlanguage == "zh_CN") {
            $configlanguage = "CN";
        }
        $preurl = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' .
                 $latitude . ',' . $longitude . '&sensor=true';
        if ($configlanguage != '') {
            $preurl = $preurl . '&language=' . $configlanguage;
        }
        
        $client = $this->utility->Post2($preurl);
        
        if (! isset($client)) {
            $ret = array(
                    'flag' => - 8,
                    'msg' => 'Invalid regioninfo'
            );
            // echo json_encode($ret);
            return false;
        } else {
            $arr = json_decode($client);
            $arr = $this->result2array($arr);
            return $arr;
        }
    }
    /**
     * result2array function
     * result to array
     *
     * @param string $result result
     *
     * @return array data
     */
    function result2array($result)
    {
        if ($result == '') {
            return;
        }
        $data = array();
        $result = $result->results;
        if (empty($result))
            return;
        $result = $result[0];
        $result = $result->address_components;
        $length = sizeof($result);
        $data["postal_code"] = '';
        $data["country"] = '';
        $data["region"] = '';
        $data["city"] = '';
        $data["street"] = '';
        $data["street_number"] = '';
        for ($i = 0; $i < sizeof($result); $i ++) {
            $geoname = $result[$i]->long_name;
            $geotype = $result[$i]->types;
            $geotype = $geotype[0];
            if ($geotype == "postal_code") {
                $data["postal_code"] = $geoname;
            } elseif ($geotype == "country") {
                $data["country"] = $geoname;
            } elseif ($geotype == "administrative_area_level_1") {
                $data["region"] = $geoname;
            } elseif ($geotype == "locality") {
                $data["city"] = $geoname;
            } elseif ($geotype == "route") {
                $data["street"] = $geoname;
            } elseif ($geotype == "street_number") {
                $data["street_number"] = $geoname;
            }
        }
        return $data;
    }
}
?>