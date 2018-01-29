<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryCatalog\Plugin\InventoryApi\StockSourceLinksSave;

use Magento\Framework\Exception\InputException;
use Magento\InventoryApi\Api\StockSourceLinksSaveInterface;
use Magento\InventoryCatalog\Api\DefaultSourceProviderInterface;
use Magento\InventoryCatalog\Api\DefaultStockProviderInterface;
use Magento\InventoryApi\Api\Data\StockSourceLinkInterface;

/**
 * Prevent assign sources to Default Stock
 */
class PreventAssignSourcesToDefaultStockPlugin
{
    /**
     * @var DefaultSourceProviderInterface
     */
    private $defaultSourceProvider;

    /**
     * @var DefaultStockProviderInterface
     */
    private $defaultStockProvider;

    /**
     * @param DefaultSourceProviderInterface $defaultSourceProvider
     * @param DefaultStockProviderInterface $defaultStockProvider
     */
    public function __construct(
        DefaultSourceProviderInterface $defaultSourceProvider,
        DefaultStockProviderInterface $defaultStockProvider
    ) {
        $this->defaultStockProvider = $defaultStockProvider;
        $this->defaultSourceProvider = $defaultSourceProvider;
    }

    /**
     * @param StockSourceLinksSaveInterface $subject
     * @param StockSourceLinkInterface[] $links
     * @return void
     * @throws InputException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeExecute(StockSourceLinksSaveInterface $subject, array $links)
    {
        if (0 !== count($links)) {
            foreach ($links as $link) {
                if ($this->defaultStockProvider->getId() == $link->getStockId() &&
                    $this->defaultSourceProvider->getCode() != $link->getSourceCode()) {
                    throw new InputException(__('You can only assign Default Source to Default Stock'));
                }
            }
        }
    }
}
