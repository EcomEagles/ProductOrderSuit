<?php

namespace Codilar\ProductOrderSuit\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ProductFinderItem
 * @package Codilar\ProductOrderSuit\Model
 */
class ProductFinderItem extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'suit_product_finder_item';

    /**
     * @var string
     */
    protected $_cacheTag = 'suit_product_finder_item';

    /**
     * @var string
     */
    protected $_eventPrefix = 'suit_product_finder_item';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem');
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
