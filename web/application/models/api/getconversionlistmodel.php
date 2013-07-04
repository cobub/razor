<?php
class getconversionlistmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'api/common', 'common' );
		$this->load->database ();
	}

	function getconversiondata($sessionkey, $productid, $fromtime, $totime)
	{
		try {
			$userid = $this->common->getuseridbysessionkey ( $sessionkey );
			if ($userid)
			{
				$verify = $this->common->verifyproductbyproductid ( $userid, $productid );
				if ($verify)
				{
					$basic = $this->getconversiondatainfo($userid,$productid, $fromtime, $totime);
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

	function getconversiondatainfo($userid,$productid, $fromtime, $totime)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$targetsql ="select t.tid targetid,
		             t.unitprice unitprice,t.targetname targetname,
		             te.eventalias startevent,tee.eventalias endevent,
		             te.eventid starteventid,tee.eventid endeventid
                     from " . $this->db->dbprefix ( 'target' ) . " t, 
                     " . $this->db->dbprefix ( 'targetevent' ) ." te,
                     " . $this->db->dbprefix ( 'targetevent' ) . " tee
                     where t.userid = '$userid' and t.productid = '$productid'
                     and t.tid = te.targetid and t.tid = tee.targetid 
                     and te.sequence = 1 and 
                     tee.sequence = (select max(sequence) from
                     " . $this->db->dbprefix ( 'targetevent' ) . "  
                     where targetid = targetid)  group by targetid";		
		$eventsql = "select distinct t.event_id,count(*) num
		             from " . $dwdb->dbprefix ( 'fact_event' ) . " e,
		           " . $dwdb->dbprefix ( 'dim_date' ) ." d,
		            " . $dwdb->dbprefix ( 'dim_product' ) . " p,
		            " . $dwdb->dbprefix ( 'dim_event' ) . " t 
		            where e.event_sk = t.event_sk and
		            e.product_sk = p.product_sk
		            and p.product_id ='$productid' and
		            e.date_sk = d.date_sk and d.datevalue
		            between '$fromtime' and '$totime'
		   			group by e.event_sk";		
		$targetdata= $this->db->query($targetsql) ;
		$eventdata = $dwdb->query($eventsql);	
		if($targetdata!=null&&$targetdata->num_rows()>0)
		{
			$targetquery=$targetdata->result_array();					
			$result=array();
			for($i = 0; $i < count ( $targetquery ); $i ++)
			{		
				
				if($eventdata!=null&&$eventdata->num_rows()>0)
				{	
					$startnum=0;
					$endnum=0;
					$eventquery= $eventdata->result_array();
					for($j = 0; $j < count ( $eventquery ); $j ++)
					{				
						if ($targetquery[$i] ['starteventid'] == $eventquery[$j]['event_id'])
						{										
							$startnum = $eventquery[$j]['num'];	
							break;																	
						}									
				     }
				     for($j = 0; $j < count ( $eventquery ); $j ++)
				     {			    
					     if ($targetquery[$i] ['endeventid'] == $eventquery[$j]['event_id'])
					     {
					         $endnum = $eventquery[$j]['num'];
					         break;
					     }
				     }				    
				     if($startnum==0||$endnum==0)
				     {
				     	$conversion= 0;
				     }
				     else
				     {				     	
				     	$conversion = $startnum/$endnum;
				     }			
				}
				else
				{
					$conversion= 0;				
				}

				$obj=array(
						'targetid' => $targetquery[$i] ['targetid'],
						'targetname' => $targetquery[$i]['targetname'],
						'unitprice' => $targetquery[$i]['unitprice'],
						'startevent' => $targetquery[$i] ['startevent'],
						'endevent' => $targetquery[$i] ['endevent'],
						'conversion'=>$conversion
				);
				array_push($result,$obj);
			
			}
			return $result;			
		}
		else
		{
			return false;
		}
      
	}

	
}