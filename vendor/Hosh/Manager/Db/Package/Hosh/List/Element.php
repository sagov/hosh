<?php

/**
 * Hosh Framework
 *
 * @category    Hosh
 * @package     Hosh_Manager
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Element.php 06.04.2016 15:37:27
 */
require_once 'Hosh/Manager/Db/Table/Hosh/List/Element.php';

/**
 * Description of file_name
 *
 * @category Hosh
 * @package Hosh_Manager
 * @subpackage  Db
 * @author Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright Copyright (c) 2016 Hosh
 *
 */
class Hosh_Manager_Db_Package_Hosh_List_Element extends Hosh_Manager_Db_Table_Hosh_List_Element
{

    /**
     * Register new list element
     *
     * @param array $bind
     * @return mixed
     */
    public function register ($bind)
    {
        $cols = $this->info('cols');
        $bind = array_intersect_key($bind, array_flip($cols));
        if (empty($bind['idstate'])) {
            $m_state = new Hosh_Manager_State();
            $bind['idstate'] = $m_state->NameToId('NORMAL');
        }
        if (! $this->insert($bind)) {
            return false;
        }
        $adapter = $this->getAdapter();
        $result = $adapter->lastInsertId($this->info('name'));
        return $result;

    }

    /**
     *
     * @param array $filter
     * @return Zend_Db_Select
     */
    protected function _getSelect ($filter = null)
    {
        $adapter = $this->getAdapter();
        $_table_object = new Hosh_Manager_Db_Table_Hosh_Object();
        $_table_state = new Hosh_Manager_Db_Table_Hosh_State();

        $select = $adapter->select()
            ->from(
                array(
                    'e' => $this->info('name')
                ))
            ->join(
                array(
                    'state' => $_table_object->info('name')
                ), 'state.id=e.idstate',
                array(
                    'snamestate' => 'sname',
                    'sstate' => 'scaption'
                ));

        $bind = array();
        if (isset($filter['idowner'])) {
            if (is_array($filter['idowner'])) {
                $select->where('e.idowner IN (?)', $filter['idowner']);
            } else {
                $select->where('e.idowner = :idowner');
                $bind['idowner'] = $filter['idowner'];
            }
        }
        if (isset($filter['snamestate'])) {
            $select->where('state.sname = :snamestate');
            $bind['snamestate'] = $filter['snamestate'];
        }
        $select->bind($bind);

        return $select;
    }

    /**
     * Get list elements
     *
     * @param array $filter
     * @return array
     */
    public function getList ($filter = null)
    {
        $adapter = $this->getAdapter();
        $select = $this->_getSelect($filter);
        $select->order(
            array(
                'e.norder',
                'e.id'
            ));
        return $adapter->fetchAll($select);

    }

    /**
     * Get data list element
     *
     * @param string $idself
     * @return array
     */
    public function getObject ($idself)
    {
        if (empty($idself)) {
            return false;
        }
        $adapter = $this->getAdapter();
        $select = $this->_getSelect();
        $select->where('e.id = :id')->bind(
            array(
                'id' => $idself
            ));
        return $adapter->fetchRow($select);
    }

    public function remove ($idself)
    {
        $bind['id'] = $idself;
        $sql = 'DELETE FROM `' . $this->info('name') . '`
					WHERE id = :id';
        $stmt = $this->getAdapter()->query($sql, $bind);
        return $stmt;
    }

}