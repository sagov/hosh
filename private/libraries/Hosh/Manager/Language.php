<?php

class Hosh_Manager_Language
{
    /**
     * Adapter
     * @var string
     */
    protected $_adapter = 'Default';
    
    /**
     *
     * @param string $adapter
     * @return Hosh_Manager_Language_AdapterAbstract
     */
    public function getAdapter ($adapter = null)
    {
        static $result;
    
        if (isset($result)) {
            return $result;
        }
    
        if (empty($adapter)) {
            $adapter = $this->_adapter;
        }
    
        $adapterName = 'Hosh_Manager_Language_Adapter_';
        $adapterName .= str_replace(' ', '_',
                ucwords(str_replace('_', ' ', strtolower($adapter))));
    
        if (! class_exists($adapterName)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($adapterName);
        }
        $result = new $adapterName();
        if (! $result instanceof Hosh_Manager_Language_AdapterAbstract) {
            require_once 'Zend/Db/Exception.php';
            throw new Zend_Db_Exception(
                    "Adapter class '$adapterName' does not extend Hosh_Manager_Language_AdapterAbstract");
        }
    
        return $result;
    }
    
    protected function _get($sname)
    {
        $list = $this->getAdapter()->getList();
        if (isset($list[$sname])){
           return $list[$sname];
        }
        return false;
    }
}