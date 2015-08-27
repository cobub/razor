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
 * ModelsOnlineconfig Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Models_Onlineconfig extends CI_Model
{


    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        $this -> load -> database();
    }

    /**
     * GetOnlineConfigByProuctId function
     *
     * Get online config
     *
     * @param int $id id
     *
     * @return row
     */
    function getOnlineConfigByProuctId($id)
    {
        $sql = "select * from " . $this -> db -> dbprefix('config') . "  where product_id=$id";
        $query = $this -> db -> query($sql);
        return $query -> first_row();
    }

    /**
     * Modifyonlineconfig function
     *
     * Online modify config
     *
     * @param string $id              id
     * @param string $autogetlocation autogetlocation
     * @param string $updateonlywifi  updateonlywifi
     * @param string $sessionmillis   sessionmillis
     * @param string $reportpolicy    reportpolicy
     *
     * @return void
     */
    function modifyonlineconfig($id, $autogetlocation, $updateonlywifi, $sessionmillis, $reportpolicy)
    {
        $data = array('autogetlocation' => $autogetlocation, 'updateonlywifi' => $updateonlywifi, 'sessionmillis' => $sessionmillis, 'reportpolicy' => $reportpolicy);
        $this -> db -> where('product_id', $id);
        $this -> db -> update('config', $data);
    }

}
