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
 * DeviceidPushid class
 * 
 * Send deviceid and pushid data, to make the mapping for deviceid and pushid
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class DeviceidPushid extends CI_Model
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
     * AddDeviceidPushid
     * 
     * @param array $content json data
     * 
     * @return void
     */
    function addDeviceidPushid($content)
    {
        $data = array(
            'deviceid' => isset($content->deviceid)?$content->deviceid:'',
            'clientid' => isset($content->clientid)?$content->clientid:''
        );
        $dw = $this->load->database('dw', true);
        $dw->insert('deviceid_pushid', $data);
    }

}
