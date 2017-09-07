<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model;

use Magento\Inventory\Model\ResourceModel\Reservation as ReservationResourceModel;
use Magento\InventoryApi\Api\Data\ReservationInterface;

/**
 * {@inheritdoc}
 *
 * @codeCoverageIgnore
 */
class Reservation implements ReservationInterface
{
    /**
     * @var int|null
     */
    private $reservationId;

    /**
     * @var int
     */
    private $stockId;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $metadata;

    public function __construct(
        $reservationId,
        int $stockId,
        string $sku,
        float $quantity,
        $metadata = null
    ) {
        $this->reservationId = $reservationId;
        $this->stockId = $stockId;
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->metadata = $metadata;
    }

    /**
     * @inheritdoc
     */
    public function getReservationId()
    {
        return $this->reservationId;
    }

    /**
     * @inheritdoc
     */
    public function getStockId(): int
    {
        return $this->stockId;
    }

    /**
     * @inheritdoc
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @inheritdoc
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @inheritdoc
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
