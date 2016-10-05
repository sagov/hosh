<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Config
 * @copyright   Copyright (c) 2016 Hosh
 * @version     $Id: Config.php 01.04.2015  16:40:10
 */
require_once 'Zend/Config.php';

/**
 * Hosh Config class
 *
 * @category Hosh
 * @package Hosh_Config
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Config extends Zend_Config
{

    /**
     * @var unknown
     */
    protected static $_instance = null;

    /**
     *
     * @return Hosh_Config
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self(array(), true);
        }
        return self::$_instance;
    }
}