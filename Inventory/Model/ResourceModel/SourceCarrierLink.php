<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Inventory\Setup\InstallSchema;

/**
 * Implementation of basic operations for SourceCarrierLink entity for specific db layer
 * This class needed for internal purposes only, to make collection work properly
 */
class SourceCarrierLink extends AbstractDb
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(InstallSchema::TABLE_NAME_SOURCE_CARRIER_LINK, 'source_carrier_link_id');
    }
}
