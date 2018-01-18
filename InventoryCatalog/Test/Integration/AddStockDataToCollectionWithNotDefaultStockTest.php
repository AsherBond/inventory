<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryCatalog\Test\Integration;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Model\ResourceModel\Stock\Status as StockStatus;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test catalog search with different stocks on second website.
 */
class AddStockDataToCollectionWithNotDefaultStockTest extends AbstractSalesChannelProvider
{
    /**
     * @var StockStatus
     */
    private $stockStatus;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->stockStatus = Bootstrap::getObjectManager()->create(StockStatus::class);
        $this->storeManager = Bootstrap::getObjectManager()->get(StoreManagerInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Store/_files/second_website_with_two_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/source_items.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_link.php
     *
     * @param int $stockId
     * @param int $expectedSize
     * @param bool $isFilterInStock
     * @return void
     *
     * @dataProvider addStockDataToCollectionDataProvider
     */
    public function testAddStockDataToCollection(int $stockId, int $expectedSize, bool $isFilterInStock)
    {
        $this->addSalesChannelTypeWebsiteToStock($stockId, 'test');

        // switch to second website
        $this->storeManager->setCurrentStore('fixture_second_store');

        /** @var Collection $collection */
        $collection = Bootstrap::getObjectManager()->create(Collection::class);
        $this->stockStatus->addStockDataToCollection($collection, $isFilterInStock);

        self::assertEquals($expectedSize, $collection->getSize());
    }

    /**
     * @return array
     */
    public function addStockDataToCollectionDataProvider(): array
    {
        return [
            [10, 1, true],
            [20, 1, true],
            [30, 2, true],
            [10, 2, false],
            [20, 1, false],
            [30, 3, false],
        ];
    }
}
