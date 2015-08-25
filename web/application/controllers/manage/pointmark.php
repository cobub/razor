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
 * Pointmark Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Pointmark extends CI_Controller
{

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('point_mark', 'pointmark');
        $this->load->model('common');
        $this->common->requireLogin();
        $this->load->helper('url');
    }

    /**
     * AddPointmark function
     * add a point mark
     *
     * @return json
     */
    public function addPointmark()
    {
        $product = $this->common->getCurrentProduct();
        if ($product == null) {
            echo json_encode('error product');
            return;
        }
        $productid = $product->id;
        $userid = $this->common->getUserId();
        $title = $_POST['title'];
        $description = $_POST['description'];
        $markdate = $_POST['markdate'];
        $rights = $_POST['rights'];
        $type = $_POST['type'];
        $data = array(
                'title' => $title,
                'description' => $description,
                'userid' => $userid,
                'productid' => $productid,
                'private' => $rights,
                'marktime' => $markdate
        );
        if ('modify' == $type) {
            if (! $this->pointmark->ifcaninsert($userid, $productid, $markdate)) {
                $affectRow = $this->pointmark->modifyPointmark($data, $userid, $productid, $markdate);
                if ($affectRow > 0) {
                    echo json_encode('mdok');
                    return;
                }
                echo json_encode('nochange');
                return;
            }
        }
        if (! $this->pointmark->ifcaninsert($userid, $productid, $markdate)) {
            echo json_encode('exists');
            return;
        }
        $affectRow = $this->pointmark->addPointmark($data);
        if ($affectRow > 0) {
            echo json_encode('ok');
        }
    }
    
    /**
     * Ifcaninsert function
     * Check if exists
     *
     * @return json
     */
    public function ifcaninsert()
    {
        $product = $this->common->getCurrentProduct();
        if ($product == null) {
            echo json_encode('error product');
            return;
        }
        $productid = $product->id;
        $userid = $this->common->getUserId();
        $date = $_GET['date'];
        if (! $this->pointmark->ifcaninsert($userid, $productid, $date)) {
            echo json_encode('exists');
            return;
        }
        echo json_encode('ok');
    }
    
    /**
     * RemovePointmark function
     * Remove a point mark
     *
     * @return json
     */
    public function removePointmark()
    {
        $pointid = $_POST['id'];
        $date = $_POST['date'];
        $product = $this->common->getCurrentProduct();
        if ($product == null) {
            echo json_encode('error product');
            return;
        }
        $productid = $product->id;
        $userid = $this->common->getUserId();
        if ($this->pointmark->ifcaninsert($userid, $productid, $date) == 1) {
            echo json_encode('noexists');
            return;
        }
        if ($this->pointmark->removePointmark($userid, $productid, $date)) {
            echo json_encode('delok');
            return;
        }
        echo json_encode('othererror');
    }
    
    /**
     * Listmarkeventspage function
     * manage all point marks
     *
     * @return json
     */
    public function listmarkeventspage()
    {
        $fromTime = $this->common->getFromTime();
        $toreTime = $this->common->getToTime();
        $product = $this->common->getCurrentProduct();
        if ($product == null) {
            echo json_encode('error product');
            return;
        }
        $productid = $product->id;
        $userid = $this->common->getUserId();
        $ponitevents = $this->pointmark->managePointmarkpagelist($userid, $productid, $fromTime, $toreTime);
        $data['ponitevents'] = $ponitevents->result_array();
        $this->common->loadHeaderWithDateControl();
        $this->load->view('manage/pointmark', $data);
    }
}
?>