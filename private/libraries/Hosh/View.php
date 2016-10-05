<?php
/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_View
 * @copyright   Copyright (c) 2016 Hosh
 * @version     $Id: View.php 01.04.2015 16:46:33
 */
/**
 * @see Zend_View
 */
require_once 'Zend/View.php';

/**
 * Description of file_name
 *
 * @category Hosh
 * @package Hosh_View
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_View extends Zend_View
{

    /**
     *
     * @var unknown
     */
    protected static $_instance = null;
    
    

    /**
     *
     * @return Hosh_View
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->addHelperPath(dirName(__FILE__) . '/View/Helper/', 
                    'Hosh_View_Helper_');
        }
        return self::$_instance;
    }
}