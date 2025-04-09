<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValueBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(RequestValueBuilder::class)]
final class RequestValueBuilderTest extends TestCase
{
    public static function buildRequestValueProvider(): \Generator
    {
        $requestValueBuilder = new RequestValueBuilder();

        $configurationEntity = new RangeListingFilterConfigurationEntity();
        $configurationEntity->setDalField('price');

        $requestStub = self::createStub(Request::class);
        $inputBagStub = new InputBag([
            $configurationEntity->getMinimalValueAggregationName() => '100',
            $configurationEntity->getMaximalValueAggregationName() => '500',
        ]);
        $requestStub->query = $inputBagStub;
        $requestStub->request = $inputBagStub;
        yield 'request with min and max values' => [$requestValueBuilder, $configurationEntity, $requestStub, 100, 500];

        $requestStub = self::createStub(Request::class);
        $inputBagStub = new InputBag([
            $configurationEntity->getMinimalValueAggregationName() => '100',
        ]);
        $requestStub->query = $inputBagStub;
        $requestStub->request = $inputBagStub;
        yield 'request with only min value' => [$requestValueBuilder, $configurationEntity, $requestStub, 100, null];

        $requestStub = self::createStub(Request::class);
        $inputBagStub = new InputBag([
            $configurationEntity->getMaximalValueAggregationName() => '500',
        ]);
        $requestStub->query = $inputBagStub;
        $requestStub->request = $inputBagStub;
        yield 'request with only max value' => [$requestValueBuilder, $configurationEntity, $requestStub, null, 500];

        $requestStub = self::createStub(Request::class);
        $inputBagStub = new InputBag([]);
        $requestStub->query = $inputBagStub;
        $requestStub->request = $inputBagStub;
        yield 'request with no values' => [$requestValueBuilder, $configurationEntity, $requestStub, null, null];

        $requestStub = self::createStub(Request::class);
        $inputBagStub = new InputBag([
            $configurationEntity->getMinimalValueAggregationName() => 'abc',
            $configurationEntity->getMaximalValueAggregationName() => 'xyz',
        ]);
        $requestStub->request = $inputBagStub;
        $requestStub->query = $inputBagStub;
        yield 'request with non-numeric values' => [$requestValueBuilder, $configurationEntity, $requestStub, 0, 0];
    }

    #[DataProvider('buildRequestValueProvider')]
    public function testBuildRequestValue(
        RequestValueBuilder $requestValueBuilder,
        RangeListingFilterConfigurationEntity $configurationEntity,
        Request $request,
        ?int $expectedGte,
        ?int $expectedLte,
    ): void {
        $requestValue = $requestValueBuilder->buildRequestValue($configurationEntity, $request);
        $range = $requestValue->range();

        if ($expectedGte !== null) {
            $this->assertArrayHasKey(RangeFilter::GTE, $range);
            $this->assertSame($expectedGte, $range[RangeFilter::GTE]);
        } else {
            $this->assertArrayNotHasKey(RangeFilter::GTE, $range);
        }

        if ($expectedLte !== null) {
            $this->assertArrayHasKey(RangeFilter::LTE, $range);
            $this->assertSame($expectedLte, $range[RangeFilter::LTE]);
        } else {
            $this->assertArrayNotHasKey(RangeFilter::LTE, $range);
        }
    }
}
