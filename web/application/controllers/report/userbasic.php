<?php

class userbasic extends CI_Controller{
	
	private $data = array();
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->Model('common');
		$this->load->model('channelmodel','channel');
		$this->load->model('product/productmodel','product');
		$this->load->model('product/newusermodel','newusermodel');
		$this->load->model('product/productanalyzemodel','productanalyzemodel');
		$this->load->model('product/usinganalyzemodel','usinganalyzemodel');
		$this->common->requireLogin();
		$this->load->library ('export');
		
		
	}
	
	function index()
	{
		$this->common->loadHeader();
		$this->load->view('report/userbasicanalyze',$this->data);
	}
	//活跃用户数据统计
	function getActiveUserByTimePhase($timePhase,$fromDate='',$toDate='')
	{
		$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('usertitle_actva7days');
		}
		if($timePhase == "1month")
		{
			$title =  lang('usertitle_actva30days');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
		
		if($timePhase == "3month")
		{
			$title =  lang('usertitle_actva3month');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
		
		if($timePhase == "all")
		{
			$title = lang('usertitle_actvaall');
			$fromTime = $currentProduct->date;
		}
		
		if($timePhase == "any")
		{
			$title = lang('usertitle_actvaanytime');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
		
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$query = $this->newusermodel->getActiveUsersByDay($fromTime,$toTime,$currentProduct->id);
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	
	
	//累计用户数据统计
	function getTotalUserByTimePhase($timePhase,$fromDate='',$toDate='')
	{
		$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('usertitlee_totalweek');
		}
		if($timePhase == "1month")
		{
			$title =lang('usertitlee_totalmonth');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
	
		if($timePhase == "3month")
		{
			$title = lang('usertitlee_total3month');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
	
		if($timePhase == "all")
		{
			$title = lang('usertitlee_totalall');
			$fromTime = $currentProduct->date;
		}
	
		if($timePhase == "any")
		{
			$title = lang('usertitlee_totalanytime');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
	
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$result = $this->newusermodel->getTotalUsersByDay($fromTime,$toTime,$currentProduct->id);
		$ret["title"] = $title;
		$ret["content"] = $result;
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	
//	//获启动趋势报告
//	function getTotalUsersByTimePhase($timePhase,$fromDate='',$toDate='')
//	{
//		$currentProduct = $this->common->getCurrentProduct();
//		$toTime = date('Y-m-d',time());
//		if($timePhase == "7day")
//		{
//			$fromTime = date("Y-m-d",strtotime("-7 day"));
//			$title = "近7日启动用户统计";
//		}
//		if($timePhase == "1month")
//		{
//			$title = "近30天启动用户统计";
//			$fromTime = date("Y-m-d",strtotime("-30 day"));
//		}
//		
//		if($timePhase == "3month")
//		{
//			$title = "近三个月启动用户统计";
//			$fromTime = date("Y-m-d",strtotime("-90 day"));
//		}
//		
//		if($timePhase == "all")
//		{
//			$title = "所有启动用户统计";
//			$fromTime = $currentProduct->date;
//		}
//		
//		if($timePhase == "any")
//		{
//			$title = "启动用户统计";
//			$fromTime = $fromDate;
//			$toTime = $toDate;
//		}
//		
//		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
//		$result = $this->newusermodel->getTotalUsersByDay($fromTime,$toTime,$currentProduct->id);
//		$ret["title"] = $title;
//		$ret["content"] = $result;
//		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
//		echo json_encode($ret);
//	}
	
	//启动用户数据统计
	function getStartUserByTimePhase($timePhase,$fromDate='',$toDate='')
	{
		$currentProduct = $this->common->getCurrentProduct();
		$toTime = date('Y-m-d',time());
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('usertitle_start7days');
		}
		if($timePhase == "1month")
		{
			$title = lang('usertitle_start30days');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
		
		if($timePhase == "3month")
		{
			$title =lang('usertitle_start3month');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
		
		if($timePhase == "all")
		{
			$title = lang('usertitle_startall');
			$fromTime = $currentProduct->date;
		}
		
		if($timePhase == "any")
		{
			$title = lang('usertitle_startanytime');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
		
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$query = $this->newusermodel->getTotalStartUserByDay($fromTime,$toTime,$currentProduct->id);
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	//新增用户数据统计
	function getNewUsersByTime($timePhase,$fromDate='',$toDate='')
	{
		$currentProduct = $this->common->getCurrentProduct();
	
		$toTime = date('Y-m-d',time());
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('usertitlee_lastweeknew');
		}
		if($timePhase == "1month")
		{
			$title = lang('usertitlee_lastmonthnenw');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
		
		if($timePhase == "3month")
		{
			$title = lang('usertitlee_last3monthnew');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
		
		if($timePhase == "all")
		{
			$title = lang('usertitlee_lastalltimenew');
			$fromTime = $currentProduct->date;
		}
		
		if($timePhase == "any")
		{
			$title =  lang('usertitlee_lastanytimenew');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
		
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$query = $this->newusermodel->getNewUserByDay($fromTime,$toTime,$currentProduct->id);
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	
	//获得平均使用时间数据统计
	function getAverageUsingTime($timePhase,$fromDate='',$toDate='')
	{
		$currentProduct = $this->common->getCurrentProduct();
	
		$toTime = date('Y-m-d',time());
	
		if($timePhase == "7day")
		{
			$fromTime = date("Y-m-d",strtotime("-7 day"));
			$title = lang('usertitle_average7days');
		}
		if($timePhase == "1month")
		{
			$title = lang('usertitle_averagemonth');
			$fromTime = date("Y-m-d",strtotime("-30 day"));
		}
	
		if($timePhase == "3month")
		{
			$title = lang('usertitle_average3month');
			$fromTime = date("Y-m-d",strtotime("-90 day"));
		}
	
		if($timePhase == "all")
		{
			$title = lang('usertitle_averageall');
			$fromTime = $currentProduct->date;
		}
	
		if($timePhase == "any")
		{
			$title = lang('usertitle_averageanytime');
			$fromTime = $fromDate;
			$toTime = $toDate;
		}
	
		$fromTime = $this->product->getReportStartDate($currentProduct,$fromTime);
		$query = $this->usinganalyzemodel->getUsingTimeByDay($fromTime,$toTime,$currentProduct->id);
		$ret["title"] = $title;
		$ret["content"] = $query->result_array();
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime );
		echo json_encode($ret);
	}
	
	//获得明细数据
	function getDetailData($pageIndex=0)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$rowArray = $this->newusermodel->getDetailUserDataByDay($currentProduct->id,$pageIndex);
		$htmlText = "";
		if($rowArray!=null && count($rowArray)>0)
		{
			for($i=0;$i<count($rowArray);$i++)
			{
				$row = $rowArray[$i];
				$htmlText = $htmlText."<tr>";
				$htmlText = $htmlText."<td>".$row['date']."</td>";
				$htmlText = $htmlText."<td>".$row['new']."</td>";
				$htmlText = $htmlText."<td>".$row['total']."</td>";
				$htmlText = $htmlText."<td>".$row['active']."</td>";
				$htmlText = $htmlText."<td>".$row['start']."</td>";
				$htmlText = $htmlText."<td>".$row['aver']."</td>";
				$htmlText = $htmlText."</tr>";
			}
		}
		echo $htmlText;
	}

   //导出明细数据
   function exportdetaildata()
   {
   	    $toTime = date('Y-m-d',time());
        $currentProduct = $this->common->getCurrentProduct();
		$detaildata = $this->newusermodel->getexportdetaildata($currentProduct->id);
		if ($detaildata != null && count($detaildata)>0) {
			$data = $detaildata;
			$this->export->setFileName ($toTime.'_userdetaildata.csv');		
           //设置列标题		
			$excel_title = array (iconv("UTF-8", "GBK", lang('allview_exportdate')),iconv("UTF-8", "GBK", lang('allview_exportnewuser')),iconv("UTF-8", "GBK", lang('allview_exportaccumulative')),iconv("UTF-8", "GBK", lang('allview_exportactive')),iconv("UTF-8", "GBK", lang('allview_exportsession')),iconv("UTF-8", "GBK",lang('allview_exportavgtime')));
			$this->export->setTitle ($excel_title );
		
			//输出内容
		    for($i=0;$i<count($data);$i++)
			{
				$row = $data[$i];				
				$this->export->addRow ( $row );
			}			
			$this->export->export ();
			die ();
		
		}
		else 
		{
			$this->load->view("region/nodataview");
		}
   }
}

?>