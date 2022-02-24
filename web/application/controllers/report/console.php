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
 * Console Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Console extends CI_Controller
{

    /**
     * Data array $data
     */
    private $_data = array();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->Model('common');
        $this->load->model('channelmodel', 'channel');
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/productanalyzemodel');
        $this->load->model('product/newusermodel', 'newusermodel');
        $this->load->model('analysis/trendandforecastmodel', 'trendmodel');
        $this->common->requireLogin();
    }

    /**
     * Index
     *
     * @return void
     */
    function index()
    {
        $this->common->cleanCurrentProduct();
        $this->_data['category'] = $this->product->getProductCategory();
        $this->_data['user_id'] = $this->common->getUserId();
        $this->_data["guest_roleid"] = $this->common->getUserRoleById($this->_data['user_id']);
        $today = date('Y-m-d', time());
        $yestoday = date("Y-m-d", strtotime("-1 day"));
        $query = $this->product->getProductListByPlatform(1, $this->_data['user_id'], $today, $yestoday);
        $this->_data['androidList'] = $query;
        // active users num
        $this->_data['today_startuser'] = 0;
        $this->_data['yestoday_startuser'] = 0;
        
        // new users num
        $this->_data['today_newuser'] = 0;
        $this->_data['yestoday_newuser'] = 0;
        
        // session num
        $this->_data['today_startcount'] = 0;
        $this->_data['yestoday_startcount'] = 0;
        
        $this->_data['today_totaluser'] = 0;
        
        for ($i = 0; $i < count($this->_data['androidList']); $i ++) {
            $row = $this->_data['androidList'][$i];
            $this->_data['today_startuser'] += $row['startUserToday'];
            $this->_data['yestoday_startuser'] += intval($row['startUserYestoday']);
            
            $this->_data['today_newuser'] += $row['newUserToday'];
            $this->_data['yestoday_newuser'] += intval($row['newUserYestoday']);
            
            $this->_data['today_startcount'] += $row['startCountToday'];
            $this->_data['yestoday_startcount'] += intval($row['startCountYestoday']);
            
            $this->_data['today_totaluser'] += $row['totaluser'];
        }
        
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        
        $this->_data['reportTitle'] = array(
                'timePase' => getTimePhaseStr($fromTime, $toTime),
                'activeUser' => lang("v_activeUserTrend"),
                'newUser' => lang("v_newUserTrend"),
                'session' => lang("v_sessoinsTrend")
        );
        $this->common->loadHeaderWithDateControl(lang('m_myapps'));
        $this->load->view('main_form', $this->_data);
    }

    /**
     * ChangeTimePhase change time phase and the data stored in the Session
     *
     * @param string $phase    phase
     * @param string $fromTime fromTime
     * @param string $toTime   toTime
     *            
     * @return json
     */
    function changeTimePhase($phase = '7day', $fromTime = '', $toTime = '')
    {
        $this->common->changeTimeSegment($phase, $fromTime, $toTime);
        $ret = array();
        $ret["msg"] = "ok";
        echo json_encode($ret);
    }

    /**
     * GetConsoleDatainfo Get Console Data by time phase
     *
     * @return json
     */
    function getConsoleDatainfo()
    {
        $userId = $this->common->getUserId();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $query = $this->newusermodel->getAlldataofVisittrends($fromTime, $toTime, $userId);
        $result = $this->newusermodel->getAlldataofVisittrends($this->common->getPredictiveValurFromTime(), $toTime, $userId);
        $res = $this->trendmodel->getPredictiveValur($result);
        $ret["content"] = $query;
        $ret["contentofTrend"] = $res;
        $ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime);
        echo json_encode($ret);
    }

    /**
     * Setnewversion set new version inform
     *
     * @return json
     */
    function setnewversion()
    {
        $this->session->set_userdata('newversion', 'noinform');
        $data = $this->session->userdata('newversion');
        echo $data;
    }
}
