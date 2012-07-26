<?php 
class Savecsv extends CI_Controller
{
	
	
	function __construct()
	{
		parent::__construct();		
		$this->common->requireLogin();
	}
	
	function index()
	{
		
	}
	
	function save($data)
	{
		$data ['data'] = $data;
		$this->load->view ( 'product/saveCsv', $data );
	}
	
}