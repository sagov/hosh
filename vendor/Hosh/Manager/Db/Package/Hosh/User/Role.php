<?php

require_once 'Hosh/Manager/Db/Table/Hosh/User/Role.php';

/**
 * Class Hosh_Manager_Db_Package_Hosh_User_Role
 */
class Hosh_Manager_Db_Package_Hosh_User_Role extends Hosh_Manager_Db_Table_Hosh_User_Role
{
    /**
     * @param $iduser
     * @param $idrole
     * @return bool
     */
    public function Add($iduser, $idrole)
    {
        $data['iduser'] = $iduser;
        $data['idrole'] = $idrole;
        if (!$this->insert($data)) {
            return false;
        }
        return true;
    }

    /**
     * @param $iduser
     * @return Zend_Db_Statement_Interface
     */
    public function removeUserRoles($iduser)
    {
        $bind['iduser'] = $iduser;
        $sql = 'DELETE FROM `' . $this->info('name') . '` 
					WHERE iduser = :iduser';

        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }

    /**
     * @param null $params
     * @return Zend_Db_Select
     */
    protected function _getSelect($params = null)
    {

        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();

        $select = $adapter->select()
            ->from(array('userrole' => $this->info('name')))
            ->join(array('obj' => $_table_object->info('name')), 'obj.id=userrole.idrole', array('snameaclrole' => 'sname', 'saclrole' => 'scaption'));

        return $select;
    }

    /**
     * @param $param
     * @return mixed
     */
    public function getList($param)
    {
        static $result;
        $key = Zend_Json::encode($param);
        if (isset($result[$key])) return $result[$key];

        $adapter = $this->getAdapter();
        $select = $this->_getSelect();

        $bind = array();
        if (!empty($param['iduser'])) {
            $select->where('userrole.iduser = :iduser');
            $bind['iduser'] = $param['iduser'];
        }
        $select->bind($bind);

        $result[$key] = $adapter->fetchAll($select);
        return $result[$key];

    }
}