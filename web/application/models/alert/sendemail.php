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
 * Sende Mail
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class SendEmail extends CI_Model
{

    /**
     * Construct funciton, to pre-load database,model and library configuration
     *
     * @return void
     */
    function __construct() 
    {
        $this->load->database();
        $this->load->model('user/ums_user', 'ums_user');
        $this->load->model('comparevalue/compare', 'compare');
        $this->load->model('product/newusermodel', 'newusermodel');
        $this->load->model('analysis/trendandforecastmodel', 'gettrend');
        $this->load->model('product/productmodel', 'product');
        $this->load->library('email');
        $this->config->load('email');
    }

    /**
     * CompareValue function
     *
     * @param date $date ldate
     *
     * @return void
     */
    function comparevalue($date)
    {
        $trendFromday = date('Y-m-d', strtotime("-5 day", strtotime($date)));
        $timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
        $emailtime = date('Y-m-d', $timezonestimestamp);
        $alertlab = array();
        $alertlab = $this->compare->getAllAlertlab();
        for ($i=0;$i<count($alertlab);$i++) {
            $label =$alertlab[$i]['label'];
            $condition = $alertlab[$i]['condition'];
            $productid=$alertlab[$i]['productid'];
            $productnamearr = $this-> product->getProductName($productid);
            $productnamearr = $productnamearr->result_array();
            $productname=$productnamearr[0]['name'];
            $query = $this->newusermodel->getallUserDataBy($date, $date, $productid);
            $ret = $query->result_array();
            $trendquery = $this->newusermodel->getallUserDataBy($trendFromday, $date, $productid);
            $trendret = $trendquery->result_array();
            $result = $this->gettrend->getPredictiveValur($trendret);
            $url=site_url();
            if ($label=="t_newUser") {
                $lessvalue= abs($result[0]['newusers']-$ret[0]['newusers']);
                $shijifudong= round($lessvalue/$result[0]['newusers'], 2)*100;
                $msg = $this->emailText($productname, lang('t_newUsers'), $ret[0]['newusers'], $result[0]['newusers'], $condition, $url, $emailtime, $shijifudong);
                $this->sendEmailornot($label, $ret[0]['newusers'], $lessvalue, $result[0]['newusers'], $condition, $msg);
            }
            if ($label=="t_activeUser") {
                $lessvalue= abs($result[0]['startusers']-$ret[0]['startusers']);
                $shijifudong= round($lessvalue/$result[0]['startusers'], 2)*100;
                $msg = $this->emailText($productname, lang('t_activeUser'), $ret[0]['startusers'], $result[0]['startusers'], $condition, $url, $emailtime, $shijifudong);
                $this->sendEmailornot($label, $ret[0]['startusers'], $lessvalue, $result[0]['startusers'], $condition, $msg);
            }
            if ($label=="t_sessions") {
                $lessvalue= abs($result[0]['sessions']-$ret[0]['sessions']);
                $shijifudong= round($lessvalue/$result[0]['sessions'], 2)*100;
                $msg = $this->emailText(
                    $productname, 
                    lang('t_sessions'), 
                    $ret[0]['sessions'], $result[0]['sessions'], $condition, 
                    $url, $emailtime, $shijifudong
                );
                $this->sendEmailornot($label, $ret[0]['sessions'], $lessvalue, $result[0]['sessions'], $condition, $msg);
            }
            if ($label=="t_accumulatedUsers") {
                $lessvalue= abs($result[0]['allusers']-$ret[0]['allusers']);
                $shijifudong= round($lessvalue/$result[0]['allusers'], 2)*100;
                $msg = $this->emailText($productname, lang('t_accumulatedUsers'), $ret[0]['allusers'], $result[0]['allusers'], $condition, $url, $emailtime, $shijifudong);
                $this->sendEmailornot($label, $ret[0]['allusers'], $lessvalue, $result[0]['allusers'], $condition, $msg);
            }
            if ($label=="t_averageUsageDuration") {
                $lessvalue= abs($result[0]['usingtime']-$ret[0]['usingtime']);
                $shijifudong= round($lessvalue/$result[0]['usingtime'], 2)*100;
                $msg = $this->emailText($productname, lang('t_averageUsageDuration'), $ret[0]['usingtime'], $result[0]['usingtime'], $condition, $url, $emailtime, $shijifudong);
                $this->sendEmailornot($label, $ret[0]['usingtime'], $lessvalue, $result[0]['usingtime'], $condition, $msg);
            }
        }
    }

    /**
    * SendEmailornot function
    * 
    * @param string  $label      label
    * @param integer $facdata    facdata
    * @param integer $lessvalue  lessvalue
    * @param integer $trendvalue trendvalue
    * @param integer $condition  condition
    * @param string  $msg        msg
    * 
    * @return void
    */
    function sendEmailornot($label,$facdata,$lessvalue,$trendvalue,$condition,$msg)
    {
        if ($trendvalue!=0) {
            $consult = round($lessvalue/$trendvalue, 2)*100;
            if ($consult>=$condition) {
                $this->sendEmail($label, $facdata, $trendvalue, $msg, $condition);
            }
        }
        if ($trendvalue==0&&$lessvalue!=0) {
            $this->sendEmail($label, $facdata, $trendvalue, $msg, $condition);
        }
    }

    /**
    * SendMail function
    * 
    * @param string  $label      label
    * @param integer $facdata    facdata
    * @param integer $trendvalue trendvalue
    * @param string  $msg        msg
    * @param string  $condition  condition
    *
    *@return void
    */
    function sendEmail($label,$facdata,$trendvalue,$msg,$condition)
    {
        $alertlab = array();
        $alertlab = $this->compare->getAllAlertlab();
        for ($i=0;$i<count($alertlab);$i++) {
            $userid = $alertlab[$i]['userid'];
            $userinfo= $this->ums_user->getUserInfoById($userid);
            $email = $userinfo->email;
            $queryarr = $this->getEmails($label, $condition);
            if (count($queryarr)>0) {
                for ($i=0;$i<count($queryarr);$i++) {
                    $emailstr = $queryarr[$i]['emails'];
                    $emailstr = trim($emailstr);
                    if (strpos($emailstr, ";")) {
                        $arr= explode(";", $emailstr);
                        for ($j=0;$j<count($arr);$j++) {
                            $this->sendEmailto(trim($arr[$j]), $msg, $label, $facdata, $trendvalue);
                        }
                    } else {
                        $this->sendEmailto($emailstr, $msg, $label, $facdata, $trendvalue);
                    }
                }
            }
        }
    }

    /**
    * GetEmail function
    * 
    * @param string $label     label
    * @param string $condition condition
    * 
    * @return email
    */
    function getEmails($label,$condition)
    {
        $sql ="
            SELECT 
                *
            FROM 
                " . $this->db->dbprefix('alert') . "
            WHERE 
                label =  '".$label."'
                AND active =1
                AND  abs(`condition` -".$condition.")<0.001";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    /**
    * SendEmail function
    * 
    * @param string  $emailaddress emailaddress
    * @param string  $msg          msg
    * @param string  $label        label
    * @param string  $facdata      facdata
    * @param integer $trendvalue   trendvalue
    * 
    * @return void
    */
    function sendEmailto($emailaddress,$msg,$label,$facdata,$trendvalue)
    {
        $fromEmail =$this->config->item('smtp_user');
        $this->email->from("$fromEmail", lang('g_cobubRazor'));
        $this->email->to("$emailaddress");
        $subject = '['.lang('g_cobubRazor').']'.lang('m_rpt_exception');
        $this->email->subject("$subject");
        $this->email->message("$msg");
        $this->email->send();
        $timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
        $time = date('Y-m-d H:i:m', $timezonestimestamp);
        $this->compare->addAlertEmail($label, $facdata, $trendvalue, $time, 1);
    }

    /**
    * EmailText functuion
    * 
    * @param integer $product     product
    * @param integer $biaobiao    biaobiao
    * @param integer $shiji       shiji
    * @param string  $qushi       qushi
    * @param string  $fanwei      fanwei
    * @param integer $url         url
    * @param string  $time        time
    * @param integer $fudongshiji shiji
    * 
    * @return email text
    */
    function emailText($product,$biaobiao,$shiji,$qushi,$fanwei,$url,$time,$fudongshiji)
    {
        $text = lang('emailtext');
        $text = sprintf(
            $text, $product, $time,
            $biaobiao, $qushi, $shiji, $fanwei,
            $fudongshiji, $url
        );
        echo $text;
        return $text;
    }
}
