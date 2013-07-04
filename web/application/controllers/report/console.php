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
class Console extends CI_Controller {
    private $data = array();
    function __construct() {
        parent::__construct();
        $this -> load -> helper(array('form', 'url'));
        $this -> load -> library('form_validation');
        $this -> load -> Model('common');
        $this -> load -> model('channelmodel', 'channel');
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> model('product/productanalyzemodel');
        $this -> load -> model('product/newusermodel', 'newusermodel');
        $this -> load -> model('analysis/trendandforecastmodel', 'trendmodel');
        $this -> common -> requireLogin();
    }

    function index() {
        $this -> common -> cleanCurrentProduct();
        $this -> data['category'] = $this -> product -> getProductCategory();
        $this -> data['user_id'] = $this -> common -> getUserId();
        $today = date('Y-m-d', time());
        $yestoday = date("Y-m-d", strtotime("-1 day"));
        $query = $this -> product -> getProductListByPlatform(1, $this -> data['user_id'], $today, $yestoday);
        $this -> data['androidList'] = $query;
        // active users num
        $this -> data['today_startuser'] = 0;
        $this -> data['yestoday_startuser'] = 0;

        // new users num
        $this -> data['today_newuser'] = 0;
        $this -> data['yestoday_newuser'] = 0;

        // session num
        $this -> data['today_startcount'] = 0;
        $this -> data['yestoday_startcount'] = 0;

        $this -> data['today_totaluser'] = 0;

        for ($i = 0; $i < count($this -> data['androidList']); $i++) {
            $row = $this -> data['androidList'][$i];
            $this -> data['today_startuser'] += $row['startUserToday'];
            $this -> data['yestoday_startuser'] += $row['startUserYestoday'];

            $this -> data['today_newuser'] += $row['newUserToday'];
            $this -> data['yestoday_newuser'] += $row['newUserYestoday'];

            $this -> data['today_startcount'] += $row['startCountToday'];
            $this -> data['yestoday_startcount'] += $row['startCountYestoday'];

            $this -> data['today_totaluser'] += $row['totaluser'];
        }

        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();

        $this -> data['reportTitle'] = array('timePase' => getTimePhaseStr($fromTime, $toTime), 'activeUser' => lang("v_activeUserTrend"), 'newUser' => lang("v_newUserTrend"), 'session' => lang("v_sessoinsTrend"));
        $this -> common -> loadHeaderWithDateControl(lang('m_myapps'));
        $this -> load -> view('main_form', $this -> data);
    }

    /*
     * change time phase and the data stored in the Session
     */
    function changeTimePhase($phase = '7day', $fromTime = '', $toTime = '') {
        $this -> common -> changeTimeSegment($phase, $fromTime, $toTime);
        $ret = array();
        $ret["msg"] = "ok";
        echo json_encode($ret);
    }

    /*
     * Get Console Data by time phase
     */
    function getConsoleDatainfo() {
        $userId = $this -> common -> getUserId();
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $query = $this -> newusermodel -> getAlldataofVisittrends($fromTime, $toTime, $userId);
        $result = $this -> newusermodel -> getAlldataofVisittrends($this -> common -> getPredictiveValurFromTime(), $toTime, $userId);
        $res = $this -> trendmodel -> getPredictiveValur($result);
        $ret["content"] = $query;
        $ret["contentofTrend"] = $res;
        $ret["timeTick"] = $this -> common -> getTimeTick($toTime - $fromTime);
        echo json_encode($ret);
    }

    /*
     * set new version inform
     *
     *
     */
    function setnewversion() {
        $this -> session -> set_userdata('newversion', 'noinform');
        $data = $this -> session -> userdata('newversion');
        echo $data;
    }

}
