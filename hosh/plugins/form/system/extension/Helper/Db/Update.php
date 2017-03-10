<?php
require_once 'Hosh/Form/Helper/Hosh/Db/Update.php';

class HoshPluginForm_System_Extension_Helper_Db_Update extends Hosh_Form_Helper_Hosh_Db_Update
{

    public function render ($options)
    {
        $package = new Hosh_Manager_Db_Package_Hosh_Extension();
        return $this->_update($package);
    }

    protected function _appendUpdate ()
    {
        $form = $this->getObject();

        $package = new Hosh_Manager_Db_Package_Hosh_Extension();
        $cols_ex  = $package->info('cols');
        $package_object = new Hosh_Manager_Db_Package_Hosh_Object();
        $cols_object  = $package_object->info('cols');
        $cols_custom = array('idform','idcategory');

        $cols = array_merge($cols_ex,$cols_object,$cols_custom);

        $pattern = $form->getPattern();
        $pattern_elements = $pattern->getElements();
        $data = $form->getDataAll();

        $bind = array();
        foreach ($pattern_elements as $key=>$valpattern)
        {
            $value = null;
            if ($element = $form->getElement($key)){
                $value = $element->getValue();
            }else if (isset($data[$key])){
                $value = $data[$key];
            }
            if ($value === '') {
                $value = null;
            }

            if (!in_array($key,$cols)){
                if (isset($value)){
                    $arr = $this->getArrValue($key,$value);
                    $bind = array_merge_recursive($bind,$arr);
                }
            }
        }

        if (isset($bind['options'])){
            $bind['options'] = Zend_Json::encode($bind['options']);
            if (!$package->setObject($data['id'], $bind)){
                return false;
            }
        }




        $bind_object = array();
        if ($element = $form->getElement('scaption')) {
            $bind_object['scaption'] = $element->getValue();
        }
        if (count($bind_object) > 0) {
            $package_object = new Hosh_Manager_Db_Package_Hosh_Object();
            if (! $package_object->setObject($data['id'], $bind_object)) {
                return false;
            }
        }
        
        if ($element = $form->getElement('idform')) {
            $idform = $element->getValue();
            
            if ($idform !== $data['idform']) {
                $extpackage = new Hosh_Manager_Db_Package_Hosh_Form_Extension();
                if (! $extpackage->removeExtension($data['id'])) {
                    return false;
                }
                if (! empty($idform)) {
                    if (! $extpackage->insert(
                            array(
                                    'idowner' => $idform,
                                    'idextension' => $data['id']
                            ))) {
                        return false;
                    }
                }
            }
        }
        
        if ($element = $form->getElement('idcategory')) {
            $idcategories = $element->getValue();
            if (is_array($idcategories)) {
                $extctgpackage = new Hosh_Manager_Db_Package_Hosh_Object_Category();
                $extctgpackage->removeObject($data['id']);
                foreach ($idcategories as $idcategory) {
                    if (! $extctgpackage->Add($form->getData('id'), $idcategory)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }


    protected function getArrValue($key,$value){
        $arr = array();
        $akey = explode('_',$key);
        if (count($akey) == 1){
            $arr[$key] = $value;
        }else{
            $akeyval = null;
            foreach ($akey as $valkey){
                $akeyval .= '[\''.$valkey.'\']';
            }
            if (!empty($akeyval)){
                $b = array();
                eval('$b'.$akeyval.' = null;');

                foreach ($b as $keyval=>$valarr){
                    if (is_array($valarr)){
                        foreach ($valarr as $keyval1=>$valarr1){
                            if (is_array($valarr1)){
                                foreach ($valarr1 as $keyval2=>$valarr2){
                                    if (is_array($valarr2)){
                                        foreach ($valarr2 as $keyval3=>$valarr3){
                                            if (is_array($valarr3)){
                                                foreach ($valarr3 as $keyval4=>$valarr4){
                                                    if (is_array($valarr4)){

                                                    }else{
                                                        $arr[$keyval][$keyval1][$keyval2][$keyval3][$keyval4] = $value;
                                                    }
                                                }
                                            }else{
                                                $arr[$keyval][$keyval1][$keyval2][$keyval3] = $value;
                                            }
                                        }
                                    }else{
                                        $arr[$keyval][$keyval1][$keyval2] = $value;
                                    }
                                }
                            }else{
                                $arr[$keyval][$keyval1] = $value;
                            }
                        }
                    }else{
                        $arr[$keyval] = $value;
                    }
                }

            }
        }
        $result['options'] = $arr;
        return $result;
    }
}		