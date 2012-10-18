<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Config for the CodeIgniter Redis library
 *
 * @see ../libraries/Redis.php
 */

// Connection details
$config['redis_host'] = 'localhost';		// IP address or host
$config['redis_port'] = '6379';				// Default Redis port is 6379
$config['redis_password'] = '';				// Can be left empty when the server does not require AUTH