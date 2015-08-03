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
 * Hint Message
 */
 if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User_Autologin
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class User_Autologin extends CI_Model
{
    private $_table_name         = 'user_autologin';
    private $_users_table_name   = 'users';

    /**
     * __construct function
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();

        $ci =& get_instance();
        $this->table_name       = $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
        $this->users_table_name = $ci->config->item('db_table_prefix', 'tank_auth').$this->users_table_name;
    }

    /**
     * Get user data for auto-logged in user.
     * Return NULL if given key or user ID is invalid.
     *
     * @param int    $user_id user id
     * @param string $key     key
     * 
     * @return object
     */
    function get($user_id, $key)
    {
        $this->db->select($this->users_table_name.'.id');
        $this->db->select($this->users_table_name.'.username');
        $this->db->from($this->users_table_name);
        $this->db->join($this->table_name, $this->table_name.'.user_id = '.$this->users_table_name.'.id');
        $this->db->where($this->table_name.'.user_id', $user_id);
        $this->db->where($this->table_name.'.key_id', $key);
        $query = $this->db->get();
        if ($query->num_rows() == 1) return $query->row();
        return null;
    }

    /**
     * Save data for user's autologin
     *
     * @param int    $user_id user id
     * @param string $key     key
     * 
     * @return bool
     */
    function set ($user_id, $key)
    {
        return $this->db->insert(
            $this->table_name, array(
                'user_id' => $user_id,
                'key_id' => $key,
                'user_agent' => substr($this->input->user_agent(), 0, 149),
                'last_ip' => $this->input->ip_address()
                )
        );
    }

    /**
     * Delete user's autologin data
     *
     * @param int    $user_id user id
     * @param string $key     key
     * 
     * @return void
     */
    function delete ($user_id, $key)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('key_id', $key);
        $this->db->delete($this->table_name);
    }

    /**
     * Delete all autologin data for given user
     *
     * @param int $user_id user id
     * 
     * @return void
     */
    function clear ($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->table_name);
    }

    /**
     * Purge autologin data for given user and login conditions
     *
     * @param int $user_id user id
     * 
     * @return void
     */
    function purge ($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('user_agent', substr($this->input->user_agent(), 0, 149));
        $this->db->where('last_ip', $this->input->ip_address());
        $this->db->delete($this->table_name);
    }
}

/* End of file user_autologin.php */
/* Location: ./application/models/auth/user_autologin.php */