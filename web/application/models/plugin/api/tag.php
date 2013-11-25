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
 * @filesource
 */
class Tag extends CI_Model
{
		function __construct()
		{
			$this->load->database();
			$this->load->model('tag/tagmodel','tag');
		}
		
		
		//get deviceIdList  according to tags
		function getDeviceidList($productId,$tags,$pagenum,$size)
		{
			$this->tag->getDeviceidList($productId,$tags,$pagenum,$size);
			
		}

		//add Annotation interface
		function addTagsGroup($id,$name,$tags)
		{
			$this->tag->addTagsGroup($id,$name,$tags);
		}
		
		
		
		//get tags group 
		function getTagsGroupJson($productId)
		{
			$this->tag->getTagsGroupJson($productId);
		}


		//Tag page inferface

		function getTagPage($productId)
		{
			$data['usernum'] = $this->tag->getUserNumAndPercent($productId);
    		$data['tagsgroup'] = $this->tag->getTagsGroup($productId);
    		$data['tagsgroupjson'] = $this->tag->getTagsGroupJson($productId);
    		$data['version']=$this->tag->getVersionById($productId);
    		$data['channel']=$this->tag->getChannelById($productId);
    		$data['region'] = $this->tag->getRegion($productId);
    		$data['productId'] = $productId;
    		return $data;
		}
	
}
?>