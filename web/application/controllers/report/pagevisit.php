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
 * Pagevisit Controller
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Pagevisit extends CI_Controller
{
    private $data = array();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('common');
        $this->common->requireLogin();
        $this->common->requireProduct();
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/pagemodel', 'page');
    }

    /**
     * Index function,load pageview
     *
     * @return void
     */
    function index()
    {
        $this->common->loadHeaderWithDateControl();
        $currentProduct = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $this->data['version'] = $this->page->getallVersionBasicData($fromTime, $toTime, $currentProduct->id);
        $this->load->view('usage/pageview', $this->data);
    }

    /**
     * Addvisitpathreport fucntion,load visit path report 
     *
     * @param string $delete delete
     * @param string $type   type
     *
     * @return void
     */
    function addvisitpathreport($delete = null, $type = null)
    {
        if ($delete == null) {
            $this->data['add'] = "add";
        }
        if ($delete == "del") {
            $this->data['delete'] = "delete";
        }
        if ($type != null) {
            $this->data['type'] = $type;
        }
        $currentProduct = $this->common->getCurrentProduct();
        $result = $this->page->getVersionData($currentProduct->id);
        $this->data['newversion'] = 'noversion';
        if ($result != null && $result->num_rows() > 0) {
            $this->data['version'] = $result;
            $this->data['newversion'] = $result->first_row()->version_name;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/visitpath', $this->data);
    }

    /**
     * GetWeelChart fucntion,load flowchartview
     *
     * @return void
     */
    function getWeelChart()
    {
        $this->load->view("widgets/flowchartview");
    }

    /**
     * GetFlowChart fucntion
     *
     * @param string $version version
     *
     * @return encode json
     */
    function getFlowChart($version)
    {
        if ($version == 'noversion' || $version == null) {
            $rootArray = array('name' => lang('g_noData'),'percentage' => 1,'level' => 0,"children" => array());
            echo json_encode($rootArray);
            return;
        }
        $currentProduct = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $productId = $currentProduct->id;
        $version = trim($version, '');
        $query = $this->page->getFlowData($version, $productId);
        $topLevelArray = array();
        $topLevelData = $this->page->getTopLevelData($version, $productId);
        foreach ($topLevelData->result() as $topRow) {
            $key = $topRow->activity_name;
            $value = $topRow->count;
            $topPercentage = $topRow->percentage;
            /**
             * *****Level1************
             */
            $firstLevelArray = array();
            $firstCount = 0;
            foreach ($query->result() as $firstLevelRow) {
                if ($firstLevelRow->level == 1 && $firstLevelRow->activity_from == $key) {
                    if ($firstCount > 4) {
                        break;
                    }
                    $firstCount ++;
                    /**
                     * *******Level 2***********
                     */
                    $secondLevelArray = array();
                    $secondCount = 0;
                    $firstPercentage = $topPercentage * $firstLevelRow->percentage;
                    foreach ($query->result() as $secondLevelRow) {
                        if ($secondLevelRow->level == 2 && $secondLevelRow->activity_from == $firstLevelRow->activity_to) {
                            if ($secondCount > 4)
                                break;
                            $secondCount ++;
                            
                            /**
                             * ********Level3************
                             */
                            $thirdLevelArray = array();
                            $thirdCount = 0;
                            $secondPercentage = $firstPercentage * $secondLevelRow->percentage;
                            foreach ($query->result() as $thirdLevelRow) {
                                if ($thirdLevelRow->level == 3 && $thirdLevelRow->activity_from == $secondLevelRow->activity_to) {
                                    if ($thirdCount > 4)
                                        break;
                                    $thirdCount ++;
                                    $thirdLevelChild = array('name' => $thirdLevelRow->activity_to . "( " . $thirdLevelRow->count . " , " . round($thirdLevelRow->percentage, 1) * 100 . "% )",'percentage' => $secondPercentage * $thirdLevelRow->percentage,'level' => 3,'size' => $thirdLevelRow->count
                                    );
                                    array_push($thirdLevelArray, $thirdLevelChild);
                                }
                            }
                            /**
                             * *********End Level3***********
                             */
                            $secondLevelChild = array('name' => $secondLevelRow->activity_to . "( " . $secondLevelRow->count . " , " . round($secondLevelRow->percentage, 1) * 100 . "% )",'children' => $thirdLevelArray,'percentage' => $firstPercentage * $secondLevelRow->percentage,'level' => 2,'size' => $secondLevelRow->count
                            );
                            array_push($secondLevelArray, $secondLevelChild);
                        }
                    }
                    /**
                     * *******End Level2***********
                     */
                    $firstLevelChild = array('name' => $firstLevelRow->activity_to . "( " . $firstLevelRow->count . " , " . round($firstLevelRow->percentage, 1) * 100 . "% )",'children' => $secondLevelArray,'percentage' => $topPercentage * $firstLevelRow->percentage,'level' => 1,'size' => $firstLevelRow->count
                    );
                    array_push($firstLevelArray, $firstLevelChild);
                }
            }
            /**
             * *****End Level1*******
             */
            $topLevelChild = array("name" => $key . " ( " . $value . " , " . round($topRow->percentage * 100, 1) . "% )",'children' => $firstLevelArray,'percentage' => $topRow->percentage,'level' => 0,"size" => $value
            );
            array_push($topLevelArray, $topLevelChild);
        }
        if (count($topLevelArray) > 0) {
            $rootArray = array('name' => " ",'percentage' => 1,'level' => 0,"children" => $topLevelArray
            );
        } else {
            $rootArray = array('name' => lang('g_noData'),'percentage' => 1,'level' => 0,"children" => $topLevelArray
            );
        }
        echo json_encode($rootArray);
    }

    /**
     * GetTopLevelData fucntion
     *
     * @param string $query query
     *
     * @return top level arrary
     */
    function getTopLevelData($query)
    {
        $topLevelArray = array();
        foreach ($query->result() as $row) {
            if ($row->level == 1) {
                if (isset($topLevelArray[$row->activity_from])) {
                    $count = intval($topLevelArray["$row->activity_from"]);
                    $count += $row->count;
                    $topLevelArray["$row->activity_from"] = $count;
                } else {
                    $topLevelArray[$row->activity_from] = $row->count;
                }
            }
        }
        return $topLevelArray;
    }

    /**
     * GetPageInfo fucntion
     *
     * @param string $version   version
     * @param int    $pageIndex page index
     * @param string $fromDate  from date
     * @param string $toDate    to date
     *
     * @return encode json
     */
    function getPageInfo($version = "all", $pageIndex = 0, $fromDate = '', $toDate = '')
    {
        $currentProduct = $this->common->getCurrentProduct();
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $rowArray = $this->page->getVersionBasicData($fromTime, $toTime, $currentProduct->id);
        echo json_encode($rowArray);
    }
}