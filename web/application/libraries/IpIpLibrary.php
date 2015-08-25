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
 * IpIpLibrary Interface
 *
 * IpIp.net free ip library
 *
 * @category PHP
 * @package  Library
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class IpIpLibrary implements IIPLibrary
{
    var $country;
    var $region;
    var $city;
    public function setIp($ip)
    {
        if (!class_exists('IP')) {
            require(dirname(__FILE__) . "/../third_party/ipip/IP.class.php");
        }
        $loc = IP::find($ip);
        if (isset($loc[0]))
            $this->country = $loc[0];
        if (isset($loc[1]))
            $this->region = $loc[1];
        if (isset($loc[2]))
            $this->city = $loc[2];
    }
    
    public function getCountry()
    {
        return $this->country;
    }
    
    public function getRegion()
    {
        return $this->region;
    }
    
    public function getCity()
    {
        return $this->city;
    }
}