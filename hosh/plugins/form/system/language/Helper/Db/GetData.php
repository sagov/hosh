<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Language_Helper_Db_GetData extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        $updateparams = $form->getSettings('updateparams');
        $id = $updateparams['id'];
        $m_language = new Hosh_Manager_Language();
        $languagelist = $m_language->getAdapter()->getList();
        foreach ($languagelist as $val) {
            if ($val['id'] == $id) {
                return $val;
            }
        }
        require_once 'Zend/Form/Exception.php';
        throw new Zend_Form_Exception(sprintf('Object "%s" not found', $id));
        return array();
    }
}	