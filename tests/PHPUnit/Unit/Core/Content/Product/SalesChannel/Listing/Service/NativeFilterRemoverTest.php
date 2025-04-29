<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\Core\Content\Product\SalesChannel\Listing\Service;

use ITB\ITBConfigurableListingFilters\Core\Content\Product\SalesChannel\Listing\Service\NativeFilterRemover;
use ITB\ITBConfigurableListingFilters\ITBConfigurableListingFilters;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;

#[CoversClass(NativeFilterRemover::class)]
final class NativeFilterRemoverTest extends TestCase
{
    private NativeFilterRemover $nativeFilterRemover;

    private SystemConfigService&MockObject $systemConfigServiceMock;

    protected function setUp(): void
    {
        $this->systemConfigServiceMock = $this->createMock(SystemConfigService::class);
        $this->nativeFilterRemover = new NativeFilterRemover($this->systemConfigServiceMock);
    }

    public function testRemoveNativeFiltersWhenAllDisabled(): void
    {
        $salesChannelId = 'test-sales-channel-id';
        $filterCollection = new FilterCollection([]);
        $filterCollection->add($this->createFilterMock('manufacturer'));
        $filterCollection->add($this->createFilterMock('properties'));
        $filterCollection->add($this->createFilterMock('price'));
        $filterCollection->add($this->createFilterMock('rating'));
        $filterCollection->add($this->createFilterMock('shipping-free'));

        $this->systemConfigServiceMock->method('getBool')
            ->willReturnMap([
                [$this->buildFullConfigKey('enableManufacturerFilter'), $salesChannelId, false],
                [$this->buildFullConfigKey('enablePropertiesFilter'), $salesChannelId, false],
                [$this->buildFullConfigKey('enablePriceFilter'), $salesChannelId, false],
                [$this->buildFullConfigKey('enableRatingFilter'), $salesChannelId, false],
                [$this->buildFullConfigKey('enableShippingFreeFilter'), $salesChannelId, false],
            ]);

        $this->nativeFilterRemover->removeNativeFilters($filterCollection, $salesChannelId);

        $this->assertCount(0, $filterCollection);
    }

    public function testRemoveNativeFiltersWhenAllEnabled(): void
    {
        $salesChannelId = 'test-sales-channel-id';
        $filterCollection = new FilterCollection([]);
        $filterCollection->add($this->createFilterMock('manufacturer'));
        $filterCollection->add($this->createFilterMock('properties'));
        $filterCollection->add($this->createFilterMock('price'));
        $filterCollection->add($this->createFilterMock('rating'));
        $filterCollection->add($this->createFilterMock('shipping-free'));

        $this->systemConfigServiceMock->method('getBool')
            ->willReturnMap([
                [$this->buildFullConfigKey('enableManufacturerFilter'), $salesChannelId, true],
                [$this->buildFullConfigKey('enablePropertiesFilter'), $salesChannelId, true],
                [$this->buildFullConfigKey('enablePriceFilter'), $salesChannelId, true],
                [$this->buildFullConfigKey('enableRatingFilter'), $salesChannelId, true],
                [$this->buildFullConfigKey('enableShippingFreeFilter'), $salesChannelId, true],
            ]);

        $this->nativeFilterRemover->removeNativeFilters($filterCollection, $salesChannelId);

        $this->assertCount(5, $filterCollection);
        $this->assertTrue($filterCollection->has('manufacturer'));
        $this->assertTrue($filterCollection->has('properties'));
        $this->assertTrue($filterCollection->has('price'));
        $this->assertTrue($filterCollection->has('rating'));
        $this->assertTrue($filterCollection->has('shipping-free'));
    }

    public function testRemoveNativeFiltersWhenSomeDisabled(): void
    {
        $salesChannelId = 'test-sales-channel-id';
        $filterCollection = new FilterCollection([]);
        $filterCollection->add($this->createFilterMock('manufacturer'));
        $filterCollection->add($this->createFilterMock('properties'));
        $filterCollection->add($this->createFilterMock('price'));
        $filterCollection->add($this->createFilterMock('rating'));
        $filterCollection->add($this->createFilterMock('shipping-free'));

        $this->systemConfigServiceMock->method('getBool')
            ->willReturnMap([
                [$this->buildFullConfigKey('enableManufacturerFilter'), $salesChannelId, true],
                [$this->buildFullConfigKey('enablePropertiesFilter'), $salesChannelId, false], // disabled
                [$this->buildFullConfigKey('enablePriceFilter'), $salesChannelId, true],
                [$this->buildFullConfigKey('enableRatingFilter'), $salesChannelId, false], // disabled
                [$this->buildFullConfigKey('enableShippingFreeFilter'), $salesChannelId, true],
            ]);

        $this->nativeFilterRemover->removeNativeFilters($filterCollection, $salesChannelId);

        $this->assertCount(3, $filterCollection);
        $this->assertTrue($filterCollection->has('manufacturer'));
        $this->assertTrue($filterCollection->has('price'));
        $this->assertTrue($filterCollection->has('shipping-free'));
    }

    private function buildFullConfigKey(string $configKey): string
    {
        return ITBConfigurableListingFilters::PLUGIN_NAME . '.config.' . $configKey;
    }

    private function createFilterMock(string $name): Filter
    {
        return new Filter($name, false, [], new EqualsFilter('field', 'value'), null);
    }
}
