<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Dal;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\ListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelectValueSplitter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MultiSelectValueSplitter::class)]
final class MultiSelectValueSplitterTest extends TestCase
{
    public static function splitMultiSelectValueProvider(): \Generator
    {
        $multiSelectValueSplitter = new MultiSelectValueSplitter();

        $configurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $configurationEntity->setDalField('color');

        yield 'split string with multiple values' => [
            $multiSelectValueSplitter,
            'color_red|color_blue|color_green',
            $configurationEntity,
            ['red', 'blue', 'green'],
        ];
        yield 'request with single value' => [$multiSelectValueSplitter, 'color_red', $configurationEntity, ['red']];
        yield 'request with empty string' => [$multiSelectValueSplitter, '', $configurationEntity, []];
        yield 'request with only separators' => [$multiSelectValueSplitter, '|||', $configurationEntity, []];
    }

    /**
     * @param array<string> $expectedValues
     */
    #[DataProvider('splitMultiSelectValueProvider')]
    public function testSplitMultiSelectValue(
        MultiSelectValueSplitter $multiSelectValueSplitter,
        string $valuesAsString,
        ListingFilterConfigurationEntity $filterConfigurationEntity,
        array $expectedValues
    ): void {
        $multiSelectValueSplitter->splitMultiSelectValue($valuesAsString, $filterConfigurationEntity);
        $this->assertEquals($expectedValues, $multiSelectValueSplitter->splitMultiSelectValue($valuesAsString, $filterConfigurationEntity));
    }
}
