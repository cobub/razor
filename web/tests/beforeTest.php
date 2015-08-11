<?php
 /**
  * Cobub Razor
  *
  * An open source mobile analytics system
  *
  * PHP versions 5
  *
  * @category  MobileAnalytics
  * @package   CobubRazor
  * @author    Cobub Team <open.cobub@gmail.com>
  * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
  * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
  * @link      http://www.cobub.com
  * @since     Version 0.1
  */

/**
 * Hint Message
 */
require_once dirname(dirname(__FILE__)) . '/application/controllers/install/installation.php';


/**
 * BeforeTest
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */

class BeforeTest
{

    private $_hostname;
    private $_username;
    private $_password;
    private $_database_db;
    private $_database_dw;
    private $_prefix_db;
    private $_prefix_dw;
    private $_install;

    /**
     * __construct function
     *
     * @return void
     */
    public function __construct()
    {
        log_message('debug', 'Enter Testsql construct');
        ini_set('memory_limit', '-1');
        $this -> _ci = &get_instance();

        $db = $this -> readTestDBConfig('default');
        $dw = $this -> readTestDBConfig('dw');
        $_hostname        = $db['hostname'];
        $_username        = $db['username'];
        $_password        = $db['password'];
        $_database_db     = $db['database'];
        $_database_dw     = $dw['database'];
        $_prefix_db       = $db['dbprefix'];
        $_prefix_dw       = $dw['dbprefix'];
       
        $this -> _install = new Installation();

        $con = mysqli_connect($_hostname, $_username, $_password);

        if (!$con) {
            die('Could not connect: ' . mysqli_error());
        }

        $file_dir = dirname(dirname(__FILE__)) . "/assets/sql";

        //db
        $this -> operatedatabase($_database_db, $con);
        $sqlPath = $file_dir . "/dbtables.sql";
        $this -> _install -> createdatabasesql($_hostname, $_username, $_password, $_database_db, $sqlPath, null, $_prefix_db);

        //dw
        $this -> operatedatabase($_database_dw, $con);
        $sqlPath = $file_dir . "/dwtables.sql";
        $this -> _install -> createdatabasesql($_hostname, $_username, $_password, $_database_dw, $sqlPath, null, $_prefix_dw);

        //producre
        $sqlPath = $file_dir . "/";
        $this -> _install -> createallproducre($_hostname, $_username, $_password, $_database_dw, $sqlPath, $_database_db . '.' . $_prefix_db, $_prefix_dw);

        mysqli_close($con);
    }

    /**
     * ReadTestDBConfig function
     * @param  string $name name=''
     * @return string
     */
    function readTestDBConfig($name = '')
    {

        if (!defined('ENVIRONMENT') OR !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php')) {
            show_error('The configuration file database.php does not exist.');
        }

        include $file_path;

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

    /**
     * Create or drop database
     * @param  string $db   $db
     * @param  string $conn $conn
     * @return string
     */
    function operatedatabase($db, $conn)
    {
        if (mysqli_query($conn, "Drop DATABASE " . $db)) {
            echo $db . " dropped\n";
        }

        if (mysqli_query($conn, "CREATE DATABASE " . $db)) {
            echo $db . " created\n";
        } else {
            echo "Error creating database: " . mysqli_error();
        }
    }

}





