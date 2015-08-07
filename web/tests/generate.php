<?php

//echo 'with php'; die();

require_once dirname(__FILE__) . '/../application/third_party/CIUnit/bootstrap_phpunit.php';
include_once dirname(__FILE__) . '/getops.php';

class Generate
{
    function __construct()
    {
        $this->CI = & set_controller('CI_Controller');
        $this->CI->load->database();
    }

    function get_table_fields($table)
    {
        $result = $this->select_from_table($table);

        $i = 0;
        $fields = array();
        while ($i < mysql_num_fields($result)) {
            $fields[$i] = mysql_fetch_field($result, $i);
            /*
                PROPERTIES
                $field->blob
                $field->max_length
                $field->multiple_key
                $field->name
                $field->not_null
                $field->numeric
                $field->primary_key
                $field->table
                $field->type
                $field->def
                $field->unique_key
                $field->unsigned
                $field->zerofill
            */
            $i++;
        }
        return $fields;
    }

    function select_from_table($table, $limit = 1)
    {
        $query = "SELECT * FROM `$table`";
        if ($limit > 0) {
            $query .= " LIMIT " . (int)$limit;
        }
        $res = mysql_query($query) or die(mysql_error());
        return $res;

    }

    function get_table_data($table, $limit = 5)
    {
        $table_fields = $this->get_table_fields($table);
        $res = $this->select_from_table($table, $limit);
        $data = Array();
        while (($row = mysql_fetch_assoc($res)) !== false) {
            $i = 0;
            foreach ($row as $field => $val) {
                if (!$table_fields[$i]->numeric) {
                    $row[$field] = '"' . addSlashes($val) . '"';
                }
                $i++;
            }
            $data['row' . (count($data) + 1)] = $row;
        }
        mysql_free_result($res);
        return $data;
    }

    function fixtures($args = Array())
    {
        if (substr($this->CI->db->database, -5, 5) != '_test') {
            die("\nSorry, the name of your test database must end on '_test'.\n" .
                "This prevents deleting important data by accident.\n");
        }

        //$this->CI->db->database = preg_replace("#_test$#", "_development", $this->CI->db->database);
        if (!$this->CI->db->db_select()) {
            die("\nCould not select development database.\n");
        }

        $opts = getopts(array(
            'rows' => array('switch' => 'n', 'type' => GETOPT_VAL, 'default' => 5),
            'fixtures' => array('switch' => 'f', 'type' => GETOPT_MULTIVAL),
            'output' => array('switch' => 'o', 'type' => GETOPT_VAL, 'default' => '/fixtures')
        ), $args);


        $rows = $opts['rows'];
        $fixtures = $opts['fixtures'];
        $output = rtrim(str_replace('\\', '/', $opts['output']), '/') . '/';
        if (!@chdir(dirname(__FILE__) . '/' . $output)) {
            die("\nOutput directory '$output' does not exist.\n");
        }


        $tables = $this->CI->db->list_tables();
        if (count($fixtures) == 0) {
            $fixtures = $tables;
        } else {
            /* check tables */
            foreach ($fixtures as $fixture) {
                if (!in_array($fixture, $tables)) {
                    die("\nTable `$fixture` does not exist.\n");
                }
            }
        }


        foreach ($fixtures as $fixture) {
            $filename = $fixture . '_fixt.yml';
            $data = $this->get_table_data($fixture, $rows);
            $yaml_data = CIUnit::$spyc->dump($data);

            $yaml_data = preg_replace('#^\-\-\-#', '', $yaml_data);

            /* don't check if the file already exists */
            file_put_contents($filename, $yaml_data);
        }
    }
}

$args = $_SERVER['argv'];
$self = array_shift($args);

$generate = new Generate;
$generate_what = array_shift($args);

if (!method_exists($generate, $generate_what)) {
    die("\nMethod '$generate_what' is invalid.
Usage:
	php generate.php fixtures <options>
Options:
	-f  tables of which fixtures should be created (-f table1 -f table2 etc)
		 omitting the -f option, selects all tables in the database.
	-n  number of rows in fixtures <default: 5>
	-o  output directory\n");
} else {
    $generate->$generate_what($_SERVER['argv']);
}
