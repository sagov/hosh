<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Category.php 06.04.2016 19:09:33
 */

/**
 * Category
 * 
 * @category   Hosh
 * @package     Hosh_Manager
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Manager_Category
{
    /**
     * Category data
     * @param string $id
     * @return array
     */
    public function getObject($id)
    {
        static $result;
        if (isset($result[$id])){
            return $result[$id];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Category();
        $result[$id] = $package->getObject($id);
        return $result[$id];
    }
    
    /**
     * Categories list
     * @param array $filter
     * @return array
     */
    public function getList($filter = null)
    {
        static $result;
        $key = Zend_Json::encode($filter);
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Category();
        $result[$key] = $package->getList($filter);
        return $result[$key];
    }
    
    public function getKinds()
    {
        static $result;        
        if (isset($result)) {
            return $result;
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Category_Kind();
        $result = $package->getList();
        return $result;
    }
}