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
 * UserTag Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class UserTag extends CI_Model
{



    /** 
     * Construct load 
     * Construct function 
     * 
     * @return void 
     */
    function __construct()
    {
        parent::__construct();
        $this -> load -> library('redis');
        $this -> load -> model("redis_service/processor");
    }
    
    /** 
     * Add user tag 
     * AddUserTag function 
     * 
     * @param string $content content 
     * 
     * @return void 
     */
    function addUserTag($content)
    {
        $this -> load -> model('servicepublicclass/posttagpublic', 'posttagpublic');
        $posttag = new posttagpublic();
        $posttag -> loadtag($content);
        $insertdate = date('Y-m-d H:i:s');
        $data = array(
            'deviceid' => $posttag->deviceid,
            'tags' => $posttag->tag,
            'appkey' => $posttag->appkey,
            'useridentifier' => $posttag->useridentifier,
            'lib_version' => $posttag->lib_version,
            'insertdate' => $insertdate
        );
        $this -> redis -> lpush('razor_usertag', serialize($data));
        $this -> processor -> process();
    }

}
