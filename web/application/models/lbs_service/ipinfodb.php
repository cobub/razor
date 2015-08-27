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
 * Ipinfodb Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Ipinfodb extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function google ()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('array');
        $this->load->model('service/utility', 'utility');
    }
    
    /**
     * getregioninfobyip function
     * Get region info By ipinfodb - ip query location
     *
     * @param string $ip ip
     *
     * @return array arr
     */
    function getregioninfobyip ($ip)
    {
        if ($ip == '')
            return false;
        $key = 'b2dff8fe622f22b7db124cb5a7925779b21bad03f7be12f4fb36cae4c4118e92';
        $url = "http://api.ipinfodb.com/v3/ip-city/?key=" . $key . "&ip=" . $ip;
        $client = $this->utility->Post2($url);
        
        if (! isset($client)) {
            $ret = array(
                    'flag' => - 8,
                    'msg' => 'Invalid regioninfo'
            );
            // echo json_encode($ret);
            return false;
        } else {
            $arr = explode(";", $client);
            if ($arr[0] != "OK") {
                $ret = array(
                        'flag' => - 9,
                        'msg' => 'Invalid IP'
                );
                return false;
            } else {
                $arr = $this->result2array($arr);
                return $arr;
            }
        }
    }

    /**
     * result2array function
     * result to array
     *
     * @param string $result result
     *
     * @return array data
     */
    function result2array ($result)
    {
        if ($result == '') {
            return;
        }
        $data = array();
        $data["postal_code"] = '';
        if ($result[4] != '-') {
            $data["country"] = $result[4];
        }
        if ($result[5] != '-') {
            $data["region"] = $result[5];
        }
        if ($result[6] != '-') {
            $data["city"] = $result[6];
        }
        $data["country"] = '';
        $data["region"] = '';
        $data["city"] = '';
        $data["street"] = '';
        $data["street_number"] = '';
        return $data;
    }
}
?>