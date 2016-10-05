<?php

/** Zend_Locale */
require_once 'Zend/Locale.php';

/**
 * Zend_Translate_Adapter
 */
require_once 'Zend/Translate/Adapter/Ini.php';

class Hosh_Translate_Adapter_Hosh extends Zend_Translate_Adapter_Ini
{
    
    
    protected function _loadTranslationData ($data, $locale, 
            array $options = array())
    {
        $config = Hosh_Config::getInstance();
        $path = $config->get('path');
        $path_locale = $path . '/extensions/language/';
        $data = $path_locale . $locale . '/' . trim($data) . '.ini';
        
        if (file_exists($data)) {
            return parent::_loadTranslationData($data, $locale, $options);
        }
    }

    public function toString ()
    {
        return "Hosh";
    }
}