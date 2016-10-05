<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Abstract.php 08.04.2016 10:57:43
 */
require_once 'Zend/Db/Table/Abstract.php';
/**
 * Description of file_name
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @subpackage  Db
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Db_Table_Abstract extends Zend_Db_Table_Abstract
{

    /**
     * Preff table
     * @var string
     */
    protected $_namespace = 'db_default';
    
    protected $_name = '';
    
    
    
    
    /**
     * 
     */
    function __construct ()
    {        
        
        $this->_db = Hosh_Db::get($this->_namespace);
        $_namespace = $this->_namespace;
        $config = Hosh_Config::getInstance();
        if (isset($config->get($_namespace)->params->preff)) {
            $preff = $config->get($_namespace)->params->preff;
        } else {
            $preff = null;
        }
        $this->_name = $preff . $this->_name; 
        
    } 

    /* (non-PHPdoc)
     * @see Zend_Db_Table_Abstract::info()
     */
    public function info($key = null)
    {
        static $result;
        if (isset($result[$this->_name][$key])){
            return $result[$this->_name][$key];
        }
       $result[$this->_name][$key] = parent::info($key);
       return $result[$this->_name][$key];
    }
    
   

    /**
     * @param string $idself
     * @param array $bind
     * @return boolean|Zend_Db_Statement_Interface
     */
    public function setObject ($idself, array $bind)
    {
        if (count($bind) == 0){
           return true; 
        }
        $bindsql = array();
        foreach ($bind as $key => $val) {
            $bindsql[] = $key . '=:' . $key;
        }
        $bind['id'] = $idself;
        $sql = 'UPDATE `' . $this->info('name') . '` SET
					' . implode(',', $bindsql) . '
					WHERE id = :id';
        $stmt = $this->getAdapter()->query($sql, $bind);                
        return $stmt;
    }
    
    public function setState ($idself, $idstate)
    {
        return $this->setObject($idself,
                array(
                        'idstate' => $idstate
                ));
    }
    
    public function setStateName ($idself, $statename)
    {
        $m_state = new Hosh_Manager_State();
        $idstate = $m_state->NameToId($statename);
        return $this->setObject($idself,
                array(
                        'idstate' => $idstate
                ));
    }
    
    /**
     * @return mixed
     */
    protected function _getTableStatus()
    {
        $adapter = $this->getAdapter();
        $config = $adapter->getConfig();
        $dbname = $config['dbname'];
        $sql = "SHOW TABLE STATUS  FROM `".$dbname."` LIKE ".$adapter->quote($this->info('name'));
        $result = $adapter->fetchRow($sql);
        return $result;
    }
    
    /**
     * @param string $snameclass
     * @param array $data
     * @throws Zend_Exception
     * @return mixed
     */
    protected function _register ($snameclass,$data)
    {
        $class = new Hosh_Manager_Class();
        $idclass = $class->NameToId($snameclass);        
        $object_package = new Hosh_Manager_Db_Package_Hosh_Object();
        if (! isset($data['scaption'])) {
            $data['scaption'] = null;
        }
        if (! isset($data['sname'])) {
            $data['sname'] = null;
        }
        $idobject = $object_package->register($idclass, $data['scaption'],$data['sname']);
        
        if (empty($idobject)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception(
                    sprintf('When you create an object the error occurred'));
            return false;
        }
        $data['id'] = $idobject;
        $cols = $this->info('cols');
        $bind = array_intersect_key($data, array_flip($cols));
        if (! $this->insert($bind)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception(
                    sprintf('When you create an form the error occurred'));
            return false;
        }
    
        return $idobject;
    }
    
    /**
     * @param mixed $param
     * @return string
     */
    protected function getKey($param)
    {
        return md5(Zend_Json::encode($param));
    }
}