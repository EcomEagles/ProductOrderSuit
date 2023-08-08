<?php

namespace Codilar\ProductOrderSuit\ViewModel;

use Codilar\ProductOrderSuit\Model\ProductFinder;
use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder\CollectionFactory;
use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem\Collection as productFinderItem;
use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem\CollectionFactory as ItemCollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Codilar\ProductOrderSuit\Model\ProductFinderFactory;
use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder as ProductFinderResourceModel;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 * @package Codilar\ProductOrderSuit\ViewModel
 */
class Data implements ArgumentInterface
{
    const XML_QUESTION_ID = 'productordersuit/general/question_to_show';

    const XML_QUESTION_ENABLED = 'productordersuit/general/product_finder_enabled';

    /**
     * @var ProductFinderFactory
     */
    protected $productFinderFactory;

    /**
     * @var ProductFinderResourceModel
     */
    protected $productFinderResourceModel;

    /**
     * @var CollectionFactory
     */
    protected $productFinder;

    /**
     * @var ItemCollectionFactory
     */
    protected $productFinderItem;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ProductFinderFactory $productFinderFactory
     * @param ProductFinderResourceModel $productFinderResourceModel
     * @param CollectionFactory $productFinder
     * @param ItemCollectionFactory $productFinderItem
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ProductFinderFactory       $productFinderFactory,
        ProductFinderResourceModel $productFinderResourceModel,
        CollectionFactory          $productFinder,
        ItemCollectionFactory      $productFinderItem,
        LoggerInterface            $logger,
        ScopeConfigInterface       $scopeConfig,
        UrlInterface               $urlBuilder
    )
    {
        $this->productFinderFactory = $productFinderFactory;
        $this->productFinderResourceModel = $productFinderResourceModel;
        $this->productFinder = $productFinder;
        $this->productFinderItem = $productFinderItem;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param $id
     * @return ProductFinder|null
     */
    public function getProductFinderById($id)
    {
        try {
            $productFinder = $this->productFinderFactory->create();
            $this->productFinderResourceModel->load($productFinder, $id);
            return $productFinder;
        } catch (\Exception $e) {
            $this->logger->critical('product finder error ' . $e->getMessage());
        }
        return null;
    }

    /**
     * @param $id
     * @return productFinderItem
     */
    public function getProductFinderItem($id)
    {
        try {
            $productFinderItem = $this->productFinderItem->create()
                ->addFieldToFilter('product_finder_id', $id);
        } catch (\Exception $e) {
            $this->logger->critical('product finder item error ' . $e->getMessage());
        }
        return $productFinderItem;
    }

    /**
     * @param $message
     * @return void
     */
    public function addLogger($message)
    {
        $this->logger->critical($message);
    }

    /**
     * @return DataObject
     */
    public function getProductFinder()
    {
        $questionId = $this->getProductFinderId();
        $productFinder = $this->productFinder->create()
            ->addFieldToFilter('status', 1);
        if ($questionId) {
            $productFinder->addFieldToFilter('product_finder_id', $questionId);
        }
        return $productFinder->getLastItem();
    }

    /**
     * @param $productFinder
     * @return void
     */
    public function deleteProductFinder($productFinder): void
    {
        try {
            $this->productFinderResourceModel->delete($productFinder);
        } catch (\Exception $e) {
            $this->logger->critical('product finder error ' . $e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getProductFinderId()
    {
        return $this->scopeConfig->getValue(self::XML_QUESTION_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function getIsProductFinderEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_QUESTION_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getQuestionUrl(): string
    {
        return $this->urlBuilder->getUrl('suit/index/questions');
    }
}
