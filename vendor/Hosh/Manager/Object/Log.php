<?php

class Hosh_Manager_Object_Log
{

    const KIND_UPDATE = 'u';    

    const KIND_VIEW = 'v';

    

    protected static $_instance = null;

    /**
     *
     * @return Hosh_Manager_Object_Log
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function Add ($idobject, $skind, $iduser = null)
    {
        $config = Hosh_Config::getInstance();
        $islog = $config->get('log', false);
        if (! $islog) {
            return false;
        }
        $tbl_log = new Hosh_Manager_Db_Table_Hosh_Object_Log();
        $data = array();
        $data['idobject'] = $idobject;
        $data['skind'] = $skind;
        $data['iduser'] = $iduser;
        return $tbl_log->insert($data);
    }
}