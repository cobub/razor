<?php
class Help extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->lang->load ( 'allview' );
		$this->load->model ( 'common' );
		$this->load->helper ( 'cookie' );
	}
	function index() {
		$this->loadHeader ();
		$this->load->view ( 'helper/helpview' );
	}
	function loadHeader() {
		if (! $this->common->isUserLogin ()) {
			$dataheader ['login'] = false;
			$this->load->view ( 'helper/header', $dataheader );
		} else {
			$dataheader ['user_id'] = $this->common->getUserId ();
			$dataheader ['pageTitle'] = $this->common->getPageTitle ( $this->router->fetch_class () );
			if ($this->common->isAdmin ()) {
				$dataheader ['admin'] = true;
			}
			$dataheader ['login'] = true;
			$dataheader ['username'] = $this->common->getUserName ();
			log_message ( "error", "Load Header 123" );
			$dataheader ['language'] = $this->config->item ( 'language' );
			$this->load->view ( 'helper/header', $dataheader );
		}
	}
}