<?php

/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: List.php 05.04.2016 18:02:54
 */

/**
 * Manager List Class
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_List
{
    const CLASSNAME = 'LIST';


    /**
     * Get list object
     *
     * @param string|number $idself
     *            id list
     * @return array|mixed
     */
    public function getObject ($idself)
    {
        static $result;
        if (isset($result[$idself])) {
            return $result[$idself];
        }

        $package = new Hosh_Manager_Db_Package_Hosh_List();
        $result[$idself] = $package->getObject($idself);

        return $result[$idself];
    }

    /**
     * Get list оbject from сustom сode
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

        $package = new Hosh_Manager_Db_Package_Hosh_List();
        $result[$sname] = $package->getObjectByName($sname);

        return $result[$sname];
    }

    /**
     * Get list lists
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
        $package = new Hosh_Manager_Db_Package_Hosh_List();
        $result[$key] = $package->getList($filter, $count, $offset);
        return $result[$key];
    }

    /**
     * Get count lists
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
        $package = new Hosh_Manager_Db_Package_Hosh_List();
        $result[$key] = $package->getTotal($filter);
        return $result[$key];
    }

    /**
     * Get elements list
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
        $package = new Hosh_Manager_Db_Package_Hosh_List_Element();
        $result[$key] = $package->getList($filter);

        return $result[$key];
    }

    /**
     * @param string $idself
     * @return Zend_Db_Statement_Interface
     */
    public function removeElements($idself)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_List();
        return $package->removeElements($idself);
    }

}