<?php

class HoshManager_Model_Menu extends Hosh_Manager_Menu
{
    protected static $_instance = null;
    protected $_adapter = 'Xml';
    protected $_pattern;
    
    public static function getInstance ()
    {
        if (null === self::$_instance) {           
            self::$_instance = new self(); 
            self::$_instance->_setMenu();
        }
        return self::$_instance;
    }
    
    protected function _setMenu()
    {
       static $result;

       if (isset($this->_pattern)) {
           return $this->_pattern;
       }
       
       $adapter = $this->getAdapter($this->_adapter);
       $this->_pattern = $adapter->getPattern();
       $view = Hosh_View::getInstance();       
       
       foreach ($this->_pattern as $key=>$menu){
           if (empty($menu['link'])and(!empty($menu['controller']))and(!empty($menu['action']))){
               $this->_pattern[$key]['link'] = $view->Hosh_Url(array('controller'=>$menu['controller'],'action'=>$menu['action']));
           }
           if ($menu['items']){
               foreach ($menu['items'] as $keyitem=>$item){
                  if (empty($item['link'])and(!empty($item['controller']))and(!empty($item['action']))){
                      $this->_pattern[$key]['items'][$keyitem]['link'] = $view->Hosh_Url(array('controller'=>$item['controller'],'action'=>$item['action']));
                  } 
               }
           }
       }
       return $this;
    }
    
    public function getMenu($key = null)
    {
        if (isset($key)){
            if (!isset($this->_pattern[$key])){
                return false;
            }
            return $this->_pattern[$key];
        }
        return $this->_pattern;
    }
}