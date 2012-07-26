<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
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
class Autoupdate extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		$this->load->model ( 'common' );
		$this->common->loadHeader ();
		$this->load->model ( 'channelmodel', 'channel' );
		$this->load->library ( 'upload' );	
	
	}
	function index($cp_id,$channel_id) {
		$userid = $this->common->getUserId ();			
		$result = $this->channel->getuapkplatform ($channel_id);
		$this->data ['cp_id'] = $cp_id;			
		$isupdate=$this->channel->judgeupdate($cp_id);		 
		if ($result ['platform'] == 1) {
			if($isupdate)
			{
				$this->data ['apkinfo'] = $this->channel->getakpinfo( $userid, $cp_id );
				$this->data ['androidinfo'] = $this->channel->getupdatehistory($cp_id);
				$this->load->view ( 'autoupdate/androidhistory', $this->data );
			}
			else
			{
				$this->data['upinfo']= 1; 
				$this->load->view ( 'autoupdate/updateandroid', $this->data );
			}
		}
		if ($result ['platform'] == 2) {
			if($isupdate)
			{
				$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
				$this->data ['iphoneinfo'] = $this->channel->getupdatehistory($cp_id);
			    $this->load->view ( 'autoupdate/iphonehistory', $this->data );
			}
			else
			{
			  $this->data['upinfo']= 1; 
			 $this->load->view ( 'autoupdate/updateiphone', $this->data );
			
			}
		}
	
	}
	//上传apk文件
	function uploadapk($cp_id,$upinfo) {
		
		$config ['upload_path'] = 'assets/android/';
		$config ['allowed_types'] = '*';
		$config ['max_size'] = '30000000';
		$this->upload->initialize ( $config );
		$this->load->library ( 'upload', $config );				
		$this->form_validation->set_rules ( 'description', lang('allview_autoupdatedescription'), 'trim|required|xss_clean|min_length[10]' );
		$this->form_validation->set_rules ( 'versionid', lang('allview_autoupdateversionid'), 'trim|required|xss_clean' );
		$this->data['upinfo']= $upinfo;  
		if (($this->form_validation->run () == FALSE)||(! $this->upload->do_upload ())){
			$error = array ('error' => $this->upload->display_errors () );
			$this->data['error'] =array ('error' => $this->upload->display_errors () );		
			$this->data['cp_id']=$cp_id;
			$this->load->view('autoupdate/updateandroid', $this->data );
		
		} 
		else{
								
			$upload_data = $this->upload->data ();
			$updateurl = base_url () . '/assets/android/' . $upload_data ['file_name'];			
			$userid = $this->common->getUserId ();						
			$description = $this->input->post ('description');
			$versionid = $this->input->post ('versionid');
			$versioninfo=$this->channel->getversionid($cp_id,$versionid,$upinfo);						
			if($versioninfo)
			{				
			    $isupdate = $this->channel->updateapk( $userid, $cp_id, $description, $updateurl, $versionid ,$upinfo);
				if ($isupdate)
				 {
					$this->data ['apkinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
					$this->load->view ( 'autoupdate/updateandrlist', $this->data );
				 }
			}
			else 
			{			   
		        $this->data['errorversion']=lang('updateandroid_versionsizeremind');
				$this->data['cp_id']=$cp_id;				
			    $this->load->view ( 'autoupdate/updateandroid', $this->data );
			}

	}
		
		
	
	}
	//iphone app的处理
	function uploadapp($cp_id,$upinfo) {
		
		$this->form_validation->set_rules ( 'appurl', lang('allview_autoupdateappurl'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'versionid', lang('allview_autoupdateversionid'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'description', lang('allview_autoupdatedescription'), 'trim|required|xss_clean|min_length[10]' );
		$this->data['upinfo']= $upinfo;  
		if ($this->form_validation->run () == FALSE) {
			$error = array ('error' => $this->upload->display_errors () );
			$this->data['error'] = array ('error' => $this->upload->display_errors () );
			$this->data['cp_id']=$cp_id;
			$this->load->view ( 'autoupdate/updateiphone', $this->data);
		
		}
	else	{		  
			
		     $updateurl = $this->input->post ( 'appurl' );
			 $userid = $this->common->getUserId ();		
			 $description = $this->input->post ( 'description' );
			 $versionid = $this->input->post ('versionid');
			 $versioninfo=$this->channel->getversionid($cp_id,$versionid,$upinfo);								
			if($versioninfo)
			{
				$isupdate = $this->channel->updateapp ( $userid, $cp_id, $description, $updateurl, $versionid,$upinfo);
			    if ($isupdate) {
				$this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
				$this->load->view ( 'autoupdate/updateipolist', $this->data );
			    }
				
			}
			else 
			{
				$this->data['errorversion']=lang('updateandroid_versionsizeremind');
				$this->data['cp_id']=$cp_id;				
			    $this->load->view ( 'autoupdate/updateiphone', $this->data);
			}
			 
			 }
		
	}
//	
//	
//   //自动更新历史列表中的更新功能
//	function updateinfo($channel_id,$cpid,$vp_id)	
//	{  
//		 
//	    $this->data ['cp_id']= $cpid;
//		$result = $this->channel->getuapkplatform ($channel_id);
//	    if ($result ['platform'] == 1) {
//	    	
//	    $this->data ['updateinfo'] = $this->channel->getupdatelistinfo($vp_id);	
//		$this->load->view ( 'autoupdate/updateandroid', $this->data );
//	    }
//	    if ($result ['platform'] == 2) {
//	    	 $this->data ['updateinfo'] = $this->channel->getupdatelistinfo($vp_id);			
//			 $this->load->view ( 'autoupdate/updateiphone', $this->data );		
//		}
//	}

	//$upinfo 标记是否为更新还是升级      0为更新 1为升级
	//自动更新列表中的更新功能
	function updatenewinfo($channel_id,$cp_id)	
	{	  
	    $this->data['cp_id']= $cp_id;	
	     $this->data['upinfo']= 0;    
		$result = $this->channel->getuapkplatform ($channel_id);
	    if ($result ['platform'] == 1) {
	    	
	    $this->data ['updateinfo'] = $this->channel->getnewlistinfo($cp_id);	
		$this->load->view ('autoupdate/updateandroid', $this->data);
	    }
	    if ($result ['platform'] == 2) {
	    	 $this->data ['updateinfo'] = $this->channel->getnewlistinfo($cp_id);			
			 $this->load->view ('autoupdate/updateiphone', $this->data);		
		}
	}
	//升级自动更新内容
	function upgradeinfo($channel_id,$cp_id)
	{
	    $this->data['cp_id']= $cp_id;
	     $this->data['upinfo']= 1;
		$result = $this->channel->getuapkplatform ($channel_id);
	    if ($result ['platform'] == 1) {	    	
	   
		$this->load->view ('autoupdate/updateandroid', $this->data);
	    }
	    if ($result ['platform'] == 2) {	    	 		
			 $this->load->view ('autoupdate/updateiphone', $this->data);		
		}
	}
	//删除历史列表中的自动更新
	function deleteupdate($channel_id,$cp_id,$vp_id)
	{
	    $userid = $this->common->getUserId ();
	    $this->channel->deleteupdate($vp_id);
		$result = $this->channel->getuapkplatform ($channel_id);					
		if ($result ['platform'] == 1) {
			
			$this->data ['apkinfo'] = $this->channel->getakpinfo( $userid, $cp_id );
			$this->data ['androidinfo'] = $this->channel->getupdatehistory($cp_id);
			$this->load->view ( 'autoupdate/androidhistory', $this->data );
			
		}
		if ($result ['platform'] == 2) {
			    $this->data ['appinfo'] = $this->channel->getakpinfo ( $userid, $cp_id );
				$this->data ['iphoneinfo'] = $this->channel->getupdatehistory($cp_id);
			    $this->load->view ( 'autoupdate/iphonehistory', $this->data );
		
		}
	
	}
	
}