<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventorySalesApi\Test\Api\StockRepository;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\InventoryApi\Api\Data\StockInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

class SalesChannelManagementTest extends WebapiAbstract
{
    /**#@+
     * Service constants
     */
    const RESOURCE_PATH = '/V1/inventory/stock';
    const SERVICE_NAME = 'inventorySalesApiStockRepositoryV1';
    /**#@-*/

    /**
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock.php
     * @magentoApiDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites.php
     */
    public function testCreateStockWithSalesChannels()
    {
        $stockId = 10;
        $salesChannels = [
            [
                SalesChannelInterface::TYPE => SalesChannelInterface::TYPE_WEBSITE,
                SalesChannelInterface::CODE => 'test_0',
            ],
            [
                SalesChannelInterface::TYPE => SalesChannelInterface::TYPE_WEBSITE,
                SalesChannelInterface::CODE => 'test_1',
            ],
        ];
        $expectedData = [
            StockInterface::NAME => 'stockName',
            ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY => [
                'sales_channels' => $salesChannels,
            ],
        ];
        $this->saveStock($stockId, $expectedData);
        $stockData = $this->getStockDataById($stockId);

        self::assertArrayHasKey('sales_channels', $stockData[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);
        self::assertEquals(
            $stockData[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]['sales_channels'],
            $salesChannels
        );
    }
    /**
     * @magentoApiDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites.php
     * @magentoApiDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_with_sales_channels.php
     */
    public function testUpdateStockWithSalesChannelsReplacing()
    {
        $stockId = 10;
        $salesChannels = [
            [
                SalesChannelInterface::TYPE => SalesChannelInterface::TYPE_WEBSITE,
                SalesChannelInterface::CODE => 'test_1',
            ],
            [
                SalesChannelInterface::TYPE => SalesChannelInterface::TYPE_WEBSITE,
                SalesChannelInterface::CODE => 'test_2',
            ],
        ];
        $expectedData = [
            StockInterface::NAME => 'stockName',
            ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY => [
                'sales_channels' => $salesChannels,
            ],
        ];
        $this->saveStock($stockId, $expectedData);
        $stockData = $this->getStockDataById($stockId);

        self::assertArrayHasKey('sales_channels', $stockData[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);
        self::assertEquals(
            $stockData[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]['sales_channels'],
            $salesChannels
        );
    }

    /**
     * @param int $stockId
     * @param array $data
     * @return void
     */
    private function saveStock(int $stockId, array $data)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $stockId,
                'httpMethod' => Request::HTTP_METHOD_PUT,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];
        if (TESTS_WEB_API_ADAPTER == self::ADAPTER_REST) {
            $this->_webApiCall($serviceInfo, ['stock' => $data]);
        } else {
            $requestData = $data;
            $requestData['stockId'] = $stockId;
            $this->_webApiCall($serviceInfo, ['stock' => $requestData]);
        }
    }

    /**
     * @param int $stockId
     * @return array
     */
    private function getStockDataById(int $stockId): array
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $stockId,
                'httpMethod' => Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'Get',
            ],
        ];
        $response = (TESTS_WEB_API_ADAPTER == self::ADAPTER_REST)
            ? $this->_webApiCall($serviceInfo)
            : $this->_webApiCall($serviceInfo, ['stockId' => $stockId]);
        self::assertArrayHasKey(StockInterface::STOCK_ID, $response);
        return $response;
    }
}
