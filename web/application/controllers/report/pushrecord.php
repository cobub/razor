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
 * PushRecord Controller
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class PushRecord extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('common');
        $this->load->model('product/pushrecordmodel', 'pushrecordmodel');
    }

    /**
     * Index function,input user push app record to table
     *
     * @return void
     */
    function index()
    {
        /*
         * if (! isset ( $_POST ["content"] )) {
         * $ret = array (
         * 'flag' => - 1,
         * 'msg' => 'PushRecord Wrong!'
         * );
         * echo json_encode ( $ret );
         * return;
         * }
         * $pushinfo = $_POST ["content"];
         * log_message ( "debug", $pushinfo );
         * $content = json_decode ( $pushinfo );
         * $ret=$this->pushrecordmodel->confirm($content->userid,$content->username,$content->appname,$content->channelname,$content->num,$content->content,$content->date);
         */
        /*
         * $date = date('Y-m-d H:i:s',time());
         * $ret =
         * $this->pushrecordmodel->confirm('110000','push_name','getui','cobub','1000000','Done
         * is better than perfect.哦哦',$date);
         * if($ret['flag']<=0)
         * {
         * echo json_encode($ret);
         * return;
         * }
         * echo json_encode($ret);
         */
    }
}
