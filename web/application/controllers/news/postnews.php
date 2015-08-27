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
 * Postnews Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class PostNews extends CI_Controller
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('interface/postdatautility', 'postdata');
        $this->load->model('news/getnews', 'getnews');
        $this->load->model('common');
    }

    /**
     * GetConfig function , get configration
     * 
     * @param int    $version  version
     * @param string $language language
     * @param string $baseurl  base url
     * 
     * @return void
     */
    function getConfig(&$version, &$language, &$baseurl)
    {
        $version = $this->config->item('version');
        $language = $this->config->item('language');
        $baseurl = $this->config->item('base_url');
    }

    /**
     * Index function 
     * 
     * @return responsedata
     */
    public function index()
    {
        $totalUsers = 0;
        $appList = array();
        $appRow = array();
        $today = date('Y-m-d', time());
        $userId = $this->common->getUserId();
        $this->getConfig($version, $language, $baseurl);
        $this->getnews->getData($userId, $email, $modified);
        $appList = $this->getnews->getAppList($userId, $today);
        foreach ($appList as $appRow) {
            $totalUsers += $appRow['totaluser'];
        }
        $postData = array(
                'appList' => $appList,
                'email' => $email,
                'version' => $version,
                'language' => $language,
                'totalusers' => $totalUsers,
                'baseurl' => $baseurl,
                'modified' => $modified
        );
        $serverURL = "http://news.cobub.com/index.php?/news/addClientdata";
        $jsonData = array(
                'content' => json_encode($postData)
        );
        $responseData = $this->postdata->post($serverURL, $jsonData);
        echo $responseData;
    }
}
?>
