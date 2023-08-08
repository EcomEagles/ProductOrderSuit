<?php

namespace Codilar\ProductOrderSuit\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\UrlInterface;

/**
 * Class Submit
 * @package Codilar\ProductOrderSuit\Controller\Index
 */
class Submit extends Action
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param Context $context
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Context      $context,
        UrlInterface $urlBuilder
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|void
     */
    public function execute()
    {
        $productRequests = $this->getRequest()->getParams();
        if ($productRequests) {
            unset($productRequests['form_key']);
            $skuList = [];
            foreach ($productRequests as $productRequest) {
                if (isset($productRequest[0])) {
                    $skuList[] = $productRequest[0];
                }
            }
            $params = array('finder' => implode('-', $skuList));
            $redirecturl = $this->urlBuilder->getUrl('suit/index/productlist', ['_current' => true, '_use_rewrite' => true, '_query' => $params]);
            return $this->resultRedirectFactory->create()->setUrl($redirecturl);
        }
    }
}
