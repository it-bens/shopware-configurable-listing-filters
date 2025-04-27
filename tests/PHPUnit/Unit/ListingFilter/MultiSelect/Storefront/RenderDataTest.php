<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementCollection;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RenderData::class)]
final class RenderDataTest extends TestCase
{
    public static function shouldBeRenderedProvider(): \Generator
    {
        $elements = [new Element('red', 'Red'), new Element('blue', 'Blue')];

        yield 'with elements' => [
            new RenderData(
                'template',
                'name',
                'displayName',
                'pluginSelector',
                new ElementCollection($elements, []),
                'disabledFilterTooltip'
            ),
            true,
        ];

        yield 'without elements' => [
            new RenderData('template', 'name', 'displayName', 'pluginSelector', new ElementCollection([], []), 'disabledFilterTooltip'),
            false,
        ];
    }

    #[DataProvider('shouldBeRenderedProvider')]
    public function testShouldBeRendered(RenderData $renderData, bool $expected): void
    {
        $this->assertSame($expected, $renderData->shouldBeRendered());
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
     * @return \Generator<int, array{RenderData, array<string, mixed>}, mixed, void>
     */
    public static function toArrayProvider(): \Generator
    {
        $elements = [new Element('red', 'Red'), new Element('blue', 'Blue')];

        yield [
            new RenderData(
                'template',
                'name',
                'displayName',
                'pluginSelector',
                new ElementCollection($elements, []),
                'disabledFilterTooltip'
            ),
            [
                'name' => 'name',
                'displayName' => 'displayName',
                'pluginSelector' => 'pluginSelector',
                'dataPluginSelectorOptions' => [
                    'name' => 'name',
                    'displayName' => 'displayName',
                    'snippets' => [
                        'disabledFilterText' => 'disabledFilterTooltip',
                    ],
                ],
                'elements' => $elements,
            ],
        ];
    }
}
