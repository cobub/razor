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
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Test extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		
		$this->load->Model ( 'common' );
			$this->load->library('memcached_library');
		
	}
	
	function index() {
		
		
		echo $this->memcached_library->getversion();
		echo "<br/>";

 }

}