<?php

class HoshPluginForm_System_Listelement_Helper_Append extends Hosh_Form_Helper_Abstract
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