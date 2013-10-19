<?php
class posttagpublic extends CI_Model
{
	var $deviceid;
	var $tags;
	var $productkey;
		
	function loadtag($content)
	{
		$this->deviceid=$content->deviceid;
		$this->tags=$content->tags;
		$this->productkey=$content->productkey;		
	}
}


