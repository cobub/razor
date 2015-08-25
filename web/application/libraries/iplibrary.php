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
require_once('GeoIpLibrary.php');
require_once('IpIpLibrary.php');
/**
 * IPLibrary Implementation
 *
 * IPLibrary function
 *
 * @category PHP
 * @package  Library
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */

class IPLibrary
{
    var $ip;
    public function setLibrary($ipclass,$ipaddr)
    {
        $this->ip = new $ipclass;
        $this->ip->setIp($ipaddr);
    }
    
    public function getCountry()
    {
        $this->ip->getCountry();
    }

    public function getRegion()
    {
        $this->ip->getRegion();
    }
    
    public function getCity()
    {
        $this->ip->getCity();
    }
}
