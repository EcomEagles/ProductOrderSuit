<?php
declare(strict_types=1);

namespace Codilar\ProductOrderSuit\Plugin;

use Magento\Framework\Serialize\Serializer\Json;
use \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as TypeConfigurable;
use Codilar\ProductOrderSuit\ViewModel\ProductOrderCount;

/**
 * class Configurable
 * @package Codilar\ProductOrderSuit\Plugin
 */
class Configurable
{
    /**
     * @var ProductOrderCount
     */
    private $productOrderCount;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @param Json $json
     * @param ProductOrderCount $productOrderCount
     */
    public function __construct(
        Json              $json,
        ProductOrderCount $productOrderCount
    )
    {
        $this->productOrderCount = $productOrderCount;
        $this->json = $json;
    }

    /**
     * @param TypeConfigurable $subject
     * @param $result
     * @return string
     */
    public function afterGetJsonConfig(TypeConfigurable $subject, $result)
    {
        $result = $this->json->unserialize($result);
        foreach ($subject->getAllowProducts() as $product) {
            $salableQty = $this->productOrderCount->getProductQty($product);
            $result['salable_qty'][$product->getId()] = $salableQty;
            $result['product_count'][$product->getId()] =
                $this->productOrderCount->getCountForProduct($product->getSku());
        }
        return $this->json->serialize($result);
    }
}
