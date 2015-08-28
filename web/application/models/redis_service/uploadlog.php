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
 * Uploadlog Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Uploadlog extends CI_Model
{



    /** 
     * Uploadlog load 
     * Uploadlog function 
     * 
     * @return void 
     */
    function Uploadlog()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> model('redis_service/event', 'event');
        $this -> load -> model('redis_service/userlog', 'userlog');
        $this -> load -> model('redis_service/clientdata', 'clientdata');
        $this -> load -> model('redis_service/activitylog', 'activitylog');
        $this -> load -> model('redis_service/utility', 'utility');
        $this -> load -> model('redis_service/usertag', 'usertag');
    }
    
    /** 
     * Add uploadlog 
     * AddUploadlog function 
     * 
     * @param string $content content 
     * 
     * @return void 
     */
    function addUploadlog($content)
    {
        //$eventInfo = $content->eventInfo;
        $eventInfo = isset($content -> eventInfo) ? $content -> eventInfo : "";
        if (isset($eventInfo)) {
            if (is_array($eventInfo)) {
                foreach ($eventInfo as $event) {
                    $this -> event -> addEvent($event);
                }
            }
        }

        $orderInfo = isset($content -> orderInfo) ? $content -> orderInfo : "";
        if (isset($orderInfo)) {
            if (is_array($orderInfo)) {
                foreach ($orderInfo as $event) {
                    $this -> event -> addOrder($event);
                }
            }
        }

        $errorInfo = isset($content -> errorInfo) ? $content -> errorInfo : "";
        if (isset($errorInfo)) {
            if (is_array($errorInfo)) {
                foreach ($errorInfo as $errorlog) {
                    $this -> userlog -> addUserlog($errorlog);
                }
            }
        }
        $clientData = isset($content -> clientData) ? $content -> clientData : "";
        if (isset($clientData)) {
            if (is_array($clientData)) {
                foreach ($clientData as $clientdataInfo) {
                    $this -> clientdata -> addClientdata($clientdataInfo);
                }
            }
        }
        $activityInfo = isset($content -> activityInfo) ? $content -> activityInfo : "";
        if (isset($activityInfo)) {
            if (is_array($activityInfo)) {
                foreach ($activityInfo as $erroractivity) {
                    $this -> activitylog -> addActivitylog($erroractivity);
                }
            }
        }

        $tagInfo = isset($content -> tags) ? $content -> tags : "";
        if (isset($tagInfo)) {
            if (is_array($tagInfo)) {
                foreach ($tagInfo as $tagactivity) {
                    $this -> usertag -> addusertag($tagactivity);
                }
            }
        }

    }

}
?>