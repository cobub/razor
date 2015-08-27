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
 * Channel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Channel extends CI_Model
{


    /**
     * Construct funciton
     *
     * Construct funciton, to pre-load database configuration
     * 
     * @return void
     */
    function __construct()
    {
        $this -> load -> database();
        $this -> load -> model('common');
    }
    /**
     * Getplatform funciton
     *
     * Get all platform
     * 
     * @return array
     */
    function getplatform()
    {
        $sql = "select * from  " . $this -> db -> dbprefix('platform') . "  ";
        $query = $this -> db -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> result_array();
        }
        return null;
    }

    /**
     * Getallchanbyplatform funciton
     *
     * Platform 1=Android,2=iOS,3=windows phone
     *
     * @param int $platform platform
     *
     * @return array
     */
    function getallchanbyplatform($platform)
    {
        $userid = $this -> common -> getUserId();
        $sql = "select * from  " . $this -> db -> dbprefix('channel') . "  where active=1 and platform='$platform' and type='system' union 
		    select * from  " . $this -> db -> dbprefix('channel') . "  where active=1 and platform='$platform' and type='user'and user_id=$userid";
        $query = $this -> db -> query($sql);

        if ($query != null && $query -> num_rows() > 0) {
            return $query -> result_array();
        }

        return null;
    }

    /**
     * Getallsychannelbyplatform funciton
     *
     * Platform 1=Android,2=iOS,3=windows phone
     *
     * @param int $platform platform
     *
     * @return array
     */
    function getallsychannelbyplatform($platform)
    {
        $sql = "select c.*,p.name from  " . $this -> db -> dbprefix('channel') . "  c inner join  " . $this -> db -> dbprefix('platform') . "  p on c.platform = p.id where c.type='system' and c.active=1 and c.platform=$platform ";
        $query = $this -> db -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> result_array();
        }
        return null;
    }

    /**
     * Getdechannelbyplatform funciton
     *
     * Getdechannelbyplatform through userid,platform get self-built channels
     *
     * @param int $userid   userid
     * @param int $platform platform
     *
     * @return array
     */
    function getdechannelbyplatform($userid, $platform)
    {
        $sql = "select c.*,p.name from " . $this -> db -> dbprefix('channel') . "  c inner join  " . $this -> db -> dbprefix('platform') . "   p on c.platform = p.id where c.user_id = $userid and c.type='user' and c.active=1 and c.platform=$platform ";
        $query = $this -> db -> query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> result_array();
        }
        return null;
    }

}
