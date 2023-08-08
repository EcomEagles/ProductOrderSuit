<?php

namespace Codilar\ProductOrderSuit\Plugin;

use Codilar\ProductOrderSuit\ViewModel\Data;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Theme\Block\Html\Topmenu;

/**
 * Class ProductFinderLink
 * @package Codilar\ProductOrderSuit\Plugin
 */
class ProductFinderLink
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;

    /**
     * @var Data
     */
    protected $viewModel;

    /**
     * @param NodeFactory $nodeFactory
     * @param Data $viewModel
     */
    public function __construct(
        NodeFactory $nodeFactory,
        Data        $viewModel
    )
    {
        $this->nodeFactory = $nodeFactory;
        $this->viewModel = $viewModel;
    }

    /**
     * @param Topmenu $subject
     * @param $outermostClass
     * @param $childrenWrapClass
     * @param $limit
     * @return void
     */
    public function beforeGetHtml(
        Topmenu $subject,
                $outermostClass = '',
                $childrenWrapClass = '',
                $limit = 0
    )
    {
        if ($this->viewModel->getIsProductFinderEnabled()) {
            $node = $this->nodeFactory->create(
                [
                    'data' => $this->getNodeAsArray(),
                    'idField' => 'id',
                    'tree' => $subject->getMenu()->getTree()
                ]
            );
            $subject->getMenu()->addChild($node);
        }
    }

    /**
     * @return array
     */
    protected function getNodeAsArray()
    {
        return [
            'name' => __('Product Finder'),
            'id' => 'product-finder-link',
            'url' => $this->viewModel->getQuestionUrl(),
            'has_active' => false,
            'is_active' => false
        ];
    }
}
