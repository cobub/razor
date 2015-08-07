<?php

class CommonDbfix extends CIUnit_TestCase
{

    public static function loadCommonDbfix($obj)
    {
        $obj->dbfixt('razor_users');
        $obj->dbfixt('razor_ci_sessions');
        $obj->dbfixt('razor_user_permissions');
        $obj->dbfixt('razor_user_resources');
        $obj->dbfixt('razor_user_roles');
        $obj->dbfixt('razor_user2role');
        $obj->dbfixt('razor_channel');
    }

    public static function unloadCommonDbfix($obj)
    {

    }

    public function testloadCommonDbfix()
    {
        $this->assertTrue(true);
    }
}

?>