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
 * DeviceidUid class
 * 
 * Deviceid and userid mapping
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class DeviceidUid extends CI_Model
{
    /**
     * Construct
     * 
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * AddDeviceidUid
     * 
     * @param array $content json data
     * 
     * @return void
     */
    function addDeviceidUid($content)
    {
        $data = array(
            'deviceid' => isset($content->deviceid)?$content->deviceid:'',
            'userid' => isset($content->userid)?$content->userid:''
        );
        $dw = $this->load->database('dw', true);
        $dw->insert('deviceid_userid', $data);
    }

}
