<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Hosh_List
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Hosh_List_Decorator_Abstract */
require_once 'Hosh/List/Decorator/Abstract.php';

/**
 * Hosh_List_Decorator_FormElements
 *
 * Render all form elements registered with current form
 *
 * Accepts following options:
 * - separator: Separator to use between elements
 *
 * Any other options passed will be used as HTML attributes of the form tag.
 *
 * @category   Zend
 * @package    Hosh_List
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: FormElements.php 25093 2012-11-07 20:08:05Z rob $
 */
class Hosh_List_Decorator_ViewElements extends Hosh_List_Decorator_Abstract
{

    /**
     * Render form elements
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {

        $element    = $this->getElement();

        if ((!$element instanceof Hosh_List_Item)) {
            return $content;
        }

        $separator      = $this->getSeparator();
        $view           = $element->getView();

        $elementContent = $view->ListTmp($element);

        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $elementContent . $separator . $content;
            case self::APPEND:
            default:
                return $content . $separator . $elementContent;
        }
    }
}
