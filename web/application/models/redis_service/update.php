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
 * Update Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Update extends CI_Model
{



    /** 
     * Update load 
     * Update function 
     * 
     * @return void 
     */
    function Update()
    {
        parent::__construct();
        $this -> load -> database();
    }
    
    /** 
     * Have new version 
     * HaveNewVersion function 
     * 
     * @param string $key          key 
     * @param string $version_code version_code 
     * 
     * @return bool 
     */
    function haveNewVersion($key, $version_code)
    {
        $query = $this -> db -> query("select version from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query -> num_rows() > 0) {
            $version = $query -> first_row() -> version;
            if (strcmp($version, $version_code) > 0) {
                return true;
            }
        }
        return false;
    }
    
    /** 
     * Get product update 
     * GetProductUpdate function 
     * 
     * @param string $key key 
     * 
     * @return array 
     */
    function getProductUpdate($key)
    {
        $query = $this -> db -> query("select * from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row();
        }
        return null;
    }

}
