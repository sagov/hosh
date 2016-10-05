<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @subpackage  User
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Joomla.php 07.04.2016 14:42:51
 */
require_once 'Hosh/Manager/User/AdapterAbstract.php';

/**
 * User Adapter Joomla
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_User_Adapter_Joomla extends Hosh_Manager_User_AdapterAbstract
{
    
    /*
     * (non-PHPdoc) @see Hosh_Manager_User_AdapterAbstract::getObject()
     */
    public function getObject ($id)
    {
        static $result;
        if (isset($result[$id])) {
            return $result[$id];
        }
        $db = Hosh_Db::get();
        $select = $this->_select();
        $select->where('user.id=:id')
        ->bind(array('id'=>$id));
        $result[$id] = $db->fetchRow($select); 
        $roles = $this->getAclRoles($id);
        foreach ($roles as $val)
        {
            if ($val['sname'] == 8){
                $result[$id]['bsuper'] = 1;
            }
        }     
        return $result[$id];
    }
    
    /*
     * (non-PHPdoc) @see Hosh_Manager_User_AdapterAbstract::getAclRoles()
     */
    public function getAclRoles ($id)
    {
        static $result;
        if (isset($result[$id])) {
            return $result[$id];
        }        
        $config = Hosh_Config::getInstance();
        $preff = $config->get('db_default')->params->preff;
        $db = Hosh_Db::get();
        $select = $db->select()->from(array('userrole'=>$preff.'user_usergroup_map'),array('iduser'=>'user_id','idrole'=>'group_id'));
        $select->join(array('role'=>$preff.'usergroups'), 'userrole.group_id=role.id',array('sname'=>'id','scaption'=>'title'));
        $select->where('userrole.user_id=:iduser')
        ->bind(array('iduser'=>$id));
        $result[$id] = $db->fetchAll($select);
        return $result[$id];
    }

    /* (non-PHPdoc)
     * @see Hosh_Manager_User_AdapterAbstract::getList()
     */
    public function getList ($filter= null, $count = null, $offset = 0)
    {
        static $result;
        $key = Zend_Json::encode(array(
                $filter,
                $count,
                $offset
        ));
        if (isset($result[$key])) {
            return $result[$key];
        }
        
        $db = Hosh_Db::get();
        $select = $this->_select($filter);
        
        if (isset($count)) {
            if (! isset($offset) or $offset < 0) {
                $offset = 0;
            }
            $select->limit($count, $offset);
        }
        $result[$key] = $db->fetchAll($select);
        
        return $result[$key];
    }
    
    /* (non-PHPdoc)
     * @see Hosh_Manager_User_AdapterAbstract::getTotal()
     */
    public function getTotal($filter = null)
    {
        static $result;
        $key = Zend_Json::encode(array(
                $filter                
        ));
        if (isset($result[$key])) {
            return $result[$key];
        }
        $db = Hosh_Db::get();
        $select = $this->_select($filter);
        $select->reset('order')
        ->reset('group')
        ->reset('limitcount')
        ->reset('limitoffset')
        ->reset('columns');
        $expr = new Zend_Db_Expr('count(user.id) as count');
        $select->columns($expr);        
        $result[$key] = (int)($db->fetchOne($select));
        return $result[$key];
    }
    
    /**
     * @return Zend_Db_Select
     */
    protected function _select($filter= null)
    {
        $config = Hosh_Config::getInstance();
        $preff = $config->get('db_default')->params->preff;
        $db = Hosh_Db::get();
        $select = $db->select()->from(array('user'=>$preff.'users'),array('id'=>'id','suser'=>'name','susername'=>'username'));
        $bind = array();
        if (isset($filter['susername'])) {
            $select->where('user.username = :susername');
            $bind['susername'] = $filter['susername'];
        }
        if (isset($filter['search'])) {
            $select->where(
                    'user.name like :search OR user.username like :search');
            $bind['search'] = $filter['search'] . '%';
        }
        $select->bind($bind);
        return $select;
    }
    
    public function getAuthUser()
    {
        $user   = JFactory::getUser();        
        return $user;
    }
}