<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @subpackage  Language
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Default.php 07.04.2016 14:42:51
 */
require_once 'Hosh/Manager/Language/AdapterAbstract.php';

/**
 * Language Adapter Default
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_Language_Adapter_Default extends Hosh_Manager_Language_AdapterAbstract
{
    /* (non-PHPdoc)
     * @see Hosh_Manager_Language_AdapterAbstract::getList()
     */
    public function getList()
    {
        static $result;
        if (isset($result)){
            return $result;
        }
        $result = array();
        $package = new Hosh_Manager_Db_Package_Hosh_Language();
        $list = $package->getList();
        foreach ($list as $val){
            $result[$val['sname']] = $val;
        }
        return $result;
    }
    
    /* (non-PHPdoc)
     * @see Hosh_Manager_Language_AdapterAbstract::getListAccess()
     */
    public function getListAccess()
    {
        static $result;
        if (isset($result)){
            return $result;
        }
        $result = array();
        $package = new Hosh_Manager_Db_Package_Hosh_Language();
        $list = $package->getList(array('snamestate'=>'NORMAL','bpublic'=>1));
        foreach ($list as $val){
            $result[$val['sname']] = $val;
        }
        return $result;
    }
}