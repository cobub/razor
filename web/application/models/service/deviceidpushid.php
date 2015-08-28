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
        $dw = $this->load->database('dw', true);
        $data = array(
            'deviceid' => isset($content->deviceid)?$content->deviceid:'',
            'pushid' => isset($content->clientid)?$content->clientid:''
        );
        
        $dw->from($dw->dbprefix("deviceid_pushid"));
        
        $dw->where($data);
        $query = $dw->get();

        if ($query && $query->num_rows() > 0) { //Dupplicate row
             return;
        }
        
        
        $dw->insert('deviceid_pushid', $data);
    }

}
