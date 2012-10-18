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
class Profile extends CI_Controller
{
	private $data = array();
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->model( 'common' );
		$this->load->library('form_validation');
		$this->load->model ( 'user/ums_user', 'user' );
		$this->load->model('user/user_profile','profile');	    
	    $this->canRead = $this->common->canRead ( $this->router->fetch_class () );
	    $this->common->requireLogin();
	    
	   

	}

	
	function modify()
	{
		$userid = $this->common->getUserId();
	    $profile=$this->profile->getUserPorfile($userid);
	    $this->data['profile']=$profile;
	    $this->common->loadHeader(lang('m_pr_editProfile'));
		$this->load->view ( 'user/userprofile', $this->data);
	}
    function saveprofile()
	{
		$this->form_validation->set_rules('email', lang('l_re_email'), 'trim');
		$this->form_validation->set_rules('username', lang('l_username'), 'trim');
		$this->form_validation->set_rules('contact', lang('m_pr_contact'), 'trim');
		$this->form_validation->set_rules('companyname', lang('m_pr_company'), 'trim');		
		$this->form_validation->set_rules('telephone', lang('m_pr_telephone'), 'trim|valid_telephone|xss_clean|callback_check_telephone');
		$this->form_validation->set_rules('QQ', 'QQ', 'trim|callback_check_qq');
		$this->form_validation->set_rules('MSN', 'MSN', 'trim|xss_clean|valid_email');
		$this->form_validation->set_rules('Gtalk', 'Gtalk', 'trim');
		if ($this->form_validation->run()) 
		{
			$userId = $this->common->getUserId();
			$username = $this->input->post('username');
			$companyname = $this->input->post('companyname');
			$contact = $this->input->post('contact');
			$telephone = $this->input->post('telephone');
			$QQ = $this->input->post('QQ');
			$MSN = $this->input->post('MSN');
			$Gtalk = $this->input->post('Gtalk');
		    $this->profile->addUserPorfile($userId,$username,$companyname,$contact,$telephone,$QQ,$MSN,$Gtalk);
//			$this->common->show_message("产品添加成功，AppKey为$key,".anchor('/', '返回应用程序列表'));
			$this->common->show_message(lang('m_pr_modifyS'));
		}
		else 
		{	$this->common->loadHeader();		
			$this->load->view ( 'user/userprofile');
		}
	}
	
	function check_qq($qq){
		if(empty($qq)){
			return true;
		}
		if(!preg_match('/^[1-9]{5,10}$/', $qq)){
			$this->form_validation->set_message('check_qq',lang('m_pr_modifyQQ'));
			return false;
		}
		return true;
	}
	
	function check_telephone($phone){
		if(empty($phone)){
			return true;
		}
		if(!preg_match('/^(^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^1[3458]\d{9}$)$/',$phone)){
			$this->form_validation->set_message('check_telephone',lang('m_pr_modifyTP'));
			return false;
		}
		return true;
	}
}