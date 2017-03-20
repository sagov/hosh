<?php


class Hosh_List_Factory extends Hosh_List_Abstract
{

    const ITEM = 'ITEM';

    protected $_items = array();


    public function init()
    {

    }

    /**
     * Retrieve a single item
     *
     * @param  string $name
     * @return Hosh_List_Item|null
     */
    public function getItem($name)
    {
        if (array_key_exists($name, $this->_items)) {
            return $this->_items[$name];
        }
        return null;
    }

    /**
     * Retrieve all items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @return $this
     */
    public function addItem($name)
    {
        $this->_items[$name] = $this->createItem();
        return $this;
    }

    /**
     * Create an item
     *
     *
     * @return Hosh_List_Item
     */
    public function createItem()
    {
        $item = new Hosh_List_Item();
        return $item;
    }

    public function run()
    {

    }
}