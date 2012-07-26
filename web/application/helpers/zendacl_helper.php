<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 
 * ZendAcl Helper
 
 *
 
 * Contains shortcuts to well used Userlib functions
 
 * Using the Zend Framework ACL Library in Codeigniter 

 * @package         CentWare
 
 * @subpackage    Helpers
 
 * @author          Liu Guoqing
 
 * @copyright       Copyright (c) 2010
 
 * @license         

 * @link            

 * @filesource
 
 */
 
 
 
// ---------------------------------------------------------------------------
 
/*
 
 * 

 * check_acl
 
 * check_acl 权限控制设置
 
 * $resource 资源
 
 * $action   动作
 
 * @author Liuguoqing
 
 */
 
if( ! function_exists('check_acl'))
 
{
 
    function check_acl($resource,$action=NULL)
 
    {
 
       $CI = & get_instance();
 
       $role=$CI->session->userdata('Roelid');
 
       if($action=='read'){
 
           return $CI->acl->can_read($role, $resource);
 
       }
 
       if($action=='add'){
 
           return $CI->acl->can_write($role, $resource);
 
       }
 
       if($action=='modify'){
 
           return $CI->acl->can_modify($role, $resource);
 
       }
 
       if($action=='delete'){
 
           return $CI->acl->can_delete($role, $resource);
 
       }
 
       if($action=='publish'){
 
           return $CI->acl->can_publish($role, $resource);
 
       }
 
       return FALSE;
 
    }
 
}
