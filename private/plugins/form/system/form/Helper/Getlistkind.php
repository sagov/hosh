<?php
require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Form_Helper_Getlistkind extends Hosh_Form_Helper_Abstract
{

    public function render ($options)
    {
        $h_transl = Hosh_Translate::getInstance();
        $h_transl->load('manager/_');
        $m_form = new Hosh_Manager_Form();
        $list = $m_form->getKinds();
        $result = array();
        $result[''] = '-- --';
        $user = Hosh_Manager_User_Auth::getInstance();
        foreach ($list as $val) {
            $flag = true;
            if (! empty($val['acl_value'])) {
                $flag = $user->isAllowed($val['acl_value']);
            }
            if ($flag) {
                $result[$val['id']] = $val['scaption'];
            }
        }
        return $result;
    }
}