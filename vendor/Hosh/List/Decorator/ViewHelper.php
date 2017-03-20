<?php

/** Hosh_List_Decorator_Abstract */
require_once 'Hosh/List/Decorator/Abstract.php';

/**
 * Hosh_List_Decorator_ViewHelper
 *
 * Render all form elements registered with current form
 *
 * Accepts following options:
 * - separator: Separator to use between elements
 *
 * Any other options passed will be used as HTML attributes of the form tag.
 *
 * @category   Hosh
 * @package    Hosh_List
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ViewHelper.php 25093 2012-11-07 20:08:05Z rob $
 */
class Hosh_List_Decorator_ViewHelper extends Hosh_List_Decorator_Abstract
{


    /**
     * View helper to use when rendering
     * @var string
     */
    protected $_helper;

    /**
     * Set view helper to use when rendering
     *
     * @param  string $helper
     * @return Hosh_List_Decorator_ViewHelper
     */
    public function setHelper($helper)
    {
        $this->_helper = (string) $helper;
        return $this;
    }

    /**
     * Retrieve view helper for rendering element
     *
     * @return string
     */
    public function getHelper()
    {
        if (null === $this->_helper) {
            $options = $this->getOptions();
            if (isset($options['helper'])) {
                $this->setHelper($options['helper']);
                $this->removeOption('helper');
            } else {
                $element = $this->getElement();
                if (null !== $element) {
                    if (null !== ($helper = $element->getAttrib('helper'))) {
                        $this->setHelper($helper);
                    } else {
                        $type = $element->getType();
                        if ($pos = strrpos($type, '_')) {
                            $type = substr($type, $pos + 1);
                        }
                        $this->setHelper('list' . ucfirst($type));
                    }
                }
            }
        }

        return $this->_helper;
    }

    /**
     * Get name
     *
     * If element is a Zend_Form_Element, will attempt to namespace it if the
     * element belongs to an array.
     *
     * @return string
     */
    public function getName()
    {
        if (null === ($element = $this->getElement())) {
            return '';
        }

        $name = $element->getName();

        if (!$element instanceof Hosh_List_Element) {
            return $name;
        }

        if (null !== ($belongsTo = $element->getBelongsTo())) {
            $name = $belongsTo . '['
                . $name
                . ']';
        }

        if ($element->isArray()) {
            $name .= '[]';
        }

        return $name;
    }

    /**
     * Retrieve element attributes
     *
     * Set id to element name and/or array item.
     *
     * @return array
     */
    public function getElementAttribs()
    {
        if (null === ($element = $this->getElement())) {
            return null;
        }

        $attribs = $element->getAttribs();
        if (isset($attribs['helper'])) {
            unset($attribs['helper']);
        }

        if (method_exists($element, 'getSeparator')) {
            if (null !== ($listsep = $element->getSeparator())) {
                $attribs['listsep'] = $listsep;
            }
        }

        if (isset($attribs['id'])) {
            return $attribs;
        }

        $id = $element->getName();



        $element->setAttrib('id', $id);
        $attribs['id'] = $id;

        return $attribs;
    }

    /**
     * Get value
     *
     * If element type is one of the button types, returns the label.
     *
     * @param  Hosh_List_Element $element
     * @return string|null
     */
    public function getValue($element)
    {
        if (!$element instanceof Hosh_List_Element) {
            return null;
        }
        return $element->getValue();
    }

    /**
     * Render an element using a view helper
     *
     * Determine view helper from 'viewHelper' option, or, if none set, from
     * the element type. Then call as
     * helper($element->getName(), $element->getValue(), $element->getAttribs())
     *
     * @param  string $content
     * @return string
     * @throws Zend_Form_Decorator_Exception if element or view are not registered
     */
    public function render($content)
    {
        $element = $this->getElement();

        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }



        $helper        = $this->getHelper();
        $separator     = $this->getSeparator();
        $value         = $this->getValue($element);
        $attribs       = $this->getElementAttribs();
        $name          = $element->getFullyQualifiedName();
        $id            = $element->getId();
        $attribs['id'] = $id;

        $helperObject  = $view->getHelper($helper);
        if (method_exists($helperObject, 'setTranslator')) {
            $helperObject->setTranslator($element->getTranslator());
        }
        if (!isset($element->options)){
            $element->setAttrib('options', array());
        }
        $elementContent = $view->$helper($name, $value, $attribs, $element->options);


        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $separator . $elementContent;
            case self::PREPEND:
                return $elementContent . $separator . $content;
            default:
                return $elementContent;
        }
    }
}
