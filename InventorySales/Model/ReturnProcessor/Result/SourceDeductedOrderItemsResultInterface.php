<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventorySales\Model\ReturnProcessor\Result;

/**
 * @api
 */
interface SourceDeductedOrderItemsResultInterface
{
    /**
     * @return string
     */
    public function getSourceCode() : string;

    /**
     * @return \Magento\InventorySales\Model\ReturnProcessor\Result\SourceDeductedOrderItemInterface[]
     */
    public function getItems(): array;
}
