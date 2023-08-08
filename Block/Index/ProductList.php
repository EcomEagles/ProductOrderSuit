<?php

namespace Codilar\ProductOrderSuit\Block\Index;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class ProductList
 * @package Codilar\ProductOrderSuit\Block\Index
 */
class ProductList extends Template
{
    /**
     * @var ListProduct
     */
    protected $productListBlock;

    /**
     * @var CollectionFactory
     */
    protected $productCollection;

    /**
     * @param Context $context
     * @param ListProduct $productListBlock
     * @param CollectionFactory $productCollection
     * @param array $data
     */
    public function __construct(
        Context           $context,
        ListProduct       $productListBlock,
        CollectionFactory $productCollection,
        array             $data = []
    )
    {
        $this->productListBlock = $productListBlock;
        $this->productCollection = $productCollection;
        parent::__construct($context, $data);
    }

    /**
     * To get Product collection in Product FInder PLP
     * @return Collection|void
     */
    public function getLoadedProductCollection()
    {
        $productFinder = $this->getRequest()->getParam('finder');
        $searchSku = [];
        if ($productFinder) {
            $productFinder = explode("-", $productFinder);
            foreach ($productFinder as $product) {
                $productSkus = base64_decode($product);
                $productSkus = explode(",", $productSkus);
                if ($productSkus) {
                    foreach ($productSkus as $sku) {
                        $searchSku[] = $sku;
                    }
                }
            }

            if ($searchSku) {
                $collection = $this->productCollection->create();
                $collection->addFieldToSelect('*');
                $collection->addAttributeToFilter('sku', ['in' => $searchSku]);
            }
            return $collection;
        }
    }

    /**
     * Used Magento PLP Block
     * @return ListProduct
     */
    public function getProductListBlock()
    {
        return $this->productListBlock;
    }
}
