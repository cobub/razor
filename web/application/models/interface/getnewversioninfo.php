<?php
class getnewversioninfo extends CI_Model 
{
	
	function __construct() 
	{
		parent::__construct ();
		$this->load->model("interface/postdatautility","datautility");
	}

	
	function newversioninfo($version)
	{	
			
		$postdata=array(				
				         'version'=>"$version"
		               );
		$serverURL = "http://news.cobub.com/index.php?/news/getUpdateUrl";		
		$responseData = $this->datautility->post($serverURL, $postdata);			
		$retObject = json_decode($responseData,true);		
		if($retObject['flag'] > 0)
		{			
			return $retObject['msg'];
		}
		else
		{
			return FALSE;
		}
	}
	
}