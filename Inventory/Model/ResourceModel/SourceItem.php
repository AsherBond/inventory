<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Inventory\Setup\InstallSchema;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Implementation of basic operations for Source Item entity for specific db layer
 */
class SourceItem extends AbstractDb
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(InstallSchema::TABLE_NAME_SOURCE_ITEM, 'source_item_id');
    }
}
