<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Layout_AlertObject extends Hosh_Form_Helper_Abstract
{
    protected $_islockself = false;

    public function render ($options)
    {
        $form = $this->getObject();
        $transl = $form->getTranslator();
        $id = $form->getData('id');        
        $obj = new Hosh_Manager_Object($id);
        $alert = array();
        $view = Hosh_View::getInstance();
        $xhtml = null;
        $auth = Hosh_Manager_User_Auth::getInstance();
        $iduser = $auth->getId();
        if ($obj->isLock()){
            $listlockitems = $obj->getListEditItems();
            $xhtml_modal = '<table class="table table-head table-hover table-bordered"><tr><th>User</th><th>Datetime</th></tr>';
            foreach ($listlockitems as $val){
                if ($val['iduser'] == $iduser){
                    $this->_islockself = true;                   
                }
                $xhtml_modal .= '
			                 <tr>
                             <td>'.$val['iduser'].'</td>
			                 <td>'.$val['dtinner'].'</td>			                
			                 </tr>
			               ';
            } 
            $xhtml_modal .= '</table>';
            $xhtml_modal_footer = null;
            if ($auth->isAllowed('HOSH_SYS_FORM_LOCK_REMOVE')){
                $xhtml_modal_footer = '<a href="javascript:void(0);" data-task="form-lock-remove" class="btn btn-primary"><i class="fa fa-unlock" aria-hidden="true"></i> '.$transl->_('SYS_UNLOKED').'</a>';
            }
            $alert[] = $transl->_('SYS_OBJECT_IS_LOCKED').'. <a href="javascript:void(0);" data-toggle="modal" data-target="#FormInfoModalLock">'.$transl->_('SYS_DETAIL').'</a>';
            $xhtml .=  $view->Bootstrap_Modal()->render('FormInfoModalLock',$xhtml_modal,array('title'=>'Form # '.$form->getData('id'),'footer'=>$xhtml_modal_footer));
            
        }
        
        if (count($alert) == 0) {
            return ;
        }      
        return $view->Bootstrap_Alert(implode('<br />', $alert),array('kind'=>'warning','close'=>false)).$xhtml;
        
    }
}    