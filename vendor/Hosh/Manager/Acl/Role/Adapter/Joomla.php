<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Joomla.php 07.04.2016 11:24:46
 */
require_once 'Hosh/Manager/Acl/Role/AdapterAbstract.php';
/**
 * Adapter Acl role Joomla
 * 
 * @category   Hosh
 * @package    Hosh_Manager
 * @subpackage Acl 
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Acl_Role_Adapter_Joomla extends Hosh_Manager_Acl_Role_AdapterAbstract
{    
    
    
    /* (non-PHPdoc)
     * @see Hosh_Manager_Acl_Role_AdapterAbstract::getList()
     */
    public function getList ($filter = null)
    {
        static $result;
        $key = Zend_Json::encode($filter);
        if (isset($result[$key])) {
            return $result[$key];
        }
        
        $db = Hosh_Db::get();
        $select = $this->_select();
        $result[$key] = $db->fetchAll($select);        
        
        return $result[$key];
    }

    /* (non-PHPdoc)
     * @see Hosh_Manager_Acl_Role_AdapterAbstract::getObject()
     */
    public function getObject ($id)
    {
        static $result;
        
        if (isset($result[$id])) {
            return $result[$id];
        }
        
        $db = Hosh_Db::get();
        $select = $this->_select();
        $select->where('group.id=:id')
        ->bind(array('id'=>$id));
        $result[$id] = $db->fetchRow($select);        
        
        return $result[$id];
    }
    
    /**
     * @return Zend_Db_Select
     */
    protected function _select()
    {
        $config = Hosh_Config::getInstance();
        $preff = $config->get('db_default')->params->preff;
        $db = Hosh_Db::get();
        $select = $db->select()->from(array('group'=>$preff.'usergroups'),array('id'=>'id','idparent'=>'parent_id','scaption'=>'title','sname'=>'id'));
        return $select;
    }
}