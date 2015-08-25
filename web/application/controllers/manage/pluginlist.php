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
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Pluginlist Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Pluginlist extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->load->library('session');
        $this->load->model('common');
        $this->load->model('pluginlistmodel');
    }

    /**
     * Index function
     *
     * @return void
     */
    function index()
    {
        $userId = $this->common->getUserId();
        $userKeys = $this->pluginlistmodel->getUserKeys($userId);
        $plugins = array();
        if ($userKeys) {
            $this->data['puserkey'] = $userKeys->user_key;
            $this->data['pusersecret'] = $userKeys->user_secret;
            // /all use plugins
            $language = $this->config->item('language');
            $json = $this->pluginlistmodel->getAllPlugins($language);
            $this->data['allplugins'] = json_decode($json);
            // /my plug_ins
            $this->data['myPlugins'] = $this->pluginlistmodel->getMyPlugins($userId);
            if ($this->data['myPlugins'] && count($this->data['myPlugins']) > 0) {
                foreach ($this->data['myPlugins'] as $plugin) {
                    $plugin['status'] = $this->pluginlistmodel->getPluginStatus($userId, $plugin['identifier']);
                    
                    foreach ($this->data['allplugins'] as $allplugin) {
                        if ($allplugin->plugin_name == $plugin['name']) {
                            $myver = preg_replace('/[^\d]/', '', $plugin['version']);
                            $allver = preg_replace('/[^\d]/', '', $allplugin->plugin_version);
                            if ($myver < $allver) {
                                $plugin['new_version'] = $allplugin->plugin_version;
                            }
                        }
                    }
                    array_push($plugins, $plugin);
                }
            }
        } else {
            $this->data['msg'] = lang('plg_get_keysecret');
        }
        
        // //my plugins
        $this->data['myPlugins'] = $plugins;
        // user role
        $this->data['guest_roleid'] = $this->common->getUserRoleById($userId);
        
        $this->common->loadHeader(lang('plg_plugin_manage'));
        $this->load->view('manage/pluginsview', $this->data);
    }

    /**
     * ActivePlug function
     * ActivePlug active plugin
     *
     * @param string $identifier identifier
     *            
     * @return void
     */
    function activePlug($identifier)
    {
        $userId = $this->common->getUserId();
        $this->pluginlistmodel->activePlugin($userId, $identifier);
        redirect(site_url() . "/manage/pluginlist");
    }

    /**
     * DisablePlug fobidden plugin
     *
     * @param string $identifier identifier
     *            
     * @return void
     */
    function disablePlug($identifier)
    {
        $userId = $this->common->getUserId();
        $this->pluginlistmodel->disablePlugin($userId, $identifier);
        redirect(site_url() . "/manage/pluginlist");
    }
}

