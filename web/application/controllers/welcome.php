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
class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->Model('common');
		$this->load->model('product/productmodel','product');
		$this->load->model('product/productanalyzemodel');
		$this->common->requireLogin();
		
	}

	function index()
	{	
		    $this->common->cleanCurrentProduct();
			$data['user_id'] = $this->common->getUserId();
			$query = $this->product->getProductListByPlatform(1,$data['user_id']);
			$data['androidList'] = $query; 
			$queryIphone = $this->product->getProductListByPlatform(2,$data['user_id']);
			$data['iphoneList'] = $queryIphone;
			$today = date ( 'Y-m-d', time () );
			$yestoday = date ( "Y-m-d", strtotime ( "-1 day" ) );
			//获取今日昨日启动用户数
			$data['today_startuser'] = $this->productanalyzemodel->getStartUserCountByUserId($data['user_id'],$today);
			$data['yestoday_startuser'] = $this->productanalyzemodel->getStartUserCountByUserId($data['user_id'],$yestoday);
			
			//获取今日昨日所有项目的新用户数
			$data['today_newuser'] = $this->productanalyzemodel->getTotalNewUsersCountByUserId($data['user_id'],$today);
			$data['yestoday_newuser'] = $this->productanalyzemodel->getTotalNewUsersCountByUserId($data['user_id'],$yestoday);
			
			$this->load->view('main_form', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */