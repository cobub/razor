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
 * Pluginlist Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Pluginlistmodel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        $this->load->database();
        $this->load->model('common');
        $this->load->model('pluginm');
    }

    /**
     * getUserKeys function
     * Get user keys by user id
     *
     * @param int $userId user id
     *
     * @return query first_row
     */
    function getUserKeys ($userId)
    {
        if (! $userId)
            return false;
        
        $query = $this->db->query(
            "select * from " . $this->db->dbprefix("userkeys") .
            " where user_id = $userId"
        );
        if ($query && $query->num_rows() > 0) {
            return $query->first_row();
        }
        return false;
    }
    
    /**
     * getMyPlugins function
     * get myself plugins
     *
     * @param int $userId user id
     *
     * @return query pluginArray
     */
    function getMyPlugins ($userId)
    {
        $pluginsArray = $this->pluginm->run("getPluginInfo", "");
        return $pluginsArray;
    }
    
    /**
     * getAllPlugins function
     * get serve all plugins
     *
     * @param string $language language
     *
     * @return query response
     */
    function getAllPlugins ($language)
    {
        $url = SERVER_BASE_URL . "/index.php?/api/plugin/getPluginList";
        $datas = array(
                'language' => $language
        );
        $response = $this->common->curl_post($url, $datas);
        return $response;
    }
    
    /**
     * verifyUserKeys function
     * test and verify user's userKey&userSecret Success:return true or return
     *
     * @param string $userKey    user key
     * @param string $userSecret user secret
     *
     * @return boolean
     */
    function verifyUserKeys ($userKey, $userSecret)
    {
        $url = SERVER_BASE_URL . "/index.php?/api/igetui/auth";
        $data = array(
                'userKey' => $userKey,
                'userSecret' => $userSecret
        );
        $response = $this->common->curl_post($url, $data);
        $responseArray = json_decode($response, true);
        if ($responseArray['flag'] > 0) {
            return true;
        }
        return false;
    }
    
    /**
     * saveUserKeys function
     * save user's userkey and usersecret to razor_userkeys's table
     *
     * @param int    $userId     user id
     * @param string $userKey    user key
     * @param string $userSecret user secret
     *
     * @return void
     */
    function saveUserKeys ($userId, $userKey, $userSecret)
    {
        $this->db->from($this->db->dbprefix('userkeys'));
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        if ($query && $query->num_rows() > 0) {
            
            $this->db->where('user_id', $userId);
            $data = array(
                    'user_key' => $userKey,
                    'user_secret' => $userSecret
            );
            $this->db->update($this->db->dbprefix('userkeys'), $data);
        } else {
            
            $data = array(
                    'user_id' => $userId,
                    'user_key' => $userKey,
                    'user_secret' => $userSecret
            );
            $this->db->insert($this->db->dbprefix('userkeys'), $data);
        }
    }
    
    /**
     * activePlugin function
     * active Plugin
     *
     * @param int    $userId           user id
     * @param string $pluginIdentifier plugin identifier
     *
     * @return void
     */
    function activePlugin ($userId, $pluginIdentifier)
    {
        if ($this->isPluginExist($pluginIdentifier, $userId)) {
            $data = array(
                    'status' => 1
            );
            // $this->db->where ( 'user_id', $userId );
            $this->db->where('identifier', $pluginIdentifier);
            $this->db->update($this->db->dbprefix("plugins"), $data);
        } else {
            $data = array(
                    'status' => 1,
                    'user_id' => $userId,
                    'identifier' => $pluginIdentifier
            );
            $this->db->insert($this->db->dbprefix("plugins"), $data);
        }
    }
    
    /**
     * isPluginExist function
     * plugin is exist:Success return true or return false
     *
     * @param string $pluginIdentifier plugin identifier
     * @param int    $userId           user id
     *
     * @return bool
     */
    function isPluginExist ($pluginIdentifier, $userId)
    {
        $sql = "select * from " . $this->db->dbprefix("plugins") .
                 " where user_id = $userId and identifier = '$pluginIdentifier'";
        $query = $this->db->query($sql);
        if ($query && $query->num_rows() > 0) {
            return true;
        }
        return false;
    }
    
    /**
     * disablePlugin function
     * fobidden one plugin
     *
     * @param int    $userId           user id
     * @param string $pluginIdentifier plugin identifier
     *
     * @return void
     */
    function disablePlugin ($userId, $pluginIdentifier)
    {
        $data = array(
                'status' => 0
        );
        // $this->db->where ( 'user_id', $userId );
        $this->db->where('identifier', $pluginIdentifier);
        $this->db->update($this->db->dbprefix("plugins"), $data);
    }
    
    /**
     * getPluginStatus function
     * get plugin's status 0-->active; 1-->fobidden
     *
     * @param int    $userId     user id
     * @param string $identifier identifier
     *
     * @return void
     */
    function getPluginStatus ($userId, $identifier)
    {
        // $this->db->where ( 'user_id', $userId );
        $this->db->where('identifier', $identifier);
        $query = $this->db->get($this->db->dbprefix("plugins"));
        if ($query && $query->num_rows() > 0) {
            return $query->first_row()->status;
        }
        return 0;
    }

    /**
     * getPluginStatusByIdentifier function
     * get Plugin Status By Identifier
     *
     * @param string $identifier identifier
     *
     * @return query arraa
     */
    function getPluginStatusByIdentifier ($identifier)
    {
        $sql = "select * from " . $this->db->dbprefix('plugins') .
                 " where identifier='" . $identifier . "';";
        // echo $sql;
        $ret = $this->db->query($sql);
        if ($ret != null && $ret->num_rows() > 0) {
            $arraa = $ret->result_array();
            return $arraa[0]['status'];
        } else {
            return 0;
        }
    }

    /**
     * getUserActive function
     * get User Active
     *
     * @param int $uid uid
     *
     * @return query arraa
     */
    function getUserActive ($uid)
    {
        $sql = "select * from " . $this->db->dbprefix('userkeys') .
                 " where user_id='" . $uid . "';";
        $ret = $this->db->query($sql);
        if ($ret != null && $ret->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
