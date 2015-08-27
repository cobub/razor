<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Channel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class ChannelModel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        $this->load->database();
        $this->load->model('common');
    }

    /**
     * getdechannel function
     * through username get self-built channels
     *
     * @param int $userid user id
     *
     * @return query result
     */
    function getdechannel($userid)
    {
        $sql = "select c.*,p.name from ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."   p on c.platform = p.id where c.user_id = $userid and c.type='user' and c.active=1 ";
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                 return $query->result_array();
        }
               return null; 
    }
    /**
     * getsychannel function
     * get System channels
     *
     * @param int    $user_id    user id
     * @param int    $product_id product id
     * @param string $platform   platform
     *
     * @return query result
     */
    function getsychannel($user_id, $product_id, $platform)
    {
        $sql = "select channel_name,channel_id from  ".$this->db->dbprefix('channel')."   where channel_id not in (select channel_id from  ".$this->db->dbprefix('channel_product')."  where product_id=$product_id and user_id=$user_id )and type='system' and platform=$platform and active=1 ";
          $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                      return $query->result_array();
        }
               return null; 
    }
    /**
     * getallsychannel function
     * get System channels
     *
     * @return query result
     */
    function getallsychannel()
    {
            $sql = "select c.*,p.name from  ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."  p on c.platform = p.id where c.type='system' and c.active=1 ";
            $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                return $query->result_array();
        }
            return null;
    }
    
    /**
     * getChannelType function
     * get channel type
     *
     *@param int $channel_id channel id
     *
     * @return query type
     */
    function getChannelType($channel_id)
    {
        $sql = 'select type from '.$this->db->dbprefix('channel').'
            where channel_id="'.$channel_id.'"' ;
            $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
                return $query->row()->type;
        }
        return null;
    }

    /**
     * getChannelName function 
     * Get channel name by id
     * 
     * @param int $channel_id channel id
     * 
     * @return query channel_name
     */
    function getChannelName($channel_id)
    {
        $this->db->from($this->db->dbprefix('channel'));
        $this->db->where('channel_id', $channel_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
                return $query->row()->channel_name;
        } else {
                return '';
        }
    }

    /**
     * getChannelList function 
     * Get channel list by id
     * 
     * @param int $product_id product id
     * 
     * @return query result
     */
    function getChannelList($product_id)
    {
            $dwdb = $this->load->database('dw', true);
            $sql = 'select distinct channel_name from '.$dwdb->dbprefix('dim_product')
                .' where product_active=1 and channel_active=1 and version_active=1'
                .' and product_id='.$product_id.' order by channel_name asc';
            $query = $dwdb->query($sql);
            return $query->result();
    }

    /**
     * isUniqueChannel function
     * is unique chanel
     *
     * @param int    $userid      user id
     * @param string $channelname channel name
     * @param string $platform    platform
     *
     * @return query result
     */
    function isUniqueChannel($userid, $channelname, $platform)
    {
            $sql = 'select * from '.$this->db->dbprefix('channel').' 
            where (user_id = "'.$userid.'" or type="system") and active=1 
            and channel_name="'.$channelname.'" and platform="'.$platform.'"' ;
            $query = $this->db->query($sql);
            return $query->result();
    }
    
    /**
     * isUniqueSystemchannel function
     * is unique system chanel
     *
     * @param string $channelname channel name
     * @param string $platform    platform
     *
     * @return query result
     */
    function isUniqueSystemchannel($channelname,$platform)
    {
            $sql = 'select * from  '.$this->db->dbprefix('channel').' 
            where active=1 and channel_name="'.$channelname.'" and 
            platform="'.$platform.'"';
            $query = $this->db->query($sql);
            return $query->result();
    }

    /**
     * getproductkey function
     * get the appkey of system channels
     *
     * @param int    $user_id    user id
     * @param int    $product_id product id
     * @param string $platform   platform
     *
     * @return query result
     */
    function getproductkey($user_id,$product_id,$platform)
    {
        $sql="select cp.cp_id, c.channel_name ,cp.productkey,c.channel_id from  ".$this->db->dbprefix('channel')."   c  inner join  ".$this->db->dbprefix('channel_product')."  cp  on c.channel_id = cp.channel_id where c.type='system' and c.active=1 and cp.product_id=$product_id and cp.user_id=$user_id and c.platform=$platform";            
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
            return $query->result_array();
        }
           return null; 
    }

    /**
     * getdefinechannel function
     * get self-built channels
     *
     * @param int    $user_id    user id
     * @param int    $product_id product id
     * @param string $platform   platform
     *
     * @return query result
     */
    function getdefinechannel($user_id,$product_id,$platform)
    {
            $sql = "select channel_name,channel_id from  ".$this->db->dbprefix('channel')."   where channel_id not in (select channel_id from  ".$this->db->dbprefix('channel_product')."  where product_id=$product_id and user_id=$user_id )and type='user' and active=1 and user_id=$user_id and platform=$platform";
            $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
            return $query->result_array();
        }
               return null; 
    }
    
    /**
     * getdefineproductkey function
     * get the appkey of self-built channels
     *
     * @param int    $user_id    user id
     * @param int    $product_id product id
     * @param string $platform   platform
     *
     * @return query result
     */
    function getdefineproductkey($user_id,$product_id,$platform)
    {
        $sql="select cp.cp_id, c.channel_name ,cp.productkey,c.channel_id from   ".$this->db->dbprefix('channel')."  c  inner join ".$this->db->dbprefix('channel_product')."   cp  on c.channel_id = cp.channel_id and c.user_id=cp.user_id where c.type='user' and c.active=1  and cp.product_id=$product_id and cp.user_id=$user_id and c.platform=$platform";            
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
              return $query->result_array();
        }
               return null; 
    }

     /**
      * getdechannelnum function
      * get the num of self-built channels
      *
      * @param int $userid user id
      *
      * @return query num_rows
      */
    function getdechannelnum($userid)
    {
        $sql = "select * from  ".$this->db->dbprefix('channel')."  where user_id = $userid and type='user' and active=1 ";
        $query = $this->db->query($sql);
        return $query->num_rows(); 
    }

    /**
     * getplatform function
     * get platform
     *
     * @return query result_array
     */
    function getplatform()
    {
        $sql = "select * from  ".$this->db->dbprefix('platform')."  ";
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
            return $query->result_array();
        }
            return null; 
    }

    /**
     * addchannel function
     * add self-built channel
     *
     * @param string $channel_name channel name
     * @param string $platform     platform
     * @param int    $userid       user id
     *
     * @return void
     */
    function addchannel($channel_name, $platform,$userid)
    {
            $create_date=date('Y-m-d H:i:s');            
            $data = array (
            'user_id'=>$userid,
            'channel_name' => $channel_name, 
            'platform' => $platform ,
            'create_date'=>$create_date
            );        
             $this->db->insert('channel', $data);
    }
    
    /**
     * addsychannel function
     * add self-built system channel
     *
     * @param string $channel_name channel name
     * @param string $platform     platform
     * @param int    $userid       user id
     *
     * @return void
     */
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
            $this->db->insert('channel', $data);
    }
    
    /**
     * getdechaninfo function
     * get self-built channel information
     *
     * @param int $userid     user id
     * @param int $channel_id channel id
     *
     * @return query row_array
     */
    function getdechaninfo($userid,$channel_id)
    {
        $sql = "select c.*,p.name from  ".$this->db->dbprefix('channel')."  c inner join  ".$this->db->dbprefix('platform')."  p on c.platform = p.id
        where c.user_id =$userid and c.channel_id=$channel_id and c.active=1 ";            
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
               return $query->row_array();
        }
               return null; 
    }
    
    /**
     * updatechannel function
     * the information of updating channel
     *
     * @param string $channel_name channel name
     * @param string $platform     platform
     * @param int    $channel_id   channel id
     *
     * @return void
     */
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
    
    /**
     * deletechannel function
     * detele channel
     *
     * @param int $channel_id channel id
     *
     * @return void
     */
    function deletechannel($channel_id)
    {
            $data=array(
            'active'=>0
            );
            $this->db->where('channel_id', $channel_id);
            $this->db->update('channel', $data);
    }

    /**
     * getchanbyplat function
     * through platform get channel
     *
     * @param string $platform platform
     *
     * @return query result_array
     */
    function getchanbyplat($platform)
    {
        $userid=$this->common->getUserId();    
        $sql="select * from  ".$this->db->dbprefix('channel')."  where active=1 and platform='$platform' and type='system' union 
        select * from  ".$this->db->dbprefix('channel')."  where active=1 and platform='$platform' and type='user'and user_id=$userid"; 
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                return $query->result_array();
        }
          return null; 
    }

    /**
     * updateapk function
     * insert android apk url
     *
     * @param int    $userid      user id
     * @param int    $cp_id       cp id
     * @param string $description description
     * @param string $updateurl   update url
     * @param int    $versionid   version id
     * @param string $upinfo      upinfo
     *
     * @return boolean
     */
    function updateapk($userid,$cp_id,$description,$updateurl,$versionid,$upinfo)
    {
        $date=date('Y-m-d H:i:s');     
        if ($upinfo==0) {
                 $query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."  where cp_id=$cp_id");
                $row = $query->row();
                $time= $row->date;
               
                $queryid = $this->db->query("select id from  ".$this->db->dbprefix('product_version')."  where product_channel_id=$cp_id and updatetime='$time'");
                $rel = $queryid->row();
                $id= $rel->id;
                
                $queryactive = $this->db->query("select active from  ".$this->db->dbprefix('product_version')." where id=$id");
                $acrel = $queryactive->row();
                $active= $acrel->active;
            if ($active==1) {
                    $sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date'
                    where id = $id ";
            } else {
                    $sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date', active=1
                    where id = $id ";
            }
                
                                       
               $this->db->query($sql);
        } else {            
                    $data = array (
                    
                    'product_channel_id' => $cp_id, 
                    'version' => $versionid ,
                    'updateurl'=>$updateurl,
                    'updatetime'=>$date    ,
                    'description'=>$description                    
                    );        
                     $this->db->insert('product_version', $data);
        }
            $sql = "update ".$this->db->dbprefix('channel_product')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',date='$date' 
            where cp_id = $cp_id and user_id = $userid";                    
            $this->db->query($sql);
            $affect = $this->db->affected_rows();
        if ($affect>0) {
                return true;
        }
            return false;
            
    }
    
    /**
     * updateapp function
     * insert iphone apk url
     *
     * @param int    $userid      user id
     * @param int    $cp_id       cp id
     * @param string $description description
     * @param string $updateurl   update url
     * @param int    $versionid   version id
     * @param string $upinfo      upinfo
     *
     * @return boolean
     */
    function updateapp($userid,$cp_id,$description,$updateurl,$versionid,$upinfo)
    {
            $date=date('Y-m-d H:i:s');    
        if ($upinfo==0) {
                 $query = $this->db->query("select date from ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
                $row = $query->row();
                $time= $row->date;
                $queryid = $this->db->query("select id from  ".$this->db->dbprefix('product_version')."  where product_channel_id=$cp_id and updatetime='$time'");
                $rel = $queryid->row();
                $id= $rel->id;
                $queryactive = $this->db->query("select active from  ".$this->db->dbprefix('product_version')." where id=$id");
                $acrel = $queryactive->row();
                $active= $acrel->active;
                
            if ($active==1) {
                    $sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date'
                    where id = $id ";
            } else {
                    $sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date', active=1
                    where id = $id ";
            }
               $this->db->query($sql);
        } else {                
            $data = array (
            'product_channel_id' => $cp_id, 
            'version' => $versionid ,
            'updateurl'=>$updateurl,
            'updatetime'=>$date    ,
            'description'=>$description    
            );        
             $this->db->insert('product_version', $data);
             
        }
            $sql = "update ".$this->db->dbprefix('channel_product')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',date='$date' 
            where cp_id = $cp_id and user_id = $userid";                    
            $this->db->query($sql);
            $affect = $this->db->affected_rows();
        if ($affect>0) {
                return true;
        }
            return false;
    }
    
    /**
     * judgeupdate function
     * To determine whether the automatic updates
     *
     * @param int $cp_id cp id
     *
     * @return boolean
     */
    function judgeupdate($cp_id)
    {
        $sql="select updateurl from ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id";
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
            foreach ($query->result() as $row) {
                if ($row->updateurl!="") {
                           return true;
                }
            }
        }
                   return false;
    }

    /**
     * getupdatehistory function
     * Get automatically update history information
     *
     * @param int $cp_id cp id
     *
     * @return boolean
     */
    function getupdatehistory($cp_id)
    {
            $sql="select pv.id,pv.product_channel_id,cp.channel_id,pv.version,pv.updateurl,pv.updatetime,c.channel_name from
             ".$this->db->dbprefix('product_version')."   pv, ".$this->db->dbprefix('channel_product')."  cp, ".$this->db->dbprefix('channel')."  c where pv.product_channel_id=cp.cp_id 
             and c.channel_id=cp.channel_id and pv.product_channel_id=$cp_id and pv.active=1";
            $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                      return $query->result_array();
        }
               return null; 
    }

    /**
     * getuapkplatform function
     * Get platform information
     *
     * @param int $channel_id channel id
     *
     * @return query row_array
     */
    function getuapkplatform($channel_id)
    {
            $sql="select platform from   ".$this->db->dbprefix('channel')."  where channel_id =$channel_id";                            
            $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                      return $query->row_array();
        }
               return null; 
    }
    
    /**
     * getakpinfo function
     * Get updated apk with the app information
     *
     * @param int $userid user id
     * @param int $cp_id  cp id
     *
     * @return query result_array
     */
    function getakpinfo($userid,$cp_id)
    {                
            $sql="select cp.*,c.channel_name from  ".$this->db->dbprefix('channel_product')."  cp  inner join  ".$this->db->dbprefix('channel')."  c on 
            c.channel_id=cp.channel_id where cp.cp_id=$cp_id and cp.user_id=$userid";             
            $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                      return $query->result_array();
        }
               return null; 
    }
    
    /**
     * getversionid function
     * Get automatically update the version number information
     *
     * @param int    $cp_id     cp id
     * @param int    $versionid version id
     * @param string $upinfo    upinfo
     *
     * @return boolean
     */
    function getversionid($cp_id,$versionid,$upinfo)
    {
        $query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
        $row = $query->row();
        $time= $row->date;
        $channelcount = $this->getchannelversioncount($cp_id);            
        if ($upinfo==0) {
            if ($channelcount==1) {
                        $sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and active=1";
            } else {
                        $sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <'$time'  and active=1";
            }
        } else {
                  $sql="select  distinct version from  ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <='$time'  and active=1";
        }
            $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                  $dataversion= $query->result_array();
            foreach ($dataversion as $data) {
                      $result=strcmp($data['version'], $versionid);
                if ( $channelcount==1 && $upinfo==0) {
                          $comversion=true;
                } else {
                    if ($result>=0) {
                         $comversion=false;
                         break;
                    } else {
                         $comversion=true;
                    }
                }
            }
            return $comversion;        
        } else {
            if ($sql!=""&& $upinfo==1) {
                return true;
            }
        }
        if ($sql!="" && $upinfo==0 && $channelcount==1) {
             return true;
        } 
          return false; 
    }
    
    /**
     * getchannelversioncount function
     * get channel version count from product_version
     *
     * @param int $cp_id cp id
     *
     * @return int count
     */
    function getchannelversioncount($cp_id)
    {
        $sql="select count(*) channelcount  from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and active=1";
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                $row = $query->row();
                $count=  $row->channelcount;
            if ($count==1||$count==0) {
                    return 1;
            } else {
                    return $count;
            }
        }
            return 1;
            
    }
    
    /**
     * getupdatelistinfo function
     * Get updated automatically update the list of history
     *
     * @param int $vp_id vp id
     *
     * @return query row_array
     */
    function getupdatelistinfo($vp_id)
    {
        $sql="select * from   ".$this->db->dbprefix('product_version')."   where id=$vp_id";
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                      return $query->row_array();
        }
               return null; 
    }
    
    /**
     * getnewlistinfo function
     * Get automatic updates of the information in the new list
     *
     * @param int $cp_id cp id
     *
     * @return query row_array
     */
    function getnewlistinfo($cp_id)
    {
        $sql="select * from  ".$this->db->dbprefix('channel_product')."    where cp_id=$cp_id";
        $query = $this->db->query($sql);
        if ($query!=null&&$query->num_rows()>0) {
                      return $query->row_array();
        }
               return null; 
    }
    
    /**
     * deleteupdate function
     * Delete automatically update in the history list
     *
     * @param int $vp_id vp id
     *
     * @return void
     */
    function deleteupdate($vp_id)
    {
            $data=array(
            'active'=>0
            );
            $this->db->where('id', $vp_id);
            $this->db->update('product_version', $data);
    }
}
