<?php

class beforeTest
{

    private $hostname;
    private $username;
    private $password;
    private $database_db;
    private $database_dw;
    private $prefix_db;
    private $prefix_dw;

    public function __construct()
    {
        log_message('debug', 'Enter Testsql construct');
        ini_set('memory_limit', '-1');
        $this->_ci =& get_instance();

        $db = $this->readTestDBConfig('default');
        $dw = $this->readTestDBConfig('dw');
        $hostname = $db['hostname'];
        $username = $db['username'];
        $password = $db['password'];
        $database_db = $db['database'];
        $database_dw = $dw['database'];
        $prefix_db = $db['dbprefix'];
        $prefix_dw = $dw['dbprefix'];

        $con = mysqli_connect($hostname, $username, $password);

        if (!$con) {
            die('Could not connect: ' . mysqli_error());
        }

        $file_dir = dirname(__FILE__) . "/../assets/sql";
        $this->operatedatabase($database_db, $con);
        $this->createtables($file_dir, "dbtables.sql", $con, $database_db, $prefix_db);

        $this->operatedatabase($database_dw, $con);
        $this->createtables($file_dir, "dwtables.sql", $con, $database_dw, $prefix_dw);
        $this->createallproducre($con, $file_dir, $database_dw, $prefix_dw, $database_db, $prefix_db);
        mysqli_close($con);
    }

    function readTestDBConfig($name = '')
    {

        if (!defined('ENVIRONMENT') OR !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php')) {
            show_error('The configuration file database.php does not exist.');
        }

        include($file_path);

        if (!isset($db) OR count($db) == 0) {
            show_error('No database connection settings were found in the database config file.');
        }

        if ($name != '') {
            $active_group = $name;
        }

        if (!isset($active_group) OR !isset($db[$active_group])) {
            show_error('You have specified an invalid database connection group.');
        }

        return $db[$active_group];
    }


    /******************create or drop database**************************/
    function operatedatabase($db, $conn)
    {
        if (mysqli_query($conn,"Drop DATABASE " . $db)) {
            echo $db . " dropped\n";
        }
        if (mysqli_query($conn,"CREATE DATABASE " . $db)) {
            echo $db . " created\n";
        } else {
            echo "Error creating database: " . mysqli_error();
        }
    }

    /***************create tables****************************/
    function createtables($file_dir, $file_name, $conn, $db, $prefix)
    {
        mysqli_select_db($conn,$db);

        $sqlpath = realpath($file_dir . "/" . $file_name);

        if (!file_exists($sqlpath))
            return false;

        $handle = fopen($sqlpath, 'rb');
        $sqlStr = fread($handle, filesize($sqlpath));
        //Sql syntax statement separator preg_split
        $segment = explode(";", trim($sqlStr));

        //Remove comments and extra blank line
        $newSegment = array();
        $commenter = array('#', '--');
        foreach ($segment as $statement) {
            $sentence = explode("\n", $statement);

            $newStatement = array();

            foreach ($sentence as $subSentence) {
                if ('' != trim($subSentence)) {
                    //To judge whether a comment
                    $isComment = false;
                    foreach ($commenter as $comer) {
                        if (preg_match("/^(" . $comer . ")/", trim($subSentence))) {
                            $isComment = true;
                            break;
                        }
                    }
                    if (!$isComment)
                        $newStatement[] = $subSentence;
                }
            }


            $statement = $newStatement;

            array_push($newSegment, $statement);
        }


        //add table name prefix
        $prefixsegment = array();
        if ('' != $prefix) {
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
            foreach ($newSegment as $statement) {
                $tokens = explode(" ", @$statement[0]);
                $tableName = array();

                $tableName = $this->gettablename($sqlFlagTree, $tokens, 0, $tableName);

                if (empty($tableName['leftWall'])) {
                    //Add the prefix
                    $newTableName = $prefix . $tableName['name'];

                } else {
                    //Add the prefix
                    $newTableName = $tableName['leftWall'] . $prefix . substr($tableName['name'], 1);
                }
                $tablesuffix = date('Ym');
                $statement[0] = str_replace("umsinstall_", $prefix, @$statement[0]);
                $statement[0] = str_replace("TABLESUFFIX", $tablesuffix, @$statement[0]);
                array_push($prefixsegment, $statement);
            }

        }

        $combiansegment = array();
        //Combination of sql statement
        foreach ($prefixsegment as $statement) {
            $newStmt = '';
            foreach ($statement as $sentence) {
                if ($sentence != null)
                    $newStmt = $newStmt . trim($sentence) . "\n";
            }
            $statement = $newStmt;
            array_push($combiansegment, $statement);

        }
        
        $this->runsqlfile($conn, $db, $combiansegment, $prefix);
        return true;

    }


