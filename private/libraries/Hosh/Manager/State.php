<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: State.php 07.04.2016 15:24:22
 */

/**
 * Class
 * 
 * @category   Hosh
 * @package    Hosh_Manager
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_State
{
    /**
     * @param string $name
     * @throws Zend_Exception
     * @return mixed
     */
    public function NameToId($sname)
    {
        $list = $this->getListArray();
        if (isset($list[strtolower($sname)])){
            $result[$sname] =  $list[strtolower($sname)]['id'];
            return $result[$sname];
        }        
        require_once 'Zend/Exception.php';
        throw new Zend_Exception(sprintf('State "'.$sname.'" not found'));
        return false;         
    }
    
    
    /**
     * @param array $filter
     * @param number $count
     * @param number $offset
     * @return array
     */
    public function getList($filter = null, $count = null, $offset = 0)
    {
        static $result;
        $key = Zend_Json::encode($filter);
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_State();
        $result[$key] = $package->getList($filter,$count,$offset);
        return $result[$key];
    }
    
    /**
     * @param array $filter
     * @return integer
     */
    public function getTotal($filter = null)
    {
        static $result;
        $key = Zend_Json::encode($filter);
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_State();
        $result[$key] = $package->getTotal($filter);
        return $result[$key];
    }
    
    
    /**
     * Get all list class, sname key
     * @return array
     */
    public function getListArray(){
        static $list;
        if (isset($list)) {
            return $list;
        }        
        $listall = $this->getList();
        $list = array();
        foreach ($listall as $val){
            $list[strtolower($val['sname'])] = $val;
        }
        return $list;
    }
}