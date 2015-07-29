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
 * Alert Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Alertmodel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        $this->load->database();
    }

    /**
     * GetProductAlertByProuctId function
     * Get product alert information through productid
     *
     * @param int $productId product id
     *
     * @return query result
     */
    function getProductAlertByProuctId($productId)
    {
        $sql = "
            select 
                * 
            from 
                " . $this->db->dbprefix('alert') . " as d 
            where 
                d.productid=" . $productId . " 
            group by 
                d.id";
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * Identify if the condition is been configured or not
     * 
     * @param string $exceptionlab lab
     * @param string $condition    condition
     * @param string $emails       email
     *
     * @return query result
     */
    function isUnique($exceptionlab, $condition, $emails)
    {
        $product = $this->common->getCurrentProduct();
        $id = $product->id;
        $sql = "
            select 
                * 
            from 
                " . $this->db->dbprefix('alert') . " a 
            where 
                a.productid=$id and 
                a.emails='" . $emails . "'  and 
                a.label='" . $exceptionlab . "' and 
                a.condition='" . $condition . "';";
        $result = $this->db->query($sql);
        return $result;

    }

    /**
     * Add a condtion for product
     * 
     * @param string $exceptionlab lab
     * @param string $condition    condition
     * @param string $emailstr     email
     *
     * @return query result
     */
    function addlab($exceptionlab, $condition, $emailstr)
    {
        $userId = $this->common->getUserId();
        $product = $this->common->getCurrentProduct();
        $data = array(
            'label' => $exceptionlab,
            'condition' => $condition,
            'productid' => $product->id,
            'userid' => $userId,
            'emails' => $emailstr
        );
        $this->db->insert('alert', $data);
    }

    /**
     * Getalertbyid function
     *
     * Get a alert through product id
     * 
     * @param integer $id        lab
     * @param string  $condition condition
     *
     * @return query result
     */
    function getalertbyid($id, $condition)
    {
        $sql = "
            SELECT 
                *
            FROM 
                " . $this->db->dbprefix('alert') . "    
            WHERE 
                id =  '" . $id . "' AND 
                active =1 AND 
                abs(`condition` -" . $condition . ")<0.001";
        
        $result = $this->db->query($sql);
        if ($result != null && $result->num_rows() > 0) {
            return $result->row_array();
        }
        return null;
    }

    /**
     * Delalert function
     * 
     * Delete a specified alert
     *
     * @param integer $id        lab
     * @param string  $condition condition
     *
     * @return query result
     */
    function delalert($id, $condition)
    {
        $sql = "
            delete from  
                " . $this->db->dbprefix('alert') . " 
            where 
                id='" . $id . "' and 
                `condition` = $condition";
        $this->db->query($sql);
    }

    /**
     * Resetalert function
     *
     * Reset a alert
     * 
     * @param integer $Id        lab
     * @param string  $label     label
     * @param string  $condition condition
     * @param string  $emails    email
     *
     * @return query result
     */
    function resetalert($Id, $label, $condition, $emails)
    {
        $sql = "
            UPDATE 
                " . $this->db->dbprefix('alert') . " 
            SET 
                `condition`=$condition,
                `label`='$label',
                `emails`='$emails' 
            WHERE 
                `id`=$Id";
        $this->db->query($sql);
    }

}
