<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\Range\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\Range\RangeListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\InputValueExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\Range\Storefront\RenderDataBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(RenderDataBuilder::class)]
final class RenderDataBuilderTest extends TestCase
{
    public static function buildRenderDataProvider(): \Generator
    {
        $translator = self::createStub(TranslatorInterface::class);

        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('price');
        $configEntity->setDisplayName('Price');
        $configEntity->setUnit('€');
        $configEntity->setTwigTemplate(RangeListingFilterConfigurationEntity::TWIG_TEMPLATE);

        $aggregationResults = new AggregationResultCollection();

        $inputValueExtractor = self::createStub(InputValueExtractorInterface::class);
        $inputValueExtractor->method('extractGteInputValueFromAggregations')
            ->willReturn(100);
        $inputValueExtractor->method('extractLteInputValueFromAggregations')
            ->willReturn(500);
        $translator->method('trans')
            ->willReturn('This filter is disabled');

        yield 'with unit' => [
            new RenderDataBuilder($inputValueExtractor, $translator),
            $configEntity,
            $aggregationResults,
            [
                'twigTemplate' => RangeListingFilterConfigurationEntity::TWIG_TEMPLATE,
                'filterName' => $configEntity->getFilterName(),
                'displayName' => 'Price',
                'gteQueryParameter' => $configEntity->getMinimalValueAggregationName(),
                'lteQueryParameter' => $configEntity->getMaximalValueAggregationName(),
                'gteInputValue' => 100,
                'lteInputValue' => 500,
                'unit' => '€',
                'disabledFilterTooltip' => 'This filter is disabled',
            ],
        ];

        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('length');
        $configEntity->setDisplayName('Length');
        $configEntity->setTwigTemplate(RangeListingFilterConfigurationEntity::TWIG_TEMPLATE);

        $aggregationResults = new AggregationResultCollection();

        $inputValueExtractor = self::createStub(InputValueExtractorInterface::class);
        $inputValueExtractor->method('extractGteInputValueFromAggregations')
            ->willReturn(10);
        $inputValueExtractor->method('extractLteInputValueFromAggregations')
            ->willReturn(100);
        $translator->method('trans')
            ->willReturn('This filter is disabled');

        yield 'without unit' => [
            new RenderDataBuilder($inputValueExtractor, $translator),
            $configEntity,
            $aggregationResults,
            [
                'twigTemplate' => RangeListingFilterConfigurationEntity::TWIG_TEMPLATE,
                'filterName' => $configEntity->getFilterName(),
                'displayName' => 'Length',
                'gteQueryParameter' => $configEntity->getMinimalValueAggregationName(),
                'lteQueryParameter' => $configEntity->getMaximalValueAggregationName(),
                'gteInputValue' => 10,
                'lteInputValue' => 100,
                'unit' => null,
                'disabledFilterTooltip' => 'This filter is disabled',
            ],
        ];

        $configEntity = new RangeListingFilterConfigurationEntity();
        $configEntity->setDalField('weight');
        $configEntity->setDisplayName('Weight');
        $configEntity->setTwigTemplate(RangeListingFilterConfigurationEntity::TWIG_TEMPLATE);

        $aggregationResults = new AggregationResultCollection();

        $inputValueExtractor = self::createStub(InputValueExtractorInterface::class);
        $inputValueExtractor->method('extractGteInputValueFromAggregations')
            ->willReturn(null);
        $inputValueExtractor->method('extractLteInputValueFromAggregations')
            ->willReturn(null);
        $translator->method('trans')
            ->willReturn('This filter is disabled');

        yield 'with null values' => [
            new RenderDataBuilder($inputValueExtractor, $translator),
            $configEntity,
            $aggregationResults,
            [
                'twigTemplate' => RangeListingFilterConfigurationEntity::TWIG_TEMPLATE,
                'filterName' => $configEntity->getFilterName(),
                'displayName' => 'Weight',
                'gteQueryParameter' => $configEntity->getMinimalValueAggregationName(),
                'lteQueryParameter' => $configEntity->getMaximalValueAggregationName(),
                'gteInputValue' => null,
                'lteInputValue' => null,
                'unit' => null,
                'disabledFilterTooltip' => 'This filter is disabled',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expectedData
     */
    #[DataProvider('buildRenderDataProvider')]
    public function testBuildRenderData(
        RenderDataBuilder $renderDataBuilder,
        RangeListingFilterConfigurationEntity $configurationEntity,
        AggregationResultCollection $aggregationResults,
        array $expectedData
    ): void {
        $renderData = $renderDataBuilder->buildRenderData($configurationEntity, $aggregationResults);

        $this->assertEquals($expectedData['twigTemplate'], $renderData->twigTemplate);

        $renderDataArray = $renderData->toArray();
        $this->assertEquals($expectedData['filterName'], $renderDataArray['name']);
        $this->assertEquals($expectedData['displayName'], $renderDataArray['displayName']);
        $this->assertEquals($expectedData['gteQueryParameter'], $renderDataArray['minKey']);
        $this->assertEquals($expectedData['lteQueryParameter'], $renderDataArray['maxKey']);
        $this->assertEquals($expectedData['gteInputValue'], $renderDataArray['minInputValue']);
        $this->assertEquals($expectedData['lteInputValue'], $renderDataArray['maxInputValue']);
        $this->assertEquals($expectedData['unit'] ?? '', $renderDataArray['unit']);

        $this->assertEquals($expectedData['filterName'], $renderDataArray['dataPluginSelectorOptions']['name']);
        $this->assertEquals(
            $expectedData['disabledFilterTooltip'],
            $renderDataArray['dataPluginSelectorOptions']['snippets']['disabledFilterText']
        );
    }
}
