<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model\OptionSource;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Provide option values for UI
 *
 * @api
 */
class SourceItemStatus implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => SourceItemInterface::STATUS_IN_STOCK,
                'label' => __('In Stock'),
            ],
            [
                'value' => SourceItemInterface::STATUS_OUT_OF_STOCK,
                'label' => __('Out of Stock'),
            ],
        ];
    }
}
