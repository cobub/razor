<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* CodeIgniter source modified for CIUnit
* 
* If you use MY_Session, change the paraent class.
*/

class CIU_Session extends CI_Session
{

    /**
     * Destroy the current session
     *
     * @access    public
     * @return    void
     */
    function sess_destroy()
    {
        // Kill the session DB row
        if ($this->sess_use_database === TRUE AND isset($this->userdata['session_id'])) {
            $this->CI->db->where('session_id', $this->userdata['session_id']);
            $this->CI->db->delete($this->sess_table_name);
        }

        // Kill the cookie: modified for CIUnit
        $array = array(
            $this->sess_cookie_name,
            addslashes(serialize(array())),
            ($this->now - 31500000),
            $this->cookie_path,
            $this->cookie_domain,
            0
        );
        $this->CI->output->set_cookie($array);
    }

    // --------------------------------------------------------------------

    /**
     * Write the session cookie
     *
     * @access    public
     * @return    void
     */
    function _set_cookie($cookie_data = NULL)
    {
        if (is_null($cookie_data)) {
            $cookie_data = $this->userdata;
        }

        // Serialize the userdata for the cookie
        $cookie_data = $this->_serialize($cookie_data);

        if ($this->sess_encrypt_cookie == TRUE) {
            $cookie_data = $this->CI->encrypt->encode($cookie_data);
        } else {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data = $cookie_data . md5($cookie_data . $this->encryption_key);
        }

        $expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();

        // Set the cookie: modified for CIUnit
        $array = array(
            $this->sess_cookie_name,
            $cookie_data,
            $expire,
            $this->cookie_path,
            $this->cookie_domain,
            $this->cookie_secure
        );
        $this->CI->output->set_cookie($array);
    }
}

/* End of file CIU_Session.php */
/* Location: ./application/third_party/CIUnit/libraries/CIU_Session.php */