<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Controller\Adminhtml\Stock;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Inventory\Model\StockSourceLink;
use Magento\Inventory\Model\StockSourceLinkFactory;
use Magento\InventoryApi\Api\AssignSourcesToStockInterface;
use Magento\InventoryApi\Api\GetAssignedSourcesForStockInterface;
use Magento\InventoryApi\Api\UnassignSourceFromStockInterface;

/**
 * At the time of processing Stock save form this class used to save links correctly
 * Perform replace strategy of sources for the stock
 */
class StockSourceLinkProcessor
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var StockSourceLinkFactory
     */
    private $stockSourceLinkFactory;

    /**
     * @var GetAssignedSourcesForStockInterface
     */
    private $getAssignedSourcesForStock;

    /**
     * @var AssignSourcesToStockInterface
     */
    private $assignSourcesToStock;

    /**
     * @var UnassignSourceFromStockInterface
     */
    private $unassignSourceFromStock;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StockSourceLinkFactory $stockSourceLinkFactory
     * @param GetAssignedSourcesForStockInterface $getAssignedSourcesForStock
     * @param AssignSourcesToStockInterface $assignSourcesToStock
     * @param UnassignSourceFromStockInterface $unassignSourceFromStock
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StockSourceLinkFactory $stockSourceLinkFactory,
        GetAssignedSourcesForStockInterface $getAssignedSourcesForStock,
        AssignSourcesToStockInterface $assignSourcesToStock,
        UnassignSourceFromStockInterface $unassignSourceFromStock
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->stockSourceLinkFactory = $stockSourceLinkFactory;
        $this->getAssignedSourcesForStock = $getAssignedSourcesForStock;
        $this->assignSourcesToStock = $assignSourcesToStock;
        $this->unassignSourceFromStock = $unassignSourceFromStock;
    }

    /**
     * @param string $stockId
     * @param array $stockSourceLinksData
     * @return void
     * @throws InputException
     */
    public function process($stockId, array $stockSourceLinksData)
    {
        $assignedSources = $this->getAssignedSourcesForStock->execute($stockId);
        $sourceIdsForSave = array_column($stockSourceLinksData, StockSourceLink::SOURCE_ID);
        $sourceIdsForDelete = [];

        foreach ($assignedSources as $assignedSource) {
            if (in_array($assignedSource->getSourceId(), $sourceIdsForSave)) {
                unset($sourceIdsForSave[$assignedSource->getSourceId()]);
            } else {
                $sourceIdsForDelete[] = $assignedSource->getSourceId();
            }
        }

        if (!empty($sourceIdsForSave)) {
            $this->assignSourcesToStock->execute($stockId, $sourceIdsForSave);
        }
        foreach ($sourceIdsForDelete as $sourceIdForDelete) {
            $this->unassignSourceFromStock->execute($stockId, $sourceIdForDelete);
        }
    }

    /**
     * TODO:
     * @param array $stockSourceLinkData
     * @return void
     * @throws InputException
     */
    private function validateStockSourceData(array $stockSourceLinkData)
    {
        if (!isset($stockSourceLinkData[StockSourceLink::SOURCE_ID])) {
            throw new InputException(__('Wrong Stock to Source relation parameters given.'));
        }
    }
}
