<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Form.php 05.04.2016 18:02:54
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
class Hosh_Manager_Form
{
    const CLASSNAME = 'FORM';

    const CLASSNAME_KIND = 'FORM_KIND';

    /**
     * Get form object
     *
     * @param string|number $idself
     *            id form
     * @return array|mixed
     */
    public function getObject ($idself)
    {
        static $result;
        if (isset($result[$idself])) {
            return $result[$idself];
        }
        
        $package = new Hosh_Manager_Db_Package_Hosh_Form();
        $result[$idself] = $package->getObject($idself);
        
        return $result[$idself];
    }

    /**
     * Get form оbject from сustom сode
     *
     * @param string $sname            
     * @return array|mixed
     */
    public function getObjectByName ($sname)
    {
        static $result;
        if (isset($result[$sname])) {
            return $result[$sname];
        }
        
        $package = new Hosh_Manager_Db_Package_Hosh_Form();
        $result[$sname] = $package->getObjectByName($sname);
        
        return $result[$sname];
    }

    /**
     * Get list forms
     *
     * @param array $filter            
     * @param number $count            
     * @param number $offset            
     * @return array
     */
    public function getList ($filter = null, $count = 10, $offset = 0)
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
        $package = new Hosh_Manager_Db_Package_Hosh_Form();
        $result[$key] = $package->getList($filter, $count, $offset);
        return $result[$key];
    }

    /**
     * Get count forms
     *
     * @param array $filter            
     * @return integer
     */
    public function getTotal ($filter = null)
    {
        static $result;
        $key = Zend_Json::encode($filter);
        if (isset($result[$key])) {
            return $result[$key];
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Form();
        $result[$key] = $package->getTotal($filter);
        return $result[$key];
    }

    /**
     * Get elements form
     *
     * @param string $idself            
     * @param string $filter            
     * @return array
     */
    public function getElements ($idself, $filter = null)
    {
        static $result;
        
        if (empty($idself)) {
            return false;
        }
        
        $key = Zend_Json::encode(array(
                $idself,
                $filter
        ));
        if (isset($result[$key])) {
            return $result[$key];
        }
        $filter['idowner'] = $idself;
        $package = new Hosh_Manager_Db_Package_Hosh_Form_Element();
        $result[$key] = $package->getList($filter);
        
        return $result[$key];
    }
    
    /**
     * @param string $idself
     * @return Zend_Db_Statement_Interface
     */
    public function removeElements($idself)
    {        
        $package = new Hosh_Manager_Db_Package_Hosh_Form();
        return $package->removeElements($idself);
    }
    

    

    /**
     * List kinds form
     * 
     * @return array
     */
    public function getKinds ()
    {
        static $result;
        if (isset($result)) {
            return $result;
        }
        $package = new Hosh_Manager_Db_Package_Hosh_Form_Kind();
        $list = $package->getList();
        foreach ($list as $val) {
            $result[$val['sname']] = $val;
        }
        return $result;
    }
    
    
}