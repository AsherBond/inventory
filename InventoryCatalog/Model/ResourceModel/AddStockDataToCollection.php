<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryCatalog\Model\ResourceModel;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\InventoryCatalog\Model\InStockConditionResolver;
use Magento\InventoryIndexer\Indexer\IndexStructure;
use Magento\InventoryIndexer\Model\StockIndexTableNameResolverInterface;

/**
 * Add Stock data to collection
 */
class AddStockDataToCollection
{
    /**
     * @var StockIndexTableNameResolverInterface
     */
    private $stockIndexTableNameResolver;

    /**
     * @var InStockConditionResolver
     */
    private $inStockConditionResolver;

    /**
     * @param StockIndexTableNameResolverInterface $stockIndexTableNameResolver
     * @param InStockConditionResolver $inStockConditionResolver
     */
    public function __construct(
        StockIndexTableNameResolverInterface $stockIndexTableNameResolver,
        InStockConditionResolver $inStockConditionResolver
    ) {
        $this->stockIndexTableNameResolver = $stockIndexTableNameResolver;
        $this->inStockConditionResolver = $inStockConditionResolver;
    }

    /**
     * @param Collection $collection
     * @param bool $isFilterInStock
     * @param int $stockId
     * @return void
     */
    public function addStockDataToCollection(Collection $collection, bool $isFilterInStock, int $stockId)
    {
        $tableName = $this->stockIndexTableNameResolver->execute($stockId);

        $isSalableExpression = $collection->getConnection()
            ->getCheckSql($this->inStockConditionResolver->execute('stock_status_index'), 1, 0);

        $resource = $collection->getResource();
        $collection->getSelect()->joinInner(
            ['product' => $resource->getTable('catalog_product_entity')],
            sprintf('product.entity_id = %s.entity_id', Collection::MAIN_TABLE_ALIAS),
            []
        );
        $collection->getSelect()->join(
            ['stock_status_index' => $tableName],
            'product.sku = stock_status_index.' . IndexStructure::SKU,
            ['is_salable' => $isSalableExpression]
        );

        if ($isFilterInStock) {
            $collection->getSelect()->where(
                $this->inStockConditionResolver->execute('stock_status_index')
            );
        }
    }
}
