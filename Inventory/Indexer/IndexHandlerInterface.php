<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Inventory\Indexer;

/**
 * Represents manipulation with index data
 *
 * @api
 */
interface IndexHandlerInterface
{
    /**
     * Add data to index
     *
     * @param IndexName $indexName
     * @param \Traversable $documents
     * @param string $connectionName
     * @return void
     */
    public function saveIndex(IndexName $indexName, \Traversable $documents, string $connectionName);

    /**
     * Remove given documents from Index. For StockItem index we provide list of SKUs, aggregated Quantity
     * for which should be re-calculated
     *
     * @param IndexName $indexName
     * @param \Traversable $documents
     * @param string $connectionName
     * @return void
     */
    public function cleanIndex(IndexName $indexName, \Traversable $documents, string $connectionName);
}
