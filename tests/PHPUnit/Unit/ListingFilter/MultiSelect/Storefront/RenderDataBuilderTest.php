<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\MultiSelect\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\MultiSelect\MultiSelectListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\ElementsExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderDataBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(RenderDataBuilder::class)]
final class RenderDataBuilderTest extends TestCase
{
    /**
     * @return \Generator<string, array{MultiSelectListingFilterConfigurationEntity, array<Element>, string, string}, mixed, void>
     */
    public static function buildRenderDataProvider(): \Generator
    {
        $configurationEntity = new MultiSelectListingFilterConfigurationEntity();
        $configurationEntity->setDalField('color');
        $configurationEntity->setTwigTemplate(MultiSelectListingFilterConfigurationEntity::TWIG_TEMPLATE);
        $configurationEntity->setDisplayName('Product Color');

        $elements = [new Element('red', 'Red'), new Element('blue', 'Blue'), new Element('green', 'Green')];

        yield 'standard configuration' => [
            $configurationEntity,
            $elements,
            'listing.disabledFilterTooltip',
            RenderDataBuilder::JS_PLUGIN_SELECTOR,
        ];
    }

    /**
     * @param array<Element> $elements
     */
    #[DataProvider('buildRenderDataProvider')]
    public function testBuildRenderData(
        MultiSelectListingFilterConfigurationEntity $configurationEntity,
        array $elements,
        string $expectedMessageToTranslate,
        string $expectedJsPluginSelector
    ): void {
        // Mock the ElementsExtractor
        $elementsExtractorMock = $this->createMock(ElementsExtractorInterface::class);
        $elementsExtractorMock->method('extractElementsFromAggregations')
            ->willReturn($elements);

        // Mock the Translator
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $translatorMock->method('trans')
            ->willReturnCallback(function (string $message) use ($expectedMessageToTranslate): string {
                $this->assertSame($expectedMessageToTranslate, $message);
                return 'translated disabled filter text';
            });

        $renderDataBuilder = new RenderDataBuilder($elementsExtractorMock, $translatorMock);

        // Create a mock aggregation result collection
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $renderData = $renderDataBuilder->buildRenderData($configurationEntity, $aggregationResultsMock);

        // Test render data properties
        $this->assertSame($configurationEntity->getTwigTemplate(), $renderData->twigTemplate);

        $renderDataArray = $renderData->toArray();
        $this->assertSame($configurationEntity->getFilterName(), $renderDataArray['name']);
        $this->assertSame($configurationEntity->getDisplayName(), $renderDataArray['displayName']);
        $this->assertSame($expectedJsPluginSelector, $renderDataArray['pluginSelector']);
        $this->assertSame(
            'translated disabled filter text',
            $renderDataArray['dataPluginSelectorOptions']['snippets']['disabledFilterText']
        );
        $this->assertSame($elements, $renderDataArray['elements']);
    }
}
