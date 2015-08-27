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
 * Datamanage Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Datamanage extends CI_Model
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
        $this->load->config('tank_auth', true);
        require_once('PasswordHash.php');
    }

    /**
     * createurl function
     * Returns the current URL does not include the index.php
     *
     * @return string newurl
     */
    function createurl ()
    {
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $res = explode("/index", $url);
        $newurl = $res['0'];
        return $newurl;
    }

    /**
     * createuser function
     * create superuser
     *
     * @param string $username         username
     * @param string $email            email
     * @param string $password         password
     * @param string $email_activation email activation
     * 
     * @return array data
     */
    function createuser ($username, $email, $password, $email_activation)
    {
        $this->load->database();
        $hasher = new PasswordHash(
            $this->config->item('phpass_hash_strength', 'tank_auth'), 
            $this->config->item('phpass_hash_portable', 'tank_auth')
        );
        $hashed_password = $hasher->HashPassword($password);
        $data = array(
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email,
                'last_ip' => $this->input->ip_address()
        );
        
        if ($email_activation) {
            $data['new_email_key'] = md5(rand() . microtime());
        }
        if (! is_null($res = $this->create_user($data, ! $email_activation))) {
            $data['user_id'] = $res['user_id'];
            $data['password'] = $password;
            unset($data['last_ip']);
            return $data;
        }
        return null;
    }

    /**
     * create_user function
     * create userinfo and insert to database
     *
     * @param array   $data      data
     * @param boolean $activated activated
     *            
     * @return array user_id
     */
    function create_user ($data, $activated = true)
    {
        $this->load->database();
        $data['created'] = date('Y-m-d H:i:s');
        $data['activated'] = $activated ? 1 : 0;
        
        if ($this->db->insert('users', $data)) {
            $user_id = $this->db->insert_id();
            if ($activated)
                $this->create_profile($user_id);
            return array(
                    'user_id' => $user_id
            );
        }
        return null;
    }

    /**
     * activateuser function
     * active user
     *
     * @param int     $user_id           user id
     * @param string  $activation_key    activation key
     * @param boolean $activate_by_email activate by email
     * 
     * @return array activate_user
     */
    function activateuser ($user_id, $activation_key, $activate_by_email = true)
    {
        $this->purge_na(
            $this->config->item('email_activation_expire', 'tank_auth')
        );
        
        if ((strlen($user_id) > 0) and (strlen($activation_key) > 0)) {
            return $this->activate_user($user_id, $activation_key, $activate_by_email);
        }
        return false;
    }

    /**
     * activa_teuser function
     * real active user
     *
     * @param int     $user_id           user id
     * @param string  $activation_key    activation key
     * @param boolean $activate_by_email activate by email
     *            
     * @return boolean
     */
    function activate_user ($user_id, $activation_key, $activate_by_email)
    {
        $this->load->database();
        $this->db->select('1', false);
        $this->db->where('id', $user_id);
        if ($activate_by_email) {
            $this->db->where('new_email_key', $activation_key);
        } else {
            $this->db->where('new_password_key', $activation_key);
        }
        $this->db->where('activated', 0);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 1) {
            
            $this->db->set('activated', 1);
            $this->db->set('new_email_key', null);
            $this->db->where('id', $user_id);
            $this->db->update('users');
            
            $this->create_profile($user_id);
            return true;
        }
        return false;
    }

    /**
     * purge_na function
     * purge na
     *
     * @param int $expire_period expire period
     * 
     * @return void
     */
    function purge_na ($expire_period = 172800)
    {
        $this->load->database();
        $this->db->where('activated', 0);
        $this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
        $this->db->delete('users');
    }

    /**
     * createsuperuser function
     * create user
     *
     * @param string  $username  user name
     * @param string  $password  password
     * @param string  $email     email
     * @param boolean $activated activated
     *            
     * @return array user_id
     */
    function createsuperuser ($username, $password, $email, $activated = true)
    {
        $this->load->database();
        $data['created'] = date('Y-m-d H:i:s');
        $data['activated'] = $activated ? 1 : 0;
        $hasher = new PasswordHash(
            $this->config->item('phpass_hash_strength', 'tank_auth'), 
            $this->config->item('phpass_hash_portable', 'tank_auth')
        );
        $hashed_password = $hasher->HashPassword($password);
        $data = array(
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email,
                'last_ip' => $this->input->ip_address()
        );
        if ($this->db->insert('users', $data)) {
            $user_id = $this->db->insert_id();
            if ($activated)
                $this->create_profile($user_id);
            $this->insertrole($email);
            return array(
                    'user_id' => $user_id
            );
        }
        return false;
    }

    /**
     * insertrole function
     * insert default role
     *
     * @param string $email email
     * 
     * @return viod
     */
    function insertrole ($email)
    {
        $this->load->database();
        $user = $this->get_user_by_email($email);
        if ($user != null && isset($user->id)) {
            $data = array(
                    'userid' => $user->id,
                    'roleid' => 3
            );
            $this->db->insert('user2role', $data);
        }
    }

    /**
     * get_user_by_email function
     * get user by email
     *
     * @param string $email email
     * 
     * @return query row
     */
    function get_user_by_email ($email)
    {
        $this->load->database();
        $this->db->where('LOWER(email)=', strtolower($email));
        
        $query = $this->db->get('users');
        if ($query->num_rows() == 1)
            return $query->row();
        return null;
    }

    /**
     * create_profile function
     * create profile
     *
     * @param int $user_id user id
     * 
     * @return void
     */
    function create_profile ($user_id)
    {
        $this->load->database();
        $this->db->set('user_id', $user_id);
        return $this->db->insert('user_profiles');
    }
}
