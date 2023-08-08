<?php
declare(strict_types=1);

/**
 * class  Configurable
 *
 * @package      ProductOrderSuit
 * @description  Configurable class  provides count of a product is ordered
 *
 */

namespace Codilar\ProductOrderSuit\Plugin;

use Exception;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Json\DecoderInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as TypeConfigurable;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Codilar\ProductOrderSuit\Block\ProductOrderCount;

/**
 * class  Configurable
 */
class Configurable implements ArgumentInterface
{
    const SIMPLE_PRODUCT = 'simple';

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;
    /**
     * @var DecoderInterface
     */
    protected $jsonDecoder;
    /**
     * @var
     */
    protected $stockRegistry;
    private GetProductSalableQtyInterface $salableQty;
    private StockResolverInterface $stockResolver;
    private ProductOrderCount $productOrderCount;

    public function __construct(
        EncoderInterface              $jsonEncoder,
        DecoderInterface              $jsonDecoder,
        GetProductSalableQtyInterface $salableQty,
        StockResolverInterface        $stockResolverInterface,
        ProductOrderCount             $productOrderCount
    )
    {

        $this->jsonDecoder = $jsonDecoder;
        $this->jsonEncoder = $jsonEncoder;
        $this->salableQty = $salableQty;
        $this->stockResolverInterface = $stockResolverInterface;
        $this->productOrderCount = $productOrderCount;
    }

    /**
     * @param TypeConfigurable $subject
     * @param $result
     * @return string
     * @throws Exception
     */
    public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject, $result)
    {
        $result = $this->jsonDecoder->decode($result);
        foreach ($subject->getAllowProducts() as $product) {
            $salableQty = $this->productOrderCount->getProductQty($product);
            $result['salable_qty'][$product->getId()] = $salableQty;
         $count =    $this->productOrderCount->getCountForProduct($product->getSku());
            $result['product_count'][$product->getId()] =
                $this->productOrderCount->getCountForProduct($product->getSku());
        }
        return $this->jsonEncoder->encode($result);
    }

}