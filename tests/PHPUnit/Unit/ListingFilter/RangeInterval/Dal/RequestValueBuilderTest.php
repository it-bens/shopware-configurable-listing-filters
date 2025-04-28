<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalCollection;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitterInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValue;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValueBuilder;
use ITB\ITBConfigurableListingFilters\ListingFilter\ValueFromRequestExtractorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(RequestValueBuilder::class)]
final class RequestValueBuilderTest extends TestCase
{
    private MockObject&MultiSelectValueSplitterInterface $multiSelectValueSplitterMock;

    private RequestValueBuilder $requestValueBuilder;

    private MockObject&ValueFromRequestExtractorInterface $valueFromRequestExtractorMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->valueFromRequestExtractorMock = $this->createMock(ValueFromRequestExtractorInterface::class);
        $this->multiSelectValueSplitterMock = $this->createMock(MultiSelectValueSplitterInterface::class);
        $this->requestValueBuilder = new RequestValueBuilder($this->valueFromRequestExtractorMock, $this->multiSelectValueSplitterMock);
    }

    public function testBuildRequestValueHappyPath(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName('test_filter');
        $config->setDalField('product.price');

        $filterName = $config->getFilterName();

        $interval1Id = Uuid::randomHex();
        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId($interval1Id);
        $interval1->setMin(0);
        $interval1->setMax(100);

        $interval2Id = Uuid::randomHex();
        $interval2 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval2->setId($interval2Id);
        $interval2->setMin(100);
        $interval2->setMax(200);

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1, $interval2]);
        $config->setIntervals($intervalCollection);

        $request = new Request();
        $requestIdsString = $interval1Id . '|' . $interval2Id;
        $requestIdsArray = [$interval1Id, $interval2Id];

        $this->valueFromRequestExtractorMock
            ->expects($this->once())
            ->method('extractValueFromRequest')
            ->with($this->identicalTo($request), $filterName)
            ->willReturn($requestIdsString);

        $this->multiSelectValueSplitterMock
            ->expects($this->once())
            ->method('splitMultiSelectValue')
            ->with($requestIdsString, $this->identicalTo($config))
            ->willReturn($requestIdsArray);

        $result = $this->requestValueBuilder->buildRequestValue($config, $request);

        $this->assertInstanceOf(RequestValue::class, $result);
        $this->assertTrue($result->isFiltered());
        $expectedRange = [
            'gte' => 0,
            'lte' => 200,
        ];
        $this->assertSame($expectedRange, $result->range());
    }

    public function testBuildRequestValueIdsNotInCollection(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName('test_filter_wrong_ids');
        $config->setDalField('product.rating');

        $filterName = $config->getFilterName();

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId(Uuid::randomHex());

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1]);
        $config->setIntervals($intervalCollection);

        $request = new Request();
        $nonExistentId = Uuid::randomHex();
        $requestIdsString = $nonExistentId;
        $requestIdsArray = [$nonExistentId];

        $this->valueFromRequestExtractorMock
            ->expects($this->once())
            ->method('extractValueFromRequest')
            ->with($this->identicalTo($request), $filterName)
            ->willReturn($requestIdsString);

        $this->multiSelectValueSplitterMock
            ->expects($this->once())
            ->method('splitMultiSelectValue')
            ->with($requestIdsString, $this->identicalTo($config))
            ->willReturn($requestIdsArray);

        $result = $this->requestValueBuilder->buildRequestValue($config, $request);

        $this->assertInstanceOf(RequestValue::class, $result);
        $this->assertFalse($result->isFiltered());
        $this->assertEmpty($result->range());
    }

    public function testBuildRequestValueNoIdsInRequest(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName('test_filter_no_ids');
        $config->setDalField('product.stock');

        $filterName = $config->getFilterName();

        $interval1 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval1->setId(Uuid::randomHex());

        $intervalCollection = new RangeIntervalListingFilterConfigurationIntervalCollection([$interval1]);
        $config->setIntervals($intervalCollection);

        $request = new Request();
        $requestIdsString = '';
        $requestIdsArray = [];

        $this->valueFromRequestExtractorMock
            ->expects($this->once())
            ->method('extractValueFromRequest')
            ->with($this->identicalTo($request), $filterName)
            ->willReturn($requestIdsString);

        $this->multiSelectValueSplitterMock
            ->expects($this->once())
            ->method('splitMultiSelectValue')
            ->with($requestIdsString, $this->identicalTo($config))
            ->willReturn($requestIdsArray);

        $result = $this->requestValueBuilder->buildRequestValue($config, $request);

        $this->assertInstanceOf(RequestValue::class, $result);
        $this->assertFalse($result->isFiltered());
        $this->assertEmpty($result->range());
    }

    public function testBuildRequestValueThrowsExceptionWhenIntervalsNotLoaded(): void
    {
        $config = new RangeIntervalListingFilterConfigurationEntity();
        $config->setId(Uuid::randomHex());
        $config->setUniqueName('test_filter_no_intervals_loaded');
        $config->setDalField('product.someField');

        $filterName = $config->getFilterName();

        $request = new Request();
        $requestIdsString = Uuid::randomHex();
        $requestIdsArray = [$requestIdsString];

        $this->valueFromRequestExtractorMock
            ->expects($this->once())
            ->method('extractValueFromRequest')
            ->with($this->identicalTo($request), $filterName)
            ->willReturn($requestIdsString);

        $this->multiSelectValueSplitterMock
            ->expects($this->once())
            ->method('splitMultiSelectValue')
            ->with($requestIdsString, $this->identicalTo($config))
            ->willReturn($requestIdsArray);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('`intervals` not loaded in `RangeIntervalListingFilterConfigurationEntity`');

        $this->requestValueBuilder->buildRequestValue($config, $request);
    }
}
