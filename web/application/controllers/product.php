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
class Product extends CI_Controller
{
	private $data = array();
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->Model('common');
		$this->load->model('channelmodel','channel');
		$this->load->model('product/productmodel','product');
		$this->load->model('product/productanalyzemodel');
		$this->load->model('product/newusermodel','newusermodel');
		$this->common->requireLogin();
		
	}
	
	function index()
	{
	
        $this->common->cleanCurrentProduct();       
		$this->data['category'] = $this->product->getProductCategory();		
		$this->data['user_id'] = $this->common->getUserId();
		$today = date ( 'Y-m-d', time () );
		$yestoday = date ( "Y-m-d", strtotime ( "-1 day" ) );
		
		$query = $this->product->getProductListByPlatform(1,$this->data['user_id'],$today,$yestoday);
		$this->data['androidList'] = $query;
		//$queryIphone = $this->product->getProductListByPlatform(2,$this->data['user_id']);
		//$this->data['iphoneList'] = $queryIphone;
		
		
		//活跃用户数
		$this->data['today_startuser'] = 0;
		$this->data['yestoday_startuser'] = 0;
		
		//获取今日昨日所有项目的新用户数
		$this->data['today_newuser'] = 0;
		$this->data['yestoday_newuser'] = 0;
		
		//启动次数
		$this->data['today_startcount'] = 0;
		$this->data['yestoday_startcount'] = 0;
		
		$this->data['today_totaluser'] = 0;
		
		for($i=0;$i<count($this->data['androidList']);$i++)
		{
			$row = $this->data['androidList'][$i];
			$this->data['today_startuser'] += $row['startUserToday'];
			$this->data['yestoday_startuser'] += $row['startUserYestoday'];
			
			$this->data['today_newuser'] += $row['newUserToday'];
			$this->data['yestoday_newuser'] += $row['newUserYestoday'];
			
			$this->data['today_startcount'] += $row['startCountToday'];
			$this->data['yestoday_startcount'] += $row['startCountYestoday'];
			
			$this->data['today_totaluser'] += $row['totaluser'];
			
		}
		
// 		for($i=0;$i<count($this->data['iphoneList']);$i++)
// 		{
// 			$row = $this->data['iphoneList'][$i];
// 			$this->data['today_startuser'] += $row['startUserToday'];
// 			$this->data['yestoday_startuser'] += $row['startUserYestoday'];
		
// 			$this->data['today_newuser'] += $row['newUserToday'];
// 			$this->data['yestoday_newuser'] += $row['newUserYestoday'];
		
// 			$this->data['today_startcount'] += $row['startCountToday'];
// 			$this->data['yestoday_startcount'] += $row['startCountYestoday'];
		
// 			$this->data['today_totaluser'] += $row['totaluser'];
		
// 		}
// 		//获取今日昨日启动用户数
// 		$this->data['today_startuser'] = $this->productanalyzemodel->getStartUserCountByUserId($this->data['user_id'],$today);
// 		$this->data['yestoday_startuser'] = $this->productanalyzemodel->getStartUserCountByUserId($this->data['user_id'],$yestoday);
		
// 		//获取今日昨日所有项目的新用户数
// 		$this->data['today_newuser'] = $this->productanalyzemodel->getTotalNewUsersCountByUserId($this->data['user_id'],$today);
// 		$this->data['yestoday_newuser'] = $this->productanalyzemodel->getTotalNewUsersCountByUserId($this->data['user_id'],$yestoday);
		
		$this->common->loadHeader();
		$this->load->view('main_form', $this->data);
	}
	
	
	function changeProduct($productId)
	{
		$this->common->cleanCurrentProduct();
		$this->common->setCurrentProduct ( $productId );
		$ret = array();
		$ret["msg"] = "ok";
		echo json_encode($ret);
	}
	
