<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Range\Dal;

use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Dal\RequestValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

#[CoversClass(RequestValue::class)]
final class RequestValueTest extends TestCase
{
    public static function isFilteredProvider(): \Generator
    {
        yield 'is filtered with only gte value' => [new RequestValue(100, null), true];
        yield 'is filtered with only lte value' => [new RequestValue(null, 500), true];
        yield 'is filtered with both values' => [new RequestValue(100, 500), true];
        yield 'is not filtered with null values' => [new RequestValue(null, null), false];
    }

    /**
     * @return \Generator<string, array{RequestValue, array<string, int>}, mixed, void>
     */
    public static function rangeProvider(): \Generator
    {
        yield 'range with only gte value' => [
            new RequestValue(100, null),
            [
                RangeFilter::GTE => 100,
            ],
        ];

        yield 'range with only lte value' => [
            new RequestValue(null, 500),
            [
                RangeFilter::LTE => 500,
            ],
        ];

        yield 'range with both values' => [
            new RequestValue(100, 500),
            [
                RangeFilter::GTE => 100,
                RangeFilter::LTE => 500,
            ],
        ];

        yield 'empty range with null values' => [new RequestValue(null, null), []];
    }

    #[DataProvider('isFilteredProvider')]
    public function testIsFiltered(RequestValue $requestValue, bool $expectedIsFiltered): void
    {
        $this->assertSame($expectedIsFiltered, $requestValue->isFiltered());
    }

    /**
     * @param array<string, int> $expectedRange
     */
    #[DataProvider('rangeProvider')]
    public function testRange(RequestValue $requestValue, array $expectedRange): void
    {
        $this->assertEquals($expectedRange, $requestValue->range());
    }
}
