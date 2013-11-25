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
class Home extends CI_Controller {
   
    function __construct() {
        parent::__construct();       
        $this -> load -> Model('common');
        $this->load->model('pluginm');
        $this -> common -> requireLogin();
        $this->load->model ( 'pluginlistmodel' );
    }

    function index() {
        
        $this -> common -> cleanCurrentProduct();
        $this -> common -> loadHeader();
        $userId = $this->common->getUserId ();
        $userKeys = $this->pluginlistmodel->getUserKeys ( $userId );
		if ($userKeys) {
			$key = $userKeys->user_key;
			$secret = $userKeys->user_secret;
				
			$this->data['key'] = $key;
			$this->data['secret'] = $secret;
				
			$this -> load -> view('home',$this->data);
		}
		else {
			$this->data ['msg'] = lang ( 'plg_get_keysecret_home' );
			$this -> load -> view('home',$this->data);
		}
       
    }

   

}
