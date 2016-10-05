<?php
require_once 'Hosh/Manager/Db/Table/Hosh/Object.php';

class Hosh_Manager_Db_Package_Hosh_Object extends Hosh_Manager_Db_Table_Hosh_Object
{

    public function register ($idclass, $scaption = null, $sname = null, $idstate = null)
    {
        if (empty($idstate)) {
            $m_state = new Hosh_Manager_State();
            $idstate = $m_state->NameToId('NORMAL');
        }
       
        if (empty($sname)) {
            $tablestatus = $this->_getTableStatus();
            $sname = $tablestatus['Auto_increment'];
        }
        
        if (! $this->insert(
                array(
                        'idclass' => $idclass,
                        'idstate' => $idstate,
                        'sname' => $sname,
                        'scaption' => $scaption,
                        'dtinsert' => new Zend_Db_Expr('NOW()')
                ))) {
            return false;
        }
        $adapter = $this->getAdapter();
        $result = $adapter->lastInsertId($this->info('name'));
        return $result;
    }

    

    public function removeObject ($idself)
    {
        $bind['id'] = $idself;
        $bind['bsystem'] = 1;
        $sql = 'DELETE FROM `' . $this->info('name') . '` 
					WHERE id = :id and bsystem <> :bsystem';
        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }
}