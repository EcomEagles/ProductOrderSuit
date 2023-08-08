<?php

namespace Codilar\ProductOrderSuit\Model;

use Codilar\ProductOrderSuit\Model\ResourceModel\ProductFinder\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 * @package Codilar\ProductOrderSuit\Model
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string            $name,
        string            $primaryFieldName,
        string            $requestFieldName,
        CollectionFactory $collectionFactory,
        array             $meta = [],
        array             $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }
        return $this->loadedData;
    }
}
