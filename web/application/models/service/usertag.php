<?php
class usertag extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->database ();
	}
	
	function addusertag($content)
	{
		$this->load->model('servicepublicclass/posttagpublic','posttagpublic');
		$posttag= new posttagpublic();
		$posttag->loadtag($content);
		$data=array(
				   'deviceid'=>$posttag->deviceid,
				    'tags'=>$posttag->tags,
				    'productkey'=>$posttag->productkey
				    );
		$this->db->insert('device_tag',$data);
	}
}