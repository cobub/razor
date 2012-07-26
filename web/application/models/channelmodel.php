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
class ChannelModel extends CI_Model
{
		function __construct()
		{
			$this->load->database();
			$this->load->model('common');
			
		}
		
		//通过用户名获得自建渠道
		function getdechannel($userid)
		{
			$sql = "select c.*,p.name from ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."   p on c.platform = p.id where c.user_id = $userid and c.type='user' and c.active=1 ";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		//获得系统渠道
       function getsychannel($user_id,$product_id,$platform)
		{
			$sql = "select channel_name,channel_id from  ".$this->db->dbprefix('channel')."   where channel_id not in (select channel_id from  ".$this->db->dbprefix('channel_product')."  where product_id=$product_id and user_id=$user_id )and type='system' and platform=$platform and active=1 ";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		//获得系统渠道(全部)
		function getallsychannel()
		{
			$sql = "select c.*,p.name from  ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."  p on c.platform = p.id where c.type='system' and c.active=1 ";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			{
				return $query->result_array();
			}
			return null;
		}
		//获系统渠道的appkey
		function getproductkey($user_id,$product_id,$platform)
		{
			$sql="select cp.cp_id, c.channel_name ,cp.productkey,c.channel_id from  ".$this->db->dbprefix('channel')."   c  inner join  ".$this->db->dbprefix('channel_product')."  cp  on c.channel_id = cp.channel_id where c.type='system' and c.active=1 and cp.product_id=$product_id and cp.user_id=$user_id and c.platform=$platform";			
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		
      //获得自定义渠道
       function getdefinechannel($user_id,$product_id,$platform)
		{
			$sql = "select channel_name,channel_id from  ".$this->db->dbprefix('channel')."   where channel_id not in (select channel_id from  ".$this->db->dbprefix('channel_product')."  where product_id=$product_id and user_id=$user_id )and type='user' and active=1 and user_id=$user_id and platform=$platform";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		//获得自定义渠道appkey
		function getdefineproductkey($user_id,$product_id,$platform)
		{
			$sql="select cp.cp_id, c.channel_name ,cp.productkey,c.channel_id from   ".$this->db->dbprefix('channel')."  c  inner join ".$this->db->dbprefix('channel_product')."   cp  on c.channel_id = cp.channel_id and c.user_id=cp.user_id where c.type='user' and c.active=1  and cp.product_id=$product_id and cp.user_id=$user_id and c.platform=$platform";			
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		//获得自定义渠道的个数
		function getdechannelnum($userid)
		{
			$sql = "select * from  ".$this->db->dbprefix('channel')."  where user_id = $userid and type='user' and active=1 ";
			$query = $this->db->query($sql);
			return $query->num_rows(); 
		}
		//获取平台
		function getplatform()
		{
			$sql = "select * from  ".$this->db->dbprefix('platform')."  ";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}	
       //添加自定义渠道
		function addchannel($channel_name, $platform,$userid)
		{
			$create_date=date('Y-m-d H:i:s');			
			$data = array (
			'user_id'=>$userid,
			'channel_name' => $channel_name, 
			'platform' => $platform ,
			'create_date'=>$create_date
			);		
		     $this->db->insert ( 'channel', $data );
		}
		//添加自定义 系统渠道
		function addsychannel($channel_name, $platform,$userid)
		{
			$create_date=date('Y-m-d H:i:s');
			$data = array (
					'user_id'=>$userid,
					'channel_name' => $channel_name,
					'platform' => $platform ,
					'create_date'=>$create_date,
					'type'=>'system'    
			);
			$this->db->insert ( 'channel', $data );
		}
		//获得自定义渠道信息
		function getdechaninfo($userid,$channel_id)
		{
			$sql = "select c.*,p.name from  ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."  p on c.platform = p.id
			 where c.user_id =$userid and c.channel_id=$channel_id and c.active=1 ";			
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->row_array();
			      }
			   return null; 
		}
		//更新渠道信息
		function updatechannel($channel_name, $platform,$channel_id)
		{			
			$data = array(
               'channel_name' => $channel_name,
               'platform' => $platform               
            );

			$this->db->where('channel_id', $channel_id);
			$this->db->where('active', 1);
			$this->db->update('channel', $data); 
		}
	   //删除渠道
		function deletechannel($channel_id)
		{
			$data=array(
			'active'=>0
			);
			$this->db->where('channel_id', $channel_id);
			$this->db->update('channel', $data);
		}
		
		//获取渠道通过平台
		function getchanbyplat($platform)
		{
			$userid=$this->common->getUserId();	
		    $sql="select * from  ".$this->db->dbprefix('channel')."  where active=1 and platform='$platform' and type='system' union 
		    select * from  ".$this->db->dbprefix('channel')."  where active=1 and platform='$platform' and type='user'and user_id=$userid"; 
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		//插入 android apk url
		function updateapk($userid,$cp_id,$description,$updateurl,$versionid,$upinfo)
		{  $date=date('Y-m-d H:i:s');	 
			 if($upinfo==0)
		     {
		     	$query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."  where cp_id=$cp_id");
                $row = $query->row();
                $time= $row->date;
                $queryid = $this->db->query("select id from  ".$this->db->dbprefix('product_version')."  where product_channel_id=$cp_id and updatetime='$time'");
                $rel = $queryid->row();
                $id= $rel->id;
                $sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date' 
			   where id = $id ";              			
			   $this->db->query($sql);
		     }
			else 
			{			
					$data = array (
					
					'product_channel_id' => $cp_id, 
					'version' => $versionid ,
					'updateurl'=>$updateurl,
					'updatetime'=>$date	,
					'description'=>$description					
					);		
				     $this->db->insert ( 'product_version', $data );
			}
			$sql = "update ".$this->db->dbprefix('channel_product')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',date='$date' 
			where cp_id = $cp_id and user_id = $userid";					
			$this->db->query($sql);
			$affect = $this->db->affected_rows();
			if($affect>0)
			{
				return true;
			}
			return false;
			
		}
		////插入iphone apk url
		//$upinfo 标记是否为更新还是升级      0为更新 1为升级
		function updateapp($userid,$cp_id,$description,$updateurl,$versionid,$upinfo)
		{
			$date=date('Y-m-d H:i:s');	
			if($upinfo==0)
		     {
		     	$query = $this->db->query("select date from ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
                $row = $query->row();
                $time= $row->date;
                $queryid = $this->db->query("select id from  ".$this->db->dbprefix('product_version')."  where product_channel_id=$cp_id and updatetime='$time'");
                $rel = $queryid->row();
                $id= $rel->id;
                $sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date' 
			   where id = $id ";               					
			   $this->db->query($sql);
		     }
			else 
			{				
			$data = array (			
			'product_channel_id' => $cp_id, 
			'version' => $versionid ,
			'updateurl'=>$updateurl,
			'updatetime'=>$date	,
			'description'=>$description	
			);		
		     $this->db->insert ( 'product_version', $data );
		     
			}
			$sql = "update ".$this->db->dbprefix('channel_product')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',date='$date' 
			where cp_id = $cp_id and user_id = $userid";					
			$this->db->query($sql);
			$affect = $this->db->affected_rows();
			if($affect>0)
			{
				return true;
			}
			return false;
		}
		//判断是否已进行自动更新
		function judgeupdate($cp_id)
		{
			$sql="select updateurl from ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id";
			$query = $this->db->query($sql);
				if($query!=null&&$query->num_rows()>0)
				{    foreach ($query->result() as $row)
                    {
				      if($row->updateurl!="")
				      {
				      	 return true;
				      } 
				      
                    }
				}
				   return false; 			
		}
		//获取自动更新历史信息
		function getupdatehistory($cp_id)
		{
			$sql="select pv.id,pv.product_channel_id,cp.channel_id,pv.version,pv.updateurl,pv.updatetime,c.channel_name from
			 ".$this->db->dbprefix('product_version')."   pv, ".$this->db->dbprefix('channel_product')."  cp, ".$this->db->dbprefix('channel')."  c where pv.product_channel_id=cp.cp_id 
			 and c.channel_id=cp.channel_id and pv.product_channel_id=$cp_id and pv.active=1";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
			
		}	
		
		//获取平台信息
		function getuapkplatform($channel_id)
		{
			$sql="select platform from   ".$this->db->dbprefix('channel')."  where channel_id =$channel_id";							
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->row_array();
			      }
			   return null; 
		}
		
		//获取更新的apk与app信息
		function getakpinfo($userid,$cp_id)
		{				
		    $sql="select cp.*,c.channel_name from  ".$this->db->dbprefix('channel_product')."  cp  inner join  ".$this->db->dbprefix('channel')."  c on 
		    c.channel_id=cp.channel_id where cp.cp_id=$cp_id and cp.user_id=$userid"; 			
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->result_array();
			      }
			   return null; 
		}
		//获取自动更新版本号信息
		function getversionid($cp_id,$versionid,$upinfo)
		{
			$query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
            $row = $query->row();
            $time= $row->date;
            if($upinfo==0)			
            {
            	$sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <'$time'  and active=1";            	
            }
            else 
            {
            	$sql="select  distinct version from  ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <='$time'  and active=1";
            }                      		
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			{
				  $dataversion= $query->result_array();
			      foreach($dataversion as $data)
			   { 				
				 $result=strcmp($data['version'],$versionid);				 
				 if($result>=0)
				{				
					$comversion=false;												
					break;
				}
				else
				{
					$comversion=true;	 								
				}
				
			}
			return $comversion;		
		   }
		 else
		 {
		 	if($sql!=""&&$upinfo==1)
		 	{
		 	  return true;	
		 	}
		 } 
		  return false; 
		}
		//获取自动更新历史列表中更新的信息
		function getupdatelistinfo($vp_id)
		{
		  $sql="select * from   ".$this->db->dbprefix('product_version')."   where id=$vp_id";
		  $query = $this->db->query($sql);
		  if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->row_array();
			      }
			   return null; 
		}
		//获取自动更新新列表中的信息
		function getnewlistinfo($cp_id)
		{
			$sql="select * from  ".$this->db->dbprefix('channel_product')."    where cp_id=$cp_id";
		    $query = $this->db->query($sql);
		    if($query!=null&&$query->num_rows()>0)
			      {
				      return $query->row_array();
			      }
			   return null; 
		}
		//删除历史列表中的自动更新
		function deleteupdate($vp_id)
		{
			$data=array(
			'active'=>0
			);
			$this->db->where('id', $vp_id);
			$this->db->update('product_version', $data);
		}
}