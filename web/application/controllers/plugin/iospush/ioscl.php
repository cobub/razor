<?php
class ioscl extends CI_Controller {
	private $data = array ();
	function __construct() {
		parent::__construct ();
		$this->load->language('plugin_ios');
		$this->load->Model ( 'common' );
		$this->load->model('plugin/iospush/iosapplistmodel','plugina');
		$this->common->requireLogin ();
	
	}
	
	function index() {
		
		// get   userkey userSecret appid appname appkey
		$productid=$_POST['product_id'];
		$tagtype = $_POST['tag_type'];//all  
		//echo $tagtype;	
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
		
		$registerid = $appinfo[0]['register_id'];
		$appname =$productname;
		$bundleid = $appinfo[0]['bundle_id'];
		
		//$producrid= $this->plugina->getproductid($appid);



		// $this->common->cleanCurrentProduct ();
		$this->data['productid']=$productid;
		$this->data ['appname'] = $appname;
		$this->data ['registerid'] = $registerid;
		$this->data ['userSecret'] = $userSecret;
		$this->data ['userKey'] = $userKey;
		$this->data ['bundleid'] = $bundleid;
		$this->data['tagvalue']=$tag;

		$this->data['tagtype']=false;
		if($tagtype=='all'){
			$this->data['tagtype']=true;
		}	
		$this->common->loadHeader ( lang ( 'm_ios_push' ) );
		$this->load->view ( 'plugin/iospush/iospushnote', $this->data );
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
		$registerid = $appinfo[0]['register_id'];
		$appname =$productname;
		$bundleid = $appinfo[0]['bundle_id'];
	
		$this->data['tagtype']=false;
		if($tagtype=='all'){
			$this->data['tagtype']=true;
		}

		// $this->common->cleanCurrentProduct ();
		$this->data['productid']=$productid;
		$this->data['tagvalue']=$tag;
		$this->data ['appname'] = $appname;
		$this->data ['registerid'] = $registerid;
		$this->data ['userSecret'] = $userSecret;
		$this->data ['userKey'] = $userKey;
		$this->data ['bundleid'] = $bundleid;
		$this->data['tagvalue']=$tag;
		$this->common->loadHeader ( lang ( 'm_ios_push' ) );
		$this->load->view ( 'plugin/iospush/transmission', $this->data );
	}

}

?>