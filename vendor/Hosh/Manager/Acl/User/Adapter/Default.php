<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Default.php 07.04.2016 15:03:17
 */
require_once 'Hosh/Manager/Acl/User/AdapterAbstract.php';

/**
 * Default class Acl User Adapter
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage Acl
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_Acl_User_Adapter_Default extends Hosh_Manager_Acl_User_AdapterAbstract
{

    /* (non-PHPdoc)
     * @see Hosh_Manager_Acl_User_AdapterAbstract::getList()
     */
    public function getList ($filter = null)
    {
        $_table_acl = new Hosh_Manager_Db_Table_Hosh_Acl();
        $_table_user = new Hosh_Manager_Db_Table_Hosh_User();
        $adapter = $_table_acl->getAdapter();
        $bind = array();
        $select = $adapter->select()
            ->from(
                array(
                        'acl' => $_table_acl->info('name')
                ))
            ->join(
                array(
                        'user' => $_table_user->info('name')
                ), 'acl.idowner=user.id and acl.skind=' . $adapter->quote('u'), 
                array(
                        'susername' => 'susername',
                        'suser' => 'suser'
                ));
        if (isset($filter['idvalue'])) {
            $select->where('acl.idvalue = :idvalue');
            $bind['idvalue'] = $filter['idvalue'];
        }
        if (count($bind) > 0) {
            $select->bind($bind);
        }
        return $adapter->fetchAll($select);
    }
}