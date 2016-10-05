<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Role_Helper_GetParent extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $updateparams = $form->getSettings('updateparams');        
        $translate = $form->getTranslator();
        $idselected = $updateparams['id'];
        
        $role = new Hosh_Manager_Acl_Role();
        $adapter = $role->getAdapter();
        $list = $adapter->getList();
        $applist = Hosh_Application_List::getInstance();
        $treelist = $applist->toTree($list);
        
        $result = array();
        $result[''] = '-- --';
        $array_disable = array();
        $valselected = array();
        $disable = false;
        
        foreach ($treelist as $val) {
            
            $xhtml_level = $applist->getLevelCaption($val['level']);
            if ($disable) {
                if ((int) ($val['level']) <= (int) ($valselected['level']))
                    $disable = false;
            }
            if (($val['id'] == $idselected) or ($disable)) {
                $array_disable[] = $val['id'];
                if ($val['id'] == $idselected) {
                    $valselected = $val;
                }
                $disable = true;
            }
            $result[$val['id']] = $xhtml_level . $translate->_($val['scaption']) . ' (' .
                     $val['sname'] . ')';
        }
        
        if (count($array_disable) > 0) {
            $element = $form->getElement($options['element_name']);
            $element->setOptions(array(
                    'disable' => $array_disable
            ));
        }
        
        return $result;
    }
}	