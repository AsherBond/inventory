<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryCatalog\Test\Integration;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Model\ResourceModel\Stock\Status as StockStatus;
use Magento\Store\Model\Website;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * Test add stock status to select on default website.
 */
class AddStockStatusToSelectWithDefaultStockTest extends TestCase
{
    /**
     * @var StockStatus
     */
    private $stockStatus;

    /**
     * @var Website
     */
    private $website;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->stockStatus = Bootstrap::getObjectManager()->get(StockStatus::class);
        $this->website = Bootstrap::getObjectManager()->get(Website::class);
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryCatalog/Test/_files/source_items_on_default_source.php
     *
     * @param string $websiteCode
     *
     * @dataProvider addStockStatusToSelectDataProvider
     */
    public function testAddStockStatusToSelect(string $websiteCode)
    {
        $actualIsSalableCount = $actualNotSalableCount = 0;
        $expectedIsSalableCount = 2;
        $expectedNotSalableCount = 1;

        /** @var Collection $collection */
        $collection = Bootstrap::getObjectManager()->create(Collection::class);

        $this->stockStatus->addStockStatusToSelect($collection->getSelect(), $this->website->load($websiteCode));

        foreach ($collection as $item) {
            $item->getIsSalable() === true ? $actualIsSalableCount++ : $actualNotSalableCount++;
        }

        self::assertEquals($expectedIsSalableCount, $actualIsSalableCount);
        self::assertEquals($expectedNotSalableCount, $actualNotSalableCount);
        self::assertEquals($expectedNotSalableCount + $expectedIsSalableCount, $collection->getSize());
    }

    /**
     * @return array
     */
    public function addStockStatusToSelectDataProvider(): array
    {
        return [
            ['fakeCode'],
            ['base'],
        ];
    }
}
