<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\ProductOrderSuit\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Codilar\ProductOrderSuit\ViewModel\Data as ViewModel;

/**
 * Class Delete
 * @package Codilar\ProductOrderSuit\Model\ProductFinder
 */
class Delete extends Action
{
    /**
     * @var ViewModel
     */
    protected $viewModel;

    /**
     * @param Context $context
     * @param ViewModel $viewModel
     */
    public function __construct(
        Context   $context,
        ViewModel $viewModel
    )
    {
        $this->viewModel = $viewModel;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->viewModel->getProductFinderById($id);
                $this->viewModel->deleteProductFinder($model);
                $this->messageManager->addSuccessMessage(__('You deleted the product finder.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a product finder to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
