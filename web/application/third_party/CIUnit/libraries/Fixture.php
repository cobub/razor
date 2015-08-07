<?php
/*
* fooStack, CIUnit for CodeIgniter
* Copyright (c) 2008-2009 Clemens Gruenberger
* Released under the MIT license, see:
* http://www.opensource.org/licenses/mit-license.php
*/

/**
 * Fixture Class
 * loads fixtures
 * can be used with CIUnit
 */
class Fixture
{

    function __construct()
    {
        //security measure 1: only load if CIUnit is loaded
        if (!defined('CIUnit_Version')) {
            exit('can\'t load fixture library class when not in test mode!');
        }

        log_message('debug', 'Enter Fixture construct');
    }

    /**
     * loads fixture data $fixt into corresponding table
     */
    function load($table, $fixt)
    {
        log_message('debug', 'Enter Fixture load');

        $this->_assign_db();

        // $fixt is supposed to be an associative array
        // E.g. outputted by spyc from reading a YAML file
        $this->CI->db->simple_query('truncate table ' . $table . ';');

        foreach ($fixt as $id => $row) {
            foreach ($row as $key => $val) {
                if ($val !== '') {
                    $row["`$key`"] = $val;
                }
                //unset the rest
                unset($row[$key]);
            }

            $this->CI->db->insert($table, $row);
        }

        $nbr_of_rows = sizeof($fixt);
        log_message('debug',
            "Data fixture for db table '$table' loaded - $nbr_of_rows rows");
    }

    public function unload($table)
    {
        $this->_assign_db();

        $Q = $this->CI->db->simple_query('truncate table ' . $table . ';');

        if (!$Q) {
            echo $this->CI->db->call_function('error', $this->CI->db->conn_id);
            echo "\n";
            echo "Failed to truncate the table " . $table . "\n\n";
        }
    }

    function load_dw($table, $fixt)
    {
        log_message('debug', 'Enter Fixture load');

        $this->_assign_dw();

        // $fixt is supposed to be an associative array
        // E.g. outputted by spyc from reading a YAML file
        $this->CI->dw->simple_query('truncate table ' . $table . ';');

        foreach ($fixt as $id => $row) {
            foreach ($row as $key => $val) {
                if ($val !== '') {
                    $row["`$key`"] = $val;
                }
                //unset the rest
                unset($row[$key]);
            }

            $this->CI->dw->insert($table, $row);
        }

        $nbr_of_rows = sizeof($fixt);
        log_message('debug',
            "Data fixture for db table '$table' loaded - $nbr_of_rows rows");
    }

    public function unload_dw($table)
    {
        $this->_assign_dw();

        $Q = $this->CI->dw->simple_query('truncate table ' . $table . ';');

        if (!$Q) {
            echo $this->CI->dw->call_function('error', $this->CI->dw->conn_id);
            echo "\n";
            echo "Failed to truncate the table " . $table . "\n\n";
        }
    }

    private function _assign_db()
    {
        if (!isset($this->CI->db) OR
            !isset($this->CI->db->database)
        ) {
            $this->CI =& get_instance();
            $this->CI->load->database();

            //log_message("debug", "db connection id: " . $this->CI->load->database());
            //log_message("debug", "db name: " . $this->CI->db->database);
        }

        //security measure 2: only load if used database ends on '_test'
        $len = strlen($this->CI->db->database);

        if (substr($this->CI->db->database, $len - 5, $len) != '_test') {
            die("\nSorry, the name of your test database must end on '_test'.\n" .
                "This prevents deleting important data by accident.\n");
        }
    }

    private function _assign_dw()
    {
        if (!isset($this->CI->dw) OR
            !isset($this->CI->dw->database)
        ) {
            $this->CI =& get_instance();
            $this->CI->dw = $this->CI->load->database('dw', true);

            //log_message("debug", "dw connection id: " . $this->CI->dw);
            //log_message("debug", "dw name: " . $this->CI->dw->database);
        }

        //security measure 2: only load if used database ends on '_test'
        $len = strlen($this->CI->dw->database);

        if (substr($this->CI->dw->database, $len - 5, $len) != '_test') {
            die("\nSorry, the name of your test database must end on '_test'.\n" .
                "This prevents deleting important data by accident.\n");
        }
    }

}

/* End of file Fixture.php */
/* Location: ./application/third_party/CIUnit/libraries/Fixture.php */