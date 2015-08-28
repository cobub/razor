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
 * Activitylog Model
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Activitylog extends CI_Model
{

    /**
     * Activitylog function,to pre-load database configuration
     *
     * @return void
     */
    function Activitylog()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * AddActivitylog,add log of activity
     *
     * @param string $content content
     *
     * @return void
     */
    function addActivitylog($content)
    {
        $this->load->model('servicepublicclass/activitypublic', 'activitypublic');
        $activitylog = new activitypublic();
        $activitylog->loadactivity($content);
        $nowtime = date('Y-m-d H:i:s');
        if (isset($activitylog->start_millis)) {
            $nowtime = $activitylog->start_millis;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $nowtime2 = date('Y-m-d H:i:s');
        if (isset($activitylog->end_millis)) {
            $nowtime2 = $activitylog->end_millis;
            if (strtotime($nowtime2) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime2) == '') {
                $nowtime2 = date('Y-m-d H:i:s');
            }
        }
        $data = array('appkey' => $activitylog->appkey,'session_id' => $activitylog->session_id,'start_millis' => $nowtime,'end_millis' => $nowtime2,'activities' => $activitylog->activities,'duration' => $activitylog->duration,'version' => isset($activitylog->version) ? $activitylog->version : ''
        );
        
        $this->db->insert('clientusinglog', $data);
    }
}
?>