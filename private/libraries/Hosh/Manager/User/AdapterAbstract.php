<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @subpackage  User
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: AdapterAbstract.php 07.04.2016 14:42:51
 */

/**
 * Abstract class User Adapter
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
abstract class Hosh_Manager_User_AdapterAbstract
{

    /**
     * Get role users
     * @param string $iduser
     */
    abstract public function getAclRoles ($iduser);

    /**
     * Get data user
     * @param string $iduser
     */
    abstract public function getObject ($iduser);
    
    
    /**
     * Get List
     * @param array $filter
     * @param integer $count
     * @param integer $offset
     */
    abstract public function getList($filter, $count, $offset);
    
    /**
     * @param array $filter
     */
    abstract public function getTotal($filter);
    
    /**
     * 
     */
    abstract public function getAuthUser();
}