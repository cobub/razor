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
    var $ipobj;
    public function setIp($ip)
    {
        $this->ipobj = $ip;
    }
    
    public function getCountry()
    {
        
    }
    
    public function getRegion()
    {
        
    }
    
    public function getCity()
    {
        
    }
}