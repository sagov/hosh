<?php

require_once 'Zend/View/Helper/HtmlElement.php';

class Hosh_View_Helper_Bootstrap_Tab extends Zend_View_Helper_HtmlElement
{	
	public function Bootstrap_Tab(Hosh_Application_Tab $tab)
	{
		
		$items = $tab->getItems();
		$activeitem = $tab->getOption('active');
		$id = $tab->getOption('id');
		
		$xhtml_item =  array();
		$i = 0;
		foreach ($items as $key=>$val){
			
			if (!isset($activeitem)and($i == 0)) $activeitem = $key;
			
			$classactive = $classactive_content = null;			
			if ($activeitem == $key) {
				$classactive = ' active'; 
				$classactive_content = ' in'.$classactive;
			}
			$xhtml_item['head'][$key] = '<li class="itemtab'.$classactive.'"><a href="#'.$key.'" data-toggle="tab">'.$val['title'].'</a></li>';
			$xhtml_item['content'][$key] = '<div class="tab-pane fade'.$classactive_content.'" id="'.$key.'">'.$val['content'].'</div>';
			++$i;
		}
		
		$xhtml = null;
		if (count($xhtml_item)>0){
			$xhtml .= '<ul class="nav nav-tabs" id="'.$id.'">'.implode('',$xhtml_item['head']).'</ul>';
			$xhtml .= '<div class="tab-content">'.implode('',$xhtml_item['content']).'</div>';
		}
		return $xhtml;
		
	}
}	