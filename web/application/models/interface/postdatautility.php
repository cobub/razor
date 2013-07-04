 <?php
class postdatautility extends CI_Model
{

	function __construct()
	{
	  parent::__construct();   
	}


function post($url,$postdata)
{
	$postdata = http_build_query($postdata);
	log_message("debug",$url);
	$opts = array('http' =>
			array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $postdata
			)
	);

	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	log_message("debug",$result);
	return $result;
}

}