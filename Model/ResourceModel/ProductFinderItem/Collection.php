<?php

namespace Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'question_id';

    /***
     * @var string
     */
    protected $_eventPrefix = 'suit_product_finder_item_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'product_finder_item_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Codilar\ProductOrderSuit\Model\ProductFinderItem', 'Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem');
    }

}
