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

class main extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');	
		$this->load->model('datamanage');	
		$this->load->config('tank_auth', TRUE);		
		$this->load->helper('file');	
		$language=$this->config->item('language');		
		
	}
	//load welcome view
   function index()   
	{	
		$this->data['language']=$this->config->item('language');				
		$this->data['newurl']=$this->datamanage->createurl();				
		$this->load->view('installwelcome',$this->data);
	}	
 //load systemcheck view
	function systemcheck()
	{  	 
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
		$assetspath=realpath('./assets');
		$captchapath=realpath('./captcha'); 
		$this->data['phpversion']=$phpversion;
		$this->data['mysqli']=$mysqli; 
		$this->data['language']=$this->config->item('language');      
        $configfile = get_dir_file_info($configpath); 
        $configwrite=$this->iscanwrite($configfile);
        $this->data['configwrite']=$configwrite;
        $this->data['configpath']=$configpath;
        $captchfile = get_dir_file_info($captchapath);
        $captchwrite=$this->iscanwrite($captchfile);    
        $this->data['captchwrite']= $captchwrite;
        $this->data['captchapath']=$captchapath;
        $this->data['assetspath'] = get_dir_file_info($assetspath); 
        $assetsfile = get_dir_file_info($assetspath);
        $assetswrite=$this->iscanwrite($assetsfile);
        $this->data['assetswrite']=$assetswrite;
        if($configwrite=="true"&&$captchwrite=="true"&&$assetswrite=="true")   
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
		$this->data['language']=$this->config->item('language');
		$this->data['newurl']=$this->datamanage->createurl();			
		$this->load->view('installcheckview',$this->data);
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
	function databaseinfo()
	{
		$ip="localhost";
		$this->data['ip']=$ip;
		$this->data['language']=$this->config->item('language');
		$this->data['newurl']=$this->datamanage->createurl();		
		$this->load->view('installdatabaseview',$this->data);
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
			$this->form_validation->set_rules ( 'tablehead', lang('installview_verficationtablehead'), 'trim|required|xss_clean' );
			//dataware data set rule
			$this->form_validation->set_rules ( 'depotip', lang('installview_verficationdepotip'), 'trim|required|xss_clean' );
			$this->form_validation->set_rules ( 'depotdbname', lang('installview_verficationdepotdbname'), 'trim|required|xss_clean' );
			$this->form_validation->set_rules ( 'depotusername', lang('installview_verficationdepotusername'), 'trim|required|xss_clean' );
			$this->form_validation->set_rules ( 'depotpassword', lang('installview_verficationdepotpassword'), 'trim|required|xss_clean' );
			$this->form_validation->set_rules ( 'depottablehead', lang('installview_verficationdepottablehead'), 'trim|required|xss_clean' );
			if ($this->form_validation->run () == FALSE){				
				
				$this->data['ip']=$ip;
				$this->data['newurl']=$this->datamanage->createurl();
				$this->data['language']=$this->config->item('language');				
				$this->load->view ('installdatabaseview',$this->data);
			
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
					 $this->load->view('installdatabaseview',$this->data);
				}
				else
				{		
					//deal with database			
					$db=mysql_select_db($sqlname);									
					if($db){
						
					  if($language=="chinese")
						{
						  $ret= $this->createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,'assets/sql/databaseinfo.sql',null,$tablehead);				          
						}
						else 
						{
							$ret= $this->createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,'assets/sql/edatabaseinfo.sql',null,$tablehead); 					          
						}
					 
					}
					else
					{
					   mysql_query("CREATE DATABASE `".$sqlname."` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin");//create database						
					  if($language=="chinese")
						{
						   $ret= $this->createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,'assets/sql/databaseinfo.sql',null,$tablehead); 					         
						}
						else 
						{
							$ret= $this->createdatabasesql($servname,$dbuser,$dbpwd,$sqlname,'assets/sql/edatabaseinfo.sql',null,$tablehead); 					            
						}
	                   
					}
					mysql_close($conn); 
					// deal with dataware 
					$depotconn=mysql_connect($depotservname, $depotdbuser, $depotdbpwd) ;
				    $dwdb=mysql_select_db($depotsqlname);				   
					if($dwdb){
					     if($language=="chinese")
						{
						   $rel=$this->createdatabasesql($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/dataware.sql',null,$depottablehead);
					       $this->createproducre($servname,$dbuser,$dbpwd,$depotsqlname,'assets/sql/datawarestore.sql','updatedatawarestore',$sqlname,null,$tablehead);    
						}
						else 
						{
							$rel=$this->createdatabasesql($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/edataware.sql',null,$depottablehead);
					        $this->createproducre($servname,$dbuser,$dbpwd,$depotsqlname,'assets/sql/datawarestore.sql','updatedatawarestore',$sqlname,null,$tablehead);        
						}
					   
					}
					else
					{						
						mysql_query("CREATE DATABASE `".$depotsqlname."` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin");//create dataware																	
					if($language=="chinese")
						{
						   $rel=$this->createdatabasesql($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/dataware.sql',null,$depottablehead);
					      $this->createproducre($servname,$dbuser,$dbpwd,$depotsqlname,'assets/sql/datawarestore.sql','updatedatawarestore',$sqlname,null,$tablehead);    
						}
						else 
						{
							$rel=$this->createdatabasesql($depotservname,$depotdbuser,$depotdbpwd,$depotsqlname,'assets/sql/edataware.sql',null,$depottablehead);
					        $this->createproducre($servname,$dbuser,$dbpwd,$depotsqlname,'assets/sql/datawarestore.sql','updatedatawarestore',$sqlname,null,$tablehead);        
						}
						//mysql_close($depotconn); 
	                   
					}		
					 			
					if($rel&&$ret)
					{
						$this->userinfo();
						//modify config file---database file
						$currentfiledir = dirname(__FILE__);
						$dir =  str_replace("controllers","",$currentfiledir)."config/database.php";						
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
						 $this->load->view('installdatabaseview',$this->data);
					}
					    
	            }
	
		}
	
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
        //判断文件是否存在
        if(!file_exists($sqlPath))
            return false;
       
        $handle = fopen($sqlPath,'rb');       	
			if($handle)
		{
			$sqlStr = '';			
			while(!feof($handle)) 
			{				        	 		
        		$sqlStrtemp = fgets($handle);
        		$sqlStr = $sqlStr.str_replace("databaseprefix",$replacedatabase,@$sqlStrtemp);       		
			} 			
			   	
		} 	 
		   fclose($handle); 	
		   $datadeal=fopen('assets/storedprocedure/'.$storename.'.sql',"w"); //写入方式打开新路径 
		   fwrite($datadeal,$sqlStr);
		   fclose($datadeal);	
		   
		   $lasthandle = fopen('assets/storedprocedure/'.$storename.'.sql','rb');       	
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
		   $lastdatadeal=fopen('assets/storedprocedure/'.$storename.'.sql',"w"); //写入方式打开新路径 
		   fwrite($lastdatadeal,$lastsqlStr);
		   fclose($lastdatadeal);
		   $filepath="assets/storedprocedure/".$storename.".sql";	
		  
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
                            $tableName['leftWall'] = $tableName['name']{0};
                         
                           
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
	  $this->load->view('installuserview',$this->data);
	}
	//create superuser and deploy site info
	function createuserinfo()
	{ 	
		$configlanguage=$this->config->item('language');	    
		$this->form_validation->set_rules ( 'siteurl', lang('installview_verficationsiteurl'), 'trim|required|xss_clean|valid_url');
	    $this->form_validation->set_rules ( 'superuser', lang('installview_verficationsuperuser'), 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules ( 'pwd', lang('installview_verficationpwd'), 'trim|required|xss_cleanmin_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash' );
		$this->form_validation->set_rules ( 'verifypassword', lang('installview_verficationverifypwd'), 'trim|required|xss_clean|matches[pwd]|alpha_dash' );
		$this->form_validation->set_rules ( 'email', lang('installview_verficationemail'), 'trim|required|xss_clean|valid_email' );	
		if ($this->form_validation->run () == FALSE){			
			
			$this->data['language']=$this->config->item('language');
			$this->data['newurl']=$this->datamanage->createurl();		
			$this->load->view ('installuserview',$this->data);
		
		}
		else
		{	
			    $siteurl= $this->input->post('siteurl');			  
			    $language=$this->input->post('weblanguage');
			    $currenturl=base_url();				   			    	  
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
					 $this->load->view('installfinshview', $this->data);
					 //modify config file---config file;
					 $dir =	"./application/config/config.php";				
						$fh = fopen($dir,'r+');						  
						$data=fread($fh,filesize($dir));
						$data=str_replace($configlanguage, $language, $data);
						$data=str_replace($currenturl, $siteurl, $data);		        		
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
					 $data=str_replace('main', 'product', $data);								        		
					 fclose($fh); 						
					 $handle=fopen($dir,"w"); 
					 fwrite($handle,$data); 
					 fclose($handle);
		          }
				         
					
								
		}
		 
	}
}

