<?php

namespace Codilar\ProductOrderSuit\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class ProductFinderItem
 * @package Codilar\ProductOrderSuit\Model\ResourceModel
 */
class ProductFinderItem extends AbstractDb
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('suit_product_finder_item', 'question_id');
    }
}
