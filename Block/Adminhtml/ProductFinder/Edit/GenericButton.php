<?php

namespace Codilar\ProductOrderSuit\Block\Adminhtml\ProductFinder\Edit;

use Magento\Backend\Block\Widget\Context;
use Codilar\ProductOrderSuit\ViewModel\Data;

/**
 * Class GenericButton
 * @package Codilar\ProductOrderSuit\Block\Adminhtml\ProductFinder\Edit
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Data
     */
    protected $viewModel;

    /**
     * @param Context $context
     * @param Data $viewModel
     */
    public function __construct(
        Context $context,
        Data    $viewModel
    )
    {
        $this->context = $context;
        $this->viewModel = $viewModel;
    }

    /**
     * @return int|null
     */
    public function getProductFinderId(): ?int
    {
        try {
            $productFinder = $this->viewModel->getProductFinderById($this->context->getRequest()->getParam('id'));
            return (int)$productFinder->getId();
        } catch (\Exception $e) {
            $this->viewModel->addLogger($e->getMessage());
        }
        return null;
    }

    /**
     * @param $route
     * @param $params
     * @return string
     */
    public function getUrl($route = '', $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

