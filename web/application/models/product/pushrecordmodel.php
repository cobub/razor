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
 * PushRecordModel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class PushRecordModel extends CI_Model
{


    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> model('common');
    }
    
    /** 
     * Confirm 
     * Confirm function
     * 
     * @param string $UserId       userid 
     * @param string $UserName     Username 
     * @param string $AppName      appname 
     * @param string $ChannnelName channnelName 
     * @param string $PushNum      pushnum 
     * @param string $Content      content 
     * @param string $Date         date 
     * 
     * @return void
     */
    function confirm($UserId, $UserName, $AppName, $ChannnelName, $PushNum, $Content, $Date)
    {
        /* $data = array (
         'user_id' => $UserId,
         'user_name'=>$UserName,
         'appname' => $AppName,
         'channel_name' => $ChannnelName,
         'push_num' => $PushNum,
         'content' => $Content,
         'date' => $Date
         );

         $flag = $this->db->insert ( $this->db->dbprefix ( 'push_record' ), $data );
         if($flag)
         {
         $ret = array('flag'=> '1',
         'msg'=>'Record Success!');
         }
         else
         {
         $ret = array('flag'=> '0',
         'msg'=>'Record Failed!');
         }

         return $ret; */

    }

}
