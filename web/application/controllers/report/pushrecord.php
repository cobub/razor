<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class PushRecord extends CI_Controller {
	
    function __construct() {
        parent::__construct();
		$this->load->model('common');
		$this->load->model('product/pushrecordmodel','pushrecordmodel');
    }

    /*
     * input user push app record to table
     * 
     */
    function index() {
/*     	if (! isset ( $_POST ["content"] )) {
    		$ret = array (
    				'flag' => - 1,
    				'msg' => 'PushRecord Wrong!'
    		);
    		echo json_encode ( $ret );
    		return;
    	}
    	$pushinfo = $_POST ["content"];
    	log_message ( "debug", $pushinfo );
    	$content = json_decode ( $pushinfo );
    	$ret=$this->pushrecordmodel->confirm($content->userid,$content->username,$content->appname,$content->channelname,$content->num,$content->content,$content->date); */
    	/* $date = date('Y-m-d H:i:s',time());
    	$ret = $this->pushrecordmodel->confirm('110000','push_name','getui','cobub','1000000','Done is better than perfect.哦哦',$date);
    	if($ret['flag']<=0)
    	{
    		echo json_encode($ret);
    		return;
    	}
    	echo json_encode($ret);  */
    }

}

