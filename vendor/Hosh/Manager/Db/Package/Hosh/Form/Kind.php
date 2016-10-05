<?php
require_once 'Hosh/Manager/Db/Table/Hosh/Extension/Kind.php';

class Hosh_Manager_Db_Package_Hosh_Form_Kind extends Hosh_Manager_Db_Table_Hosh_Form_Kind
{
    public function register ($data)
    {
        return parent::_register('FORM_KIND', $data);
    }

    public function getList ($filter = null, $count = null, $offset = 0)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        if (isset($count)) {
            if (! isset($offset) or $offset < 0) {
                $offset = 0;
            }
            $select->limit($count, $offset);
        }
        return $adapter->fetchAll($select);
    }

    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        
        $select = $adapter->select()
            ->from(array(
                'fkind' => $this->info('name')
        ))
            ->join(array(
                'obj' => $_table_object->info('name')
        ), 'obj.id=fkind.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'sname',
                        'scaption'
                ))
            ->join(array(
                'state' => $_table_object->info('name')
        ), 'state.id=obj.idstate', 
                array(
                        'snamestate' => 'sname',
                        'sstate' => 'scaption'
                ));
        
        $bind = array();
        if (! empty($filter['sname'])) {
            $select->where('obj.sname = :sname');
            $bind['sname'] = $filter['sname'];
        }
        $select->bind($bind);
        
        return $select;
    }
    

}