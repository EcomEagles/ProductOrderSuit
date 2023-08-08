<?php
declare(strict_types=1);

namespace Codilar\ProductOrderSuit\ViewModel;

use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Catalog\Helper\Data;
use Psr\Log\LoggerInterface;


/**
 * class ProductOrderCount
 * @package Codilar\ProductOrderSuit\ViewModel
 */
class ProductOrderCount implements ArgumentInterface
{
    const SIMPLE_PRODUCT = 'simple';

    const XML_SALABLE_QTY_MESSAGE = 'productordersuit/general/salable_qty_message';

    const XML_PRODUCTCOUNT_MESSAGE = 'productordersuit/general/order_count_message';

    const XML_SALABLE_QTY_ALERT = 'productordersuit/general/salable_qty_alert_value';

    const XML_ORDER_COUNT_VALUE = 'productordersuit/general/order_count_message_alert_value';

    const XML_ELIGIBLE_ORDERS_NUMBERS = 'productordersuit/general/eligible_orders_for_product';


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

    /**
     * @var GetSalableQuantityDataBySku
     */
    private $getProductQuantityDataBySku;

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Data
     */
    private $coreRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TimezoneInterface $timezone
     * @param Data $coreRegistry
     * @param GetSalableQuantityDataBySku $getProductQuantityDataBySku
     * @param ScopeConfigInterface $config
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context                      $context,
        OrderItemRepositoryInterface $orderItemRepository,
        SearchCriteriaBuilder        $searchCriteriaBuilder,
        TimezoneInterface            $timezone,
        Data                         $coreRegistry,
        GetSalableQuantityDataBySku  $getProductQuantityDataBySku,
        ScopeConfigInterface         $config,
        StoreManagerInterface        $storeManager,
        LoggerInterface              $logger
    )
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timezone = $timezone;
        $this->coreRegistry = $coreRegistry;
        $this->getProductQuantityDataBySku = $getProductQuantityDataBySku;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @param $sku
     * @return false|string
     */
    public function getCountForProduct($sku = null)
    {
        try {
            $currentDate = $this->timezone->date();
            /**
             * @var $product ProductInterface
             */
            $product = $this->getCurrentProduct();
            $sku = $sku ?? $product->getSku();
            $numberOfDaysAgo = $this->getConfigValue(self::XML_ORDER_COUNT_VALUE);
            // Calculate the date numberOfDays days ago
            $numberOfDaysAgoDate = $currentDate->sub(new \DateInterval('P' . $numberOfDaysAgo . 'D'))
                ->format('Y-m-d H:i:s');
            // search criteria to filter order items by SKU and creation date
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('sku', $sku)
                ->addFilter('created_at', $numberOfDaysAgoDate, 'gteq')
                ->create();
            // Get the count of order items for the specified product SKU
            $orderItemsCount = $this->orderItemRepository->getList($searchCriteria)->getTotalCount();
            $message = '';
            if ($orderItemsCount >= $this->getConfigValue(self::XML_ELIGIBLE_ORDERS_NUMBERS)) {
                $message = $this->getOrderMessage(self::XML_PRODUCTCOUNT_MESSAGE, $orderItemsCount, $numberOfDaysAgo);
            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            return false;
        }
        // Return the count of ordered items
        return $message ?? false;
    }

    /**
     * @return ProductInterface
     */
    private function getCurrentProduct()
    {
        return $this->coreRegistry->getProduct();
    }

    /**
     * @param $configPath
     * @return mixed|null
     */
    protected function getConfigValue($configPath)
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
            return $this->config->getvalue($configPath, ScopeInterface::SCOPE_STORE, $storeId);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return null;
        }
    }

    /**
     * @param $messagePath
     * @param $orderCount
     * @param $numberOfDaysAgo
     * @return string
     */
    protected function getOrderMessage($messagePath, $orderCount, $numberOfDaysAgo)
    {
        try {
            $message = $this->getConfigValue($messagePath);
        } catch (\Exception $e) {
            $message = '';
            $this->logger->critical($e->getMessage());
        }

        return sprintf($message, $numberOfDaysAgo, $orderCount);
    }

    /**
     * @param $messagePath
     * @param $value
     * @return string
     */
    protected function getMessage($messagePath, $value = null)
    {
        try {
            $message = $this->getConfigValue($messagePath);
        } catch (\Exception $e) {
            $message = '';
            $this->logger->critical($e->getMessage());
        }
        return sprintf($message, $value);
    }

    /**
     * @param $product
     * @return false|string
     */
    public function getProductQty($product = null)
    {
        try {
            $product = $product ?? $this->getCurrentProduct();
            if ($product->getTypeId() == self::SIMPLE_PRODUCT) {
                $productQty = $this->getProductQuantityDataBySku->execute($product->getSku())[0]['qty'];
                if ($productQty <= $this->getConfigValue(self::XML_SALABLE_QTY_ALERT)) {
                    return $this->getMessage(self::XML_SALABLE_QTY_MESSAGE, $productQty);
                }

            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        return false;
    }
}
