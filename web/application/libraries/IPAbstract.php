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
 * IPLibrary Interface
 *
 * IPLibrary interface to privide base function
 *
 * @category PHP
 * @package  Library
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
abstract class IPAbstract
{
    public $country = 'unknown';
    public $region ='unknown';
    public $city = 'unknown'; 
    /**
     * Set ip address
     * 
     * @param string $ip ip address
     * 
     * @return void
     */
    abstract public function setIp($ip);
}