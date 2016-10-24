<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Layout_BtnAction extends Hosh_Form_Helper_Abstract
{

    protected $_islockself = false;
    
    public function render ($options)
    {
        $form = $this->getObject();
        $transl = $form->getTranslator();        
        $data = $form->getDataAll();
        
        $obj = new Hosh_Manager_Object($form->getData('id'));
        if ($obj->isLock()){
            return;
        }
        
        $objedit = new Hosh_Manager_Object_Edit();
        $listedititems = $objedit->getListByObject($form->getData('id'));
        
        $auth = Hosh_Manager_User_Auth::getInstance();
        $iduser = $auth->getId();
        
        foreach ($listedititems as $val){
            if ($val['iduser'] == $iduser){
                $this->_islockself = true;
                break;
            }        
        }
        
        $btn = $btn2 =  array();
        
        $btn[] = '<a href="javascript:void(0);" data-task="copy"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp; ' .
                 $transl->_('SYS_FORM_ADD_COPY') . '</a>';
        if (! empty($data['idclone'])) {
            $btn[] = '<a href="javascript:void(0);" data-task="clone-remove"><i class="fa fa-chain-broken" aria-hidden="true"></i>&nbsp; ' .
                     $transl->_('SYS_FORM_REMOVE_CLONE') . '</a>';
        } else {
            $btn[] = '<a href="javascript:void(0);" data-task="clone"><i class="fa fa-clone" aria-hidden="true"></i>&nbsp; ' .
                     $transl->_('SYS_FORM_NEW_CLONE') . '</a>';
            $btn[] = '<a href="javascript:void(0);" data-task="clone-set"><i class="fa fa-link" aria-hidden="true"></i>&nbsp; ' .
                     $transl->_('SYS_FORM_SET_CLONE') . '</a>';
        }
        
        if (!$this->_islockself){
            $btn2[] = '<a href="javascript:void(0);" data-task="form-lock"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp; '.$transl->_('SYS_LOCKED').'</a>';
        }else{
            $btn2[] = '<a href="javascript:void(0);" data-task="form-unlock"><i class="fa fa-unlock" aria-hidden="true"></i>&nbsp; '.$transl->_('SYS_UNLOCKED').'</a>';
        }
        $btn2[] = '<a href="javascript:void(0);" data-task="export-xml"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp; ' .
                $transl->_('SYS_FORM_EXPORT_XML') . '</a>';
        $btn2[] = '<a href="javascript:void(0);" data-task="export-xml-commit"><i class="fa fa-hdd-o" aria-hidden="true"></i>&nbsp; Commit</a>';
        
        $btn3[] = '<a href="javascript:void(0);" data-task="form-preview" data-title="Preview Form # '.$form->getData('sname').'"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp; Preview</a>';
        
        $actions = array($btn,$btn2,$btn3);
        
        $xhtml = null;
        $xhtml .= '<div class="btn-group pull-right">
                  <button type="submit" class="btn btn-primary">' .
                 '<span class="visible-xs">&nbsp;<i class="fa fa-floppy-o"></i>&nbsp;</span><span class="hidden-xs"><i class="fa fa-floppy-o"></i>&nbsp; '.$transl->_('SYS_SAVE') . '</span></button>
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" >
                  &nbsp;<span class="fa fa-caret-down"></span>&nbsp;
              </button>';
        $xhtml .= '<ul class="dropdown-menu">';
        $action_xhtml = null;
        foreach ($actions as $btn) {
            if (count($btn)>0){
                if (!empty($action_xhtml)){
                    $action_xhtml .= '<li class="divider"></li>';
                }
                foreach ($btn as $val) {
                    $action_xhtml .= '<li>' . $val . '</li>';
                }
            }
            
        }
        $xhtml .= $action_xhtml;
        $xhtml .= '</ul>
            </div>';
        return $xhtml;
    }
}    