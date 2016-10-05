<?php
/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @subpackage  Language
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: AdapterAbstract.php 07.04.2016 14:42:51
 */

/**
 * Abstract class Language Adapter
 *
 * @category Hosh
 * @package Hosh_Manager
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *
 */
abstract class Hosh_Manager_Language_AdapterAbstract
{
    /**
     * 
     */
    abstract public function getList(); 
    
    abstract public function getListAccess();
}