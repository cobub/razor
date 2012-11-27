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
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class install extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('datamanage');
		$this->load->config('tank_auth', TRUE);
		$this->load->helper('file');	
	}
	//Check the directory read and write permissions
	function file_mode_info($file_path)
	{
		/* judgment if a file exists. */
		if (!file_exists($file_path))
		{
			return false;
		}
		$mark = 0;
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
		{
			/* test file  */
			$test_file = $file_path . '/cf_test.txt';
			/* directory */
			if (is_dir($file_path))
			{
				/* check readable */
				$dir = @opendir($file_path);
				if ($dir === false)
				{
					return $mark; //If unreadable returns can not be modified directly, unreadable unwritable
				}
				if (@readdir($dir) !== false)
				{
					$mark ^= 1; //readable 001,unreadable 000
				}
				@closedir($dir);
				/* check writable */
				$fp = @fopen($test_file, 'wb');
				if ($fp === false)
				{
					return $mark; //If the file is created in the directory fails, the return is not writable.
				}
				if (@fwrite($fp, 'directory access testing.') !== false)
				{
					$mark ^= 2; //The directories can write readable 011,the directories can write unreadable 010
				}
				@fclose($fp);
				@unlink($test_file);
				/* Check whether the directory can be modified */
				$fp = @fopen($test_file, 'ab+');
				if ($fp === false)
				{
					return $mark;
				}
				if (@fwrite($fp, "modify test.\r\n") !== false)
				{
					$mark ^= 4;
				}
				@fclose($fp);
				/* Check whether the directory a rename () function permissions */
				if (@rename($test_file, $test_file) !== false)
				{
					$mark ^= 8;
				}
				@unlink($test_file);
			}
			
		}
		else
		{
			if (@is_readable($file_path))
			{
				$mark ^= 1;
			}
			if (@is_writable($file_path))
			{
				$mark ^= 14;
			}
		}
		return $mark;
	}
	
	
	//load select language view
	function index()
	{			
		$languanginfo = array();		
		$filepath   =   dir( "./application/language");	
			
		//$directory=$filepath->read();   //if do not read current directory(just like.)，read one time		
		//$directory=$filepath-> read();   //if do not read parent directory(just like..)，read two times
		
		while($directory=$filepath->read())   {				
			if($directory!=".." && $directory!=".svn" && $directory!="." )
			{ 				
				array_push($languanginfo, $directory);
			}			
		 }
		 //print_r($languanginfo);
		$filepath-> close();
		$this->data['languageinfo']	=$languanginfo;
		$this->data['newurl']=$this->datamanage->createurl();
		$this->load->view('install/installselectlanguage',$this->data);
	}
	//deal with  select language
	function selectlanguage()
	{
		$newurl="http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];		
		$language=$this->input->post('weblanguage');		
		$this->load->helper('language');
		$this->lang->load('installview', $language);
		$this->	welcome($language);	
	}
	//load welcome view
	function welcome($language)
	{	
		$this->load->helper('language');
		$this->lang->load('installview', $language);
		$this->data['language']=$language;	
		$this->load->helper('language');
		$this->data['newurl']=$this->datamanage->createurl();
		$this->load->view('install/installwelcome',$this->data);
	}
	//load systemcheck view
	function systemcheck($language)
	{		
	    $this->load->helper('language');
		$this->lang->load('installview', $language);
		$this->data['language']=$language;
		if((int)substr(PHP_VERSION, 0,1)>=5)
		{
			$phpversion=true;
		}
		else
		{
			$phpversion=false;
			$this->data['versionerror']=lang('installview_versionerror');
		}
		if(function_exists('mysqli_close') )
		{
			$mysqli=true;
		}
		else
		{
			$mysqli=false;
			$this->data['mysqlierror']=lang('installview_mysqlierror');
		}

		$configpath=realpath('./application/config');
		$assetspath=realpath('./assets/android');
		$captchapath=realpath('./captcha');
		$sqlpath=realpath('./assets/sql');
		$this->data['phpversion']=$phpversion;
		$this->data['mysqli']=$mysqli;
		
		$configfile = get_dir_file_info($configpath);		
		$configwrite=$this->iscanwrite($configfile);
		$this->data['configwrite']=$configwrite;
		$this->data['configpath']=$configpath;
		
		$captchfile = get_dir_file_info($captchapath);
		$captchwrite=$this->iscanwrite($captchfile);
		$this->data['captchwrite']= $captchwrite;
		$this->data['captchapath']=$captchapath;
				
		$assetswrite=$this->file_mode_info($assetspath);				
		$this->data['assetswrite']=$assetswrite;
		$this->data['assetspath'] = $assetspath;
		
		$sqlfile = get_dir_file_info($sqlpath);
		$sqlwrite=$this->iscanwrite($sqlfile);
		$this->data['sqlwrite']=$sqlwrite;
		$this->data['sqlpath'] = $sqlpath;
		
		if($configwrite=="true"&&$captchwrite=="true"&&$assetswrite!=0&&$sqlwrite=="true")
		{
			$writetrue=true;
			$this->data['writetrue']=$writetrue;
			 
		}
		else
		{
			$writetrue=false;
			$this->data['writetrue']=$writetrue;
			$this->data['writeerror']=lang('installview_writeerror');
		}	
				
		$this->data['newurl']=$this->datamanage->createurl();
		$this->load->view('install/installcheckview',$this->data);
	}
	
	//check iscanwerte
	function iscanwrite($fileinfo)
	{		
		foreach ($fileinfo as $row)
		{
			if($row['readable']==1&&$row['writable']==1)
			{
				$iswrite=true;
			}
			else
			{
				$iswrite=false;
				break;
			}
			return $iswrite;
		}
	}
	//load creata database view
	function databaseinfo($language)
	{	
		$configlanguage=$this->config->item('language');
		//modify config file---config file;
		$dir =	"./application/config/config.php";
		$fh = fopen($dir,'r+');
		$data=fread($fh,filesize($dir));
		$data=str_replace($configlanguage, $language, $data);
		fclose($fh);
		$handle=fopen($dir,"w");
		fwrite($handle,$data);
		fclose($handle);
			
		//modify config file---autoload file;
		$dir =	"./application/config/autoload.php";
		$fh = fopen($dir,'r+');
		$data=fread($fh,filesize($dir));
		$beforestring="$";
		$afterstring="autoload['language'] = array()";
		$autoloadlanguage=$beforestring.$afterstring;
		$afternewstring="autoload['language'] = array('installview')";
		$autoloadnewlanguage=$beforestring.$afternewstring;
		$data=str_replace($autoloadlanguage, $autoloadnewlanguage, $data);
		fclose($fh);
		$handle=fopen($dir,"w");
		fwrite($handle,$data);
		fclose($handle);
		
		$ip="localhost";
		$this->data['ip']=$ip;
		$this->load->helper('language');
		$this->lang->load('installview', $language);
		$this->data['language']=$language;
		$this->data['newurl']=$this->datamanage->createurl();
		$this->load->view('install/installdatabaseview',$this->data);
	}


	//deal with database info
	function createdatabase()
	{
		$language=$this->config->item('language');
		$ip="localhost";
	 //deal with database and dataware

		//database data set rule
		$this->form_validation->set_rules ( 'ip', lang('installview_verficationip'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'dbname', lang('installview_verficationdbname'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'username', lang('installview_verficationusername'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'password', lang('installview_verficationpassword'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'tablehead', lang('installview_verficationtablehead'), 'trim|required|xss_clean|alpha_dash' );
		//dataware data set rule
		$this->form_validation->set_rules ( 'depotip', lang('installview_verficationdepotip'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'depotdbname', lang('installview_verficationdepotdbname'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'depotusername', lang('installview_verficationdepotusername'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'depotpassword', lang('installview_verficationdepotpassword'), 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'depottablehead', lang('installview_verficationdepottablehead'), 'trim|required|xss_clean|alpha_dash' );
		if ($this->form_validation->run () == FALSE){

			$this->data['ip']=$ip;
			$this->data['newurl']=$this->datamanage->createurl();
			$this->data['language']=$this->config->item('language');
			$this->load->view ('install/installdatabaseview',$this->data);
				
		}
		else
		{
			//database data
			$servname= $this->input->post('ip');
			$dbuser= $this->input->post('username');
			$dbpwd= $this->input->post('password');
			$sqlname=$this->input->post('dbname');
			$tablehead=	$this->input->post('tablehead');
			//dataware data
			$depotservname= $this->input->post('depotip');
			$depotdbuser= $this->input->post('depotusername');
			$depotdbpwd= $this->input->post('depotpassword');
			$depotsqlname=$this->input->post('depotdbname');
			$depottablehead=$this->input->post('depottablehead');
			$conn=mysql_connect($servname, $dbuser, $dbpwd) ;
			$depotconn=mysql_connect($depotservname, $depotdbuser, $depotdbpwd) ;
			if(!$conn||!$depotconn)
			{
				$this->data['ip']=$ip;
				if (!$conn) {

					$this->data['error']=lang('installview_verficationconnecterror'). mysql_error ();
				}
				if (!$depotconn) {
					$this->data['errord']=lang('installview_verficationdepotconnecterror') . mysql_error ();

				}
				$this->data['language']=$this->config->item('language');
				$this->data['newurl']=$this->datamanage->createurl();
				$this->load->view('install/installdatabaseview',$this->data);
			}
			else
			{ 
				//check if exist database info
				$exitdatabase=$this->checkexistdatabase($servname, $dbuser, $dbpwd, $sqlname);
				$exitdatabasedw=$this->checkexistdatabase($depotservname, $depotdbuser, $depotdbpwd, $depotsqlname);
				if($exitdatabase && $exitdatabasedw)
				{
					// check  innodb info
					$datainfo  = $this->checkinnodb($servname, $dbuser, $dbpwd);
					$depotdatainfo = $this->checkinnodb($depotservname, $depotdbuser, $depotdbpwd);
					if($datainfo =="true" && $depotdatainfo=="true")
					{
						// deal with database and dataware
						$runsql=$this->dealwitndatainfo($servname, $dbuser, $dbpwd, $sqlname, $tablehead, $depotservname, $depotdbuser, $depotdbpwd, $depotsqlname, $depottablehead);	
						if($runsql)	
						{
							$this->userinfo();
							//modify config file---database file							
							$dir =	"./application/config/database.php";
							$fh = fopen($dir,'r+');
							$data=fread($fh,filesize($dir));//read file
							$data=str_replace('DWDBPREFIX', $depottablehead, $data);
							$data=str_replace('DWDATABASE', $depotsqlname, $data);
							$data=str_replace('DWPASSWORD', $depotdbpwd, $data);
							$data=str_replace('DWUSERNAME', $depotdbuser, $data);
							$data=str_replace('DWHOSTNAME', $depotservname, $data);
							$data=str_replace('DBPREFIX', $tablehead, $data);
							$data=str_replace('DATABASE', $sqlname, $data);
							$data=str_replace('PASSWORD', $dbpwd, $data);
							$data=str_replace('USERNAME', $dbuser, $data);
							$data=str_replace('HOSTNAME', $servname, $data);
							fclose($fh);
							$handle=fopen($dir,"w");
							fwrite($handle,$data); //to write file
							fclose($handle);
						}	
						else
						{
							$this->data['error']=lang('installview_verficationcreatefailed');
							$this->data['language']=$this->config->item('language');
							$this->data['newurl']=$this->datamanage->createurl();
							$this->load->view('install/installdatabaseview',$this->data);
						}	
					
					}
					else
					{
						if($datainfo=='false')
						{
							$this->data['inerror']=lang('installview_innodberror');
						}
						if($datainfo=='can')
						{
							$this->data['inerror']=lang('installview_innodbclose');
						}
						if($depotdatainfo=='false')
						{
							$this->data['inerrordw']=lang('installview_innodberrordw');
						}
						if($depotdatainfo=='can')
						{
							$this->data['inerrordw']=lang('installview_innodbclosedw');
						}
					
						$this->data['language']=$this->config->item('language');
						$this->data['newurl']=$this->datamanage->createurl();
						$this->load->view('install/installdatabaseview',$this->data);
					}
				}
				else 
				{
					if(!$exitdatabase)
					{
						$this->data['error']=lang('installview_noexistdata');
					}
					if(!$exitdatabasedw)
					{
						$this->data['errord']=lang('installview_noexistdatadw');
					}				
					$this->data['language']=$this->config->item('language');
					$this->data['newurl']=$this->datamanage->createurl();
					$this->load->view('install/installdatabaseview',$this->data);
				}					
					
			}

		}

	}
	function dealwitndatainfo($servname,$dbuser,$dbpwd,$sqlname,$tablehead,$depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,$depottablehead)
	{
		$language=$this->config->item('language');
		//deal with database and dataware
		$replacedatabase=$sqlname.".".$tablehead;		
		if($language=="zh_CN")
		{			
			$ret= $this->createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,'assets/sql/databaseinfo.sql',null,$tablehead);
			$rel=$this->createdatabasesql($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/dataware.sql',null,$depottablehead);
			$this->createproducre($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/datawarestore.sql','updatedatawarestore',$replacedatabase,null,$depottablehead);
		}
		else
		{
			$ret= $this->createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,'assets/sql/edatabaseinfo.sql',null,$tablehead);
			$rel=$this->createdatabasesql($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/edataware.sql',null,$depottablehead);
			$this->createproducre($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/datawarestore.sql','updatedatawarestore',$replacedatabase,null,$depottablehead);
		}		
		if($ret&&$rel)
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	//check mysql if exist database
    function checkexistdatabase($servname, $dbuser, $dbpwd,$sqlname)
    {
    	$conn=mysql_connect($servname, $dbuser, $dbpwd) ;
    	$db=mysql_select_db($sqlname);
    	if($db)
    	{
    	   return  true;
    	}
    	else
    	{    		
    	   return false;
    	}
    	mysql_close($conn);
    }
	//check mysql if can support innodb
	function checkinnodb($servname,$dbuser,$dbpwd)
	{
		$con = mysql_connect($servname, $dbuser, $dbpwd);
		$result=mysql_query("show engines");
		while ($row=mysql_fetch_row($result))
		{
			for ($i=0 ;$i<count($row);$i++)
		 {
				if($row[$i]=="InnoDB")
			{
			  $property=$row[$i+1];
			  if($property=="YES")
			  {
			  	$iscanuse="true";
			  }
			  if($property=="NO")
			  {
			  	$iscanuse="false";
			  }
			  if($property=="DISABLED")
			  {
			  	$iscanuse="can";
			  }
			  if($property=="DEFAULT")
			  {
			  	$iscanuse="true";
			  }
			  break;
		   }

	   }
		}
		return $iscanuse;
	}

	//check real server ip info
	function realserverip(){
		static $serverip = NULL;

		if ($serverip !== NULL){
			return $serverip;
		}

		if (isset($_SERVER)){
			if (isset($_SERVER['SERVER_ADDR'])){
				$serverip = $_SERVER['SERVER_ADDR'];
			}
			else{
				$serverip = '0.0.0.0';
			}
		}
		else{
			$serverip = getenv('SERVER_ADDR');
		}

		return $serverip;
	}

	//modify  table  .sql file
	function createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,$sqlPath,$delimiter = '(;\n)|((;\r\n))|(;\r)',$prefix = '',$commenter = array('#','--'))
	{
		echo "<Meta http-equiv='Content-Type' Content='text/html; Charset=utf8'>";
		//Determine if a file exists.
		if(!file_exists($sqlPath))
			return false;
		 
		$handle = fopen($sqlPath,'rb');
		$sqlStr = fread($handle,filesize($sqlPath));
		//Sql syntax statement separator preg_split
		$segment = explode(";",trim($sqlStr));
		 
		//Remove comments and extra blank line
		$newSegment = array();
		foreach($segment as $statement)
		{
			$sentence = explode("\n",$statement);
			 
			$newStatement = array();
			 
			foreach($sentence as $subSentence)
			{
				if('' != trim($subSentence))
				{
					//To judge whether a comment
					$isComment = false;
					foreach($commenter as $comer)
					{
						if(preg_match("/^(".$comer.")/",trim($subSentence)))
						{
							$isComment = true;
							break;
						}
					}
					if(!$isComment)
						$newStatement[] = $subSentence;
				}
			}
			$statement = $newStatement;
			array_push($newSegment,$statement);
		}
		//add table name prefix
		$prefixsegment=array();
		if('' != $prefix)
		{
			$regxTable = "^[\`\'\"]{0,1}[\_a-zA-Z]+[\_a-zA-Z0-9]*[\`\'\"]{0,1}$";
			$regxLeftWall = "^[\`\'\"]{1}";
			 
			$sqlFlagTree = array(
					"CREATE" => array(
							"TABLE" => array(
									"IF" => array(
											"NOT" => array(
													"EXISTS" => array(
															"$regxTable" => 0
													)
											)
									)
							)
					),
					"INSERT" => array(
							"INTO" => array(
									"$regxTable" => 0
							)
					)
			);
			foreach($newSegment as $statement)
			{
				$tokens = explode(" ", @$statement[0]);
				$tableName = array();
				$tableName=$this->gettablename($sqlFlagTree,$tokens,0,$tableName);

				if(empty($tableName['leftWall']))
				{
					//Add the prefix
					$newTableName = $prefix.$tableName['name'];

				}
				else{
					//Add the prefix
					$newTableName = $tableName['leftWall'].$prefix.substr($tableName['name'],1);
				}
				 
				$statement[0] = str_replace("umsinstall_",$prefix,@$statement[0]);
				array_push($prefixsegment,$statement);
			}
			 
		}
		 
		$combiansegment=array();
		//Combination of sql statement
		foreach($prefixsegment as $statement)
		{
			$newStmt = '';
			foreach($statement as $sentence)
			{
				 
				$newStmt = $newStmt.trim($sentence)."\n";
			}
			$statement = $newStmt;
			array_push($combiansegment,$statement);
		}
		$this->runsqlfile($servname,$dbuser,$dbpwd,$sqlname, $combiansegment,$prefix);
		 
		return true;
	}
	//modify  procedure  .sql file
	function createproducre($servname,$dbuser,$dbpwd,$sqlname,$sqlPath,$storename,$replacedatabase,$delimiter = '(;\n)|((;\r\n))|(;\r)',$prefix = '',$commenter = array('#','--'))
	{
		echo "<Meta http-equiv='Content-Type' Content='text/html; Charset=utf8'>";
		//judge if exist file
		if(!file_exists($sqlPath))
			return false;		
		$handle = fopen($sqlPath,'rb');
		if($handle)
		{
			$sqlStr = '';
			while(!feof($handle))
			{
				$sqlStrtemp = fgets($handle);
				$sqlStr = $sqlStr.str_replace("databaseprefix.umsdatainstall_",$replacedatabase,@$sqlStrtemp);
			}
			 
		}
		fclose($handle);
		$datadeal=fopen('assets/sql/'.$storename.'.sql',"w"); //open file by write way
		fwrite($datadeal,$sqlStr);
		fclose($datadeal);
		 
		$lasthandle = fopen('assets/sql/'.$storename.'.sql','rb');
		if($lasthandle)
		{
			$lastsqlStr = '';
			while(!feof($lasthandle))
			{
				$sqlStrtemp = fgets($lasthandle);
				$lastsqlStr = $lastsqlStr.str_replace("umsinstall_",$prefix,@$sqlStrtemp);
			}
			 
		}
		fclose($lasthandle);
		$lastdatadeal=fopen('assets/sql/'.$storename.'.sql',"w"); //写入方式打开新路径
		fwrite($lastdatadeal,$lastsqlStr);
		fclose($lastdatadeal);
		$filepath="assets/sql/".$storename.".sql";
		
		$handle = fopen($filepath,'rb');
		$sqlStr = fread($handle,filesize($filepath));
		$segment = explode("--$$",trim($sqlStr));				
		$this->runsqlfile($servname,$dbuser,$dbpwd,$sqlname, $segment,$prefix);
		return true;
	}
	//run sql file
	function runsqlfile($servname,$dbuser,$dbpwd,$sqlname,$sqlArray,$tablehead)
	{
		$conn = mysql_connect($servname,$dbuser,$dbpwd);
		mysql_select_db($sqlname);
		foreach($sqlArray as $sql)
		{
			mysql_query($sql);			
		}
		mysql_close($conn);

	}
	 
	//get table name from .sql file
	function gettablename($sqlFlagTree,$tokens,$tokensKey=0, $tableName = array())
	{
		$regxLeftWall = "^[\`\'\"]{1}";
		 
		if(count($tokens)<=$tokensKey)
			return false;
		 
		if('' == trim($tokens[$tokensKey]))
		{
			$this->gettablename($sqlFlagTree,$tokens,$tokensKey+1,$tableName);
		}
		else
		{
			foreach($sqlFlagTree as $flag => $v)
			{
				if(preg_match("/".$flag."/",$tokens[$tokensKey]))
				{
					if(0==$v)
					{
						$tableName['name'] = $tokens[$tokensKey];

						if(preg_match("/".$regxLeftWall."/",$tableName['name']))
						{
							$tableName['leftWall'] = $tableName['name']{
								0};
								 
								 
						}
						 
						return  $tableName;
					}
					else{
						return $this->gettablename($v,$tokens,$tokensKey+1, $tableName);
					}
				}
			}
		}
		 
		return false;
	}
	//load  superuser and deploy site view
	function userinfo()
	{
		$this->data['language']=$this->config->item('language');
		$this->data['newurl']=$this->datamanage->createurl();
		$this->load->view('install/installuserview',$this->data);
	}
	//create superuser and deploy site info
	function createuserinfo()
	{
		$this->form_validation->set_rules ( 'siteurl', lang('installview_verficationsiteurl'), 'trim|required|xss_clean|valid_url');
		$this->form_validation->set_rules ( 'superuser', lang('installview_verficationsuperuser'), 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules ( 'pwd', lang('installview_verficationpwd'), 'trim|required|xss_cleanmin_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash' );
		$this->form_validation->set_rules ( 'verifypassword', lang('installview_verficationverifypwd'), 'trim|required|xss_clean|matches[pwd]|alpha_dash' );
		$this->form_validation->set_rules ( 'email', lang('installview_verficationemail'), 'trim|required|xss_clean|valid_email' );
		if ($this->form_validation->run () == FALSE){
				
			$this->data['language']=$this->config->item('language');
			$this->data['newurl']=$this->datamanage->createurl();
			$this->load->view ('install/installuserview',$this->data);

		}
		else
		{
			$timezones=$this->input->post('webtimezones');
			$currentimezones= $this->config->item('timezones');
			$siteurl= $this->input->post('siteurl');			
			$currenturl=$this->config->item('base_url');
			//$currentfiledir = dirname(__FILE__);
			//$dir =  str_replace("controllers","",$currentfiledir)."config/database.php";
			$username= $this->input->post('superuser');
			$password= $this->input->post('pwd');
			$verifypwd= $this->input->post('verifypassword');
			$email=$this->input->post('email');
			$email_activation = $this->config->item ( 'email_activation', 'tank_auth' );
			$data = $this->datamanage->createuser($username, $email, $password, $email_activation );
			$userid = $data ['user_id'];
			$new_email_key = $data ['new_email_key'];
			$this->datamanage->insertrole($email);
			if ($this->datamanage->activateuser($userid, $new_email_key ))
			{
				$this->data['newurl']=$this->datamanage->createurl();
				$this->data['siteurl']=$siteurl;
				$this->data['language']=$this->config->item('language');
				$this->load->view('install/installfinshview', $this->data);

				//modify config file---config file;
				$dir =	"./application/config/config.php";
				$fh = fopen($dir,'r+');
				$data=fread($fh,filesize($dir));
				$data=str_replace($currenturl, $siteurl, $data);
				$data=str_replace($currentimezones, $timezones, $data);	
				fclose($fh);
				$handle=fopen($dir,"w");
				fwrite($handle,$data);
				fclose($handle);

				//modify config file---autoload file;
				$dir =	"./application/config/autoload.php";
				$fh = fopen($dir,'r+');
				$data=fread($fh,filesize($dir));
				$data=str_replace('installview', 'allview', $data);
				fclose($fh);
				$handle=fopen($dir,"w");
				fwrite($handle,$data);
				fclose($handle);
					
				//modify config file---routes file;
				$dir =	"./application/config/routes.php";
				$fh = fopen($dir,'r+');
				$data=fread($fh,filesize($dir));//read
				$data=str_replace('install/install', 'report/console', $data);
				fclose($fh);
				$handle=fopen($dir,"w");
				fwrite($handle,$data);
				fclose($handle);
			}			

		}
			
	}

}

