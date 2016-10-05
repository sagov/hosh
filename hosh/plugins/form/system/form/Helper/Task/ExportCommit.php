<?php
require_once dirName(__FILE__) . '/Export.php';

class HoshPluginForm_System_Form_Helper_Task_ExportCommit extends HoshPluginForm_System_Form_Helper_Task_Export
{

    public function render ($options)
    {
        $form = $this->getObject();
        $idform = $form->getData('sname');
        $result = $this->_getXmlData($idform);
        $config = Hosh_Config::getInstance();
        $flag = false;
        $config = Hosh_Config::getInstance();
        $configform = $config->get('form');
        $path = $configform->get('path_patternxml');        
        $fp = fopen($path . $idform . '.xml', 'w+');
        if ($fp) {
            flock($fp, LOCK_EX);
            if (fwrite($fp, $result)) {
                $flag = true;
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        return $flag;
    }
}	