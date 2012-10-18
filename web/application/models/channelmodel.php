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
		
		//through username get self-built channels 
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
		//get System channels
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
		//get all System channels
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
		function getChannelType($channel_id){
			$sql = 'select type from '.$this->db->dbprefix('channel').'
			where channel_id="'.$channel_id.'"' ;
			$query = $this->db->query($sql);
			if($query->num_rows() > 0){
				return $query->row()->type;
			}
			return null;
		}
		function isUniqueChannel($userid,$channelname,$platform)
		{
			$sql = 'select * from '.$this->db->dbprefix('channel').' 
			where (user_id = "'.$userid.'" or type="system") and active=1 
			and channel_name="'.$channelname.'" and platform="'.$platform.'"' ;
			$query = $this->db->query($sql);
			return $query->result();
	
		}
		function isUniqueSystemchannel($channelname,$platform){
			$sql = 'select * from  '.$this->db->dbprefix('channel').' 
			where active=1 and channel_name="'.$channelname.'" and 
			platform="'.$platform.'"';
			$query = $this->db->query($sql);
			return $query->result();
		}
		//get the appkey of system channels
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
		
      //get self-built channels
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
		//get the appkey of self-built channels
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
		//get the num of self-built channels
		function getdechannelnum($userid)
		{
			$sql = "select * from  ".$this->db->dbprefix('channel')."  where user_id = $userid and type='user' and active=1 ";
			$query = $this->db->query($sql);
			return $query->num_rows(); 
		}
		//get platform
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
       //add self-built channel
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
		//add self-built system channel
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
		//get self-built channel information  
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
		//the information of updating channel  
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
	   //delete channel
		function deletechannel($channel_id)
		{
			$data=array(
			'active'=>0
			);
			$this->db->where('channel_id', $channel_id);
			$this->db->update('channel', $data);
		}
		
		//through platform get channel
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
		//insert android apk url
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
                
                $queryactive = $this->db->query("select active from  ".$this->db->dbprefix('product_version')." where id=$id");
                $acrel = $queryactive->row();
                $active= $acrel->active;
                if($active==1)
                {
                	$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date'
                	where id = $id ";
                }
                else
                {
                	$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date', active=1
                	where id = $id ";
                }
                
                           			
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
		////insert iphone apk url
		//$upinfo Tag is updated or upgrade(0:update,1:upgrade)
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
                $queryactive = $this->db->query("select active from  ".$this->db->dbprefix('product_version')." where id=$id");
                $acrel = $queryactive->row();
                $active= $acrel->active;
                
                if($active==1)
                {
                	$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date'
                	where id = $id ";
                }
                else
                {
                	$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date', active=1
                	where id = $id ";
                }                           					
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
		//To determine whether the automatic updates
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
		//Get automatically update history information
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
		
		//Get platform information
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
		
		//Get updated apk with the app information
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
		//Get automatically update the version number information
		function getversionid($cp_id,$versionid,$upinfo)
		{
			$query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
            $row = $query->row();
            $time= $row->date;
            $channelcount = $this->getchannelversioncount($cp_id);            
              if($upinfo==0)
            	{
            		if($channelcount==1)
            		{
            			$sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and active=1";
            		}
            		else
            		{
            			$sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <'$time'  and active=1";
            		}
            		
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
				 if( $channelcount==1 && $upinfo==0)
				 {
				 	$comversion=true;
				 }			 
				 else
				 {
				 	if ($result>=0)
				 	{
				 		$comversion=false;
				 		break;
				 	}
				 	else
				 	{
				 		$comversion=true;
				 	}
				 }							
			}
			return $comversion;		
		   }
		 else
		 {
		 	if($sql!=""&& $upinfo==1)
		 	{
		 	  return true;	
		 	}		 	
		 }
		 if($sql!="" && $upinfo==0 && $channelcount==1)
		 {
		 	return true;
		 } 
		  return false; 
		}
		//get channel version count from product_version
		function getchannelversioncount($cp_id)
		{
			$sql="select count(*) channelcount  from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and active=1";
			$query = $this->db->query($sql);
			if($query!=null&&$query->num_rows()>0)
			{				
				$row = $query->row();
				$count=  $row->channelcount;
				if($count==1||$count==0)
				{
					return 1;
				}
				else 
				{
					return $count;
				}
			}
			return 1;
			
		}
		//Get updated automatically update the list of history
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
		//Get automatic updates of the information in the new list
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
		//Delete automatically update in the history list
		function deleteupdate($vp_id)
		{
			$data=array(
			'active'=>0
			);
			$this->db->where('id', $vp_id);
			$this->db->update('product_version', $data);
		}
}