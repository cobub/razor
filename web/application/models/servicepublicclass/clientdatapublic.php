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
 * Clientdatapublic class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class clientdatapublic extends CI_Model
{
    var $appkey;
    var $platform;
    var $os_version;
    var $language;
    var $deviceid;
    var $resolution;
    var $ismobiledevice;
    var $devicename;
    var $defaultbrowser;
    var $javasupport;
    var $flashversion;
    var $modulename;
    var $imei;
    var $imsi;
    var $salt;
    var $havegps;
    var $havebt;
    var $havewifi;
    var $havegravity;
    var $wifimac;
    var $version;
    var $network;
    var $latitude;
    var $longitude;
    var $isjailbroken;
    var $userid;
    var $time;
    var $mccmnc;

    /**
     * Load clientdata
     *
     * @param array $content postclientdata data
     *
     * @return void
     */
    function loadclientdata($content)
    {
        $this->appkey = $content->appkey;
        $this->platform = isset($content->platform) ? $content->platform : '';
        $this->os_version = isset($content->os_version) ? $content->os_version : '';
        $this->language = isset($content->language) ? $content->language : '';
        $this->deviceid = isset($content->deviceid) ? $content->deviceid : '';
        $this->resolution = isset($content->resolution) ? $content->resolution : '';
        $this->ismobiledevice = isset($content->ismobiledevice) ? $content->ismobiledevice : '';
        $this->devicename = isset($content->devicename) ? $content->devicename : '';
        $this->defaultbrowser = isset($content->defaultbrowser) ? $content->defaultbrowser : '';
        $this->javasupport = isset($content->javasupport) ? $content->javasupport : '';
        $this->flashversion = isset($content->flashversion) ? $content->flashversion : '';
        $this->modulename = isset($content->modulename) ? $content->modulename : '';
        $this->imei = isset($content->imei) ? $content->imei : '';
        $this->imsi = isset($content->imsi) ? $content->imsi : '';
        $this->salt = isset($content->salt) ? $content->salt : '';
        $this->havegps = isset($content->havegps) ? $content->havegps : '';
        $this->havebt = isset($content->havebt) ? $content->havebt : '';
        $this->havewifi = isset($content->havewifi) ? $content->havewifi : '';
        $this->havegravity = isset($content->havegravity) ? $content->havegravity : '';
        $this->wifimac = isset($content->wifimac) ? $content->wifimac : '';
        $this->version = isset($content->version) ? $content->version : '';
        $this->network = isset($content->network) ? $content->network : '';
        $this->latitude = isset($content->latitude) ? $content->latitude : '';
        $this->longitude = isset($content->longitude) ? $content->longitude : '';
        $this->isjailbroken = isset($content->isjailbroken) ? $content->isjailbroken : 0;
        $this->userid = isset($content->userid) ? $content->userid : '';
        $this->time = isset($content->time) ? $content->time : '';
        $this->mccmnc = isset($content->mccmnc) ? $content->mccmnc : '';

    }

}
