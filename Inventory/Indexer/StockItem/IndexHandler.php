<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Inventory\Indexer\StockItem;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Indexer\SaveHandler\Batch;
use Magento\Inventory\Indexer\IndexHandlerInterface;
use Magento\Inventory\Indexer\IndexName;
use Magento\Inventory\Indexer\IndexNameResolverInterface;

/**
 * Index handler is responsible for index data manipulation
 */
class IndexHandler implements IndexHandlerInterface
{
    /**
     * @var IndexNameResolverInterface
     */
    private $indexNameResolver;

    /**
     * @var Batch
     */
    private $batch;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @param IndexNameResolverInterface $indexNameResolver
     * @param Batch $batch
     * @param ResourceConnection $resourceConnection
     * @param int $batchSize
     */
    public function __construct(
        IndexNameResolverInterface $indexNameResolver,
        Batch $batch,
        ResourceConnection $resourceConnection,
        $batchSize
    ) {
        $this->indexNameResolver = $indexNameResolver;
        $this->batch = $batch;
        $this->resourceConnection = $resourceConnection;
        $this->batchSize = $batchSize;
    }

    /**
     * @inheritdoc
     */
    public function saveIndex(IndexName $indexName, \Traversable $documents, string $connectionName)
    {
        $connection = $this->resourceConnection->getConnection($connectionName);
        $tableName = $this->indexNameResolver->resolveName($indexName);

        $columns = [IndexStructure::SKU, IndexStructure::QUANTITY];
        foreach ($this->batch->getItems($documents, $this->batchSize) as $batchDocuments) {
            $connection->insertArray($tableName, $columns, $batchDocuments);
        }
    }

    /**
     * @inheritdoc
     */
    public function cleanIndex(IndexName $indexName, \Traversable $documents, string $connectionName)
    {
        $connection = $this->resourceConnection->getConnection($connectionName);
        $tableName = $this->indexNameResolver->resolveName($indexName);
        $connection->delete($tableName, ['sku in (?)' => iterator_to_array($documents)]);
    }
}
