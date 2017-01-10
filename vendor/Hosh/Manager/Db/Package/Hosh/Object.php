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

    /**
     * Get data object
     *
     * @param string $idself
     * @return mixed
     */
    public function getObject ($idself)
    {
        if (empty($idself)) {
            return false;
        }
        $adapter = $this->getAdapter();
        $select = $this->_getSelect(
            array(
                'id' => $idself
            ));
        return $adapter->fetchRow($select);
    }

    /**
     *
     * @param array $param
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();

        $select = $adapter->select()
            ->from(
                array(
                    'obj' => $this->info('name')
                ))
            ->join(
                array(
                    'state' => $this->info('name')
                ), 'state.id=obj.idstate',
                array(
                    'snamestate' => 'sname',
                    'sstate' => 'scaption'
                ))
            ->join(
                array(
                    'class' => $this->info('name')
                ), 'class.id=obj.idclass',
                array(
                    'snameclass' => 'sname',
                    'sclass' => 'scaption'
                ));

        $bind = array();
        if (! empty($filter['id'])) {
            $select->where('obj.id = :id');
            $bind['id'] = $filter['id'];
        }

        if (! empty($filter['sname'])) {
            $select->where('obj.sname = :sname');
            $bind['sname'] = $filter['sname'];
        }

        $select->bind($bind);
        return $select;
    }


    /**
     * @param string $idself
     * @return Zend_Db_Statement_Interface
     */
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