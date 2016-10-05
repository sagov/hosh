<?php

class HoshPluginForm_System_Formelement_Helper_Append extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $form = $this->getObject();
        
        $obj = new Hosh_Manager_Object($form->getData('idowner'));
        if ($obj->isLock()){
            $elements = $form->getElements();            
            foreach ($elements as $element){
                $element->setAttrib("disabled",true);
            }
        }
        
        if (!$form->isEdit()){
            $idowner = $form->getData('idowner');
            if (!empty($idowner)){
                $manager_form = new Hosh_Manager_Form();                
                $form_data = $manager_form->getObject($idowner);
                $form->setData('snamekindowner', $form_data['snamekind']);
            }
        } 
        $snamekind = $form->getData('snamekindowner');
        $pattern = $form->getPattern();
        $layout = $pattern->get('layout');
        
        switch ($snamekind) {            
            case 'ELEMENT':
                $layout['update']['name'] = 'default/element';                
                $pattern->set('layout', $layout);
                break;
        
            default:
                break;
        }
        
        $request_http = new Zend_Controller_Request_Http();
        if (! $request_http->isPost()) {
            
            if (isset($_GET['successfully'])) {
                switch ($_GET['successfully']) {
                    case 'insert':
                        $xhtml_text = 'Элемент формы создан успешно';
                        break;
                    
                    case 'update':
                        $xhtml_text = 'Запись прошла успешно';
                        break;
                }
                if (! empty($xhtml_text)) {
                    $xhtml = '<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h3 style="margin:0 0 15px 0"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp; ' .
                             $xhtml_text . '</h3>
							<button type="button"  data-task="close" data-dismiss="modal" aria-hidden="true" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Вернуться к форме</button>
							</div>';
                    $form->addDecorator('Layout', array(
                            'prepend' => $xhtml
                    ));
                }
            }
        }
        return;
    }
}		