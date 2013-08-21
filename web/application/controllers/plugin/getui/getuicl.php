<?php
class getuicl extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		$this->load->Model ( 'common' );
		$this->load->model('plugin/getui/applistmodel','plugina');
		$this->common->requireLogin ();
	
	}
	
	function index() {
		
		// get   userkey userSecret appid appname appkey
		$productid=$_POST['product_id'];
		 $tagtype = $_POST['tag_type'];//all  
		$tag =$_POST['tag_data'];
		// echo $tag;
		$appinfo = $this->plugina->getappinfo($productid);
		$productname = $this->plugina->getProductName($productid);

		$uid=$this->common->getUserId();
		$userinfo = $this->plugina->getUserinfo($uid);
		// print_r($userinfo);
		// print_r($appinfo);

		$userKey = $userinfo[0]['user_key'];
		$userSecret = $userinfo[0]['user_secret'];
		$appid = $appinfo[0]['app_id'];
		$appname =$productname;
		$appkey = $appinfo[0]['app_key'];
		$mastersecret =$appinfo[0]['app_mastersecret'];
		$producrid= $this->plugina->getproductid($appid);



		// $this->common->cleanCurrentProduct ();
		$this->data['productid']=$producrid;
		$this->data ['appname'] = $appname;
		$this->data ['appid'] = $appid;
		$this->data ['userSecret'] = $userSecret;
		$this->data ['userKey'] = $userKey;
		$this->data ['appkey'] = $appkey;
		$this->data ['mastersecret'] = $mastersecret;
		$this->data['tagvalue']=$tag;

		$this->data['tagtype']=false;
		if($tagtype=='all'){
			$this->data['tagtype']=true;
		}
		
		$this->common->loadHeader ( lang ( 'getui' ) );
		$this->load->view ( 'plugin/getui/pushnote', $this->data );
	}


	function transmission() {
		
		// get   userkey userSecret appid appname appkey
		$productid=$_POST['product_id'];
		$tagtype = $_POST['tag_type'];//all  
		$tag =$_POST['tag_data'];
 		$appinfo = $this->plugina->getappinfo($productid);
		$productname = $this->plugina->getProductName($productid);

		$uid=$this->common->getUserId();
		$userinfo = $this->plugina->getUserinfo($uid);

		$userKey = $userinfo[0]['user_key'];
		$userSecret = $userinfo[0]['user_secret'];
		$appid = $appinfo[0]['app_id'];
		$appname =$productname;
		$appkey = $appinfo[0]['app_key'];
		$mastersecret =$appinfo[0]['app_mastersecret'];

		
		$producrid= $this->plugina->getproductid($appid);

		$this->data['tagtype']=false;
		if($tagtype=='all'){
			$this->data['tagtype']=true;
		}

		// $this->common->cleanCurrentProduct ();
		$this->data['productid']=$producrid;
		$this->data['tagvalue']=$tag;
		$this->data ['appname'] = $appname;
		$this->data ['appid'] = $appid;
		$this->data ['userSecret'] = $userSecret;
		$this->data ['userKey'] = $userKey;
		$this->data ['appkey'] = $appkey;
		$this->data ['mastersecret'] = $mastersecret;
		$this->common->loadHeader ( lang ( 'getui' ) );
		$this->load->view ( 'plugin/getui/transmission', $this->data );
	}

}

?>