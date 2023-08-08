<?php

namespace Codilar\ProductOrderSuit\Controller\Adminhtml\Index;

use Codilar\ProductOrderSuit\Model\ProductFinderFactory;
use Codilar\ProductOrderSuit\Model\ProductFinderItemFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder as ProductFinderResourceModel;
use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinderItem as ProductFinderItemResourceModel;

/**
 * Class Save
 * @package Codilar\ProductOrderSuit\Controller\Adminhtml\Index
 */
class Save extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ProductFinderFactory
     */
    protected $productFinderFactory;

    /**
     * @var ProductFinderResourceModel
     */
    protected $productFinderResourceModel;

    /**
     * @var ProductFinderItemFactory
     */
    protected $productFinderItemFactory;

    /**
     * @var ProductFinderItemResourceModel
     */
    protected $productFinderItemResourceModel;

    /**
     * @param Context $context
     * @param ProductFinderFactory $productFinderFactory
     * @param ProductFinderResourceModel $productFinderResourceModel
     * @param ProductFinderItemFactory $productFinderItemFactory
     * @param ProductFinderItemResourceModel $productFinderItemResourceModel
     */
    public function __construct(
        Context                        $context,
        ProductFinderFactory           $productFinderFactory,
        ProductFinderResourceModel     $productFinderResourceModel,
        ProductFinderItemFactory       $productFinderItemFactory,
        ProductFinderItemResourceModel $productFinderItemResourceModel
    )
    {
        $this->productFinderFactory = $productFinderFactory;
        $this->productFinderResourceModel = $productFinderResourceModel;
        $this->productFinderItemFactory = $productFinderItemFactory;
        $this->productFinderItemResourceModel = $productFinderItemResourceModel;
        parent::__construct($context);
    }

    /**
     * To save the Product Finder admin form
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($this->getRequest()->getParams()) {
                $productFinderId = $this->getRequest()->getParam('product_finder_id');
                $productFinder = $this->productFinderFactory->create();
                if ($productFinderId) {
                    try {
                        $this->productFinderResourceModel->load($productFinder, $productFinderId);
                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage(__('This product finder no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                }

                $productFinder->setData('title', $this->getRequest()->getParam('title'));
                $productFinder->setData('status', $this->getRequest()->getParam('status'));
                $productFinder->setData('description', $this->getRequest()->getParam('description'));
                $this->productFinderResourceModel->save($productFinder);

                // To save the question and option data
                $questionsData = $this->getRequest()->getParam('questions_data');
                foreach ($questionsData as $question) {
                    $options = json_encode($question['options']);
                    $productFinderItem = $this->productFinderItemFactory->create();
                    $productFinderItem->setData('product_finder_id', $productFinder->getProductFinderId());
                    $productFinderItem->setData('question', $question['question']);
                    $productFinderItem->setData('options', $options);
                    $this->productFinderItemResourceModel->save($productFinderItem);
                }
                $this->messageManager->addSuccessMessage(__('You saved the product finder.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__("Something went wrong ") . $e->getMessage());
            return $resultRedirect->setPath('*/*/edit');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
