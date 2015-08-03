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
 * Event Model
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
     * getEventListInfo function
     * Get event list information by productid and version
     *
     * @param int    $productId product id
     * @param string $version   version
     *
     * @return query result
     */
    function getEventListInfo($productId, $version)
    {
        $identifierarray = array();
        $eventlistarray = array();
        $eventresult = array();
        $count = 0;
        $eventsk = 0;
        $eventidentifier
            = $this->getEventIdentifierinfo($productId, $version);
        $evetlist
            = $this->getEventListByProductIdAndProductVersion($productId, $version);
        if ($eventidentifier != null && $eventidentifier->num_rows() > 0) {
            foreach ($eventidentifier->result() as $identifier) {
                $identifierobj
                    = array('eventidentifier'
                => $identifier->event_identifier,
                    'eventname' => $identifier->event_name);
                array_push($identifierarray, $identifierobj);
            }
        }
        if ($evetlist != null && $evetlist->num_rows() > 0) {
            foreach ($evetlist->result() as $rowlist) {
                $eventlistobj
                    = array('event_sk' => $rowlist->event_sk,
                    'eventidentifier' => $rowlist->eventidentifier,
                    'eventname' => $rowlist->eventname, 'count' => $rowlist->count);
                array_push($eventlistarray, $eventlistobj);
            }

        }
        if (count($identifierarray) != 0) {
            for ($i = 0; $i < count($identifierarray); $i++) {
                if (count($eventlistarray) != 0) {
                    for ($j = 0; $j < count($eventlistarray); $j++) {
                        if ($identifierarray[$i]['eventidentifier'] == $eventlistarray[$j]['eventidentifier']
                        ) {
                            $count = $eventlistarray[$j]['count'];
                            $eventsk = $eventlistarray[$j]['event_sk'];
                            break;
                        }
                    }
                }
                $eventobj = array('event_sk' => $eventsk,
                    'eventidentifier' => $identifierarray[$i]['eventidentifier'],
                    'eventname' => $identifierarray[$i]['eventname'],
                    'count' => $count);
                $count = 0;
                $eventsk = 0;
                array_push($eventresult, $eventobj);
            }
        }
        return $eventresult;
    }

    /**
     * getEventIdentifierinfo function
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
     * getEventListByProductIdAndProductVersion function
     * get all event list information by productid and version
     *
     * @param int    $productId product id
     * @param string $version   version
     *
     * @return query result
     */
    function getEventListByProductIdAndProductVersion($productId, $version)
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
                    count(f.eventid) count
                from
                    " . $dwdb->dbprefix('dim_product') . "   p,
                    " . $dwdb->dbprefix('fact_event') . "  f,
                    " . $dwdb->dbprefix('dim_event') . "  e
                where
                    p.product_id=$productId and
                    p.product_active=1 and
                    p.channel_active=1 and
                    p.version_active=1 and
                    f.product_sk = p.product_sk and
                    f.event_sk = e.event_sk group by
                    e.event_sk,e.eventidentifier,
	                e.eventname oder by
                    e.event_sk desc";
        } else {
            if ($version == 'unknown') {
                $version = '';
                $sql = "
                select
                    p.version_name,
                    e.event_sk,
                    e.eventidentifier,
                    e.eventname,
                count(f.eventid) count
                from
                    " . $dwdb->dbprefix('dim_product') . "   p,
                    " . $dwdb->dbprefix('fact_event') . "  f,
                    " . $dwdb->dbprefix('dim_event') . "   e
                where
                    p.product_id=$productId and
                    p.product_active=1 and
                    p.channel_active=1 and
                    p.version_active=1 and
                    f.product_sk = p.product_sk and
                    f.event_sk = e.event_sk and
                    p.version_name='$version' order by
                    p.version_name, e.event_sk,
                    e.eventidentifier,e.eventname order by
                    e.event_sk desc";
            }
        }

        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * getProductVersions function
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
	               product_id=$productid order by
	               version_name desc";

        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * getAllEventChartData function
     * get all chart data information by productid,version,from,and to
     *
     * @param int    $productid product id
     * @param string $event_sk  eventsk
     * @param string $version   version
     * @param string $from      fromdate
     * @param string $to        todate
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
     * getProductEventByProuctId function
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
     * isUnique function
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
     * isUniqueData function
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
     * addEvent function
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
        $data = array('event_identifier' => $event_id,
            'productkey' => $product->product_key,
            'event_name' => $event_name, 'channel_id' => 1,
            'product_id' => $product->id, 'user_id' => $userId);
        $this->db->insert('event_defination', $data);
    }

    /**
     * geteventbyid function
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
     * modifyEvent function
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
        $data = array('event_identifier' => $eventId, 'event_name' => $eventName);
        $this->db->where('event_id', $id);
        $this->db->update('event_defination', $data);
    }

    /**
     * stopEvent function
     * stop event by id
     *
     * @param int $id id
     *
     * @return void
     */
    function stopEvent($id)
    {
        $this->db->where('event_id', $id);
        $data = array('active' => 0);
        $this->db->update('event_defination', $data);
    }

    /**
     * startEvent function
     * start event by id
     *
     * @param int $id id
     *
     * @return void
     */
    function startEvent($id)
    {
        $this->db->where('event_id', $id);
        $data = array('active' => 1);
        $this->db->update('event_defination', $data);
    }

    /**
     *resetEvent function
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
