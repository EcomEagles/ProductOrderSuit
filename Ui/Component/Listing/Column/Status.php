<?php

namespace Codilar\ProductOrderSuit\Ui\Component\Listing\Column;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package Codilar\ProductOrderSuit\Ui\Component\Listing\Column
 */
class Status implements ArrayInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Enabled')],
            ['value' => 0, 'label' => __('Disabled')]
        ];
    }
}
