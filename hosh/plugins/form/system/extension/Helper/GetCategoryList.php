<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Extension_Helper_GetCategoryList extends Hosh_Form_Helper_Abstract
{

    protected $_snamekind = array(
            'FORM_HELPER' => 'FORM_HELPER',
            'FORM_ELEMENT' => 'FORM_ELEMENT'
    );

    public function render ($options)
    {
        $form = $this->getObject();        
        $snamekind = strtoupper($form->getData('snamekind'));                
        if (!isset($this->_snamekind[$snamekind])){
            return array();
        }
        
        $manager_category = new Hosh_Manager_Category();
        $list = $manager_category->getList(array(
                'skindname' => $this->_snamekind[$snamekind]
        ));
        if (count($list) == 0) {
            return array();
        }
        $applist = Hosh_Application_List::getInstance();
        $treelist = $applist->toTree($list);
        
        $result = array();
        foreach ($treelist as $val) {
            
            $xhtml_level = $applist->getLevelCaption($val['level']);
            $result[$val['id']] = $xhtml_level . $val['scaption'] ;
        }
        
        return $result;
    }
}	