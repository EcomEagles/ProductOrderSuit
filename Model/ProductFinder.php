<?php

namespace Codilar\ProductOrderSuit\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ProductFinder
 * @package Codilar\ProductOrderSuit\Model
 */
class ProductFinder extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'suit_product_finder';

    /**
     * @var string
     */
    protected $_cacheTag = 'suit_product_finder';

    /**
     * @var string
     */
    protected $_eventPrefix = 'suit_product_finder';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
