<?php

class Hosh_Manager_Object
{
    protected $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    /**
     * @return boolean
     */
    public function isLock()
    {          
        $list = $this->getListEditItems();
        return (count($list)>0) ? true : false;
    }
    
    /**
     * @return array
     */
    public function getListEditItems()
    {
        static $result;
        if (isset($result[$this->id])){
            return $result[$this->id];
        }
        $objedit = new Hosh_Manager_Object_Edit();
        $list = $objedit->getListByObject($this->id);
        
        $auth = Hosh_Manager_User_Auth::getInstance();
        $iduser = $auth->getId();
        $res = array();
        foreach ($list as $val)
        {
            if ($val['iduser'] != $iduser)
            {
                $res[] = $val;
            }
        
        } 
        $result[$this->id] = $res;
        return $result[$this->id];
    }
    
    /**
     * 
     * @return Zend_Db_Statement_Interface
     */
    public function Edit()
    {
        $auth = Hosh_Manager_User_Auth::getInstance();
        $iduser = $auth->getId();
        $objedit = new Hosh_Manager_Object_Edit();
        return $objedit->set($this->id, $iduser);
    }
    
    /**
     * 
     * @return Zend_Db_Statement_Interface
     */
    public function EndEdit()
    {
        $auth = Hosh_Manager_User_Auth::getInstance();
        $iduser = $auth->getId();
        $objedit = new Hosh_Manager_Object_Edit();
        return $objedit->removeItem($this->id, $iduser);
    }
}