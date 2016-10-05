<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Getliststate extends Hosh_Form_Helper_Abstract
{

    protected $statename = array(
            'DRAFT',
            'NORMAL',            
    );

    public function render ($options)
    {
        $m_state = new Hosh_Manager_State();
        $list = $m_state->getList();
        $result = array();
        $result[''] = '-- --';
        foreach ($list as $val) {
            if (in_array($val['sname'], $this->statename)) {
                $result[$val['id']] = $val['scaption'];
            }
        }
        return $result;
    }
}