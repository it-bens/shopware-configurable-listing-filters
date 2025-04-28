<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\Aggregate\Interval\RangeIntervalListingFilterConfigurationIntervalEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\Uuid\Uuid;

#[CoversClass(RequestValue::class)]
final class RequestValueTest extends TestCase
{
    /**
     * @return iterable<string, array{intervals: array<RangeIntervalListingFilterConfigurationIntervalEntity>, expectedRange: array{gte?: int, lte?: int}}>
     */
    public static function rangeDataProvider(): iterable
    {
        $interval0_100 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval0_100->setId(Uuid::randomHex());
        $interval0_100->setMin(0);
        $interval0_100->setMax(100);

        $interval50_150 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval50_150->setId(Uuid::randomHex());
        $interval50_150->setMin(50);
        $interval50_150->setMax(150);

        $interval100_200 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval100_200->setId(Uuid::randomHex());
        $interval100_200->setMin(100);
        $interval100_200->setMax(200);

        $intervalNull_50 = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $intervalNull_50->setId(Uuid::randomHex());
        $intervalNull_50->setMin(null);
        $intervalNull_50->setMax(50);

        $interval150_Null = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval150_Null->setId(Uuid::randomHex());
        $interval150_Null->setMin(150);
        $interval150_Null->setMax(null);

        $intervalNull_Null = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $intervalNull_Null->setId(Uuid::randomHex());
        $intervalNull_Null->setMin(null);
        $intervalNull_Null->setMax(null);

        yield 'Empty intervals' => [
            'intervals' => [],
            'expectedRange' => [],
        ];

        yield 'Single closed interval' => [
            'intervals' => [$interval0_100],
            'expectedRange' => [
                RangeFilter::GTE => 0,
                RangeFilter::LTE => 100,
            ],
        ];

        yield 'Two overlapping closed intervals' => [
            'intervals' => [$interval0_100, $interval50_150],
            'expectedRange' => [
                RangeFilter::GTE => 0,
                RangeFilter::LTE => 150,
            ],
        ];

        yield 'Two non-overlapping closed intervals' => [
            'intervals' => [$interval0_100, $interval100_200],
            'expectedRange' => [
                RangeFilter::GTE => 0,
                RangeFilter::LTE => 200,
            ],
        ];

        yield 'Single open lower bound interval' => [
            'intervals' => [$intervalNull_50],
            'expectedRange' => [
                RangeFilter::LTE => 50,
            ],
        ];

        yield 'Single open upper bound interval' => [
            'intervals' => [$interval150_Null],
            'expectedRange' => [
                RangeFilter::GTE => 150,
            ],
        ];

        yield 'Single open lower and upper bound interval' => [
            'intervals' => [$intervalNull_Null],
            'expectedRange' => [],
        ];

        yield 'Mixed intervals including open lower bound' => [
            'intervals' => [$interval0_100, $intervalNull_50],
            'expectedRange' => [
                RangeFilter::LTE => 100,
            ],
        ];

        yield 'Mixed intervals including open upper bound' => [
            'intervals' => [$interval0_100, $interval150_Null],
            'expectedRange' => [
                RangeFilter::GTE => 0,
            ],
        ];

        yield 'Mixed intervals including open lower and upper bounds' => [
            'intervals' => [$interval0_100, $intervalNull_50, $interval150_Null],
            'expectedRange' => [],
        ];
    }

    public function testIsFilteredWithEmptyIntervals(): void
    {
        $requestValue = new RequestValue([]);
        $this->assertFalse($requestValue->isFiltered());
    }

    public function testIsFilteredWithNonEmptyIntervals(): void
    {
        $interval = new RangeIntervalListingFilterConfigurationIntervalEntity();
        $interval->setId(Uuid::randomHex());
        $interval->setMin(0);
        $interval->setMax(100);

        $requestValue = new RequestValue([$interval]);
        $this->assertTrue($requestValue->isFiltered());
    }

    /**
     * @param array<RangeIntervalListingFilterConfigurationIntervalEntity> $intervals
     * @param array{gte?: int, lte?: int} $expectedRange
     */
    #[DataProvider('rangeDataProvider')]
    public function testRange(array $intervals, array $expectedRange): void
    {
        $requestValue = new RequestValue($intervals);
        $this->assertSame($expectedRange, $requestValue->range());
    }
}
