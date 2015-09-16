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
 * Conversion Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Conversionmodel extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    /**
     * GetConversionListByProductIdAndUserId function
     * Get conversion list through productid and userid
     *
     * @param int    $productid product id
     * @param int    $userid    user id
     * @param string $fromdate  from date
     * @param string $todate    to date
     * @param string $version   version
     *
     * @return array data
     */
    function getConversionListByProductIdAndUserId($productid, $userid, $fromdate, $todate, $version)
    {
        $dwdb = $this->load->database('dw', true);
        $sql_1 = 'select t.tid,t.unitprice,t.targetname,te.eventalias a1,tee.eventalias a2,te.eventid sid,tee.eventid eid
       from ' . $this->db->dbprefix('target') . ' t, ' . $this->db->dbprefix('targetevent') . ' te,' . $this->db->dbprefix('targetevent') . ' tee
       where  t.productid = ? and t.tid = te.targetid and t.tid = tee.targetid 
       and te.sequence = 1 and 
       tee.sequence = (select max(sequence) from ' . $this->db->dbprefix('targetevent') . '  where targetid = t.tid)  GROUP BY t.tid';
        $sql_2 = 'select t.event_id,count(*) num,d.datevalue from ' . $dwdb->dbprefix('fact_event') . ' e, ' . $dwdb->dbprefix('dim_date') . ' d, ' . $dwdb->dbprefix('dim_product') . ' p,' . $dwdb->dbprefix('dim_event') . ' t where e.event_sk = t.event_sk and 
       e.product_sk = p.product_sk and p.product_id = ?  and 
       e.date_sk = d.date_sk and d.datevalue between \'' . $fromdate . '\' and \'' . $todate . '\' 
       group by e.event_sk,d.datevalue';
        $data['targetdata'] = $this->db->query($sql_1, array($productid))->result_array();
        $data['eventdata'] = $dwdb->query($sql_2, array($productid))->result_array();
        return $data;
    }

    /**
     * AddConversionrate function
     * add conversion rate
     *
     * @param int    $userid     user id
     * @param int    $productid  product id
     * @param string $targetname target name
     * @param string $unitprie   unitprie
     * @param array  $data       data
     *
     * @return array data
     */
    function addConversionrate($userid, $productid, $targetname, $unitprie, $data = array())
    {
        $r = $this->db->query(
            'select 
            * 
        from 
            ' . $this->db->dbprefix('target') . ' 
        where 
            targetname=\'' . $targetname . '\' and 
            userid=? and 
            productid=?', 
            array(
            $userid,
            $productid)
        );
        $r1 = $this->db->query(
            'select 
                * 
            from 
                ' . $this->db->dbprefix('target') . ' 
            where 
                userid=? and 
                productid=?', 
            array(
            $userid,
            $productid)
        );
        $num_row1 = $r1->num_rows();
        $num_row = $r->num_rows();
        // return $num_row;
        if ($num_row > 0) {
            return 'existsname';
        } else if ($num_row1 >= 10) {
            return 'max';
        } else {
            $this->db->trans_start();
            $this->db->query('insert into ' . $this->db->dbprefix('target') . '(userid,productid,targetname,unitprice,createdate)values(' . $userid . ',' . $productid . ',\'' . $targetname . '\',' . $unitprie . ',sysdate())');
            $targetid = $this->db->insert_id();
            if ($data) {
                for ($i = 0; $i < count($data['events']) - 1; $i++) {
                    $this->db->query('insert into ' . $this->db->dbprefix('targetevent') . '(targetid,eventid,eventalias,sequence)values(' . $targetid . ',' . $data['events'][$i] . ',\'' . $data['names'][$i] . '\',' . ($i + 1) . ')');
                }
            }
            $affect_row = $this->db->affected_rows();
            $this->db->trans_complete();
            if ($affect_row) {
                return 'success';
            } else {
                return 'error';
            }
        }
    }

    /**
     * Deltefunnel function
     * delete funnel
     *
     * @param int $userid   user id
     * @param int $targetid target id
     *
     * @return int affected_rows
     */
    function deltefunnel($userid, $targetid)
    {
        $this->db->trans_start();
        $this->db->query('delete from ' . $this->db->dbprefix('targetevent') . ' where targetid=' . $targetid);
        $this->db->query('delete from ' . $this->db->dbprefix('target') . ' where tid=' . $targetid . ' and userid=' . $userid);
        $this->db->trans_complete();
        return $this->db->affected_rows();
    }

    /**
     * DelteFunnelEvent function
     * delete funnel event
     *
     * @param int $targetid target id
     * @param int $eventid  event  id
     *
     * @return int affected_rows
     */
    function delteFunnelEvent($targetid, $eventid)
    {
        $sql = 'DELETE FROM ' . $this->db->dbprefix('targetevent') . ' WHERE targetid=? AND eventid=?';
        $this->db->query(
            $sql, 
            array(
                $targetid,
                $eventid)
        );
        return $this->db->affected_rows();
    }

    /**
     * CheckIsDeleteFunnelEvent function
     * check is delete funnel event
     *
     * @param int $targetid target id
     *
     * @return int num_rows
     */
    function checkIsDeleteFunnelEvent($targetid)
    {
        $sql = 'SELECT * from ' . $this->db->dbprefix('targetevent') . ' where targetid=' . $targetid;
        $result = $this->db->query($sql);
        return $result->num_rows();
    }

    /**
     * Detailfunnel function
     * detail funnel
     *
     * @param int $targetid target id
     *
     * @return query queryresult
     */
    function detailfunnel($targetid)
    {
        $sql = 'select t.targetname,te.eventalias,te.eventid,te.sequence 
        from ' . $this->db->dbprefix('target') . ' t,' . $this->db->dbprefix('targetevent') . ' te 
        where t.tid = te.targetid and te.targetid = ' . $targetid . ' order by te.sequence';
        $queryresult = $this->db->query($sql);
        if (!empty($queryresult)) {
            $queryresult = $queryresult->result();
        }
        return $queryresult;
    }

    /**
     * Detailfunnel2 function
     * detail funnel
     *
     * @param string $fromdate  from time
     * @param string $todate    to date
     * @param string $version   version
     * @param int    $productId product id
     *
     * @return query queryresult
     */
    function detailfunnel2($fromdate, $todate, $version, $productId)
    {
        $dwdb = $this->load->database('dw', true);
        $sql1 = 'select t.event_id,count(*) num 
        from ' . $dwdb->dbprefix('fact_event') . ' e, ' . $dwdb->dbprefix('dim_date') . ' d,
        ' . $dwdb->dbprefix('dim_product') . ' p,' . $dwdb->dbprefix('dim_event') . ' t 
        where e.event_sk = t.event_sk and e.product_sk = p.product_sk and p.product_id = ' . $productId . '
        and p.version_name="' . $version . '" and e.date_sk = d.date_sk 
        and d.datevalue between "' . $fromdate . '" and "' . $todate . '" group by e.event_sk';
        $sql2 = 'select t.event_id,count(*) num
        from ' . $dwdb->dbprefix('fact_event') . ' e, ' . $dwdb->dbprefix('dim_date') . ' d,
        ' . $dwdb->dbprefix('dim_product') . ' p,' . $dwdb->dbprefix('dim_event') . ' t
        where e.event_sk = t.event_sk and e.product_sk = p.product_sk and p.product_id = ' . $productId . '
        and e.date_sk = d.date_sk
        and d.datevalue between "' . $fromdate . '" and "' . $todate . '" group by e.event_sk';
        if ($version != 'all') {
            $queryresult = $dwdb->query($sql1);
        } else {
            $queryresult = $dwdb->query($sql2);
        }
        if (!empty($queryresult)) {
            $queryresult = $queryresult->result();
        }
        return $queryresult;
    }

    /**
     * GetFunnelByTargetid function
     * get funnel by target id
     *
     * @param int $targetid target id
     *
     * @return query result
     */
    function getFunnelByTargetid($targetid)
    {
        $sql = 'select t.tid,t.unitprice,t.userid,t.targetname,e.eventalias,e.sequence,e.eventid,d.event_name from ' . $this->db->dbprefix('target') . ' t
left JOIN ' . $this->db->dbprefix('targetevent') . '  e on t.tid=e.targetid
 inner join ' . $this->db->dbprefix('event_defination') . ' d on e.eventid=d.event_id where t.tid=' . $targetid;
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * ModifyFunnel function
     * modify funnel
     *
     * @param int    $targetid    target id
     * @param string $target_name target name
     * @param int    $unitprice   unit price
     * @param array  $data        data
     *
     * @return int affected_rows
     */
    function modifyFunnel($targetid, $target_name, $unitprice, $data = array())
    {
        $this->db->trans_start();
        $this->db->query(
            'UPDATE ' . $this->db->dbprefix('target') . ' SET targetname=?,unitprice=? WHERE tid=?', 
            array(
            $target_name,
            $unitprice,
            $targetid)
        );
        for ($i = 0; $i <= count($data['event_ids']) - 1; $i++) {
            $this->db->query(
                'update ' . $this->db->dbprefix('targetevent') . ' set eventalias=?,sequence=? where targetid=? and eventid=?', 
                array(
                    $data['event_names'][$i],
                    $i,
                    $targetid,
                    $data['event_ids'][$i])
            );
        }
        $this->db->trans_complete();
        return $this->db->affected_rows();
    }

    /**
     * GetAllUserTarget function
     * get all user target
     *
     * @param int $userid    user id
     * @param int $productid product id
     *
     * @return query query
     */
    function getAllUserTarget($userid, $productid)
    {
        $sql = "select t.targetname,t.unitprice,te.eventalias a1,te.eventid sid
from  " . $this->db->dbprefix('target') . "  t, " . $this->db->dbprefix('targetevent') . " te
where t.userid = $userid and t.productid = $productid and t.tid = te.targetid
and te.sequence = (select max(sequence) from " . $this->db->dbprefix('targetevent') . " where targetid = t.tid) ;";
        // echo $sql;
        $query = $this->db->query($sql);
        return $query;

    }

    /**
     * GetTargetEventNumPerDay function
     * get target event number per day
     *
     * @param int $productid product id
     * @param int $from      from
     * @param int $to        to
     *
     * @return query query
     */
    function getTargetEventNumPerDay($productid, $from, $to)
    {
        $dwdb = $this->load->database('dw', true);
        $sql = "select t.event_id, date(d.datevalue) d, ifnull(s.num,0) num from (select date_sk, datevalue from " . $dwdb->dbprefix('dim_date') . " where datevalue between '$from' and '$to') d cross join " . $dwdb->dbprefix('dim_event') . " t 
left join (select event_sk, date_sk, count(*) num from " . $dwdb->dbprefix('fact_event') . " f, " . $dwdb->dbprefix('dim_product') . " p   where f.product_sk = p.product_sk and p.product_id = $productid group by event_sk,date_sk) s on d.date_sk = s.date_sk and t.event_sk = s.event_sk;";
        // echo $sql;
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * GetChartData function
     * get chart data
     *
     * @param int $userid    user id
     * @param int $productid product id
     * @param int $from      from
     * @param int $to        to
     *
     * @return query result
     */
    function getChartData($userid, $productid, $from, $to)
    {

        $target = $this->getAllUserTarget($userid, $productid);
        $numofAllTarget = $this->getTargetEventNumPerDay($productid, $from, $to);

        $result = array();
        if ($target != null && $target->num_rows() > 0) {
            $array = $target->result_array();
            $target_array = array();
            foreach ($array as $row) {
                // array_push($target_array, $row['targetname']);
                $target_Item["targetname"] = $row["targetname"];
                $target_Item['unitprice'] = $row["unitprice"];
                $target_Item["eventname"] = $row["a1"];
                $time_array = array();
                $num_array = array();
                $event_id = $row["sid"];

                foreach ($numofAllTarget->result_array() as $row2) {
                    if ($row2["event_id"] == $event_id) {
                        array_push($time_array, $row2["d"]);
                        array_push($num_array, $row2["num"]);
                    }
                }
                $target_Item['eventtime'] = $time_array;
                $target_Item['eventnum'] = $num_array;
                array_push($result, $target_Item);

            }

        } else {
            $result = '';
        }
        return $result;

    }

}
