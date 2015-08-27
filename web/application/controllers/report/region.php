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
 * Region Controller
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Region extends CI_Controller
{
    private $_data = array();

    /**
     * Defaultcountry string $_defaultcountry
     */
    private $_defaultcountry = '';

    /**
     * Countrysession int $_countrysession
     */
    private $_countrysession = 0;

    /**
     * Countrynewuser int $_countrynewuser
     */
    private $_countrynewuser = 0;

    /**
     * Prosession int $_prosession
     */
    private $_prosession = 0;

    /**
     * Pronewuser int $_pronewuser
     */
    private $_pronewuser = 0;

    /**
     * Citysession int $_citysession
     */
    private $_citysession = 0;

    /**
     * Citynewuser int $_citynewuser
     */
    private $_citynewuser = 0;

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        $this->default_country = $this->config->item('default_country');
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->model('common');
        $this->load->model('region/regionmodel', 'region');
        $this->load->model('product/productmodel', 'product');
        $this->load->library('pagination');
        $this->load->library('export');
        $this->common->checkCompareProduct();
    }

    /**
     * Index function
     *
     * @return void
     */
    function index()
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->_data['reportTitle'] = array('activeUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10Nations"), $fromTime, $toTime),'newUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10Nations"), $fromTime, $toTime),'regionActiveUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10Provinces"), $fromTime, $toTime),'regionNewUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10Provinces"), $fromTime, $toTime),'citySessionReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10City"), $fromTime, $toTime),'cityNewUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10City"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime)
        );
        
        if (isset($_GET['type']) && $_GET['type'] == 'compare') {
            $this->common->loadCompareHeader();
            $this->load->view('compare/regionview', $this->_data);
        } else {
            $this->common->loadHeaderWithDateControl();
            $currentProduct = $this->common->getCurrentProduct();
            $this->common->requireProduct();
            
            // //country --begin--
            $country = $this->region->getcountrynum($fromTime, $toTime, $currentProduct->id);
            if (! isset($country)) {
                $this->_data['counum'] = 0;
            } else {
                $this->_data['counum'] = count($country);
                foreach ($country as $row) {
                    $this->_countrysession += $row->sessions;
                    $this->_countrynewuser += $row->newusers;
                }
            }
            $countrypagecoun = $this->region->gettotalbycountry($fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS);
            $this->_data['activepagecoun'] = $countrypagecoun;
            $this->_data['country_session'] = $this->_countrysession;
            $this->_data['country_newuser'] = $this->_countrynewuser;
            // country --end--
            
            // pro --begin--
            $country_name = $this->_defaultcountry;
            // //pro
            $pro = $this->region->getpronum($fromTime, $toTime, $currentProduct->id, $country_name);
            if (! isset($pro)) {
                $this->_data['pronum'] = 0;
            } else {
                $this->_data['pronum'] = count($pro);
                foreach ($pro as $row) {
                    $this->_prosession += $row->sessions;
                    $this->_pronewuser += $row->newusers;
                }
            }
            
            $activepagepro = $this->region->gettotalbypro($fromTime, $toTime, $currentProduct->id, $country_name, 0, PAGE_NUMS);
            $this->_data['activepagepro'] = $activepagepro;
            $this->_data['pro_session'] = $this->_prosession;
            $this->_data['pro_newuser'] = $this->_pronewuser;
            // pro --end--
            
            // //city --begin--
            $city = $this->region->getcitynum($fromTime, $toTime, $currentProduct->id, $country_name);
            if (! isset($city))
                $this->_data['citynum'] = 0;
            else {
                $this->_data['citynum'] = count($city);
                foreach ($city as $row) {
                    $this->_citysession += $row->sessions;
                    $this->_citynewuser += $row->newusers;
                }
            }
            $pagecity = $this->region->gettotlebycity($fromTime, $toTime, $currentProduct->id, $country_name, 0, PAGE_NUMS);
            $this->_data['activepagecity'] = $pagecity;
            $this->_data['city_session'] = $this->_citysession;
            $this->_data['city_newuser'] = $this->_citynewuser;
            // //city --end--
            
            $this->_data['from'] = $fromTime;
            $this->_data['to'] = $toTime;
            $this->load->view('usage/regionview', $this->_data);
        }
    }

    /**
     * Addregioncountryreport function
     *
     * @param string $delete delete
     * @param string $type   type
     *            
     * @return void
     */
    function addregioncountryreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['reportTitle'] = array('activeUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10Nations"), $fromTime, $toTime),'newUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10Nations"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime));
        if ($delete == null) {
            $this->data['add'] = "add";
        }
        if ($delete == "del") {
            $this->data['delete'] = "delete";
        }
        if ($type != null) {
            $this->data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/regioncountry', $this->data);
    }

    /**
     * Addregionprovincereport
     *
     * @param string $delete delete
     * @param string $type   type
     *            
     * @return void
     */
    function addregionprovincereport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['reportTitle'] = array('regionActiveUserReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10Provinces"), $fromTime, $toTime),'regionNewUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10Provinces"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime)
        );
        if ($delete == null) {
            $this->data['add'] = "add";
        }
        if ($delete == "del") {
            $this->data['delete'] = "delete";
        }
        if ($type != null) {
            $this->data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/regionprovince', $this->data);
    }

    /**
     * Addregioncityreport
     *
     * @param string $delete delete
     * @param string $type   type
     *            
     * @return void
     */
    function addregioncityreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['reportTitle'] = array('citysessionsReport' => getReportTitle(lang("t_sessions") . " " . lang("v_rpt_re_top10City"), $fromTime, $toTime),'citynewUserReport' => getReportTitle(lang("t_newUsers") . " " . lang("v_rpt_re_top10City"), $fromTime, $toTime),'timePhase' => getTimePhaseStr($fromTime, $toTime));
        if ($delete == null) {
            $this->data['add'] = "add";
        }
        if ($delete == "del") {
            $this->data['delete'] = "delete";
        }
        if ($type != null) {
            $this->data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/regioncity', $this->data);
    }

    /**
     * GetCountryData
     *
     * @return json
     */
    function getCountryData()
    {
        $currentProduct = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        if (empty($currentProduct)) {
            $products = $this->common->getCompareProducts();
            if (empty($products)) {
                $this->common->requireProduct();
                return;
            }
            for ($i = 0; $i < count($products); $i ++) {
                $sessiondata = $this->region->getsessionbycountrytop($fromTime, $toTime, $products[$i]->id);
                $newdata = $this->region->getnewbycountrytop($fromTime, $toTime, $products[$i]->id);
                $ret["activeUserData" . $products[$i]->name] = $this->change2StandardPrecent($sessiondata, "country", 1);
                $ret["newUserData" . $products[$i]->name] = $this->change2StandardPrecent($newdata, "country", 2);
            }
        } else {
            // new user National distribution
            $this->common->requireProduct();
            $newUserData = $this->region->getnewbycountrytop($fromTime, $toTime, $currentProduct->id);
            $activeUserData = $this->region->getsessionbycountrytop($fromTime, $toTime, $currentProduct->id);
            $ret["activeUserData"] = $this->change2StandardPrecent($activeUserData, "country", 1);
            $ret["newUserData"] = $this->change2StandardPrecent($newUserData, "country", 2);
        }
        
        echo json_encode($ret);
    }

    /**
     * GetRegionData
     *
     * @return json
     */
    function getRegionData()
    {
        $currentProduct = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $country = $this->default_country;
        if (empty($currentProduct)) {
            $products = $this->common->getCompareProducts();
            if (empty($products)) {
                $this->common->requireProduct();
                return;
            }
            for ($i = 0; $i < count($products); $i ++) {
                $activedata = $this->region->getsessionbyregiontop($fromTime, $toTime, $products[$i]->id, $country);
                $newdata = $this->region->getnewuserbyregiontop($fromTime, $toTime, $products[$i]->id, $country);
                $ret["regionSessionData" . $products[$i]->name] = $this->change2StandardPrecent($activedata, "region", 1);
                $ret["regionNewUserData" . $products[$i]->name] = $this->change2StandardPrecent($newdata, "region", 2);
            }
        } else {
            // new user National distribution
            $this->common->requireProduct();
            $sessionData = $this->region->getsessionbyregiontop($fromTime, $toTime, $currentProduct->id, $country);
            $newUserData = $this->region->getnewuserbyregiontop($fromTime, $toTime, $currentProduct->id, $country);
            $ret["regionSessionData"] = $this->change2StandardPrecent($sessionData, "region", 1);
            $ret["regionNewUserData"] = $this->change2StandardPrecent($newUserData, "region", 2);
        }
        echo json_encode($ret);
    }

    /**
     * GetCityData
     *
     * @return json
     */
    function getCityData()
    {
        $currentProduct = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        if (empty($currentProduct)) {
            $products = $this->common->getCompareProducts();
            if (empty($products)) {
                $this->common->requireProduct();
                return;
            }
            for ($i = 0; $i < count($products); $i ++) {
                $activedata = $this->region->getsessionbycitytop($fromTime, $toTime, $products[$i]->id);
                $newdata = $this->region->getnewuserbycitytop($fromTime, $toTime, $products[$i]->id);
                $ret["citySessionData" . $products[$i]->name] = $this->change2StandardPrecent($activedata, "city", 1);
                $ret["cityNewUserData" . $products[$i]->name] = $this->change2StandardPrecent($newdata, "city", 2);
            }
        } else {
            // new user National distribution
            $this->common->requireProduct();
            $sessionData = $this->region->getsessionbycitytop($fromTime, $toTime, $currentProduct->id);
            $newUserData = $this->region->getnewuserbycitytop($fromTime, $toTime, $currentProduct->id);
            $ret["citySessionData"] = $this->change2StandardPrecent($sessionData, "city", 1); // $sessionData->result_array();
            $ret["cityNewUserData"] = $this->change2StandardPrecent($newUserData, "city", 2); // $newUserData->result_array();
        }
        
        echo json_encode($ret);
    }

    /**
     * Change2StandardPrecent
     *
     * @param string $userData userData
     * @param string $type     type
     * @param string $datatype datatype
     *            
     * @return array
     */
    function change2StandardPrecent($userData, $type, $datatype)
    {
        $userDataArray = array();
        $userDataObj = array();
        $totalPercent = 0;
        $numTotal = 0;
        foreach ($userData->result() as $row) {
            if ($datatype == 1)
                $numTotal += $row->sessions;
            if ($datatype == 2)
                $numTotal += $row->newusers;
        }
        
        foreach ($userData->result() as $row) {
            if (count($userData) > 10) {
                break;
            }
            if ($type == "country") {
                $userDataObj["country_name"] = $row->country_name;
            }
            if ($type == "region") {
                $userDataObj["region_name"] = $row->region_name;
            }
            if ($type == "city") {
                $userDataObj["city_name"] = $row->city_name;
            }
            
            if ($datatype == 1) {
                $userDataObj["sessions"] = $row->sessions / 1;
                $percent = round($row->sessions / $numTotal * 100, 1);
                $totalPercent += $percent;
                $userDataObj["percentage"] = $percent;
            }
            
            if ($datatype == 2) {
                $userDataObj["newusers"] = $row->newusers / 1;
                $percent = round($row->newusers / $numTotal * 100, 1);
                $totalPercent += $percent;
                $userDataObj["percentage"] = $percent;
            }
            
            array_push($userDataArray, $userDataObj);
        }
        
        if ($totalPercent < 100.0) {
            $remainPercent = round(100 - $totalPercent, 2);
            if ($type == "country") {
                $userDataObj["country_name"] = lang('g_others');
            }
            if ($type == "region") {
                $userDataObj["region_name"] = lang('g_others');
            }
            if ($type == "city") {
                $userDataObj["city_name"] = lang('g_others');
            }
            $userDataObj["percentage"] = $remainPercent;
            if ($datatype == 1) {
                $userDataObj["sessions"] = 0;
            }
            if ($datatype == 2) {
                $userDataObj["newusers"] = 0;
            }
            array_push($userDataArray, $userDataObj);
        }
        
        return $userDataArray;
    }

    /**
     * Regioninfo
     *
     * @param string $timePhase timePhase
     * @param string $fromDate  fromDate
     * @param string $toDate    toDate
     *            
     * @return void
     */
    function regioninfo($timePhase, $fromDate = '', $toDate = '')
    {
        $this->common->loadHeader();
        $country = $this->_defaultcountry;
        $currentProduct = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        
        // active user national distribution
        $activecountry = $this->region->getsessionbycountry($fromTime, $toTime, $currentProduct->id);
        if ($activecountry != null && $activecountry->num_rows() > 0) {
            $this->data['activecountry'] = $activecountry;
            $this->data['counum'] = $this->region->getcountrynum($fromTime, $toTime, $currentProduct->id);
            $activepagecoun = $this->region->getsessionbycountry($fromTime, $toTime, $currentProduct->id, 0, PAGE_NUMS);
            $this->data['activepagecoun'] = $activepagecoun;
        } else {
            $this->data['counum'] = 0;
        }
        
        // active user province distribution
        $activepro = $this->region->getactivebypro($fromTime, $toTime, $currentProduct->id, $country);
        if ($activepro != null && $activepro->num_rows() > 0) {
            $this->data['activepro'] = $activepro;
            $pro = $this->region->getpronum($fromTime, $toTime, $currentProduct->id, $country);
            if (! isset($pro)) {
                $this->data['pronum'] = 0;
            } else {
                $this->data['pronum'] = count($pro);
            }
            $activepagepro = $this->region->getactivebypro($fromTime, $toTime, $currentProduct->id, $country, 0, PAGE_NUMS);
            $this->data['activepagepro'] = $activepagepro;
        } else {
            $this->data['pronum'] = 0;
        }
        
        $this->data['from'] = $fromTime;
        $this->data['to'] = $toTime;
        $this->load->view('usage/regionview', $this->data);
    }

    /**
     * Activecountrypage
     *
     * @param string $pagenum pagenum
     *            
     * @return string
     */
    function activecountrypage($pagenum)
    {
        $percent = 100;
        $currentProduct = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $country = $this->region->getcountrynum($fromTime, $toTime, $currentProduct->id);
        if (! isset($country)) {
            $this->_countrysession = 0;
            $this->_countrynewuser = 0;
        } else {
            foreach ($country as $row) {
                $this->_countrysession += $row->sessions;
                $this->_countrynewuser += $row->newusers;
            }
        }
        $pagenum = $pagenum * PAGE_NUMS;
        $activepagecoun = $this->region->gettotalbycountry($fromTime, $toTime, $currentProduct->id, $pagenum, PAGE_NUMS);
        $htmlText = "";
        if ($activepagecoun != null && $activepagecoun->num_rows() > 0) {
            foreach ($activepagecoun->result_array() as $row) {
                $htmlText = $htmlText . "<tr>";
                $htmlText = $htmlText . "<td>" . $row['country_name'] . "</td>";
                $htmlText = $htmlText . "<td>" . $row['sessions'] . "</td>";
                if ($this->country_session > 0)
                    $htmlText = $htmlText . "<td>" . round($percent * $row['sessions'] / $this->country_session, 1) . "%</td>";
                else {
                    $htmlText = $htmlText . "<td>0%</td>";
                }
                $htmlText = $htmlText . "<td>" . $row['newusers'] . "</td>";
                if ($this->country_newuser > 0)
                    $htmlText = $htmlText . "<td>" . round($percent * $row['newusers'] / $this->country_newuser, 1) . "%</td>";
                else {
                    $htmlText = $htmlText . "<td>0%</td>";
                }
                
                $htmlText = $htmlText . "</tr>";
            }
            echo $htmlText;
        }
    }

    /**
     * Activepropage
     *
     * @param string $pagenum pagenum
     *            
     * @return string
     */
    function activepropage($pagenum)
    {
        $country = $this->default_country;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $percent = 100;
        $currentProduct = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        
        $pro = $this->region->getpronum($fromTime, $toTime, $currentProduct->id, $country);
        if (! isset($pro)) {
            $this->_prosession = 0;
            $this->_pronewuser = 0;
        } else {
            foreach ($pro as $row) {
                $this->_prosession += $row->sessions;
                $this->_pronewuser += $row->newusers;
            }
        }
        $pagenum = $pagenum * PAGE_NUMS;
        $activepagepro = $this->region->gettotalbypro($fromTime, $toTime, $currentProduct->id, $country, $pagenum, PAGE_NUMS);
        $htmlText = "";
        if ($activepagepro != null && $activepagepro->num_rows() > 0) {
            foreach ($activepagepro->result_array() as $row) {
                $htmlText = $htmlText . "<tr>";
                $htmlText = $htmlText . "<td>" . $row['region_name'] . "</td>";
                $htmlText = $htmlText . "<td>" . round($row['sessions'], 1) . "</td>";
                if ($this->pro_session > 0)
                    $htmlText = $htmlText . "<td>" . round($percent * $row['sessions'] / $this->pro_session, 1) . "%</td>";
                else {
                    $htmlText = $htmlText . "<td>" . "0%</td>";
                }
                
                $htmlText = $htmlText . "<td>" . round($row['newusers'], 1) . "</td>";
                if ($this->pro_newuser > 0)
                    $htmlText = $htmlText . "<td>" . round($percent * $row['newusers'] / $this->pro_newuser, 1) . "%</td>";
                else {
                    $htmlText = $htmlText . "<td>0%</td>";
                }
                $htmlText = $htmlText . "</tr>";
            }
            echo $htmlText;
        }
    }

    /**
     * Activecitypage
     *
     * @param string $pagenum pagenum
     *            
     * @return string
     */
    function activecitypage($pagenum)
    {
        $country = $this->default_country;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $currentProduct = $this->common->getCurrentProduct();
        
        $city = $this->region->getcitynum($fromTime, $toTime, $currentProduct->id, $country);
        if (! isset($city)) {
            $this->city_session = 0;
            $this->city_newuser = 0;
        } else {
            foreach ($city as $row) {
                $this->city_session += $row->sessions;
                $this->city_newuser += $row->newusers;
            }
        }
        
        $percent = 100;
        $this->common->requireProduct();
        $pagenum = $pagenum * PAGE_NUMS;
        $activepagepro = $this->region->gettotlebycity($fromTime, $toTime, $currentProduct->id, $country, $pagenum, PAGE_NUMS);
        $htmlText = "";
        if ($activepagepro != null && $activepagepro->num_rows() > 0) {
            foreach ($activepagepro->result_array() as $row) {
                $htmlText = $htmlText . "<tr>";
                $htmlText = $htmlText . "<td>" . $row['city_name'] . "</td>";
                $htmlText = $htmlText . "<td>" . round($row['sessions'], 1) . "</td>";
                if ($this->city_session > 0)
                    $htmlText = $htmlText . "<td>" . round($percent * $row['sessions'] / $this->city_session, 1) . "%</td>";
                else {
                    $htmlText = $htmlText . "<td>0%</td>";
                }
                
                $htmlText = $htmlText . "<td>" . $row['newusers'] . "</td>";
                if ($this->city_newuser > 0)
                    $htmlText = $htmlText . "<td>" . round($percent * $row['newusers'] / $this->city_newuser, 1) . "%</td>";
                else {
                    $htmlText = $htmlText . "<td>" . "0%</td>";
                }
                $htmlText = $htmlText . "</tr>";
            }
            echo $htmlText;
        }
    }

    /**
     * ExportCSV
     *
     * @param string $label label
     *            
     * @return void
     */
    function exportCSV($label)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $products = $this->common->getCompareProducts();
        if (empty($products)) {
            $this->common->requireProduct();
            return;
        }
        $this->load->library('export');
        $export = new Export();
        if ($label == "country") {
            $titlename = getExportReportTitle("Compare", lang("v_rpt_re_top10Nations"), $fromTime, $toTime);
        }
        if ($label == "region") {
            $titlename = getExportReportTitle("Compare", lang("v_rpt_re_top10Provinces"), $fromTime, $toTime);
        }
        if ($label == "city") {
            $titlename = getExportReportTitle("Compare", lang("v_rpt_re_top10City"), $fromTime, $toTime);
        }
        
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $export->setFileName($titlename);
        $j = 0;
        $mk = 0;
        $title[$j ++] = iconv("UTF-8", "GBK", lang('t_sessions'));
        $space[$mk ++] = ' ';
        for ($i = 0; $i < count($products); $i ++) {
            $title[$j ++] = iconv("UTF-8", "GBK", $products[$i]->name);
            $title[$j ++] = '';
            $title[$j ++] = '';
            $space[$mk ++] = ' ';
            $space[$mk ++] = ' ';
            $space[$mk ++] = ' ';
        }
        $export->setTitle($title);
        $k = 0;
        $maxlength = 0;
        $maxlength2 = 0;
        $j = 0;
        $nextlabel[$j ++] = lang('t_newUsers');
        for ($m = 0; $m < count($products); $m ++) {
            if ($label == "country") {
                $activedata = $this->region->getsessionbycountrytop($fromTime, $toTime, $products[$m]->id);
                $newdata = $this->region->getnewbycountrytop($fromTime, $toTime, $products[$m]->id);
            }
            if ($label == "region") {
                $country = $this->default_country;
                $activedata = $this->region->getsessionbyregiontop($fromTime, $toTime, $products[$m]->id, $country);
                $newdata = $this->region->getnewuserbyregiontop($fromTime, $toTime, $products[$m]->id, $country);
            }
            if ($label == "city") {
                $activedata = $this->region->getsessionbycitytop($fromTime, $toTime, $products[$m]->id);
                $newdata = $this->region->getnewuserbycitytop($fromTime, $toTime, $products[$m]->id);
            }
            $detailData[$m] = $this->change2StandardPrecent($activedata, $label, 1);
            $detailNewData[$m] = $this->change2StandardPrecent($newdata, $label, 2);
            if (count($detailData[$m]) > $maxlength) {
                $maxlength = count($detailData[$m]);
            }
            if (count($detailNewData[$m]) > $maxlength2) {
                $maxlength2 = count($detailNewData[$m]);
            }
            $nextlabel[$j ++] = $products[$m]->name;
            $nextlabel[$j ++] = ' ';
        }
        $this->getExportRowData($export, $maxlength, $detailData, $products, $label, 1);
        $export->addRow($space);
        $export->addRow($nextlabel);
        $this->getExportRowData($export, $maxlength2, $detailNewData, $products, $label, 2);
        $export->export();
        die();
    }

    /**
     * GetExportRowData
     *
     * @param string $export   export
     * @param string $length   length
     * @param string $userData userData
     * @param string $products products
     * @param string $label    label
     * @param int    $type     type
     *            
     * @return void
     */
    function getExportRowData($export, $length, $userData, $products, $label, $type)
    {
        $k = 0;
        for ($i = 0; $i < $length; $i ++) {
            $result[$k ++] = $i + 1;
            for ($j = 0; $j < count($products); $j ++) {
                $obj = $userData[$j];
                if ($i >= count($obj)) {
                    $result[$k ++] = '';
                    $result[$k ++] = '';
                    $result[$k ++] = '';
                } else {
                    $name = $label . '_name';
                    if ($obj[$i][$name] == '') {
                        $result[$k ++] = 'unknow';
                    } else {
                        $result[$k ++] = $obj[$i][$name];
                    }
                    
                    if ($type == 1)
                        $result[$k ++] = $obj[$i]['sessions'];
                    if ($type == 2)
                        $result[$k ++] = $obj[$i]['newusers'];
                    
                    $result[$k ++] = $obj[$i]['percentage'] . "%";
                }
            }
            $export->addRow($result);
            $k = 0;
        }
    }

    /**
     * Exportcountry
     *
     * @return void
     */
    function exportcountry()
    {
        $currentProduct = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        
        $fromTime = $this->product->getReportStartDate($currentProduct, $fromTime);
        $fromTime = date("Y-m-d", strtotime($fromTime));
        
        $country = $this->region->getcountrynum($fromTime, $toTime, $currentProduct->id);
        $country_session = 0;
        $country_newuser = 0;
        if (isset($country)) {
            foreach ($country as $row) {
                $country_session += $row->sessions;
                $country_newuser += $row->newusers;
            }
        }
        $activecountry = $this->region->getcountryexport($fromTime, $toTime, $currentProduct->id);
        if ($activecountry != null && $activecountry->num_rows() > 0) {
            
            $data = $activecountry;
            $titlename = getExportReportTitle($currentProduct->name, lang("v_rpt_re_detailsOfNation"), $fromTime, $toTime);
            $titlename = iconv("UTF-8", "GBK", $titlename);
            $this->export->setFileName($titlename);
            $excel_title = array(iconv("UTF-8", "GBK", lang("v_rpt_re_nation")),iconv("UTF-8", "GBK", lang("t_sessions")),iconv("UTF-8", "GBK", lang("t_sessionsP")),iconv("UTF-8", "GBK", lang("t_newUsers")),iconv("UTF-8", "GBK", lang("t_newUsersP"))
            );
            $this->export->setTitle($excel_title);
            foreach ($data->result() as $row) {
                $rowadd['country_name'] = $row->country_name;
                $rowadd['sessions'] = $row->sessions;
                $rowadd['sessions_p'] = ($country_session > 0) ? round(100 * $row->sessions / $country_session, 1) . '%' : '0%';
                $rowadd['newusers'] = $row->newusers;
                $rowadd['newusers_p'] = ($country_newuser > 0) ? round(100 * $row->newusers / $country_newuser, 1) . '%' : '0%';
                
                $this->export->addRow($rowadd);
            }
            $this->export->export();
            die();
        } else {
            $this->load->view("usage/nodataview");
        }
    }

    /**
     * Exportpro
     *
     * @return void
     */
    function exportpro()
    {
        $country = $this->default_country;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $currentProduct = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        
        $fromTime = $this->product->getReportStartDate($currentProduct, $fromTime);
        $fromTime = date("Y-m-d", strtotime($fromTime));
        
        $pro = $this->region->getpronum($fromTime, $toTime, $currentProduct->id, $country);
        $pro_session = 0;
        $pro_newuser = 0;
        if (isset($pro)) {
            foreach ($pro as $row) {
                $pro_session += $row->sessions;
                $pro_newuser += $row->newusers;
            }
        }
        
        $activepro = $this->region->getproexport($fromTime, $toTime, $currentProduct->id, $country);
        
        if ($activepro != null && $activepro->num_rows() > 0) {
            $data = $activepro;
            $titlename = getExportReportTitle($currentProduct->name, lang("v_rpt_re_detailsOfProvince"), $fromTime, $toTime);
            $titlename = iconv("UTF-8", "GBK", $titlename);
            $this->export->setFileName($titlename);
            
            $excel_title = array(iconv("UTF-8", "GBK", lang("v_rpt_re_province")),iconv("UTF-8", "GBK", lang("t_sessions")),iconv("UTF-8", "GBK", lang("t_sessionsP")),iconv("UTF-8", "GBK", lang("t_newUsers")),iconv("UTF-8", "GBK", lang("t_newUsersP"))
            );
            $this->export->setTitle($excel_title);
            // $fields = array ();
            // foreach ( $data->list_fields () as $field ) {
            // array_push ( $fields, $field );
            // }
            // $this->export->setTitle ( $fields );
            foreach ($data->result() as $row) {
                $rowadd['region_name'] = $row->region_name;
                $rowadd['sessions'] = $row->sessions;
                $rowadd['sessions_p'] = ($pro_session > 0) ? round(100 * $row->sessions / $pro_session, 1) . '%' : '0%';
                $rowadd['newusers'] = $row->newusers;
                $rowadd['newusers_p'] = ($pro_newuser > 0) ? round(100 * $row->newusers / $pro_newuser, 1) . '%' : '0%';
                
                $this->export->addRow($rowadd);
            }
            $this->export->export();
            die();
        } else {
            $this->load->view("usage/nodataview");
        }
    }

    /**
     * Exportcity
     *
     * @return void
     */
    function exportcity()
    {
        $country = $this->default_country;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $currentProduct = $this->common->getCurrentProduct();
        $this->common->requireProduct();
        $fromTime = $this->product->getReportStartDate($currentProduct, $fromTime);
        $fromTime = date("Y-m-d", strtotime($fromTime));
        
        $city = $this->region->getcitynum($fromTime, $toTime, $currentProduct->id, $country);
        $city_session = 0;
        $city_newuser = 0;
        if (isset($city)) {
            foreach ($city as $row) {
                $city_session += $row->sessions;
                $city_newuser += $row->newusers;
            }
        }
        
        $activepro = $this->region->getcityexport($fromTime, $toTime, $currentProduct->id, $country);
        if ($activepro != null && $activepro->num_rows() > 0) {
            $data = $activepro;
            $titlename = getExportReportTitle($currentProduct->name, lang("v_rpt_re_detailsOfCity"), $fromTime, $toTime);
            $titlename = iconv("UTF-8", "GBK", $titlename);
            $this->export->setFileName($titlename);
            // $fields = array ();
            // foreach ( $data->list_fields () as $field ) {
            // array_push ( $fields, $field );
            // }
            // $this->export->setTitle ( $fields );
            $excel_title = array(iconv("UTF-8", "GBK", lang("v_rpt_re_city")),iconv("UTF-8", "GBK", lang("t_sessions")),iconv("UTF-8", "GBK", lang("t_sessionsP")),iconv("UTF-8", "GBK", lang("t_newUsers")),iconv("UTF-8", "GBK", lang("t_newUsersP"))
            );
            $this->export->setTitle($excel_title);
            
            foreach ($data->result() as $row) {
                $rowadd['city_name'] = $row->city_name;
                $rowadd['sessions'] = $row->sessions;
                $rowadd['session_p'] = ($city_session > 0) ? round(100 * $row->sessions / $city_session, 1) . '%' : '0%';
                $rowadd['newusers'] = $row->newusers;
                $rowadd['newuser_p'] = ($city_newuser > 0) ? round(100 * $row->newusers / $city_newuser, 1) . '%' : '0%';
                $this->export->addRow($rowadd);
            }
            
            $this->export->export();
            die();
        } else {
            $this->load->view("usage/nodataview");
        }
    }
}
?>
