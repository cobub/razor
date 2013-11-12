<?php
class Product extends CI_Model
{
	function __construct()
	{
		$this->load->database();
		$this->load->model('common');
	}
	
	////platform:1=>Android,2=>iOS,3=>windows Phone;parameter:$plat=(1,2,3)
	function  getApplistByPlatform($plat)
	{
		
		
		$data = array(
				'active' => 1,
				'product_platform'=>$plat
		);
		
		$this->db->from($this->db->dbprefix('product'));
		$this->db->where($data);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	////get application all version
	function getProductAllVersionsById($productid)
	{
		$dwdb = $this->load->database ( 'dw', TRUE );
		$sql = "select distinct version_name
		from " . $dwdb->dbprefix ( 'sum_basic_product_version' ) . "
		where product_id='$productid'";
		$query = $dwdb->query ( $sql );
		if($query!=null&&$query->num_rows()>0)
		{
			return  $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	////get application create time
	function getProductCreateTimeById($productid)
	{
		$data = array(
				'id'=>$productid,
				'active' => 1
		);
		
		$this->db->from($this->db->dbprefix('product'));
		$this->db->where($data);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->first_row()->date;
		} else {
			return null;
		}
		
	}
	
	////get current selected app
	getCurrentProduct()
	{
		
	}
	
	
}