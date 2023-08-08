<?php
declare(strict_types=1);

/**
 * class  ProductOrderCount
 *
 * @package      ProductOrderSuit
 * @description  ProductOrderCount class  provides count of a product is ordered
 *
 */

namespace Codilar\ProductOrderSuit\Block;

use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template\Context;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;
use \Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\StoreManagerInterface;


/**
 * class  ProductOrderCount
 */
class ProductOrderCount extends \Magento\Framework\View\Element\Template
{
    const SIMPLE_PRODUCT = 'simple';
    const XML_CARRIER_FREESHIPPING_STATUS = 'carriers/freeshipping/active';
    const XML_CARRIER_FREESHIPPING_AMOUNT = 'carriers/freeshipping/free_shipping_subtotal';

    const XML_SALABLE_QTY_MESSAGE = 'productordersuit/general/salable_qty_message';
    const XML_PRODUCTCOUNT_MESSAGE = 'productordersuit/general/order_count_message';

    const XML_FREE_SHIPPING_MESSAGE = 'productordersuit/general/free_shipping_message';


    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var TimezoneInterface
     */
    private $timezone;
    private Registry $registry;

    private GetProductSalableQtyInterface $productSalableQty;
    private StockResolverInterface $stockResolverInterface;
    private GetSalableQuantityDataBySku $getProductQuantityDataBySku;
    private ScopeConfigInterface $config;
    private StoreManagerInterface $storeManager;
    private StockStateInterface $stockStateInterface;

    /**
     * @param Context $context
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TimezoneInterface $timezone
     * @param Registry $registry
     * @param GetProductSalableQtyInterface $productSalableQty
     * @param array $data
     */
    public function __construct(
        Context                       $context,
        OrderItemRepositoryInterface  $orderItemRepository,
        SearchCriteriaBuilder         $searchCriteriaBuilder,
        TimezoneInterface             $timezone,
        Registry                      $registry,
        StockResolverInterface        $stockResolverInterface,
        GetProductSalableQtyInterface $productSalableQty,
        GetSalableQuantityDataBySku   $getProductQuantityDataBySku,
        StockStateInterface           $stockStateInterface,
        ScopeConfigInterface          $config,
        StoreManagerInterface         $storeManager,
                                      $data = []
    )

    {
        $this->orderItemRepository = $orderItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timezone = $timezone;
        $this->registry = $registry;
        $this->productSalableQty = $productSalableQty;
        $this->stockResolverInterface = $stockResolverInterface;
        $this->getProductQuantityDataBySku = $getProductQuantityDataBySku;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->stockStateInterface = $stockStateInterface;
        parent::__construct($context, $data);
    }

    /**
     * getCountForProduct Get count of a product ordered
     * @param null $sku
     * @return string
     * @throws Exception
     */
    public
    function getCountForProduct($sku = null): string
    {
        try {

            $currentDate = $this->timezone->date();
            /**
             * @var $product ProductInterface
             */
            $product = $this->getCurrentProduct();
            $sku = $sku ?? $product->getSku();
            // Calculate the date numberOfDays days ago
            $twentyDaysAgo = $currentDate->sub(new \DateInterval('P200D'))
                ->format('Y-m-d H:i:s');
            // search criteria to filter order items by SKU and creation date
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('sku', $sku)
                ->addFilter('created_at', $twentyDaysAgo, 'gteq')
                ->create();

            // Get the count of order items for the specified product SKU
            $orderItemsCount = $this->orderItemRepository->getList($searchCriteria)->getTotalCount();
            $message = $this->getMessage(self::XML_PRODUCTCOUNT_MESSAGE,null, $orderItemsCount);




        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
        // Return the count of ordered items
        return $message;

    }

    /**
     * @return ProductInterface
     */
    private function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    private function getMessage($messagePath, $valuePath, $value = null)
    {
        $message = $this->config->getvalue($messagePath);
        $valueno = $value ? $value : $this->config->getvalue($valuePath);
        return sprintf($message, $valueno);
    }

    /**
     * @param $product
     * @return false|string
     */
    public function getProductQty($product = null)
    {
        $product = $product ?? $this->getCurrentProduct();
        if ($product->getTypeId() == self::SIMPLE_PRODUCT) {
            $qty = $this->getProductQuantityDataBySku->execute($product->getSku())[0]['qty'];
            return $this->getMessage(self::XML_SALABLE_QTY_MESSAGE, null, $qty);
        }
        return false;
    }

    /**
     * @return false|mixed
     */
    public function getFreeShippingAmount()
    {
        return $this->getMessage(self::XML_FREE_SHIPPING_MESSAGE,
            self::XML_CARRIER_FREESHIPPING_AMOUNT);
    }
}
