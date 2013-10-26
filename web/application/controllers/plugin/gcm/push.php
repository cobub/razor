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
class Push extends CI_Controller {

	function __construct() {
		parent::__construct ();
		
		$this->load->language('plugin_gcm');
		
		$this->load->Model ( 'common' );
		$this->common->requireLogin ();
        $this->load->model('plugin/getui/applistmodel','plugina');
        $this->load->model('plugin/gcm/checkgcminfomodel','gcm');
        $this->load->model('tag/tagmodel','tag');
        $this->load->model ( 'point_mark' );
	}
	
	function index() {
		
		$this->common->loadHeader ( lang ( 'gcm_push' ) );
       $productid=$_POST['product_id'];
         $tagtype = $_POST['tag_type'];//all  
        $tag =$_POST['tag_data'];
        //  $productid=1;
        // $tagtype = 'all';//all  
        // $tag ="ssss";


        $appinfo = $this->plugina->getappinfo($productid);
        $productname = $this->plugina->getProductName($productid);
        $appname =$productname;
        $uid=$this->common->getUserId();
        $userinfo = $this->plugina->getUserinfo($uid);
        $userKey = $userinfo[0]['user_key'];
        $userSecret = $userinfo[0]['user_secret'];

        $this->data ['userSecret'] = $userSecret;
        $this->data ['userKey'] = $userKey;
        $this->data ['appname'] = $appname;
        $this->data['tagvalue']=$tag;
        $this->data['productid']=$productid;
		
		$this->load->view ( 'plugin/gcm/pushview',$this->data);
	}
    function pushtoUcenter(){

        
         $appname = $_POST ['appname'];
        $userKey = $_POST ['userKey'];
        $userSecret = $_POST ['userSecret'];
        $tagvalue = $_POST['tagvalue'];// getted tag value to get deviceid
        $productid = $_POST['productid'];
        $message = $_POST['appcontent'];
        $uid=$this->common->getUserId();
        $appkey = $this->gcm->getappkey( $uid);
        $umsappkey = $this->gcm->getumsappkey($productid);
        
        $data = array (
               
                'productid' => $productid,
                'tagvalue' => $tagvalue,
                'appname'=>$appname,
                'userKey' => $userKey,
                'userSecret' => $userSecret,
                'message'=>$message,
                'umsappkey'=>$umsappkey,
                'appkey'=>$appkey
                
        );
        
          
        
        $flag =true;
        $i=0;

        while ($flag) {
           
            $devicelist=$this->tag->getDeviceidList($productid,$tagvalue,$i,500);
            $resarr = $devicelist->result_array();
            // echo $a[0]['deviceidentifier'];
            // print_r( $resarr);
            $data['devicelist']=json_encode($resarr);
            $data['tag'] = $tagvalue;
            // print_r($data);

            $result=$this->common->curl_post(SERVER_BASE_URL.'/index.php?/gcm/push',$data);
             // echo $result;
            if(count($resarr)<500){
                $flag=false;
            }
            $i=$i+1;
        }
        $resu= json_decode ( $result,true );
        if($resu['result']==-1){
             $res = array (
                    'flag' => 0,
                    'msg' => "can't connection host"
            );
             echo json_encode ( $res );
             return;

        }
        
        // print_r($result);
        // echo $result;
        // if ($resu['result']=='ok') {
            $res = array (
                    'flag' => 1,
                    'msg' => 'ok' ,
                    'success'=>$resu['seccess'],
                    'fail'=>$resu['fail']
            );
            $uid =$this->common->getUserId();       
            // echo $uid;
            $arr=array(
                'userid'=>$uid,
                'productid'=>$productid,
                'title'=>'push message by gcm',
                'description'=>"send message ".$message,
                'private'=>1,
                'marktime'=> date("Y-m-d")
            )
            ;
            $this->point_mark->addPointmark ( $arr );
        
        // } else {
        //     $res = array (
        //             'flag' => 0,
        //             'msg' => $resu['msg']
        //     );
        // }
        echo json_encode ( $res );

    }
	
	

}

