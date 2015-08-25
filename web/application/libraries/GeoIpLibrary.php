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

require_once('IPAbstract.php');
/**
 * GeoIpLibrary Class
 *
 * GeoIpLite library to parse Ip to Country, region and city
 *
 * @category PHP
 * @package  Library
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class GeoIpLibrary extends IPAbstract
{
    /**
     * Set Ip address
     * 
     * @param string $ipaddr ipaddress
     * 
     * @return void
     */
    public function setIp($ipaddr)
    {
        require_once (dirname(__FILE__) . "/../third_party/geoip/geoip.inc");
        require_once (dirname(__FILE__) . "/../third_party/geoip/geoipcity.inc");
        if (!isset($GEOIP_REGION_NAME))
            require_once (dirname(__FILE__).  "/../third_party/geoip/geoipregionvars.php");
        $gi = geoip_open(dirname(__FILE__) . "/../third_party/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
        $record = geoip_record_by_addr($gi, $ipaddr);
        if(isset($record->country_name)&&($record->country_name!='')) {
            $this->country = $record->country_name;
        }
            
        if (isset($record->country_code)&&
            isset($record->region)&&
            isset($GEOIP_REGION_NAME[$record->country_code][$record->region])&&
            $GEOIP_REGION_NAME[$record->country_code][$record->region]!='') {
                $this->region = $GEOIP_REGION_NAME[$record->country_code][$record->region];
            }
        if (isset($record->city)&&$record->city!='') {
            $this->city = $record->city;
        }
    }
}