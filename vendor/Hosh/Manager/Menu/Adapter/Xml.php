<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Xml.php 08.04.2016 10:50:13
 */
require_once 'Hosh/Manager/Menu/AdapterAbstract.php';

/**
 * Adapter XML
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage Menu
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *           
 */
class Hosh_Manager_Menu_Adapter_Xml extends Hosh_Manager_Menu_AdapterAbstract
{

    /**
     * @var string
     */
    protected $_path = '';

    /* (non-PHPdoc)
     * @see Hosh_Manager_Menu_AdapterAbstract::getPattern()
     */
    public function getPattern ()
    {
        $config = Hosh_Config::getInstance();
        $adapter = $config->get('adapter');
        $path = dirName(__FILE__) . '/../Xml/'.$adapter.'/';
        $dir = new Hosh_Dir();
        $files = $dir->getListScan($path, array(
                'isdir' => false,
                'ext' => 'xml'
        ));
        $result = array();
        foreach ($files as $key => $file) {
            $xml = new Zend_Config_Xml($path . $file);
            $arrmenu = $xml->toArray();
            
            if (isset($arrmenu['items']['item'])) {
                $arrmenu['items'] = $arrmenu['items']['item'];
            }
            $keyname = strtolower(substr($file, 0, strrpos($file, '.')));
            $result[$keyname] = $arrmenu;
        }
        return $result;
    }
}