<?php

require_once 'Hosh/Form/Helper/Abstract.php';

class HoshPluginForm_System_Acl_Role_Helper_View_AclValue extends Hosh_Form_Helper_Abstract
{
    public function render($options)
    {
        $form = $this->getObject();
        $view = $form->getView();
        $form->addTranslation('form/system_acl_value');
        $translate = $form->getTranslator();
        if (!$form->isEdit()) {
            return;
        }
        $idrole = $form->getData('id');
        $roles = $this->getAclRolesTreeArray();
        $acl = new Hosh_Manager_Db_Package_Hosh_Acl();
        $listaclrole = $acl->getList(
            array(
                'roles' => $roles,
                'snamestate' => Hosh_Manager_State::STATE_NORMAL
            ));


        $aclroleslist = $aclroles = array();
        foreach ($listaclrole as $valacl) {
            $aclroleslist[$valacl['idowner']][$valacl['idvalue']] = $valacl;
        }
        foreach ($roles as $val) {
            if (isset($aclroleslist[$val])) {
                $diff = array_diff_key($aclroleslist[$val], $aclroles);
                foreach ($diff as $key => $val1) {
                    $aclroles[$key] = $val1;
                }

            }
        }

        $user = Hosh_Manager_User_Auth::getInstance();
        $acl_edit = $user->isAllowed('HOSH_SYSTEM_ACL_EDIT');


        $package = new Hosh_Manager_Db_Package_Hosh_Acl_Value();
        $listacl = $package->getList(array(), null);


        $xhtml_tr = array();
        foreach ($listacl as $val) {
            $icon = $classitem = null;
            $icon['edit'] = '<a href="#" data-task="setaclrolevalue" data-target="' . $val['id'] . '" data-toggle="tooltip" data-placement="auto" title="' . $translate->_('HOSH_SYS_ACLV_SETTING_ACCESS') . '"><span class="glyphicon glyphicon-plus"></span></a>';
            $icon['state'] = '<i class="fa fa-square-o" aria-hidden="true"></i>';
            $bdeny = '-1';

            if (isset($aclroles[$val['id']])) {
                $accessacl = $aclroles[$val['id']];

                if ($accessacl['bdeny'] == 0) {
                    $icon['state'] = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
                    $bdeny = '0';
                } else {
                    $icon['state'] = '<i class="fa fa-ban" aria-hidden="true"></i>';
                    $bdeny = '1';
                }

                $dtime = array();
                if (isset($accessacl['dtfrom'])) {
                    $dtime[] = sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_FROM'), $accessacl['dtfrom']);
                }
                if (isset($accessacl['dttill'])) {
                    $dtime[] = sprintf($translate->_('HOSH_SYS_ACLV_LIMIT_TILL'), $accessacl['dttill']);
                }
                if (count($dtime) > 0) {
                    $icon['time'] = '<span class="glyphicon glyphicon-time" data-toggle="tooltip" data-placement="top" title="' . implode(' ', $dtime) . '"></span>';
                }

                if ($accessacl['idowner'] == $idrole) {
                    $icon['remove'] = '<a href="#" data-task="removeaclrole" data-target="' . $val['id'] . '" data-toggle="tooltip" data-placement="auto" title="' . $translate->_('SYS_SET_DELETE') . '"><span class="glyphicon glyphicon-trash"></span></a>';
                }

                if ($accessacl['idowner'] == $idrole) {
                    $icon['edit'] = '<a href="#" data-task="setaclrolevalue" data-target="' . $val['id'] . '"  data-toggle="tooltip" data-placement="auto" title="' . $translate->_('SYS_SET_EDIT') . '"><span class="glyphicon glyphicon-edit"></span></a>';
                    $classitem = ' item-set';
                }

            }

            if (isset($aclroleslist[$idrole][$val['id']])) {
                $param = array('bdeny' => $aclroleslist[$idrole][$val['id']]['bdeny'], 'dtfrom' => $aclroleslist[$idrole][$val['id']]['dtfrom'], 'dttill' => $aclroleslist[$idrole][$val['id']]['dttill']);
            } else {
                $param = array();
            }


            $item = null;
            $item .= '<tr class="item-value item-acl-' . $val['id'] . '' . $classitem . '" data-bdeny="' . $bdeny . '" rel=\'' . Zend_Json::encode($param) . '\'>';
            $item .= '<td class="state">';
            $item .= (isset($icon['state'])) ? $icon['state'] : '&nbsp;';
            $item .= '</td>';
            $item .= '<td class="scaption-item">';
            $item .= ($acl_edit) ? '<a href="' . $view->Hosh_Url(array('controller' => 'ref_acl_value', 'action' => 'view', 'id' => $val['id'])) . '" target="_blank">' . $translate->_($val['scaption']) . ' (' . $val['sname'] . ')</a>' : $translate->_($val['scaption']) . ' (' . $val['sname'] . ')';
            $item .= '</td>';
            $item .= '<td>';
            $item .= (isset($icon['time'])) ? $icon['time'] : '&nbsp;';
            $item .= '</td>';
            if ($acl_edit) {
                $item .= '<td class="action-item">';
                $item .= (isset($icon['edit'])) ? $icon['edit'] : null;
                $item .= '&nbsp;';
                $item .= (isset($icon['remove'])) ? $icon['remove'] : null;
                $item .= '</td>';
            }
            $item .= '</tr>';
            $xhtml_tr[] = $item;

        }

        $xhtml = '<table class="table table-hover">' . implode('', $xhtml_tr) . '</table>';
        return $xhtml;
    }

    /**
     * Get roles all tree
     *
     * @return array
     */
    protected function getAclRolesTreeArray()
    {
        $form = $this->getObject();
        $idrole = $form->getData('id');
        $role = new Hosh_Manager_Acl_Role();
        $adapter = $role->getAdapter();
        $list = $adapter->getList();
        $arrlist = array();
        foreach ($list as $val) {
            $arrlist[$val['id']] = $val;
        }
        $aroles = array();
        $aroles = $this->_toUpTreeRoles($arrlist, $idrole, $aroles);
        return $aroles;
    }

    /**
     *
     * @param array  $list
     * @param string $id
     * @param array  $result
     * @return array
     */
    protected function _toUpTreeRoles($list, $id, & $result = array())
    {
        if (isset($list[$id])) {
            if (!empty($list[$id]['idparent'])) {
                $result[$id] = $id;
                $this->_toUpTreeRoles($list, $list[$id]['idparent'], $result);
            } else {
                $result[$id] = $id;
            }
        }
        return $result;
    }
}