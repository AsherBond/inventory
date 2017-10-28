<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\InventoryImportExport\Model\Export;

use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Framework\Data\Collection;
use Magento\ImportExport\Model\Export\Factory as CollectionFactory;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryImportExport\Model\Export\Source\StockStatus;

/**
 * @api
 */
class CollectionBuilder
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var AttributeFactory
     */
    private $attributeFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param AttributeFactory $attributeFactory
     * @throws \InvalidArgumentException
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        AttributeFactory $attributeFactory
    ) {
        $this->collection = $collectionFactory->create(Collection::class);
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function create(): Collection
    {
        if (count($this->collection) === 0) {
            /** @var \Magento\Eav\Model\Entity\Attribute $sourceIdAttribute */
            $sourceIdAttribute = $this->attributeFactory->create();
            $sourceIdAttribute->setId(SourceItemInterface::SOURCE_ID);
            $sourceIdAttribute->setDefaultFrontendLabel(SourceItemInterface::SOURCE_ID);
            $sourceIdAttribute->setAttributeCode(SourceItemInterface::SOURCE_ID);
            $sourceIdAttribute->setBackendType('int');
            $this->collection->addItem($sourceIdAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $skuAttribute */
            $skuAttribute = $this->attributeFactory->create();
            $skuAttribute->setId(SourceItemInterface::SKU);
            $skuAttribute->setBackendType('varchar');
            $skuAttribute->setDefaultFrontendLabel(SourceItemInterface::SKU);
            $skuAttribute->setAttributeCode(SourceItemInterface::SKU);
            $this->collection->addItem($skuAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $statusIdAttribute */
            $statusIdAttribute = $this->attributeFactory->create();
            $statusIdAttribute->setId(SourceItemInterface::STATUS);
            $statusIdAttribute->setDefaultFrontendLabel(SourceItemInterface::STATUS);
            $statusIdAttribute->setAttributeCode(SourceItemInterface::STATUS);
            $statusIdAttribute->setBackendType('int');
            $statusIdAttribute->setFrontendInput('select');
            $statusIdAttribute->setSourceModel(StockStatus::class);
            $this->collection->addItem($statusIdAttribute);

            /** @var \Magento\Eav\Model\Entity\Attribute $quantityAttribute */
            $quantityAttribute = $this->attributeFactory->create();
            $quantityAttribute->setId(SourceItemInterface::QUANTITY);
            $quantityAttribute->setBackendType('decimal');
            $quantityAttribute->setDefaultFrontendLabel(SourceItemInterface::QUANTITY);
            $quantityAttribute->setAttributeCode(SourceItemInterface::QUANTITY);
            $this->collection->addItem($quantityAttribute);
        }

        return $this->collection;
    }
}
