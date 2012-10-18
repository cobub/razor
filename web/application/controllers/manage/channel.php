<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
class Channel extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');		
		$this->lang->load('tank_auth');
		$this->load->library('session');
		$this->load->model('common');		
		$this->load->model('channelmodel','channel');
		$this->load->model('product/productmodel','product');
		
	}
	function index()
	{
		$userid=$this->common->getUserId();		
		$this->data['platform']=$this->channel->getplatform();	
		$this->data['num']=$this->channel->getdechannelnum($userid);
		$this->data['channel']=$this->channel->getdechannel($userid);	
		$this->data['allsychannel']=$this->channel->getallsychannel();
		$this->data['isAdmin']=$this->common->isAdmin();
		$this->common->loadHeader(lang('m_channelManagement'));
		$this->load->view('manage/channelview',$this->data);
	}
	
	//add custom channel
	function addchannel()
	{
		$userid=$this->common->getUserId();	
		$channel_name=$_POST['channel_name'];
		$platform=$_POST['platform'];
		$isUnique=$this->channel->isUniqueChannel($userid,$channel_name,$platform);
		if(!empty($isUnique)){
			echo false;
		}else{
	   if ($channel_name != '' && $platform != '')
	    {
	    		
		  $this->channel->addchannel($channel_name, $platform,$userid);
		  echo true;
		}}
		
	}
	//add system channel
	function addsychannel()
	{
		$userid=$this->common->getUserId();
		$channel_name=$_POST['channel_name'];
		$platform=$_POST['platform'];
		$isUnique=$this->channel->isUniqueSystemchannel($channel_name,$platform);
		if(!empty($isUnique)){
			echo false;
		}
		else{
		if ($channel_name != '' && $platform != '')
		{
			$this->channel->addsychannel($channel_name, $platform,$userid);
			echo true;
		}
		}
	
	
	
	}
	//edit channel
	function editchannel($channel_id)
	{
		$userid=$this->common->getUserId();	
		//$channel_id=$_GET['id'];
		$this->data['platform']=$this->channel->getplatform();	
		$this->data['edit']=$this->channel->getdechaninfo($userid,$channel_id);
		$edit=$this->channel->getdechaninfo($userid,$channel_id);
		$this->common->loadHeader(lang('v_man_pr_editChannel'));
		$this->load->view('manage/channeledit',$this->data);
		
			
	}
	//modify channel
	function modifychannel()
	{	
		$channel_id=$_POST['channel_id'];
		$channel_name=$_POST['channel_name'];
		$platform=$_POST['platform'];
		$type=$this->channel->getChannelType($channel_id);
		$isUnique='';
		if($type=="user"){
			$userid=$this->common->getUserId();
			$isUnique=$this->channel->isUniqueChannel($userid,$channel_name,$platform);
		}
		if($type=="system"){
			$isUnique=$this->channel->isUniqueSystemchannel($channel_name,$platform);
		}
		if(!empty($isUnique)){
			echo false;
		}else{
	  if ($channel_name != '' && $platform != '')
	    {		
		  $this->channel->updatechannel($channel_name, $platform,$channel_id);	
		  echo true;		   
		}
		}
	}
	//delete channel (mark delete)
	function deletechannel($channel_id)
	{
	  //$channel_id=$_GET['id'];
	  $this->channel->deletechannel($channel_id);
	  $this->index();	 
	}
	//app channel
	function appchannel()
	{
		$user_id=$this->common->getUserId();	
		$product = $this->common->getCurrentProduct();
		if(!empty($product)){
		$product_id=$product->id;
		$platform = $this->common->getCurrentProduct()->product_platform;
		//echo $platform;
		$this->data['productkey']=$this->channel->getproductkey($user_id,$product_id,$platform);
		$this->data['deproductkey']	=$this->channel->getdefineproductkey($user_id,$product_id,$platform);
		$this->data['channel']=$this->channel->getdefinechannel($user_id,$product_id,$platform);	
		$this->data['sychannel']=$this->channel->getsychannel($user_id,$product_id,$platform);
		$this->common->loadHeader(lang('m_rpt_appChannel') );
		$this->load->view('manage/appchannel',$this->data);
		}
		else{
			redirect ( '/auth/login/' );
		}
		
	}
	//open channel
	function openchannel($channel_id)
	{
		$user_id=$this->common->getUserId();	
		$product = $this->common->getCurrentProduct();
		if(!empty($product)){
		$product_id=$product->id;
		//$channel_id=$_GET['channelid'];
		$this->product->addproductchannel($user_id,$product_id,$channel_id);
		$this->appchannel();
		}
		else{
			redirect ( '/auth/login/' );
		}
		
		
	}
	
	
}