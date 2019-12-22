<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryInStorePickupFrontend\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\InventoryInStorePickupApi\Model\SearchRequest\Area\SearchTerm\DelimiterConfig;

/**
 * Provide delimiter in checkout config.
 */
class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var DelimiterConfig
     */
    private $delimiterConfig;

    /**
     * @param DelimiterConfig $delimiterConfig
     */
    public function __construct(DelimiterConfig $delimiterConfig)
    {
        $this->delimiterConfig = $delimiterConfig;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return [
            'storePickupApiSearchTermDelimiter' => $this->delimiterConfig->getDelimiter()
        ];
    }
}
