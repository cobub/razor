<?php

/*
* fooStack, CIUnit for CodeIgniter
* Copyright (c) 2008-2009 Clemens Gruenberger
* Released under the MIT license, see:
* http://www.opensource.org/licenses/mit-license.php
*/

/**
 * Extending the default phpUnit Framework_TestCase Class
 * providing eg. fixtures, custom assertions, utilities etc.
 */
class CIUnit_TestCase extends PHPUnit_Framework_TestCase
{
    // ------------------------------------------------------------------------

    /**
     * An associative array of table names. The order of the fixtures
     * determines the loading and unloading sequence of the fixtures. This is
     * to help account for foreign key restraints in databases.
     *
     * For example:
     * $tables = array(
     *                'group' => 'group',
     *                'user' => 'user',
     *                'user_group' => 'user_group'
     *                'table_a' => 'table_a_01'
     *            );
     *
     * Note: To test different data scenarios for a single database, create
     * different fixtures.
     *
     * For example:
     * $tables = array(
     *                'table_a' => 'table_a_02'
     *            );
     *
     * @var array
     */
    protected $tables = array();

    // ------------------------------------------------------------------------

    /**
     * The CodeIgniter Framework Instance
     *
     * @var object
     */
    public $CI;

    // ------------------------------------------------------------------------

    /**
     * Constructor
     *
     * @param    string $name
     * @param    array $data
     * @param    string $dataName
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->CI =& get_instance();

        log_message('debug', get_class($this) . ' CIUnit_TestCase initialized');
    }

    /**
     * Set Up
     *
     * This method will run before every test.
     *
     * @return void
     *
     * @author Eric Jones
     */
    protected function setUp()
    {
        // Only run if the $tables attribute is set.
        if (!empty($this->tables)) {
            $this->dbfixt($this->tables);
        }
    }

    /**
     * Tear Down
     *
     * This method will run after every test.
     *
     * @return void
     *
     * @author Eric Jones
     */
    protected function tearDown()
    {
        // Only run if the $tables attribute is set.
        if (!empty($this->tables)) {
            $this->dbfixt_unload($this->tables);
        }
    }

    /**
     * loads a database fixture
     * for each given fixture, we look up the yaml file and insert that into the corresponding table
     * names are by convention
     * 'users' -> look for 'users_fixt.yml' fixture: 'fixtures/users_fixt.yml'
     * table is assumed to be named 'users'
     * dbfixt can have multiple strings as arguments, like so:
     * $this->dbfixt('users', 'items', 'prices');
     */
    protected function dbfixt($table_fixtures)
    {
        if (is_array($table_fixtures)) {
            $this->load_fixt($table_fixtures);
        } else {
            $table_fixtures = func_get_args();
            $this->load_fixt($table_fixtures);
        }

        /**
         * This is to allow the Unit Tester to specifiy different fixutre files for
         * a given table. An example would be the testing of two different senarios
         * of data in the database.
         *
         * @see CIUnitTestCase::tables
         */
        foreach ($table_fixtures as $table => $fixt) {
            $fixt_name = $fixt . '_fixt';
            $table = is_int($table) ? $fixt : $table;

            if (!empty($this->$fixt_name)) {
                CIUnit::$fixture->load($table, $this->$fixt_name);
            } else {
                die("The fixture {$fixt_name} failed to load properly\n");
            }

        }

        log_message('debug', 'Table fixtures "' . join('", "', $table_fixtures) . '" loaded');
    }

    /**
     * dwfixt used for Cobub Razor database ware
     */
    protected function dwfixt($table_fixtures)
    {
        log_message('debug', 'dwfixt');
        if (is_array($table_fixtures)) {
            $this->load_fixt($table_fixtures);
        } else {
            $table_fixtures = func_get_args();
            $this->load_fixt($table_fixtures);
        }

        /**
         * This is to allow the Unit Tester to specifiy different fixutre files for
         * a given table. An example would be the testing of two different senarios
         * of data in the database.
         *
         * @see CIUnitTestCase::tables
         */
        foreach ($table_fixtures as $table => $fixt) {
            $fixt_name = $fixt . '_fixt';
            $table = is_int($table) ? $fixt : $table;

            if (!empty($this->$fixt_name)) {
                CIUnit::$fixture->load_dw($table, $this->$fixt_name);
            } else {
                die("The fixture {$fixt_name} failed to load properly\n");
            }

        }

        log_message('debug', 'Table fixtures "' . join('", "', $table_fixtures) . '" loaded');
    }

    /**
     * DBFixt Unload
     *
     * Since there may be foreign key dependencies in the database, we can't just
     * truncate tables in random order. This method attempts to truncate the
     * tables by reversing the order of the $table attribute.
     *
     * @param    array $table_fixtures Typically this will be the class attribute $table.
     * @param    boolean $reverse Should the method reverse the $table_fixtures array
     * before the truncating the tables?
     *
     * @return void
     *
     * @see CIUnitTestCase::table
     *
     * @uses CIUnit::fixture
     * @uses Fixture::unload()
     *
     * @author Eric Jones <eric.web.email@gmail.com>
     */
    protected function dbfixt_unload(array $table_fixtures, $reverse = true)
    {
        // Should we reverse the order of loading?
        // Helps with truncating tables with foreign key dependencies.
        if ($reverse) {
            // Since the loading of tables took into account foreign key
            // dependencies we should be able to just reverse the order
            // of the database load. Right??
            $table_fixtures = array_reverse($table_fixtures, true);
        }

        // Iterate over the array unloading the tables
        foreach ($table_fixtures as $table => $fixture) {
            CIUnit::$fixture->unload($table);
            log_message('debug', 'Table fixture "' . $fixture . '" unloaded');
        }
    }

    protected function dwfixt_unload(array $table_fixtures, $reverse = true)
    {
        // Should we reverse the order of loading?
        // Helps with truncating tables with foreign key dependencies.
        if ($reverse) {
            // Since the loading of tables took into account foreign key
            // dependencies we should be able to just reverse the order
            // of the database load. Right??
            $table_fixtures = array_reverse($table_fixtures, true);
        }

        // Iterate over the array unloading the tables
        foreach ($table_fixtures as $table => $fixture) {
            CIUnit::$fixture->unload_dw($table);
            log_message('debug', 'Table fixture "' . $fixture . '" unloaded');
        }
    }

    /**
     * fixture wrapper, for arbitrary number of arguments
     */
    function fixt()
    {
        $fixts = func_get_args();
        $this->load_fixt($fixts);
    }

    /**
     * loads a fixture from a yaml file
     */
    protected function load_fixt($fixts)
    {
        foreach ($fixts as $fixt) {
            $fixt_name = $fixt . '_fixt';

            if (file_exists(TESTSPATH . 'fixtures/' . $fixt . '_fixt.yml')) {
                $this->$fixt_name = CIUnit::$spyc->loadFile(TESTSPATH . 'fixtures/' . $fixt . '_fixt.yml');
            } else {
                die('The file ' . TESTSPATH . 'fixtures/' . $fixt . '_fixt.yml doesn\'t exist.');
            }
        }
    }
}

/* End of file CIUnitTestCase.php */
/* Location: ./application/third_party/CIUnit/libraries/CIUnitTestCase.php */