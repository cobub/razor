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
 * PostTagpublic class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Posttagpublic extends CI_Model
{
    var $deviceid;
    var $tag;
    var $appkey;
	var $useridentifier;
	var $lib_version;

    /**
     * Load tag
     * 
     * @param array $content json data
     * 
     * @return void
     */
    function loadtag($content)
    {
        $this->deviceid = isset($content->deviceid) ? $content->deviceid : '';
        $this->tag  = isset($content->tag) ? $content->tag : '';
        $this->appkey = $content->appkey;
		$this->useridentifier = $content->useridentifier;
		$this->lib_version = isset($content->lib_version)?$content->lib_version:'unknow';
    }
}
