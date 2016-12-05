<?php

require_once 'Hosh/Form/Helper/Hosh/DisplayGroup.php';

class Hosh_Form_Helper_Hosh_DisplayGroup_Tab extends Hosh_Form_Helper_Hosh_DisplayGroup
{
	public function render($options){
		
		parent::render($options);
		
		$form = $this->getObject();
		$idform = $form->getId();
		
		$groups = $form->getDisplayGroups();
		
		$agroup = array();
		$i = 0;
		$firstgroup = $lastgroup = null;
		foreach ($groups as $key=>$group)
		{
			
						
			$group->removeDecorator('Fieldset','DtDdWrapper');
			$name = $group->getName();
			$active = false;
			if ($i == 0){
				$firstgroup = $name;
				$active = true;
			}
			
			$group->addDecorator('Bootstrap_Tab_Pane',array('id'=>$idform.'_'.$name,'active'=>$active));
			$agroup[$idform.'_'.$name] = array('label'=>$group->getLegend(),'active'=>$active);
						
			++$i;
			if (count($groups) == $i){
				$lastgroup = $name;
			}
		}
		
		if (!empty($firstgroup)){
			$group_first = $form->getDisplayGroup($firstgroup);
			$group_first->addDecorator('ExtHtmlTag',array('class'=>'tab-content','placement'=>'PREPEND','openOnly'=>true))
				   ->addDecorator('Bootstrap_Tab_Nav',array('displaygroups'=>$agroup));
			$group_last = $form->getDisplayGroup($lastgroup);
			$group_last->addDecorator('ExtHtmlTag',array('placement'=>'APPEND','closeOnly'=>true));
		}
		
		
		
		return;
	}
}	