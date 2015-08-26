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
 * Compare Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Compare extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct() 
    {
        $this->load->model('common');
        $this->load->database();
    }
    
    /**
     * getAllAlertlab function
     * Get all alert lab
     *
     * @return query result
     */
    function getAllAlertlab()
    {
        $userId = $this->common->getUserId();
        $product = $this->common->getCurrentProduct();
        $sql = "select * from ".$this->db->dbprefix('alert')."";
        $result = $this->db->query($sql);
        return $result->result_array();  
    }
    
    /**
     * addAlertEmail function
     * add alert email
     *
     * @param string $alertlabel   alert label
     * @param string $factdata     fact data
     * @param string $forecastdata forecast data
     * @param string $time         time
     * @param string $states       states
     *
     * @return query sql
     */
    function addAlertEmail($alertlabel,$factdata,$forecastdata,$time,$states)
    {
        $states=1;
        $sql="INSERT INTO ".$this->db->dbprefix('alertdetail')." (  `states` ,  `time` ,  `forecastdata` ,  `factdata` ,  `alertlabel` ) 
        VALUES ( $states,  '$time', $forecastdata, $factdata,  '$alertlabel' );";
        $this->db->query($sql);
    }
}