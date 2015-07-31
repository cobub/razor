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
 * Api Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Common extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * verifParameter function
     * Verify parameter
     *
     * @param array $content content
     * @param array $array   array
     *
     * @return array ret
     */
    function verifParameter ($content, $array)
    {
        for ($i = 0; $i < count($array); $i ++) {
            if (! isset($content->$array[$i])) {
                $ret = array(
                        'flag' => - 5,
                        'msg' => 'JSON format is not correct'
                );
                return $ret;
            }
            if ($content->$array[$i] == "") {
                $ret = array(
                        'flag' => - 7,
                        'msg' => $array[$i] . ' is null'
                );
                return $ret;
            }
            // 判断日期格式
            if ($array[$i] == "startdate" || $array[$i] == "enddate") {
                preg_match(
                    '/[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|
                    (02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))/',
                    $content->$array[$i],
                    $matches
                );
                if (count($matches)) {
                
                } else {
                    $ret = array(
                            'flag' => - 8,
                            'msg' => $array[$i] . ' is invalid'
                    );
                    return $ret;
                }
            }
            // \+?[1-9][0-9]* 判断是否是非零正整数devicesk targetid
            if ($array[$i] == "erroridentifier" || $array[$i] == "productid" 
                ||$array[$i] == "eventid" || $array[$i] == "limit" 
                ||$array[$i] == "devicesk" || $array[$i] == "targetid"
            ) {
                    preg_match("/[0-9]*[1-9][0-9]*/", $content->$array[$i], $regs);
                if (count($regs)) {
                        
                } else {
                            $ret = array(
                                    'flag' => - 8,
                                    'msg' => $array[$i] . ' is invalid'
                            );
                            return $ret;
                }
            }
        }
        $ret = array(
                'flag' => 1,
                'msg' => 'Pass check'
        );
        return $ret;
    }

    /**
     * getuseridbysessionkey function
     * Get userid through sessionkey
     *
     * @param int $sessionkey session key
     *
     * @return user id
     */
    function getuseridbysessionkey ($sessionkey)
    {
        $this->db->select('id');
        $this->db->where('sessionkey', $sessionkey);
        $result = $this->db->get('users');
        if ($result && $result->num_rows() > 0) {
            $user = $result->row();
            return $user->id;
        }
        return false;
    }

    /**
     * verifyproductbyuserid function
     * Verify product info through userid
     *
     * @param int $userid user id
     *
     * @return prodcuctid
     */
    function verifyproductbyuserid ($userid)
    {
        $this->db->select('product_id');
        $this->db->where('user_id', $userid);
        $result = $this->db->get('user2product');
        if ($result && $result->num_rows() > 0) {
            $prodcuctid = $result->result_array();
            return $prodcuctid;
        }
        return false;
    }

    /**
     * verifyproductbyproductid function
     * Verify product information through userid and productid
     *
     * @param int $userid    userid
     * @param int $productid productid
     *
     * @return boolen true or false
     */
    function verifyproductbyproductid ($userid, $productid)
    {
        $this->db->where('user_id', $userid);
        $this->db->where('product_id', $productid);
        $result = $this->db->get('user2product');
        if ($result && $result->num_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * compareproductid function
     * Match productid infomation
     *
     * @param int $allproductid all productid
     * @param int $userid       userid
     *
     * @return array ret
     */
    function compareproductid ($allproductid, $userid)
    {
        $ret = array();
        $verifyproductid = $this->verifyproductbyuserid($userid);
        if ($verifyproductid) {
            for ($i = 0; $i < count($verifyproductid); $i ++) {
                for ($j = 0; $j < count($allproductid); $j ++) {
                    if ($verifyproductid[$i] == $allproductid[$j]['id']) {
                        $obj = array(
                                'id' => $verifyproductid[$i]
                        );
                        array_push($ret, $obj);
                    }
                }
            }
        }
        if (count($ret) > 0) {
            return $ret;
        } else {
            return false;
        }
    }

}