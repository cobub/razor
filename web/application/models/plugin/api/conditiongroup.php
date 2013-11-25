<?php

class ConditionGroup extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->model('common');
        $this -> load -> model('product/newusermodel', 'newusermodel');
        $this -> load -> model('product/productmodel', 'productmodel');
        $this -> load -> database();
    }

    // search condition lists
     function getDateList($from, $to) {
       $this->common->getDateList($from, $to);
    }

    // get group condition 
    function getTimeTick($days) {
       $this->common->getTimeTick($days);
    }

    //change time segment
    //$pase-->choose the condition
    function changeTimeSegment($pase, $from, $to) {
        $this->common->changeTimeSegment($pase,$from,$to);
    }

      /*  

    function getAlldataofVisittrends($fromtime,$totime,$userId){
      $this->newusermodel-> getAlldataofVisittrends($fromtime,$totime,$userId);
    }
    function export($from, $to, $data) {
       $this->common->export($from,$to,$data);
    }*/
}

?>