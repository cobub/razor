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
 * User_profile Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class User_profile extends CI_Model
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
     * getUserPorfile function
     * Get user porfile
     *
     * @param int $uerid uerid
     *
     * @return query
     */
    function getUserPorfile($uerid)
    {
        $sql = "select " . $this->db->dbprefix('users') . ".username," . $this->db->dbprefix('users') . ".email useremail," . $this->db->dbprefix('user_profiles') . ".* from " . $this->db->dbprefix('users') . "   left join " . $this->db->dbprefix('user_profiles') . "  on " . $this->db->dbprefix('user_profiles') . ".user_id = " . $this->db->dbprefix('users') . ".id where " . $this->db->dbprefix('users') . ".id = " . $uerid;
        $query = $this->db->query($sql);
        return $query->first_row();
    }

    /**
     * AddUserPorfile function
     * Add user porfile
     *
     * @param int    $userId      userId
     * @param string $username    username
     * @param string $companyname companyname
     * @param string $contact     contact
     * @param string $telephone   telephone
     * @param string $QQ          QQ
     * @param string $MSN         MSN 
     * @param string $Gtalk       Gtalk
     *
     * @return void
     */
    function addUserPorfile($userId, $username, $companyname, $contact, $telephone, $QQ, $MSN, $Gtalk)
    {
        $userId = $this->common->getUserId();
        $username = $this->input->post('username');
        $data = array('companyname' => $companyname,'contact' => $contact,'telephone' => $telephone,'QQ' => $QQ,'MSN' => $MSN,'Gtalk' => $Gtalk);
        $query = $this->db->query("select * from " . $this->db->dbprefix('user_profiles') . "  where user_id = $userId");
        if ($query != null) {
            $this->db->where('user_id', $userId);
            $this->db->update('user_profiles', $data);
        } else {
            $this->db->insert('user_profiles', $data);
        }
        $query = $this->db->query("select * from " . $this->db->dbprefix('users') . " where id = " . $userId);
        $date2 = array('username' => $username);
        if ($query != null && $query->num_rows() > 0) {
            $this->db->where('id', $userId);
            $this->db->update('users', $date2);
        }
    }
}