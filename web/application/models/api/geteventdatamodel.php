<?php
class geteventdatamodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function geteventdata($sessionkey, $productid, $fromtime, $totime,$version)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->geteventdatainfo($productid, $fromtime, $totime,$version);
					if ($basic)
					{
						$productinfo = array (
								'flag' => 2,
								'queryResult' => $basic
						);
					}
					else
					{
						$productinfo = array (
								'flag' => - 4,
								'msg' => 'No data information'
						);
					}
				}
				else
				{
					$productinfo = array (
							'flag' => - 6,
							'msg' => 'Do not have permission'
					);
				}
				return $productinfo;
			}
			else
			{
				$productinfo = array (
						'flag' => - 2,
						'msg' => 'Sessionkey is invalide '
				);
				return $productinfo;
			}
		}
		catch ( Exception $ex )
		{
			$productinfo = array (
					'flag' => - 3,
					'msg' => 'DB Error'
			);
			return $productinfo;
		}
	}

	function geteventdatainfo($productid, $fromtime, $totime,$version)
	{
		$identifierarray = array();
		$eventlistarray  = array();
		$eventresult     = array();
		$count=0;
		$eventidentifier=$this->geteventidentifierinfo($productid,$version);
		$evetlist=$this->geteventbyproductidandversion($productid, $version);
		if($eventidentifier!=null&&$eventidentifier->num_rows()>0)
		{
			foreach($eventidentifier->result() as $identifier)
			{
				$identifierobj=array(
						'eventid'=>$identifier->event_id,
						'eventidentifier'=>$identifier->event_identifier,
						'eventname'=>$identifier->event_name
				);
				array_push($identifierarray, $identifierobj);
			}
		}
		if($evetlist!=null&&$evetlist->num_rows()>0)
		{
			foreach($evetlist->result() as $rowlist)
			{
				$eventlistobj=array(
						'eventid'=>$rowlist->event_sk,	
						'eventidentifier'=>$rowlist->eventidentifier,
						'eventname'=>$rowlist->eventname,
						'count'=>$rowlist->count
				);
				array_push($eventlistarray, $eventlistobj);
			}
				
		}
		if(count($identifierarray)!=0)
		{
			for($i=0;$i<count($identifierarray);$i++)
			{
				if(count($eventlistarray)!=0)
				{
					for($j=0;$j<count($eventlistarray);$j++)
					{
						if($identifierarray[$i]['eventidentifier']==$eventlistarray[$j]['eventidentifier'])
						{
							$count=$eventlistarray[$j]['count'];
							$eventsk=$eventlistarray[$j]['eventid'];
							break;
						}
					}
				}
				$eventobj=array(
				'eventid'=>isset($eventsk)?$eventsk:$identifierarray[$i]['eventid'],				
				'eventname'=>$identifierarray[$i]['eventname'],
				'count'=>$count
				);
				array_push($eventresult, $eventobj);
		 }
		}
		if(count($eventresult)>0)
		{
			return $eventresult;
		}
		else
		{
			return false;
		}
		
	}

	function geteventidentifierinfo($productid,$version)
	{		
		if ($version==null)
		{
			$this->db->from('event_defination');
			$this->db->where('product_id',$productid);
			$this->db->where('active',1);
			$this->db->order_by("event_id", "desc");
			$query = $this->db->get();
			return $query;
		}
		else
		{
			$sql="select distinct e.version,
			d. * from ".$this->db->dbprefix('event_defination')." d ,
			".$this->db->dbprefix('eventdata')." e
			where d.event_id=e.event_id and
			d.product_id='$productid'
			and e.version='$version'
			and d.active=1 order by  d.event_id desc";
			$query = $this->db->query($sql);
			return $query;
		}
	}

	function geteventbyproductidandversion($productid, $version)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );		
		if ($version==null)
		{
			$sql = "select
			e.event_sk,
			e.eventidentifier,
			e.eventname,
			count(f.eventid) count
			from ".$dwdb->dbprefix('dim_product')."   p,
			".$dwdb->dbprefix('fact_event')."  f,
			".$dwdb->dbprefix('dim_event')."  e
			where  p.product_id='$productid'
			and p.product_active=1 and
			p.channel_active=1 and
			p.version_active=1 and
			f.product_sk = p.product_sk
			and f.event_sk = e.event_sk
			group by  e.event_sk,e.eventidentifier,
			e.eventname order by e.event_sk desc";
		}
		else
		{	
				$sql = "select p.version_name,
				e.event_sk,
				e.eventidentifier,
				e.eventname,
				count(f.eventid) count
				from  ".$dwdb->dbprefix('dim_product')."   p,
				".$dwdb->dbprefix('fact_event')."  f,
				".$dwdb->dbprefix('dim_event')."   e
				where  p.product_id='$productid' and p.product_active=1
				and p.channel_active=1 and p.version_active=1
				and f.product_sk = p.product_sk
				and f.event_sk = e.event_sk
				and p.version_name='$version'
				group by p.version_name, e.event_sk,
				e.eventidentifier,e.eventname
				order by e.event_sk desc";
		}
		$query = $dwdb->query ( $sql );
		return $query;
    }


  }