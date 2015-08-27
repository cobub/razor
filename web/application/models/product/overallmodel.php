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
 * Overallmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Overallmodel extends CI_Model
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
        $this -> load -> database();
    }
    
    /** 
     * Get new users count
     * GetNewUsersCountByUserId function
     * 
     * @param string $userId   userid
     * @param string $dateTime datetime
     * 
     * @return void
     */
    function getNewUsersCountByUserId($userId, $dateTime)
    {

    }
    
    /** 
     * Get session users count
     * GetStartUsersCountByUserId function
     * 
     * @param string $userId   userId
     * @param string $dateTime datetime
     * 
     * @return void
     */
    function getStartUsersCountByUserId($userId, $dateTime)
    {

    }

}
?>