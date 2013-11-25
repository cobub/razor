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
class TagModel extends CI_Model
{
		function __construct()
		{
			$this->load->database();
			$this->load->model('common');
		}
		
		
		
		function getDeviceidList($productId,$tags,$pagenum,$size)
		{
			$dwdb = $this->load->database ( 'dw', TRUE );
			$sql = "SELECT distinct deviceidentifier FROM ".$dwdb->dbprefix('fact_clientdata')."  f, 
			".$dwdb->dbprefix('dim_product')." p , ".$dwdb->dbprefix('dim_location')." l 
					where p.product_sk = f.product_sk and l.location_sk = f.location_sk and 
					p.product_id = ".$productId;
			
			$arr = json_decode($tags);
			foreach ($arr as $row)
			{
				if ($row->type == "version")
				{
					$version = $row->value;
					if($version != null && count($version)>0)
					{
						$sql = $sql." and p.version_name in (";
						for($i=0;$i<count($version);$i++)
						{
							$sql = $sql."'".$version[$i]."'";
							if($i<count($version)-1)
								$sql=$sql.",";
						}
						$sql = $sql.")";
						
					}
				}
				if ($row->type == "channel")
				{
				    $channel = $row->value;
					if($channel != null && count($channel)>0)
					{
						$sql = $sql." and p.channel_name in (";
						for($i=0;$i<count($channel);$i++)
						{
							$sql = $sql."'".$channel[$i]."'";
							if($i<count($channel)-1)
								$sql=$sql.",";
						}
						$sql = $sql.")";
						
					}
				}
				if ($row->type == "region")
				{
				    $region = $row->value;
					if($region != null && count($region)>0)
					{
						$sql = $sql." and l.region in (";
						for($i=0;$i<count($region);$i++)
						{
							$sql = $sql."'".$region[$i]."'";
							if($i<count($region)-1)
								$sql=$sql.",";
						}
						$sql = $sql.")";
						
					}
				}
				
				
			}
			
			$sql= $sql." limit ".$pagenum*$size.",".$size;
			$res = $dwdb->query ( $sql );
			return $res;
			
		}
		
		function getRegion($id)
		{
			$dwdb = $this->load->database ( 'dw', TRUE );

			$sql = "SELECT product_sk
					FROM ".$dwdb->dbprefix('dim_product')."
					WHERE product_id =$id";

			$res = $dwdb->query($sql);
			$ret = array();
			if($res!=null){
				foreach ($res->result() as $row)
				{
				   $product_sk=	$row->product_sk;
				   $sql = "SELECT DISTINCT location_sk
							FROM  ".$dwdb->dbprefix('fact_clientdata')."
							WHERE product_sk =$product_sk";
					$res1 = $dwdb->query($sql);
					if($res1!=null){
						foreach ($res1->result() as $row)
						{
							$location_sk = $row->location_sk;

							$sql = "select DISTINCT region from  ".$dwdb->dbprefix('dim_location')." where region!='' and location_sk=".$location_sk;
			
							$res2 = $dwdb->query ( $sql );
			

							if ($res2!=null  )
							{
								
								
								
								foreach ($res2->result() as $row)
								{
									array_push($ret,$row->region);
								}
								
							}

						}
					}
				}
			}

			return json_encode($ret);


			
		}
		
		function getVersionById($id)
		{
			$dwdb = $this->load->database ( 'dw', TRUE );
			$sql = "select distinct version_name  from  ".$dwdb->dbprefix('dim_product')." where product_id=".$id." and version_name!=''";
				
			$res = $dwdb->query ( $sql );
				
			if ($res!=null  )
			{
		
				$ret = array();
		
				foreach ($res->result() as $row)
				{
					array_push($ret,$row->version_name);
				}
				return json_encode($ret);
			}
			else
				return "[]";
				
		}
		
		function getChannelById($id)
		{
			$dwdb = $this->load->database ( 'dw', TRUE );
			$sql = "select distinct channel_name  from  ".$dwdb->dbprefix('dim_product')." where product_id=".$id." and channel_name!=''";
		
			$res = $dwdb->query ( $sql );
		
			if ($res!=null  && $res->num_rows()>0)
			{
				
				
				$ret = array();
		
				foreach ($res->result() as $row)
				{
					array_push($ret,$row->channel_name);
				}
				return json_encode($ret);
			}
			else
				return "[]";
		
		}
		
		function addTagsGroup($id,$name,$tags)
		{
			$data = array(
					'product_id' => $id ,
					'tags' => $tags,
					'name' => $name
			);
			$this->db->insert($this->db->dbprefix('tag_group'), $data);
			
			//$res = $this->db->query ( $sql );
		}
		
		function getTagsGroup($productId)
		{
			$this->load->database ();
			$this->db->select('name');
			$this->db->where('product_id', $productId);
			$this->db->from($this->db->dbprefix('tag_group'));
			$ret = $this->db->get();
			return $ret;
		}
		
		
		
		function getTagsGroupJson($productId)
		{
			$this->db->where('product_id', $productId);
			$res = $this->db->get($this->db->dbprefix('tag_group'));
			if ($res!=null  && $res->num_rows()>0)
			{
				
				$ret = array();
				foreach ($res->result() as $row)
				{
					$ret[$row->name] = $row->tags;
				}
				return json_encode($ret);
			}
			else
				return "[]";
		}
		function getUserNumAndPercent($product_id)
		{
			
			
		}
}