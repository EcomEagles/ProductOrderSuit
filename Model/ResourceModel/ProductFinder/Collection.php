<?php

namespace Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'product_finder_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'suit_product_finder_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'product_finder_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Codilar\ProductOrderSuit\Model\ProductFinder', 'Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder');
    }

}
