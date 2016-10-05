<?php

class Hosh_Manager_Object_Edit
{
    public function set($idobject,$iduser)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_Object_Edit();
        return $package->Register(array('idobject'=>$idobject,'iduser'=>$iduser));
    }
    
    public function removeItem($idobject,$iduser)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_Object_Edit();
        return $package->removeItem($idobject, $iduser);
    }
    
    public function remove($idobject)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_Object_Edit();
        return $package->remove($idobject);
    }

    public function getListByObject($idobject)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_Object_Edit();
        $result = $package->getListByObject($idobject);
        return $result;
    }
}