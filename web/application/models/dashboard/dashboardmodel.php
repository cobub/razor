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
 * Dashboard Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class DashboardModel extends CI_Model
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
     * addreport function
     * add report
     *
     * @param int    $productid  product id
     * @param int    $userid     user id
     * @param string $reportname report name
     * @param string $controller controller
     * @param string $src        src
     * @param int    $height     height
     * @param string $type       type
     *
     * @return int num
     */
    function addreport($productid, $userid, $reportname, $controller, $src, $height, $type)
    {    
        $ret=$this->isreportname($productid, $userid, $reportname, $type);
        $num=$this->getreportnum($productid, $userid);
        $maxlocation=$this->getmaxlocation($productid, $userid);
        if ($maxlocation==0) {
            $location=0;
        } else {
            $location=$maxlocation+1;
        }        
        if ($ret && ($num<8)) {
            $date=date('Y-m-d H:i:s');
            $data = array(
                    'productid'  => $productid,
                    'userid'     => $userid,
                    'controller' => $controller,
                    'reportname' => $reportname,
                    'src'        => $src,
                    'createtime' => $date,
                    'height'     => $height,
                    'type'       => $type,
                    'location'     => $location                
            );                
            
            $this->db->insert('reportlayout', $data);
            return 1;
        } else {
            if ($num>=8) {
                return $num;
            } else {
                return 0;
            }
            
        }        
    }
    
    /**
     * getmaxlocation function
     * get max location
     *
     * @param int $productid product id
     * @param int $userid    user id
     *
     * @return array row
     */
    function getmaxlocation($productid, $userid)
    {
        $this->db->select_max('location');
        $this->db->where('productid', $productid);
        $this->db->where('userid', $userid);
        $maxlocation = $this->db->get('reportlayout');
        if ($maxlocation!=null) {
            $row = $maxlocation->row();
            return $row->location;
        } else {
            return 0;
        }
    }
    
    /**
     * isreportname function
     * is report name
     *
     * @param int    $productid  product id
     * @param int    $userid     user id
     * @param string $reportname report name
     * @param string $type       type
     *
     * @return boolean
     */
    function isreportname($productid, $userid, $reportname, $type)
    {
        $this->db->where('productid', $productid);
        $this->db->where('userid', $userid);
        $this->db->where('reportname', $reportname);
        $this->db->where('type', $type);
        $ret=$this->db->get('reportlayout');
        if ($ret!=null && $ret->num_rows()>0) {
            return false;
        } else {
            return true;
        }
        
    }
    
    /**
     * getaddreport function
     * get add report
     *
     * @param int    $productid product id
     * @param int    $userid    user id
     * @param string $type      type
     *
     * @return array ret
     */
    function getaddreport($productid, $userid, $type=null)
    {       
        $this->db->where('productid', $productid);
        $this->db->where('userid', $userid);
        if ($type!=null) {
            $this->db->where('type', $type);
        }
        $this->db->order_by("location", "asc");
        $ret=$this->db->get('reportlayout');
        if ($ret!=null && $ret->num_rows()>0) {
            return  $ret;
        }
        return false;
        
    }
    
    /**
     * getreportnum function
     * get report num
     *
     * @param int $productid product id
     * @param int $userid    user id
     *
     * @return array ret
     */
    function getreportnum($productid, $userid)
    {
        $this->db->where('productid', $productid);
        $this->db->where('userid', $userid);
        $ret=$this->db->get('reportlayout');
        if ($ret!=null && $ret->num_rows()>0) {
            $ret=$ret->num_rows();         
        } else {
            $ret=0;
        }
        return $ret;
        
    }
    
    /**
     * deletereport function
     * delete report
     *
     * @param int    $productid  product id
     * @param int    $userid     user id
     * @param string $reportname report name
     * @param string $type       type
     *
     * @return void
     */
    function deletereport($productid, $userid, $reportname, $type)
    {
        $this->db->where('productid', $productid);
        $this->db->where('reportname', $reportname);
        $this->db->where('userid', $userid);
        $this->db->where('type', $type);
        $this->db->delete('reportlayout');
    }
    
    /**
     * updatereport function
     * update report
     *
     * @param int    $productid  product id
     * @param int    $userid     user id
     * @param string $reportname report name
     * @param string $type       type
     * @param string $location   location
     *
     * @return void
     */
    function updatereport($productid, $userid, $reportname, $type, $location)
    {
        $data = array(
                'location' => $location
                        );
        
        $this->db->where('productid', $productid);
        $this->db->where('reportname', $reportname);
        $this->db->where('userid', $userid);
        $this->db->where('type', $type);        
        $this->db->update('reportlayout', $data);
    }
}