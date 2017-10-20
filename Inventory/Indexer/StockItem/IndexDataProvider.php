<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Inventory\Indexer\StockItem;

use Magento\Framework\App\ResourceConnection;
use Magento\Inventory\Model\ResourceModel\Source as SourceResourceModel;
use Magento\Inventory\Model\ResourceModel\SourceItem as SourceItemResourceModel;
use Magento\Inventory\Model\ResourceModel\StockSourceLink as StockSourceLinkResourceModel;
use Magento\Inventory\Model\StockSourceLink;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Index data provider
 */
class IndexDataProvider
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Returns all data for the index.
     *
     * @param int $stockId
     * @return \ArrayIterator
     */
    public function getData(int $stockId): \ArrayIterator
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $this->prepareSelect($stockId);

        return new \ArrayIterator($connection->fetchAll($select));
    }

    /**
     * Returns all data for the index by SKU List condition.
     *
     * @param int $stockId
     * @param array $skuList
     * @return \ArrayIterator
     */
    public function getDataBySkuList(int $stockId, array $skuList): \ArrayIterator
    {
        $connection = $this->resourceConnection->getConnection();
        $condition = count($skuList)
            ? ['source_item.' . SourceItemInterface::SKU . ' IN (?)', $skuList]
            : [];
        $select = $this->prepareSelect($stockId, $condition);


        return new \ArrayIterator($connection->fetchAll($select));
    }

    /**
     * Prepare select.
     *
     * @param int $stockId
     * @param array $conditions
     * @return \ArrayIterator|\Magento\Framework\DB\Select
     */
    private function prepareSelect($stockId, array $conditions = [])
    {
        $connection = $this->resourceConnection->getConnection();
        $sourceTable = $this->resourceConnection->getTableName(SourceResourceModel::TABLE_NAME_SOURCE);
        $sourceItemTable = $this->resourceConnection->getTableName(SourceItemResourceModel::TABLE_NAME_SOURCE_ITEM);
        $sourceStockLinkTable = $this->resourceConnection->getTableName(
            StockSourceLinkResourceModel::TABLE_NAME_STOCK_SOURCE_LINK
        );

        // find all enabled sources
        $select = $connection->select()
            ->from($sourceTable, [SourceInterface::SOURCE_ID])
            ->where(SourceInterface::ENABLED . ' = ?', 1);
        $sourceIds = $connection->fetchCol($select);

        if (0 === count($sourceIds)) {
            return new \ArrayIterator([]);
        }

        $select = $connection
            ->select()
            ->from(
                ['source_item' => $sourceItemTable],
                [
                    SourceItemInterface::SKU,
                    SourceItemInterface::QUANTITY => 'SUM(' . SourceItemInterface::QUANTITY . ')',
                ]
            )
            ->joinLeft(
                ['stock_source_link' => $sourceStockLinkTable],
                'source_item.' . SourceItemInterface::SOURCE_ID . ' = stock_source_link.' . StockSourceLink::SOURCE_ID,
                []
            )
            ->where('source_item.' . SourceItemInterface::STATUS . ' = ?', SourceItemInterface::STATUS_IN_STOCK)
            ->where('stock_source_link.' . StockSourceLink::STOCK_ID . ' = ?', $stockId)
            ->where('stock_source_link.' . StockSourceLink::SOURCE_ID . ' IN (?)', $sourceIds);

        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $select->where($condition);
            }
        }

        $select->group([SourceItemInterface::SKU]);

        return $select;
    }
}
