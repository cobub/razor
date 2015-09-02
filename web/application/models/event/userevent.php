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
 * UserEvent Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class UserEvent extends CI_Model
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        $this->load->database();
    }

    /**
     * GetEventListInfo function
     * Get event list information by productid and version
     *
     * @param int    $productId product id
     * @param string $version   version
     * @param string $fromTime  from time
     * @param string $toTime    to time
     *            
     * @return query result
     */
    function getEventListInfo($productId, $version, $fromTime, $toTime)
    {
        $identifierarray = array();
        $eventlistarray = array();
        $eventresult = array();
        $count = 0;
        $eventsk = 0;
        $eventidentifier = $this->getEventIdentifierinfo($productId, $version);
        $eventlist = $this->getEventListByProductIdAndProductVersion($productId, $version, $fromTime, $toTime);
        if ($eventlist != null && $eventlist->num_rows() > 0) {
            foreach ($eventlist->result() as $rowlist) {
                $eventlistobj = array(
                        'event_sk' => $rowlist->event_sk,
                        'eventidentifier' => $rowlist->eventidentifier,
                        'eventname' => $rowlist->eventname,
                        'count' => $rowlist->count
                );
                array_push($eventlistarray, $eventlistobj);
            }
        }
        return $eventlistarray;
    }

    /**
     * GetEventIdentifierinfo function
     * get all event identifier information by productid and version
     *
     * @param int    $productId product id
     * @param string $version   version
     *            
     * @return query result
     */
    function getEventIdentifierinfo($productId, $version)
    {
        if ($version == 'unknown') {
            $version = '';
        }
        if ($version == 'all') {
            $this->db->from('event_defination');
            $this->db->where('product_id', $productId);
            $this->db->where('active', 1);
            $this->db->order_by("event_id", "desc");
            $query = $this->db->get();
            return $query;
        } else {
            $sql = "
                select
                     distinct e.version,d.
                     *
                from
                     " . $this->db->dbprefix('event_defination') . "
                     d ," . $this->db->dbprefix('eventdata') . " e
                where
                     d.event_id=e.event_id and
                     d.product_id=$productId and
                     e.version='$version' and
                     d.active=1 order by
                     d.event_id desc";
            
            $query = $this->db->query($sql);
            return $query;
        }
    }

    /**
     * GetEventListByProductIdAndProductVersion function
     * get all event list information by productid,version,fromtime and totime
     *
     * @param int    $productId product id
     * @param string $version   version
     * @param string $fromTime  start time
     * @param string $toTime    end time
     *            
     * @return query result
     */
    function getEventListByProductIdAndProductVersion($productId, $version, $fromTime, $toTime)
    {
        $dwdb = $this->load->database('dw', true);
        if ($version == 'unknown') {
            $version = '';
        }
        if ($version == 'all') {
            
            $sql = "
                select
                e.event_sk,
                e.eventidentifier,
                e.eventname,
                sum(f.total) count
            from
                " . $dwdb->dbprefix('dim_product') . " p,
                " . $dwdb->dbprefix('sum_event') . " f,
                " . $dwdb->dbprefix('dim_date') . " d,
                " . $dwdb->dbprefix('dim_event') . "  e
            where
                p.product_id=$productId and
                p.product_active=1 and
                p.channel_active=1 and
                p.version_active=1 and
                f.product_sk = p.product_sk and
                f.event_sk = e.event_sk and
                f.date_sk = d.date_sk and
                d.datevalue between '$fromTime' and '$toTime'
            group by 
                e.event_sk,
                e.eventidentifier,
                e.eventname 
            order by 
                e.createtime desc";
        } else {
            $sql = "
                select 
                p.version_name,
                e.event_sk,
                e.eventidentifier,
                e.eventname,
                sum(f.total) count
            from 
                " . $dwdb->dbprefix('dim_product') . " p,
                " . $dwdb->dbprefix('sum_event') . " f,
                " . $dwdb->dbprefix('dim_date') . " d,
                " . $dwdb->dbprefix('dim_event') . " e
            where 
                p.product_id=$productId and 
                p.product_active=1 and 
                p.channel_active=1 and 
                p.version_active=1 and 
                f.product_sk = p.product_sk and 
                f.event_sk = e.event_sk and 
                p.version_name='$version' and 
                f.date_sk = d.date_sk and
                d.datevalue between '$fromTime' and '$toTime' 
            group by 
                p.version_name, 
                e.event_sk,
                e.eventidentifier,
                e.eventname 
            order by 
                e.createtime desc";
        }
        
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetProductVersions function
     * get product versions information by productid
     *
     * @param int $productid product id
     *
     * @return query result
     */
    function getProductVersions($productid)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "
                select
                    distinct version_name
                from
                    " . $dwdb->dbprefix('dim_product') . "
                where
	                product_active=1 and
	                channel_active=1 and
                    version_active=1 and
	                product_id=$productid 
	            order by
	                version_name desc";
        
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetAllEventChartData function
     * get all chart data information by productid,version,from,and to
     *
     * @param int    $productid product id
     * @param string $event_sk  event sk
     * @param string $version   version
     * @param string $from      from date
     * @param string $to        to date
     *
     * @return query result
     */
    function getAllEventChartData($productid, $event_sk, $version, $from, $to)
    {
        $dwdb = $this->load->database('dw', true);
        if ($version == 'all') {
            $sql = "
                 select
                     dd.datevalue,ifnull(ff.count,0) count,
					 ifnull(ff.count/(select startusers
				 from
					 " . $dwdb->dbprefix('sum_basic_product') . " s
				 where
					 s.date_sk = dd.date_sk and
					 s.product_id =$productid),0) userper,
					ifnull(ff.count/(
				 select
					 sessions
                 from
					" . $dwdb->dbprefix('sum_basic_product') . "  s
				 where
					 s.date_sk = dd.date_sk and
					 s.product_id =$productid),0) sessionper
				 from
					(
				 select
					date_sk,datevalue
				 from
					 " . $dwdb->dbprefix('dim_date') . "
				 where
					 datevalue between'$from' and '$to') dd
					 left join (
                     select
					     d.date_sk,count(*) count
					 from
					     " . $dwdb->dbprefix('fact_event') . " e,
					     " . $dwdb->dbprefix('dim_date') . " d,
					     " . $dwdb->dbprefix('dim_event') . " dm
					 where
					     d.datevalue between '$from' and '$to' and
                         d.date_sk=e.date_sk and
					     e.event_sk=$event_sk and
					     dm.event_sk=$event_sk and
					     dm.product_id=$productid group by
					     d.date_sk) ff  on
					     dd.date_sk = ff.date_sk order by
					     dd.date_sk";
        } else {
            $sql = "
                select
                    dd.datevalue,
					ifnull(ff.count,0) count,
					ifnull(ff.count/(
					select
					    startusers
					from
					    " . $dwdb->dbprefix('sum_basic_product_version') . " s
					where
					    s.date_sk = dd.date_sk and
					    s.product_id = $productid and
					    version_name='$version'),0) userper,
					   ifnull(ff.count/(
					   select
					       sessions
					  from
                           " . $dwdb->dbprefix('sum_basic_product_version') . "  s
					  where
					       s.date_sk = dd.date_sk and
					       s.product_id =$productid and
					       version_name='$version'),0) sessionper
					  from
					(
					     select
					          date_sk,datevalue
					     from
					          " . $dwdb->dbprefix('dim_date') . "
					     where
					         datevalue between '$from' and '$to') dd
					         left join (select d.date_sk,count(*) count
					     from
					         " . $dwdb->dbprefix('fact_event') . " e,
					         " . $dwdb->dbprefix('dim_date') . " d,
					         " . $dwdb->dbprefix('dim_event') . " dm,
					         " . $dwdb->dbprefix('dim_product') . " p
					     where
					         d.datevalue between '$from' and
					         '$to' and d.date_sk=e.date_sk and
					         e.event_sk=$event_sk and
					         dm.event_sk=$event_sk and
					         p.product_id=$productid and
                             p.product_active=1 and
                             p.channel_active=1 and
                             p.version_active=1 and
                             p.version_name = '$version' and
                             p.product_sk=e.product_sk group by
					         d.date_sk) ff on
					         dd.date_sk = ff.date_sk order by
					         dd.date_sk";
        }
        
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetProductEventByProuctId function
     * Get product event list information by productid
     *
     * @param int $productId product id
     *
     * @return query result
     */
    function getProductEventByProuctId($productId)
    {
        $sql = "
          select
               d.event_id eventid,d.productkey,
		       d.event_identifier,d.event_name eventName,
		       d.active,e.productkey,e.event_id,
		       sum(e.num) eventnum
		  from
		       " . $this->db->dbprefix('event_defination') . "  as d
		       left join " . $this->db->dbprefix('eventdata') . "  as e
		       on  d.event_id = e.event_id
           where  d.product_id=" . $productId . " group by d.event_id";
        
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * IsUnique function
     * Get unique information by productid and eventid
     *
     * @param int $productId product id
     * @param int $event_id  event id
     *
     * @return query result
     */
    function isUnique($productId, $event_id)
    {
        $this->db->from('event_defination');
        $this->db->where('product_id', $productId);
        $this->db->where('event_identifier', $event_id);
        $r = $this->db->get();
        return $r->result();
    }
    
    /**
     * IsUniqueName function
     * Get unique information by productid and eventid
     *
     * @param int $productId  product id
     * @param int $event_name event name
     *
     * @return query result
     */
    function isUniqueName($productId, $event_name)
    {
        $this->db->from('event_defination');
        $this->db->where('product_id', $productId);
        $this->db->where('event_name', $event_name);
        $r = $this->db->get();
        return $r->result();
    }

    /**
     * IsUniqueData function
     * Get uniquedata information by productid ,eventid and eventname
     *
     * @param int    $productId  product id
     * @param int    $event_id   event id
     * @param string $event_name event name
     *
     * @return query result
     */
    function isUniqueData($productId, $event_id, $event_name)
    {
        $this->db->from('event_defination');
        $this->db->where('product_id', $productId);
        $this->db->where('event_identifier', $event_id);
        $this->db->where('event_name', $event_name);
        $r = $this->db->get();
        return $r->result();
    }

    /**
     * AddEvent function
     * add event by eventid and eventname
     *
     * @param int    $event_id   event id
     * @param string $event_name event name
     *
     * @return void
     */
    function addEvent($event_id, $event_name)
    {
        $userId = $this->common->getUserId();
        $product = $this->common->getCurrentProduct();
        $data = array(
                'event_identifier' => $event_id,
                'productkey' => $product->product_key,
                'event_name' => $event_name,
                'channel_id' => 1,
                'product_id' => $product->id,
                'user_id' => $userId
        );
        $this->db->insert('event_defination', $data);
    }

    /**
     * Geteventbyid function
     * Get event information by eventid
     *
     * @param int $eventid event id
     *
     * @return query result
     */
    function geteventbyid($eventid)
    {
        $sql = "
            select
                event_id ,event_identifier,event_name
            from
                " . $this->db->dbprefix('event_defination') . "
            where
                event_id =$eventid";
        
        $result = $this->db->query($sql);
        if ($result != null && $result->num_rows() > 0) {
            return $result->row_array();
        }
        return null;
    }

    /**
     * ModifyEvent function
     * modify event by id ,eventid and eventname
     *
     * @param int    $id        id
     * @param int    $eventId   event id
     * @param string $eventName event name
     *
     * @return void
     */
    function modifyEvent($id, $eventId, $eventName)
    {
        $data = array(
                'event_identifier' => $eventId,
                'event_name' => $eventName
        );
        $this->db->where('event_id', $id);
        $this->db->update('event_defination', $data);
    }

    /**
     * StopEvent function
     * stop event by id
     *
     * @param int $id id
     *
     * @return void
     */
    function stopEvent($id)
    {
        $this->db->where('event_id', $id);
        $data = array(
                'active' => 0
        );
        $this->db->update('event_defination', $data);
    }

    /**
     * StartEvent function
     * start event by id
     *
     * @param int $id id
     *
     * @return void
     */
    function startEvent($id)
    {
        $this->db->where('event_id', $id);
        $data = array(
                'active' => 1
        );
        $this->db->update('event_defination', $data);
    }

    /**
     * ResetEvent function
     * reset event by id
     *
     * @param int $id id
     *
     * @return void
     */
    function resetEvent($id)
    {
        $sql = "
            delete from
                       " . $this->db->dbprefix('eventdata') . "
            where
                       event_id=" . $id;
        
        $this->db->query($sql);
    }
}
