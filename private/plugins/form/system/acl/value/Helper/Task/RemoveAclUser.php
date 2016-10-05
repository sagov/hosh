<?php
require_once dirName(__FILE__) . '/RemoveAcl.php';

class HoshPluginForm_System_Acl_Value_Helper_Task_RemoveAclUser extends HoshPluginForm_System_Acl_Value_Helper_Task_RemoveAcl
{

    protected $skind = 'u';
}