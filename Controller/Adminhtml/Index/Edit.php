<?php

namespace Codilar\ProductOrderSuit\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

/**
 * Class Edit
 * @package Codilar\ProductOrderSuit\Controller\Adminhtml\Index
 */
class Edit extends Action
{
    /**
     * @return ResponseInterface|ResultInterface|Page|(Page&ResultInterface)
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend((__('Product Finder')));
        return $resultPage;
    }
}
