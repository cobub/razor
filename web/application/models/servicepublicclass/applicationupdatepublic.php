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
 * Applicationupdatepublic class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Applicationupdatepublic extends CI_Model
{
    var $appkey;
    var $version_code;

    /**
     * Load application update
     *
     * @param array $content json data
     *
     * @return void
     */
    function loadapplicationupdate($content)
    {
        $this->appkey = $content->appkey;
        $this->version_code = isset($content->version_code) ? $content->version_code : '1';
    }

}
