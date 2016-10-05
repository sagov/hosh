<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Joomla.php 07.04.2016 15:03:17
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
class Hosh_Manager_Acl_User_Adapter_Joomla extends Hosh_Manager_Acl_User_AdapterAbstract
{

    /* (non-PHPdoc)
     * @see Hosh_Manager_Acl_User_AdapterAbstract::getList()
     */
    public function getList ($filter = null)
    {
        $_table_acl = new Hosh_Manager_Db_Table_Hosh_Acl();
        $config = Hosh_Config::getInstance();
        $preff = $config->get('db_default')->params->preff;
        $adapter = $_table_acl->getAdapter();
        $bind = array();
        $select = $adapter->select()
            ->from(
                array(
                        'acl' => $_table_acl->info('name')
                ))
            ->join(
                array(
                        'user' => $preff.'users'
                ), 'acl.idowner=user.id and acl.skind=' . $adapter->quote('u'), 
                array(
                        'susername' => 'username',
                        'suser' => 'name'
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