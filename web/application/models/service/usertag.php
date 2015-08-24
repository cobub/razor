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
 * UserTag class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Usertag extends CI_Model
{
    /**
     * Construct
     * 
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * AdduserTag
     * 
     * @param array $content json data
     * 
     * @return void
     */
    function addusertag($content)
    {
        $this->load->model('servicepublicclass/posttagpublic', 'posttagpublic');
        $posttag = new posttagpublic();
        $posttag->loadtag($content);
        $data = array(
            'deviceid' => $posttag->deviceid,
            'tags' => $posttag->tags,
            'appkey' => $posttag->appkey
        );
        $this->db->insert('device_tag', $data);
    }

}
