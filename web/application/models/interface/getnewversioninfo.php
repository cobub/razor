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
 * Interface Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Getnewversioninfo extends CI_Model
{
    /**
    * Construct funciton, to pre-load database configuration
    *
    * @return void
    */

    function __construct()
    {
        parent::__construct();
        $this->load->model("interface/postdatautility", "datautility");
    }

    /**
     * newVersionInfo function
     * Get new version information by version
     *
     * @param string $version version
     *
     * @return void
     */
    function newVersionInfo($version)
    {
        $postdata=array('version'=>"$version");
        $serverURL = "http://news.cobub.com/index.php?/news/getUpdateUrl";
        $responseData = $this->datautility->post($serverURL, $postdata);
        $retObject = json_decode($responseData, true);
        if ($retObject['flag']> 0) {
            return $retObject['msg'];
        } else {
            return false;
        }
    }
}