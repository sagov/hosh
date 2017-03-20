<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Extension_Helper_GetFormList extends Hosh_Form_Helper_Abstract
{

    protected $_snamekind = array(
            'FORM_HELPER' => 'HELPER',
            'FORM_ELEMENT' => 'ELEMENT',
            'LIST_HELPER' => 'HELPER',
            'LIST_ELEMENT' => 'ELEMENT',
    );

    public function render ($options)
    {
        $form = $this->getObject();
        $snamekind = strtoupper($form->getData('snamekind'));
        if (! isset($this->_snamekind[$snamekind])) {
            return array();
        }
        
        $form_manager = new Hosh_Manager_Form();
        $list = $form_manager->getList(
                array(
                        'snamekind' => $this->_snamekind[$snamekind]
                ),null,0);
        
        $result = array();
        $result[''] = '-- --';
        foreach ($list as $val) {
            $result[$val['id']] = $val['scaption'];
        }
        return $result;
    }
}