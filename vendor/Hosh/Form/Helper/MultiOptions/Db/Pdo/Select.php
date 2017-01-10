<?php


/**
 * Class Hosh_Form_Helper_MultiOptions_Db_Pdo_Select
 */
class Hosh_Form_Helper_MultiOptions_Db_Pdo_Select extends Hosh_Form_Helper_Abstract
{

    /**
     * @var string
     */
    protected $_field_value = 'id';

    /**
     * @var string
     */
    protected $_field_text = 'stext';


    /**
     * @param $options
     * @return array
     */
    public function render($options)
    {

        if (empty($options['sql'])) {
            return array();
        }
        $sql = $options['sql'];
        if (!empty($options['field']['value'])) {
            $this->_field_value = $options['field']['value'];
        }
        if (!empty($options['field']['text'])) {
            $this->_field_text = $options['field']['text'];
        }
        $result = array();
        $db = Hosh_Db::get();
        $list = $db->fetchAll($sql);
        foreach ($list as $val) {
            $result[$val[$this->_field_value]] = $val[$this->_field_text];
        }

        return $result;
    }
}