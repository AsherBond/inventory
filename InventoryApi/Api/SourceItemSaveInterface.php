<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventoryApi\Api;

/**
 * Service method for source items save multiple
 * Performance efficient API, used for stock synchronization
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface SourceItemSaveInterface
{
    /**
     * Save Multiple Source item data
     *
     * @param \Magento\InventoryApi\Api\Data\SourceItemInterface[] $sourceItems
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(array $sourceItems);
}
