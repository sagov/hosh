<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Extension.php 05.04.2016 18:02:54
 */

/**
 * Manager Form Class
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_Extension
{
    /**
     * Get form object extension
     *
     * @param string $idself
     * @return array
     */
    public function getObject ($idself)
    {
        static $result;
        if (isset($result[$idself])) {
            return $result[$idself];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Extension();
        $result[$idself] = $package->getObject($idself);
        return $result[$idself];
    }
    
    /**
     * Get list extensions
     *
     * @param array $filter
     * @param number $count
     * @param number $offset
     * @return array
     */
    public function getList($filter = null,$count = 10,$offset = 0){
        static $result;
        $key = Zend_Json::encode(array($filter,$count,$offset));
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Extension();
        $result[$key] = $package->getList($filter,$count,$offset);
        return $result[$key];
    }
    
    /**
     * Get count extensions
     *
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
        $package = new Hosh_Manager_Db_Package_Hosh_Extension();
        $result[$key] = $package->getTotal($filter);
        return $result[$key];
    }
}