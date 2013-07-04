<?php

/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package     Cobub Razor
 * @author      jianghe.cao@WBTECH Dev Team
 * @copyright   Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license     http://www.cobub.com/products/cobub-razor/license
 * @link        http://www.cobub.com/products/cobub-razor/
 * @since       Version 1.0
 * @filesource
 */
class PostNews extends CI_Controller 
{
    function __construct()
     {
        parent::__construct();
        /* Load models needed by this function */
        $this->load->model('interface/postdatautility', 'postdata');
        $this->load->model('news/getnews', 'getnews');
        $this->load->model('common');
     }

    /*
     * getConfig(): Get $version, $language and $baseurl
     */
   function getConfig(&$version, &$language, &$baseurl) 
    {
        $version = $this->config->item('version');
        $language = $this->config->item('language');
        $baseurl = $this->config->item('base_url');
    }    
    /*
     * test(): To test news post model
     */
    public function index() 
    {
        /* Initial parameters */
        $totalUsers = 0;
        $appList    = array();
        $appRow     = array();
        $today      = date('Y-m-d', time());        
        $userId     = $this->common->getUserId();

        /* Get all the data from databases */
        $this->getConfig($version, $language, $baseurl);
        $this->getnews->getData($userId, $email, $modified);
        $appList = $this->getnews->getAppList($userId, $today);
        foreach ($appList as $appRow)
        {
            $totalUsers += $appRow['totaluser'];
        }

        /* Prepare post data in array */
        $postData = array(
            'appList'=>$appList,
            'email'=>$email,
            'version'=>$version,
            'language'=>$language,
            'totalusers'=>$totalUsers,
            'baseurl'=>$baseurl,
            'modified'=>$modified,
        );       
        /* Call the post function */
        $serverURL = "http://news.cobub.com/index.php?/news/addClientdata";
        $jsonData = array(
            'content'=>json_encode($postData)
        );
        $responseData = $this->postdata->post($serverURL, $jsonData);
        echo $responseData;
    }
}

?>
