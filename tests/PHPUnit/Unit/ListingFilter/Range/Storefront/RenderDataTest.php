<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Range\Storefront;

use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RenderData::class)]
final class RenderDataTest extends TestCase
{
    public static function shouldBeRenderedProvider(): \Generator
    {
        yield 'with different min and max values' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'price-filter',
                'Price',
                'min_price',
                'max_price',
                100,
                500,
                '€',
                'Filter is disabled'
            ),
            true,
        ];

        yield 'with equal min and max values' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'price-filter',
                'Price',
                'min_price',
                'max_price',
                100,
                100,
                '€',
                'Filter is disabled'
            ),
            false,
        ];

        yield 'with one null value' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'price-filter',
                'Price',
                'min_price',
                'max_price',
                100,
                null,
                '€',
                'Filter is disabled'
            ),
            true,
        ];

        yield 'with both null values' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'price-filter',
                'Price',
                'min_price',
                'max_price',
                null,
                null,
                '€',
                'Filter is disabled'
            ),
            true,
        ];
    }

    #[DataProvider('shouldBeRenderedProvider')]
    public function testShouldBeRendered(RenderData $renderData, bool $expectedShouldBeRendered): void
    {
        $this->assertSame($expectedShouldBeRendered, $renderData->shouldBeRendered());
    }

    /**
     * @param array<string, mixed> $expectedArray
     */
    #[DataProvider('toArrayProvider')]
    public function testToArray(RenderData $renderData, array $expectedArray): void
    {
        $this->assertEquals($expectedArray, $renderData->toArray());
    }

    /**
     * @return \Generator<string, array{RenderData, array<string, mixed>}, mixed, void>
     */
    public static function toArrayProvider(): \Generator
    {
        yield 'complete data with units' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'price-filter',
                'Price',
                'min_price',
                'max_price',
                100,
                500,
                '€',
                'Filter is disabled'
            ),
            [
                'name' => 'price-filter',
                'displayName' => 'Price',
                'dataPluginSelectorOptions' => [
                    'name' => 'price-filter',
                    'snippets' => [
                        'disabledFilterText' => 'Filter is disabled',
                    ],
                ],
                'minKey' => 'min_price',
                'maxKey' => 'max_price',
                'minInputValue' => 100,
                'maxInputValue' => 500,
                'unit' => '€',
            ],
        ];

        yield 'data without unit' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'height-filter',
                'Height',
                'min_height',
                'max_height',
                10,
                100,
                null,
                'Filter is disabled'
            ),
            [
                'name' => 'height-filter',
                'displayName' => 'Height',
                'dataPluginSelectorOptions' => [
                    'name' => 'height-filter',
                    'snippets' => [
                        'disabledFilterText' => 'Filter is disabled',
                    ],
                ],
                'minKey' => 'min_height',
                'maxKey' => 'max_height',
                'minInputValue' => 10,
                'maxInputValue' => 100,
                'unit' => '',
            ],
        ];

        yield 'data with null values' => [
            new RenderData(
                '@Storefront/storefront/component/listing/filter/filter-range.html.twig',
                'weight-filter',
                'Weight',
                'min_weight',
                'max_weight',
                null,
                null,
                'kg',
                'Filter is disabled'
            ),
            [
                'name' => 'weight-filter',
                'displayName' => 'Weight',
                'dataPluginSelectorOptions' => [
                    'name' => 'weight-filter',
                    'snippets' => [
                        'disabledFilterText' => 'Filter is disabled',
                    ],
                ],
                'minKey' => 'min_weight',
                'maxKey' => 'max_weight',
                'minInputValue' => null,
                'maxInputValue' => null,
                'unit' => 'kg',
            ],
        ];
    }
}