//创建产品
	function create()
	{
		$this->common->loadHeader();				
		$this->data['platform']=$this->channel->getplatform();		
		$this->data['category'] = $this->product->getProductCategory();
		$this->load->view('product/createproduct',$this->data);			
	}	
	function uploadchannel()
	{
		$platform=$_POST['platform'];
		$channel=$this->channel->getchanbyplat($platform);
		echo json_encode($channel) ;
		
	}
//保存产品
	function saveApp()
	{					
		$this->common->loadHeader();
		$tablename=	$this->common->getdbprefixtable('product');		
		$this->form_validation->set_rules('appname', lang('createproduct_formvappname'), 'trim|required|xss_clean|is_unique['.$tablename.'.name'.']');
		$this->form_validation->set_rules('description', lang('createproduct_descriptionlbl'), 'trim|required|xss_clean|min_length[10]');
		$this->form_validation->set_rules('platform', lang('createproduct_formvplatform'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('channel', lang('createproduct_formvchannel'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('category', lang('createproduct_formvcategory'), 'trim|required|xss_clean');
		if ($this->form_validation->run()) 
		{
			$userId = $this->common->getUserId();
			$appname = $this->input->post('appname');			
			$channel=$this->input->post('channel');			
			$platform = $this->input->post('platform');					
			$category = $this->input->post('category');			
			$description = $this->input->post('description');					
			$key = $this->product->addProduct($userId,$appname,$channel,$platform,$category,$description);
			$this->common->show_message(lang('allview_createappsuccess').",AppKey:$key,".anchor('/', lang('allview_successbackinfo')));
		}
		else 
		{
			$this->data['platform']=$this->channel->getplatform();	
			$this->data['category'] = $this->product->getProductCategory();
			$this->load->view('product/createproduct',$this->data);
		}
	}
	
	//编辑产品
   function editproduct()
	{
		$this->common->loadHeader();		
		$product = $this->common->getCurrentProduct();				
		$this->data['product']=	 $this->product->getproductinfo($product->id);
		$this->data['category'] = $this->product->getProductCategory();
		
		$this->load->view('product/editproduct',$this->data);
	}
	//保存编辑
	function saveedit($product_id)
	{		
	    $this->common->loadHeader();
	    
	    $product = $this->common->getCurrentProduct();
	    $productkey= $product->product_key;
		$tablename=	$this->common->getdbprefixtable('product');		
		$this->form_validation->set_rules('appname', lang('createproduct_formvappname'), 'trim|required|xss_clean|is_unique['.$tablename.'.name'.']');
		$this->form_validation->set_rules('description', lang('createproduct_descriptionlbl'), 'trim|required|xss_clean|min_length[10]');		
		if ($this->form_validation->run()) 
		{						
			$appname = $this->input->post('appname');								
			$category = $this->input->post('category');			
			$description = $this->input->post('description');					
			$this->product->updateproduct($appname,$category,$description,$product_id,$productkey);
			$this->common->show_message(lang('allview_editappsuccess').anchor('/', lang('allview_successbackinfo')));
		}
		else 
		{
			$this->data['product']=	 $this->product->getproductinfo($product_id);
		    $this->data['category'] = $this->product->getProductCategory();
		     $this->load->view('product/editproduct',$this->data);
		}
	}
	
	
	function refreshProduct()
	{
		$this->load->helper('open-flash-chart');
		open_flash_chart_object(100,100,site_url().'product/flashData');
		$this->load->view('product/productview',$this->data);
	}
	
	function refreshHome()
	{
		$this->common->loadHeader();
		$this->common->cleanCurrentProduct();
		$this->data['category'] = $this->product->getProductCategory();
		
		$data['user_id'] = $this->common->getUserId();
		$query = $this->product->getProductListByPlatform(1,$data['user_id']);
		$this->data['androidList'] = $query;
		$queryIphone = $this->product->getProductListByPlatform(2,$data['user_id']);
		$this->data['iphoneList'] = $queryIphone;
		$this->load->view('main_form', $this->data);
	}
	
	function delete($productId)
	{
		$userId = $this->common->getUserId();
		$data['productId'] = $productId;
		$flag = $this->product->deleteProduct($productId,$userId);
		if($flag>0)
		{
			$this->data["message"] = lang('createproduct_delmessage');
		}
		else
		{
			$this->data["message"] = lang('createproduct_delmessageinfo');
		}
		$this->index();
	}
	
	
	function getNewUsersByTime($timePhase,$fromDate='',$toDate='')
	{
		$this->load->library('ofc');
		$userId = $this->common->getUserId();
		$this->ofc->open_flash_chart();
		$this->ofc->set_bg_colour(CHART_BG_COLOR);	
		$toTime = date("Y-m-d",strtotime("-1 day"));
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-8 day"));
			$color=CHART_LINE_1;
			$key = "近7日新增用户";
			$title = new title("近7日新增用户统计");
		}
		if($timePhase == "1month")
		{
			$title = new title("近30天新增用户统计");
			$fromTime = date("Y-m-d",strtotime("-31 day"));
			$color=CHART_LINE_2;
			$key = "近30天新增用户统计";
		}
	
		if($timePhase == "3month")
		{
			$title = new title("近三个月新增用户统计");
			$fromTime = date("Y-m-d",strtotime("-92 day"));
			$color=CHART_LINE_3;
			$key = "近三个月新增用户统计";
		}
	
		if($timePhase == "all")
		{
			$title = new title("所有新增用户统计");
			$fromTime = '1970-01-01';
			$color=CHART_LINE_4;
			$key = "所有新增用户统计";
		}
	
		if($timePhase == "any")
		{
			$title = new title("所有新增用户统计");
			$fromTime = $fromDate;
			$toTime = $toDate;
			$color=CHART_LINE_4;
			$key = "所有新增用户统计";
		}
	
		$fromTime = $this->product->getUserStartDate($userId,$fromTime);
		$query = $this->newusermodel->getNewUsersByUserId($fromTime,$toTime,$userId);
		$data = array();
		$maxY = 0;
		$recordCount = $query->num_rows();
		$steps =($recordCount -1 <= 10)?2:(int)(((int)$recordCount-1)/10);
		$xlabelArray = array();
		if($query!=null && $query->num_rows()>0)
		{
			foreach($query->result() as $row)
			{
				$dot = new dot();
				$dot->size(3)->halo_size(1)->colour($color);
				$dot->tooltip($row->startdate." 新增".$row->totalusers."用户");
				$dot->value((int)$row->totalusers);
				if((int)$row->totalusers>$maxY)
				{
					$maxY = (int)$row->totalusers;
				}
				array_push($xlabelArray, date('y-m-d',strtotime($row->startdate)));
				array_push($data, $dot);
			}
		}
	
		$y = new y_axis();
		$y->set_range( 0, $this->common->getMaxY($maxY), $this->common->getStepY($maxY));
	
		$x = new x_axis();
		$x->set_range(0, $recordCount>1?$recordCount-1:1);
		$x_labels = new x_axis_labels();
		$x_labels->set_steps( $steps );
		$x_labels->set_vertical();
		$x_labels->set_colour(CHART_LABEL_COLOR);
		$x_labels->set_size( 13 );
		$x_labels->set_labels($xlabelArray);
		$x_labels->rotate(-25);
		$x->set_labels($x_labels);
	
		$x->set_steps(1);
		$this->ofc->set_y_axis($y);
		$this->ofc->set_x_axis($x);
		$dot = new dot();
		$dot->size(3)->halo_size(1)->colour($color);
		$line  = new line();
		$line->set_default_dot_style($dot);
		$line->set_values( $data );
		$line->set_width( 2 );
		$line->set_colour( $color);
		$line->colour( $color);
		$line->set_key($key, 12);
		$this->ofc->add_element($line);
		$title->set_style("{font-size: 14px; color:#000000; font-family: Verdana; text-align: center;}");
		
// 		$x_legend = new x_legend("<a href=\"javascript:changeChartName('chartNewUser')\">新增用户</a> <a href=\"javascript:changeChartName('chartActiveUser')\">活跃用户</a> <a href=\"javascript:changeChartName('chartStartUser')\">启动用户</a>");
// 		$this->ofc->set_x_legend( $x_legend );
// 		$x_legend->set_style( '{font-size: 14px; color: #778877}' );
		$this->ofc->set_title($title);
		echo $this->ofc->toPrettyString();
	}
	
	
	function getNewUsersByTimeJSON($timePhase,$fromDate='',$toDate='')
	{
		$userId = $this->common->getUserId();
		$toTime = date("Y-m-d",strtotime("-1 day"));
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title =  lang('producttitle_newweek');
		}
		if($timePhase == "1month")
		{
			$fromTime = date("Y-m-d",strtotime("-31 day"));
			$title = lang('producttitle_newmonth');
		}
	
		if($timePhase == "3month")
		{
			$title = lang('producttitle_new3month');
			$fromTime = date("Y-m-d",strtotime("-92 day"));
		}
	
		if($timePhase == "all")
		{
			$title = lang('producttitle_newa11');
			$fromTime = '1970-01-01';
		}
	
		if($timePhase == "any")
		{
			$fromTime = $fromDate;
			$toTime = $toDate;
			$title = $fromDate." ".lang('producttitle_newto')." ".$toDate." ".lang('producttitle_newtrend');
		}
	
		$fromTime = $this->product->getUserStartDate($userId,$fromTime);
		//$query = $this->newusermodel->getNewUsersByUserId($fromTime,$toTime,$userId);
		$query = $this->newusermodel->getAlldataofVisittrends($fromTime,$toTime,$userId);
		$ret["title"] = $title;
		$ret["content"] = $query;
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	
	
	function getActiveUserByTimePhase($timePhase,$fromDate='',$toDate='')
	{
		$userId = $this->common->getUserId();
		$toTime = date("Y-m-d",strtotime("-1 day"));
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('producttitle_actweek');
		}
		if($timePhase == "1month")
		{
			$title = lang('producttitle_actmonth');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
	
		if($timePhase == "3month")
		{
			$title = lang('producttitle_act3month');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
	
		if($timePhase == "all")
		{
			$title =lang('producttitle_actall');
			$fromTime = '1970-01-01';
		}
	
		if($timePhase == "any")
		{
			$title = $fromDate." ".lang('producttitle_actto')." ".$toDate." ".lang('producttitle_acttrend');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
	
		$fromTime = $this->product->getUserStartDate($userId,$fromTime);
		$query = $this->newusermodel->getActiveUsersByUserID($fromTime,$toTime,$userId);
		$ret = array();
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toDate - $fromDate);
		echo json_encode($ret);
	}
	
	
	
	function getStartUserByTimePhase($timePhase,$fromDate='',$toDate='')
	{
		$userId = $this->common->getUserId();
		$toTime = date("Y-m-d",strtotime("-1 day"));
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('producttitle_startweek');
		}
		if($timePhase == "1month")
		{
			$title = lang('producttitle_startmonth');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
	
		if($timePhase == "3month")
		{
			$title = lang('producttitle_start3month');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
	
		if($timePhase == "all")
		{
			$title = lang('producttitle_startall');
			$fromTime = '1970-01-01';
		}
	
		if($timePhase == "any")
		{
			$title = $fromDate." ".lang('producttitle_startto')." ".$toDate." ".lang('producttitle_starttrend');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
	
		$fromTime = $this->product->getUserStartDate($userId,$fromTime);
		$query = $this->newusermodel->getTotalStartUserByUserId($fromTime,$toTime,$userId);
		$ret = array();
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toDate - $fromDate);
		echo json_encode($ret);
	}
	
	
	
}
