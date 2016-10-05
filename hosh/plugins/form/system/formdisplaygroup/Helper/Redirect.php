<?php

class HoshPluginForm_System_Formdisplaygroup_Helper_Redirect extends Hosh_Form_Helper_Hosh_Redirect
{

    public function render ($options)
    {
        $form = $this->getObject();
        $translator = $form->getTranslator();
        $this->_alertmsgdefault = '<h3 style="margin:0 0 15px 0"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp; ' .
                 $translator->_('SYS_MSG_SUCCESSFULLY') .
                 '</h3>
							<button type="button"  data-task="close" data-dismiss="modal" aria-hidden="true" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Вернуться к форме</button>';
        
        
       $options['insert']['url'] =  $options['update']['url'] = '?idform=system_formdisplaygroup&controller=form&action=view&idowner=' .
                     $form->getData('idowner') . '&id=' . $form->getData('id');
        
        return parent::render($options);
    }
}	