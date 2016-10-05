<?php

/**
 * Hosh Framework
 *
 * @category    Hosh  
 * @package     Hosh_Form
 * @copyright   Copyright (c) 2016 Hosh
 * @version   $Id: Helper.php 21.04.2016 18:23:41
 */
require_once 'Zend/Form/Element/File.php';
/**
 * Form Element Helper
 * 
 * @category   Hosh
 * @package     Hosh_Form
 * @subpackage  Element
 * @author  Vladimir Sagov <sagov.vladimir@gmail.com>
 * @copyright  Copyright (c) 2016 Hosh 
 *
 */
class Hosh_Form_Element_Dmuploader extends Zend_Form_Element_File
{
    
    const TEXT_DRAGDROP = 'Drag &amp; Drop File Here';
    const TEXT_CLICKFILEBROUSER = 'Click to open the file Browser';
    
    public function init ()
    {
        
        $form = $this->getAttrib('form');
        $name = $this->getName();
        $idelement = $this->getId();
        $translate = $form->getTranslator();
        
        $pattern = $form->getPattern();
        $patten_element = $pattern->getElement($name);
        $debug = $patten_element->get('debug');
        $text = $patten_element->get('text');
        
        if (!isset($text['dragdrop'])){
            $text['dragdrop'] = self::TEXT_DRAGDROP;
        }
        if (!isset($text['clickfilebrouser'])){
            $text['clickfilebrouser'] = self::TEXT_CLICKFILEBROUSER;
        }
        $decorator['Dmuploader'] = array(
                'decorator' => 'Dmuploader',
                'options' => array(
                        'id'=>$idelement,
                        'text_dragdrop'=>$translate->_($text['dragdrop']),
                        'text_clickfilebrouser'=>$translate->_($text['clickfilebrouser']),
                        'isdebug'=>($debug == 2) ? true : false,
                )
        );
        $this->addDecorators($decorator);
        $options = array('islog'=>(!empty($debug)) ? true : false, 'islog_view'=>($debug == 2) ? true : false,'');
        $data_pattern = $patten_element->getData();
        $options = array_merge($options,$data_pattern);
        
        $this->_setScript($idelement,$options);
        $this->_setScriptDeclaration($idelement,$options);
    }
    
    protected function _setScript ($idelement,$options = null)
    {        
        $view = Hosh_View::getInstance();
        $view->JQuery_Dmuploader();
        $view->AddScript('/libraries/hosh/Element/dmuploader/script.js');
        return $this;
    }
    
    /**
     * @return Hosh_Form_Element_Helper
     */
    protected function _setScriptDeclaration ($idelement,$options = null)
    {
            
        $form = $this->getAttrib('form');        
        $view = Hosh_View::getInstance();        
                
        $islog = ($options['islog']) ? 1 : 0;
        $islog_view = ($options['islog_view']) ? 1 : 0;
        
        $script = '
				;
			(function($){
				$(document).ready(function(){
                $.Ex_Lib_Hosh_Element_Dmuploader.islog = '.$islog.';
                $.Ex_Lib_Hosh_Element_Dmuploader.islog_view = '.$islog_view.';		            
					$("#dmuploader-'.$idelement.'").dmUploader({
        url: "'.$view->Hosh_Url($options['url']).'",
        dataType: "json",
        onInit: function(){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "default", "Plugin initialized correctly");
        },
        onBeforeUpload: function(id){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "default", "Starting the upload of #" + id);
          $.Ex_Lib_Hosh_Element_Dmuploader.updateFileStatus(id, "default", "Uploading...");
        },
        onNewFile: function(id, file){
          $.Ex_Lib_Hosh_Element_Dmuploader.addFile("#dmuploader-'.$idelement.' .uploadfiles", id, file);
        },
        onComplete: function(){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "default", "All pending tranfers completed");
        },
        onUploadProgress: function(id, percent){
          var percentStr = percent + "%";
          $.Ex_Lib_Hosh_Element_Dmuploader.updateFileProgress(id, percentStr);
        },
        onUploadSuccess: function(id, data){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "success", "Upload of file #" + id + " completed");

          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "info", "Server Response for file #" + id + ": " + JSON.stringify(data));

          $.Ex_Lib_Hosh_Element_Dmuploader.updateFileStatus(id, "success", "Upload Complete");

          $.Ex_Lib_Hosh_Element_Dmuploader.updateFileProgress(id, "100%");
        },
        onUploadError: function(id, message){
          $.Ex_Lib_Hosh_Element_Dmuploader.updateFileStatus(id, "error", message);
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "error", "Failed to Upload file #" + id + ": " + message);
        },
        onFileTypeError: function(file){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "error", "File \'" + file.name + "\' cannot be added: must be an image");
        },
        onFileSizeError: function(file){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "error", "File \'" + file.name + "\' cannot be added: size excess limit");
        },
        
        onFallbackMode: function(message){
          $.Ex_Lib_Hosh_Element_Dmuploader.addLog("#dmuploader-'.$idelement.' .debug", "info", "Browser not supported(do something else here!): " + message);
        }
      });
				});
			})(jQuery);	';
        $view->AddScriptDeclaration($script);
        return $this;
    }
    
    
}