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
 * Login_Attempts
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Login_attempts extends CI_Model
{
    private $_table_name = 'login_attempts';

    /**
     * __construct function
     * 
     * @return void
     */
    function __construct()
    {
        parent::__construct();

        $ci =& get_instance();
        $this->table_name = $ci->config->item('db_table_prefix', 'tank_auth')
        .$this->table_name;
    }

    /**
     * Get number of attempts to login occured from given IP-address or login
     *
     * @param string $ip_address ip address
     * @param string $login      login
     * 
     * @return int
     */
    function get_attempts_num($ip_address, $login)
    {
        $this->db->select('1', false);
        $this->db->where('ip_address', $ip_address);
        if (strlen($login) > 0) $this->db->or_where('login', $login);

        $qres = $this->db->get($this->table_name);
        return $qres->num_rows();
    }

    /**
     * Increase number of attempts for given IP-address and login
     *
     * @param string $ip_address ip address
     * @param string $login      login
     *  
     * @return void
     */
    function increase_attempt($ip_address, $login)
    {
        $this->db->insert($this->table_name, array('ip_address' => $ip_address, 'login' => $login));
    }

    /**
     * Clear all attempt records for given IP-address and login.
     * Also purge obsolete login attempts (to keep DB clear).
     *
     * @param string $ip_address    ip_address
     * @param string $login         login
     * @param int    $expire_period expire_period=86400
     * 
     * @return void
     */
    function clear_attempts($ip_address, $login, $expire_period = 86400)
    {
        $this->db->where(array('ip_address' => $ip_address, 'login' => $login));

        // Purge obsolete login attempts
        $this->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expire_period);

        $this->db->delete($this->table_name);
    }
}

/* End of file login_attempts.php */
/* Location: ./application/models/auth/login_attempts.php */