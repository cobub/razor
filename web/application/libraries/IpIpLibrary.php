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

 require_once 'IPAbstract.php';
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
class IpIpLibrary extends IPAbstract
{
    /**
     * Set Ip address
     * 
     * @param string $ip ipaddress
     * 
     * @return void
     */
    public function setIp($ip)
    {
        if (!class_exists('IP')) {
            include dirname(__FILE__) . "/../third_party/ipip/IP.class.php";
        }
        $loc = IP::find($ip);
        if ($loc == 'N/A') {
            return;
        }
        if (isset($loc[0])&&$loc[0]!='')
            $this->country = $loc[0];
        if (isset($loc[1])&&$loc[1]!='')
            $this->region = $loc[1];
        if (isset($loc[2])&&$loc[2]!='')
            $this->city = $loc[2];
    }
}
