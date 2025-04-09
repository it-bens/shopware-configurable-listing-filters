<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Checkbox\Storefront;

use ITB\ITBConfigurableListingFilters\ListingFilter\Checkbox\Storefront\RenderData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RenderData::class)]
final class RenderDataTest extends TestCase
{
    public static function shouldBeRenderedProvider(): \Generator
    {
        yield [new RenderData('template', 'name', 'displayName', 'disabledFilterTooltip')];
    }

    #[DataProvider('shouldBeRenderedProvider')]
    public function testShouldBeRendered(RenderData $renderData): void
    {
        $this->assertTrue($renderData->shouldBeRendered());
    }

    /**
     * @param array{
     *     name: string,
     *     displayName: string,
     *     dataPluginSelectorOptions: array{
     *         name: string,
     *         snippets: array{
     *             disabledFilterText: string
     *         }
     *     }
     * } $expectedArray
     */
    #[DataProvider('toArrayProvider')]
    public function testToArray(RenderData $renderData, array $expectedArray): void
    {
        $this->assertSame($expectedArray, $renderData->toArray());
    }

    public static function toArrayProvider(): \Generator
    {
        yield [
            new RenderData('template', 'name', 'displayName', 'disabledFilterTooltip'),
            [
                'name' => 'name',
                'displayName' => 'displayName',
                'dataPluginSelectorOptions' => [
                    'name' => 'name',
                    'snippets' => [
                        'disabledFilterText' => 'disabledFilterTooltip',
                    ],
                ],
            ],
        ];
    }
}
