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
class Authentication extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->Model('common');
		$this->load->Model('product/productmodel','product');
	}
	
	function auth()
	{
		if (!$this->common->isUserLogin()) {
			$dataheader['login'] = false;
			$dataheader['pageTitle'] = $this->common->getPageTitle($this->router->fetch_class());
			$this->load->view('layout/header',$dataheader);
		}
		else
		{
			$dataheader['user_id']	= $this->common->getUserId();
			$dataheader['pageTitle'] = $this->common->getPageTitle($this->router->fetch_class());
			if($this->common->isAdmin())
			{
				$dataheader['admin'] = true;
			}
			$dataheader['login'] = true;
			$dataheader['username']	= $this->common->getUserName();
			$product = $this->common->getCurrentProduct();
			if(isset($product) && $product!=null)
			{
				$dataheader['product'] = $product;
				log_message("error","HAS Product");
			}
			
			$productList = $this->product->getAllProducts($dataheader['user_id']);
			if($productList!=null && $productList->num_rows()>0)
			{
				$dataheader["productList"] = $productList;
			}
			log_message("error","Load Header 123");
			$this->load->view('layout/header',$dataheader);
		}
	}
}