    //get table name from .sql file
    function gettablename($sqlFlagTree, $tokens, $tokensKey = 0, $tableName = array())
    {
        $regxLeftWall = "^[\`\'\"]{1}";

        if (count($tokens) <= $tokensKey)
            return false;

        if ('' == trim($tokens[$tokensKey])) {
            $this->gettablename($sqlFlagTree, $tokens, $tokensKey + 1, $tableName);
        } else {
            foreach ($sqlFlagTree as $flag => $v) {
                if (preg_match("/" . $flag . "/", $tokens[$tokensKey])) {
                    if (0 == $v) {
                        $tableName['name'] = $tokens[$tokensKey];
                        if (preg_match("/" . $regxLeftWall . "/", $tableName['name'])) {
                            $tableName['leftWall'] = $tableName['name']{
                            0};
                        }

                        return $tableName;
                    } else {
                        return $this->gettablename($v, $tokens, $tokensKey + 1, $tableName);
                    }
                }
            }
        }

        return false;
    }

    // create all producre

    function createallproducre($conn, $file_dir, $sqlname, $tablehead, $db_name, $db_table_prefix)
    {
        $replacedw = $sqlname . "." . $tablehead;
        $replacedb = $db_name . "." . $db_table_prefix;

        //create store procedure rundaily
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_rundaily.sql', 'sp_rundaily', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }
        //create store procedure rundim
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_rundim.sql', 'sp_rundim', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/dwviews.sql', 'dwviews', $replacedb, $tablehead, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }
        //create store procedure runfact
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_runfact.sql', 'sp_runfact', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_resetbit.sql', 'sp_resetbit', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
         if (!$ret) {
             return false;
         }
        //create store procedure runmonthly
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_runmonthly.sql', 'sp_runmonthly', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }
        //create store procedure runsum
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_runsum.sql', 'sp_runsum', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }
        //create store procedure runweekly
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/sp_runweekly.sql', 'sp_runweekly', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }

        //create store procedure runweekly
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/func_defined_condition.sql', 'defined_condition', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }

        //create store procedure runweekly
        $ret = $this->createproducre($conn, $sqlname, $file_dir . '/func_defined_value.sql', 'defined_value', $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir);
        if (!$ret) {
            return false;
        }

    }

    //modify  procedure  .sql file
    function createproducre($conn, $sqlname, $sqlPath, $storename, $replacedb, $replacedw, $delimiter = '(;\n)|((;\r\n))|(;\r)', $prefix = '', $commenter = array('#', '--'), $file_dir)
    {
        //judge if exist file
        if (!file_exists($sqlPath))
            return false;
        $handle = fopen($sqlPath, 'rb');
        $date = date('Y-m-d');
        if ($handle) {
            $sqlStr = '';
            while (!feof($handle)) {
                $sqlStrtemp = fgets($handle);

                $sqlStr = $sqlStr . str_replace("databaseprefix.umsdatainstall_", $replacedb, @$sqlStrtemp);

                $sqlStr = str_replace("umsinstall_", $replacedw, $sqlStr);
                $sqlStr = str_replace("SYSTEM_LAUNCH_DATE", $date, $sqlStr);
            }

        }
        fclose($handle);

        //$filepath=$file_dir.'/'.$storename.".sql";

        //$handle = fopen($filepath,'rb');
        //$sqlStr = fread($handle,filesize($filepath));
        $segment = explode("--$$", trim($sqlStr));
        
        $this->runsqlfile($conn, $sqlname, $segment, $prefix);
        unset($sqlStr);
        return true;
    }

    //run sql file
    function runsqlfile($conn, $sqlname, $sqlArray, $tablehead)
    {
        mysqli_select_db($conn,$sqlname);
        foreach ($sqlArray as $sql) {
            if ($sql!='') {
                mysqli_query($conn,$sql);
            }
        }
    }
}

?>
