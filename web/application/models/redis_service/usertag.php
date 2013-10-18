<?php
class UserTag extends CI_Model {
    function __construct() {
        parent::__construct ();
        $this->load->library('redis');
    }

    function addUserTag($content) {
        $this->load->model('servicepublicclass/posttagpublic', 'posttagpublic');
        $posttag = new posttagpublic();
        $posttag->loadtag($content);
        $data = array(
            'deviceid'=>$posttag->deviceid,
            'tags'=>$posttag->tags,
            'productkey'=>$posttag->productkey
        );
        $this->redis->lpush('razor_usertag', serialize($data));
        $this->processor->process();
    }
}
