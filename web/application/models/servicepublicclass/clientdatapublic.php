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
	var $useridentifier;
    var $time;
    var $mccmnc;
    var $salt;
	var $session_id;
	var $lib_version;

    /**
     * Load clientdata
     *
     * @param array $content postclientdata data
     *
     * @return void
     */
    function loadclientdata($content)
    {
        if (isset($content->appkey) && (!empty($content->appkey)))
            $this->appkey = $content->appkey;
        else {
            $this->appkey = 'unknown';
        }
        if (isset($content->platform) && (!empty($content->platform)))
            $this->platform = $content->platform;
        else {
            $this->platform = 'unknown';
        }
        if (isset($content->os_version) && (!empty($content->os_version)))
            $this->os_version = $content->os_version;
        else {
            $this->os_version = 'unknown';
        }
        if (isset($content->language) && (!empty($content->language)))
            $this->language = $content->language;
        else {
            $this->language = 'unknown';
        }
        if (isset($content->deviceid) && (!empty($content->deviceid)))
            $this->deviceid = $content->deviceid;
        else {
            $this->deviceid = 'unknown';
        }
        if (isset($content->resolution) && (!empty($content->resolution)))
            $this->resolution = $content->resolution;
        else {
            $this->resolution = 'unknown';
        }
        if (isset($content->ismobiledevice) && (!empty($content->ismobiledevice)))
            $this->ismobiledevice = $content->ismobiledevice;
        else {
            $this->ismobiledevice = 'unknown';
        }
        if (isset($content->devicename) && (!empty($content->devicename)))
            $this->devicename = $content->devicename;
        else {
            $this->devicename = 'unknown';
        }
        if (isset($content->defaultbrowser) && (!empty($content->defaultbrowser)))
            $this->defaultbrowser = $content->defaultbrowser;
        else {
            $this->defaultbrowser = 'unknown';
        }
        if (isset($content->javasupport) && (!empty($content->javasupport)))
            $this->javasupport = $content->javasupport;
        else {
            $this->javasupport = 'unknown';
        }
        if (isset($content->flashversion) && (!empty($content->flashversion)))
            $this->flashversion = $content->flashversion;
        else {
            $this->flashversion = 'unknown';
        }
        if (isset($content->modulename) && (!empty($content->modulename)))
            $this->modulename = $content->modulename;
        else {
            $this->modulename = 'unknown';
        }
        if (isset($content->imei) && (!empty($content->imei)))
            $this->imei = $content->imei;
        else {
            $this->imei = 'unknown';
        }
        if (isset($content->imsi) && (!empty($content->imsi)))
            $this->imsi = $content->imsi;
        else {
            $this->imsi = 'unknown';
        }
        if (isset($content->havegps) && (!empty($content->havegps)))
            $this->havegps = $content->havegps;
        else {
            $this->havegps = 'unknown';
        }
        if (isset($content->havebt) && (!empty($content->havebt)))
            $this->havebt = $content->havebt;
        else {
            $this->havebt = 'unknown';
        }
        if (isset($content->havewifi) && (!empty($content->havewifi)))
            $this->havewifi = $content->havewifi;
        else {
            $this->havewifi = 'unknown';
        }
        if (isset($content->havegravity) && (!empty($content->havegravity)))
            $this->havegravity = $content->havegravity;
        else {
            $this->havegravity = 'unknown';
        }
        if (isset($content->wifimac) && (!empty($content->wifimac)))
            $this->wifimac = $content->wifimac;
        else {
            $this->wifimac = 'unknown';
        }
        if (isset($content->version) && (!empty($content->version)))
            $this->version = $content->version;
        else {
            $this->version = 'unknown';
        }
        if (isset($content->network) && (!empty($content->network)))
            $this->network = $content->network;
        else {
            $this->network = 'unknown';
        }
        if (isset($content->latitude) && (!empty($content->latitude)))
            $this->latitude = $content->latitude;
        else {
            $this->latitude = 'unknown';
        }
        if (isset($content->longitude) && (!empty($content->longitude)))
            $this->longitude = $content->longitude;
        else {
            $this->longitude = 'unknown';
        }
        if (isset($content->isjailbroken) && (!empty($content->isjailbroken)))
            $this->isjailbroken = $content->isjailbroken;
        else {
            $this->isjailbroken = 0;
        }
        if (isset($content->useridentifier) && (!empty($content->useridentifier)))
            $this->useridentifier = $content->useridentifier;
        else {
            $this->useridentifier = '';
        }
        if (isset($content->time) && (!empty($content->time)))
            $this->time = $content->time;
        else {
            $this->time = 'unknown';
        }
        if (isset($content->mccmnc) && (!empty($content->mccmnc)))
            $this->mccmnc = $content->mccmnc;
        else {
            $this->mccmnc = 'unknown';
        }
        if (isset($content->salt) && (!empty($content->salt)))
            $this->salt = $content->salt;
        else {
            $this->salt = 'unknown';
        }
		
		if (isset($content->session_id) && (!empty($content->session_id)))
            $this->session_id = $content->session_id;
        else {
            $this->session_id = '';
        }
		
		if (isset($content->lib_version) && (!empty($content->lib_version)))
            $this->lib_version = $content->lib_version;
        else {
            $this->lib_version = '';
        }

    }

}
