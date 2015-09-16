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
 * Point_Mark Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Point_Mark extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * addPointmark function
     * add a point mark
     *
     * @param array $data data
     *
     * @return query affected_rows
     */
    public function addPointmark ($data = array())
    {
        $this->db->insert($this->db->dbprefix('markevent'), $data);
        return $this->db->affected_rows();
    }
    
    /**
     * removePointmark function
     * remove a point mark
     *
     * @param int   $userid    user id
     * @param int   $productid product id
     * @param array $date      date
     *
     * @return query sql
     */
    public function removePointmark ($userid, $productid, $date)
    {
        $sql = 'delete from razor_markevent where userid=' . $userid .
                 ' and productid=' . $productid . ' and marktime=' . '"' . $date .
                 '"';
        return $this->db->query($sql);
    }
    
    /**
     * listPointviewtochart function
     * return a point list and content to charts
     *
     * @param int    $userid    user id
     * @param int    $productid product id
     * @param string $fromdate  from date
     * @param string $enddate   end date
     * @param int    $type      type
     *
     * @return query result_array
     */
    public function listPointviewtochart ($userid, $productid, $fromdate, $enddate, $type = '')
    {
        $sql = 'select u.username,m.userid,m.title,m.description,m.marktime,m.private from ' .
                 $this->db->dbprefix('markevent') . ' m
                 left join ' .
                 $this->db->dbprefix('users') . ' u on m.userid=u.id
                 LEFT JOIN ' .
                 $this->db->dbprefix('product') .
                 ' p on p.id=m.productid
                 where m.marktime BETWEEN "' .
                 $fromdate . '" and "' . $enddate . '" and p.id=' . $productid .
                 ' and m.userid=' . $userid . ' or(m.userid!=' . $userid .
                 ' and private=1 and p.id=' . $productid .
                 ') GROUP BY u.username,m.userid,m.title,m.description,m.marktime,m.private ORDER BY m.marktime asc';
        if ('listcount' == $type) {
            $sql = 'select count(1) c,m.userid from ' .
                     $this->db->dbprefix('markevent') . ' m
		left join ' .
                     $this->db->dbprefix('users') . ' u on m.userid=u.id
		LEFT JOIN ' .
                     $this->db->dbprefix('product') .
                     ' p on p.id=m.productid
		where m.marktime BETWEEN "' .
                     $fromdate . '" and "' . $enddate . '" and p.id=' .
                     $productid . ' and m.userid=' . $userid . ' or(m.userid!=' .
                     $userid . ' and private=1) GROUP BY m.userid';
            return $this->db->query($sql)->result_array();
        }
        return $this->db->query($sql);
    }
    
    /**
     * modifyPointmark function
     * modify a point mark
     *
     * @param int    $userid    user id
     * @param int    $productid product id
     * @param string $markdate  markdate
     * @param array  $data      data
     *
     * @return query affected_rows
     */
    public function modifyPointmark ($userid, $productid, $markdate, $data = array())
    {
        $where = 'userid = ' . $userid . ' AND productid = ' . $productid .
                 ' and marktime="' . $markdate . '"';
        $sql = $this->db->update_string($this->db->dbprefix('markevent'), $data, $where);
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    /**
     * managePointmarkpagelist function
     * manage all point marks
     *
     * @param int    $userid    user id
     * @param int    $productid product id
     * @param string $fromTime  from time
     * @param string $toreTime  tore time
     *
     * @return query sql
     */
    public function managePointmarkpagelist ($userid, $productid, $fromTime, $toreTime)
    {
        $sql = '
        select 
            m.id,
            u.username,
            m.userid,
            m.title,
            m.description,
            m.marktime,
            m.private 
        from ' .
            $this->db->dbprefix('markevent') . ' m
        left join 
            ' .$this->db->dbprefix('users') . ' u 
        on 
            m.userid=u.id
        LEFT JOIN 
            ' .$this->db->dbprefix('product') .' p 
        on 
            p.id=m.productid
        where 
            p.id=' .$productid . ' and 
            m.marktime BETWEEN \'' . $fromTime . '\' and \'' .$toreTime . '\'';
        return $this->db->query($sql);
    }
    
    /**
     * ifcaninsert function
     * check the same date for user whether if insert
     *
     * @param int    $userid    user id
     * @param int    $productid product id
     * @param string $date      date
     *
     * @return bool
     */
    public function ifcaninsert ($userid, $productid, $date)
    {
        $query = $this->db->query(
            'SELECT * FROM ' . $this->db->dbprefix('markevent') .
            ' WHERE userid=' . $userid . ' AND productid=' .
            $productid . ' AND marktime="' . $date . '"'
        );
        $count = $query->num_rows();
        if ($count >= 1) {
            return false;
        }
        return true;
    }

    /**
     * timediff function
     * time diff
     *
     * @param string $begin_time begin time
     * @param string $end_time   end time
     *
     * @return query res
     */
    function timediff ($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        $timediff = $endtime - $starttime;
        $days = intval($timediff / 86400);
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        $secs = $remain % 60;
        $res = array(
                "day" => $days,
                "hour" => $hours,
                "min" => $mins,
                "sec" => $secs
        );
        return $res;
    }
}
?>