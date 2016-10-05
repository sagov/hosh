<?php

class Hosh_Form_Helper_Hosh_GetCategoryListHelper extends Hosh_Form_Helper_Abstract
{
	
    protected $_snamekind = 'FORM_HELPER';
    
    public function render ($options)
    {
           
        $pakage = new Hosh_Manager_Db_Package_Hosh_Category();
        $list = $pakage->getList(array(
                'skindname' => $this->_snamekind
        ));
        if (count($list) == 0) {
            return array();
        }
        $applist = new Hosh_Application_List();
        $treelist = $applist->toTree($list);
    
        $result = array();
        $result[''] = '-- --';
        foreach ($treelist as $val) {    
            $xhtml_level = $applist->getLevelCaption($val['level']);
            $result[$val['id']] = $xhtml_level . $val['scaption'] ;
        }
    
        return $result;
    }
}	