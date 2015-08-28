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
 * OnlineConfig Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class OnlineConfig extends CI_Model
{



    /** 
     * Online config 
     * OnlineConfig load 
     * 
     * @return void 
     */
    function OnlineConfig()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> library('redis');
    }
    
    /** 
     * Get productid 
     * GetProductid function 
     * 
     * @param string $key key 
     * 
     * @return string 
     */
    function getProductid($key)
    {
        $query = $this -> db -> query("select product_id from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query -> num_rows() > 0) {
            $productid = $query -> first_row() -> product_id;
            return $productid;
        }
        return null;
    }
    
    /** 
     * Get config message 
     * GetConfigMessage function 
     * 
     * @param string $productid productid 
     * 
     * @return array 
     */
    function getConfigMessage($productid)
    {
        $query = $this -> db -> query("select * from " . $this -> db -> dbprefix('config') . " where product_id = '$productid'");
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row();

        }
        return null;
    }

}
