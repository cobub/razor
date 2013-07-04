<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package     Cobub Razor
 * @author      @WBTECH Dev Team
 * @copyright   Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license     http://www.cobub.com/products/cobub-razor/license
 * @link        http://www.cobub.com/products/cobub-razor/
 * @since       Version 1.0
 * @filesource
 */
class GetNews extends CI_Model {
    function __construct() {
        parent::__construct();		
        $this->load->database();
    }


    /*
     * getData(): Get $email and $modified by $userId
     */
    function getData($userId, &$email, &$modified) {
        $query = $this->db->query("select email from "
            .$this->db->dbprefix('users')
            ." where id=".$userId.";"
        );
        $email = $query->result(); /*Query result shall be an array of objects */
        $email = $email[0]->email;    	
        $modified = date('Y-m-d ', time());    	
    }

    /*
     * getSQLSentence(): Generate a SQL sentence by $postDay and $userId
     */
    function getSQLSentence($postDay, $userId) {
        $productbasicinfo = array ();
        $dwdb = $this->load->database('dw', TRUE);
        $sql = "
            select ppp.product_id,ppp.product_name,ppp.platform,
            ifnull(allusers,0) allusers,
            ifnull(newusers,0) newusers,
            ifnull(startusers,0) startusers,
            ifnull(sessions,0) sessions
            from (select product_id,product_name,platform
            from " . $dwdb->dbprefix ( 'dim_product' ) . "
            group by product_id) ppp
            left join (select product_id,max(allusers) allusers
            from " . $dwdb->dbprefix ( 'sum_basic_product' ) . " bp,
                " . $dwdb->dbprefix ( 'dim_date' ) . " dd
                where dd.datevalue='$postDay' and
                bp.date_sk<=dd.date_sk
                group by product_id) dpp
                on dpp.product_id=ppp.product_id
                left join (select  pp.product_id,newusers,
                    startusers,sessions
                    from " . $dwdb->dbprefix ( 'dim_product' ) . " p,
                        " . $dwdb->dbprefix ( 'sum_basic_product' ) . " pp,
                        " . $dwdb->dbprefix ( 'dim_date' ) . " d
                        where d.datevalue='$postDay' and
                        d.date_sk=pp.date_sk and p.userid=$userId
                        and product_active=1 and channel_active=1
                        and version_active=1
                        and p.product_id=pp.product_id
                        group by pp.product_id) ff
                        on ff.product_id=ppp.product_id
                        group by ppp.product_id

                        ";
        return $sql;
    }

    /*
     * getAppList(): Get app info list by $userId & $today
     */
    function getAppList($userId, $today)
    {
        $getIDsql = "select p.id,p.name,f.name platform
            from ".$this->db->dbprefix('product')."  p,
                ".$this->db->dbprefix('platform')."  f
                where p.product_platform = f.id  and p.active = 1
                order by p.id desc;";

        $dwdb = $this->load->database('dw', TRUE);
        $getIDsqlResult = $this->db->query($getIDsql);
        $todayquery = $dwdb->query($this->getSQLSentence($today, $userId));
        $appList = array();
        $flag = 0;
        if ($getIDsqlResult != null&& $getIDsqlResult->num_rows() > 0) {
            foreach ($getIDsqlResult->result() as $row) {
                $app = array();
                $app['name'] = $row->name;
                foreach ($todayquery->result() as $todaydata) {
                    if ($row->name == $todaydata->product_name) {
                        $app['startcount'] = $todaydata->sessions;
                        $app['newuser'] = $todaydata->newusers;
                        $app['startuser'] = $todaydata->startusers;
                        $app['platform'] = $todaydata->platform;
                        $app['totaluser'] = $todaydata->allusers;
                        array_push($appList, $app);
                        $flag = 1;
                        break;
                    }

                    if ($flag == 1) {
                        break;
                    }
                }

                if ($flag == 0) {
                    $app ['startcount'] = '0';
                    $app ['newuser'] = '0';
                    $app ['startuser'] = '0';
                    $app ['platform'] = $row->platform;
                    $app ['totaluser'] = 0;
                    array_push ( $appList, $app );
                }
                $flag = 0;
            }
        }
        return $appList;
    }
}
