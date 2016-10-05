<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class Hosh_Form_Helper_Hosh_Layout extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        if (! $form->isEdit())
            $key = 'insert';
        else
            $key = 'update';
        
        if (! isset($options[$key]))
            return $this;
        
        if (! empty($options[$key]['name'])) {
            $this->setLayout($options[$key]['name']);
        } else { 
            if (! empty($options[$key]['value'])) {
                $this->setLayoutText($options[$key]['value']);
            }
        }    
        return $this;
    }
    
    /**
     * [
     * :_[\w+\] - Текстовая константа
     * :data[\w+\] - массив данных
     * :element[\w+\] - Элемент формы
     * :elementhelper[\w+\] - Группа родителя элемента формы
     * :helper[\w+\]	- Помощник
     * ]
     * @param string $text
     * @throws Zend_Form_Exception
     * @return Hosh_Form_Factory
     */
    protected function setLayoutText($text){
    
        if (empty($text)) {
            return $this;
        }
    
        $form = $this->getObject();
    
        //:_
        preg_match_all("/:_\[\w+\]/",$text,$out);
        if (isset($out[0])){
            foreach ($out[0] as $val){
                $name = preg_replace("/:_\[(\w+)\]/","$1",$val);
                $translator = $form->getTranslator();
                $text = str_replace(':_['.$name.']',$translator->_($name),$text);
            }
            $text = preg_replace("/:_\[\w+\]/","",$text);
        }
    
        // :helper
        preg_match_all("/:helper\[\w+\]/",$text,$outparent);
        if (isset($outparent[0])){
            foreach ($outparent[0] as $val){
                $name = preg_replace("/:helper\[(\w+)\]/","$1",$val);
                $result_helper = $form->getHelper($name);
                $text = str_replace(':helper['.$name.']',$result_helper,$text);
            }
            $text = preg_replace("/:helper\[\w+\]/","",$text);
        }
    
        // :data
        preg_match_all("/:data\[\w+\]/",$text,$outparent);
        if (isset($outparent[0])){
            foreach ($outparent[0] as $val){
                $name = preg_replace("/:data\[(\w+)\]/","$1",$val);
                $result_data = $form->getData($name);
                $text = str_replace(':data['.$name.']',$result_data,$text);
            }
            $text = preg_replace("/:data\[\w+\]/","",$text);
        }
    
        // :elementhelper
        preg_match_all("/:elementhelper\[\w+\]/",$text,$outparent);
        $pattern = $form->getPattern();
        if (isset($outparent[0])){
            $pattern_elements = $pattern->getElements();
            $arr_parent = array();
            foreach ($pattern_elements as $key=>$valelement){
    
                if ($valelement->get('parent',false)){
                    $arr_parent[$valelement->get('parent')][$valelement->get('name')] = ':element['.$valelement->get('name').']';
                }
            }
            	
            if (count($arr_parent)>0){
                foreach ($outparent[0] as $val){
                    $name = preg_replace("/:elementhelper\[(\w+)\]/","$1",$val);
                    if (isset($arr_parent[$name])){
                        $textparent = '<div data-type="helper-group" data-helper="'.$name.'">'.implode('',$arr_parent[$name]).'</div>';
                        $text = str_replace(':elementhelper['.$name.']',$textparent,$text);
                    }
                }
            }
            $text = preg_replace("/:elementhelper\[(\w+)\]/","<div data-type=\"helper-group\" data-helper=\"$1\"></div>",$text);
        }
    
        // :elementtype
        preg_match_all("/:elementtype\[\w+\]/",$text,$outparent);
        if (isset($outparent[0])){
            $pattern_elements = $pattern->getElements();
            $arr_parent = array();
            foreach ($pattern_elements as $key=>$valelement){
                if ($valelement->get('displaygrouptype',false)){
                    $arr_parent[$valelement->get('displaygrouptype')][$valelement->get('name')] = ':element['.$valelement->get('name').']';
                }
            }
    
            if (count($arr_parent)>0){
                foreach ($outparent[0] as $val){
                    $name = preg_replace("/:elementtype\[(\w+)\]/","$1",$val);
                    if (isset($arr_parent[$name])){
                        $textparent = implode('',$arr_parent[$name]);
                        $text = str_replace(':elementtype['.$name.']',$textparent,$text);
                    }
                }
            }
            $text = preg_replace("/:elementtype\[(\w+)\]/","",$text);
        }
    
    
        // :element
        $out_split =  preg_split("/(?=:element\[\w+\])/", $text, -1,PREG_SPLIT_NO_EMPTY+PREG_SPLIT_DELIM_CAPTURE);
    
        $decor_elements = array();
        $decor_form = array();
        $prepend = null;
        $i = 1;
        foreach ($out_split as $val){
            preg_match("/^:element\[\w+\]/",$val,$out);
            if (isset($out[0])) {
                $name = preg_replace("/:element\[(\w+)\]/","$1",$out[0]);
                if (!empty($name))
                {
                    if (!isset($decor_elements[$name])){
                        $element = $form->getElement($name);
                        if ($element){
                            if (isset($prepend)){
                                $decor_elements[$name]['prepend'] = $prepend;
                                $prepend = null;
                            }
                            $decor_elements[$name]['append'] = str_replace($out[0],"",$val);
                            $element->setOrder($i);
                            ++$i;
                        }else{
                            $prepend .= str_replace($out[0],"",$val);
                        }
                    }else{
                        require_once 'Zend/Form/Exception.php';
                        throw new Zend_Form_Exception(sprintf('Element :%s is specified more than 1 times', $name));
                    }
                }
    
            }else{
                $prepend .= $val;
            }
            $text = preg_replace("/:element\[\w+\]/","",$text);
        }
    
        $elements = $form->getElements();
        foreach ($elements as $name=>$element){
            if (!isset($decor_elements[$name])){
                if ($name !== $form->getSettings('actionpost').$form->getIdSelf()){
                    $form->removeElement($name);
                }else{
                    $element->setOrder(0);
                }
            }else{
                $element->addDecorator('Layout',$decor_elements[$name]);
            }
        }
    
        $displaygroups = $form->getDisplayGroups();
        foreach ($displaygroups as $name_group=>$valgroup){
            $form->removeDisplayGroup($name_group);
        }
    
    
        return $this;
    }
    
    protected function setLayout($name){
        
        $form = $this->getObject();
        $plugin = $form->getPlugin();
    
        if (file_exists($name)){
            $filename = $name;
        }else if (file_exists($plugin['path'].DIRECTORY_SEPARATOR.'Layouts'.DIRECTORY_SEPARATOR.$name.'.phtml')){
            $filename = $plugin['path'].DIRECTORY_SEPARATOR.'Layouts'.DIRECTORY_SEPARATOR.$name.'.phtml';
        }else{
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(sprintf('Layout :%s not found', $name));
        }
    
        $file_handle = fopen($filename, "r");
        $text = null;
        while (!feof($file_handle)) {
            $text .= fgets($file_handle);
        }
        $this->setLayoutText($text);
        fclose($file_handle);
        return $this;
    }
}