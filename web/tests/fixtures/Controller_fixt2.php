<?php

/*
* fooStack, CIUnit
* Copyright (c) 2008 Clemens Gruenberger
* Released with permission from www.redesignme.com, thanks guys!
* Released under the MIT license, see:
* http://www.opensource.org/licenses/mit-license.php
*/

/*
* CIUnit Controller fixture, leave it here!
*/

class Controller_fixt2 extends Controller
{

    function test($in)
    {
        $out = $in;
        return $out;
    }

    function show1($data)
    {
        $this->load->viewfile(TESTSPATH . 'fixtures/view_fixt.php', array('data' => $data));
    }

    function show2()
    {
        $some_var = array(1, 2, 3);
        $data = array(
            'data' => $some_var
        );
        $this->load->viewfile(TESTSPATH . 'fixtures/view_fixt.php', $data);
    }

    function show3()
    {
        $some_var = array(1, 2, 3);
        $data = array(
            'data' => $some_var
        );
        $this->load->viewfile(TESTSPATH . 'fixtures/view_fixt2.php', $data);
    }

}

?>