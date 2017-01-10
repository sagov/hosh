<?php
require_once 'Hosh/Manager/Db/Table/Hosh/Category/Kind.php';

class Hosh_Manager_Db_Package_Hosh_Category_Kind extends Hosh_Manager_Db_Table_Hosh_Category_Kind
{

    public function register ($data)
    {
        return parent::_register('CATEGORY_KIND', $data);
    }

    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();
        
        $select = $adapter->select();
        $select->from(array(
                'e' => $this->info('name')
        ))
            ->join(array(
                'obj' => $_table_object->info('name')
        ), 'obj.id=e.id', 
                array(
                        'idstate',
                        'dtinsert',
                        'dtupdate',
                        'sname',
                        'scaption',
                        'bsystem'
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

    public function getList ($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        return $adapter->fetchAll($select);
    }

    public function getCount ($param)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($param);
        $select->reset('order')
            ->reset('group')
            ->reset('limitcount')
            ->reset('limitoffset')
            ->reset('columns');
        $expr = new Zend_Db_Expr('count(e.ID) as count');
        $select->columns($expr);
        $count = $adapter->fetchOne($select);
        return $count;
    }
}