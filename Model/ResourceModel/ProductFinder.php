<?php

namespace Codilar\ProductOrderSuit\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class ProductFinder
 * @package Codilar\ProductOrderSuit\Model\ResourceModel
 */
class ProductFinder extends AbstractDb
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
        $this->_init('suit_product_finder', 'product_finder_id');
    }

}
