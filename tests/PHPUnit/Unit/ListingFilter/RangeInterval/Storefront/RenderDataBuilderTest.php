<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Test\PHPUnit\Unit\ListingFilter\RangeInterval\Storefront;

use ITB\ITBConfigurableListingFilters\Core\Content\ListingFilterConfiguration\RangeInterval\RangeIntervalListingFilterConfigurationEntity;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\Element;
use ITB\ITBConfigurableListingFilters\ListingFilter\MultiSelect\Storefront\RenderData;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\ElementsExtractorInterface;
use ITB\ITBConfigurableListingFilters\ListingFilter\RangeInterval\Storefront\RenderDataBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(RenderDataBuilder::class)]
final class RenderDataBuilderTest extends TestCase
{
    private MockObject&ElementsExtractorInterface $elementsExtractorMock;

    private RenderDataBuilder $renderDataBuilder;

    private MockObject&TranslatorInterface $translatorMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->elementsExtractorMock = $this->createMock(ElementsExtractorInterface::class);
        $this->translatorMock = $this->createMock(TranslatorInterface::class);
        $this->renderDataBuilder = new RenderDataBuilder($this->elementsExtractorMock, $this->translatorMock);
    }

    public function testBuildRenderData(): void
    {
        $configMock = $this->createMock(RangeIntervalListingFilterConfigurationEntity::class);
        $aggregationResultsMock = $this->createMock(AggregationResultCollection::class);

        $expectedTemplate = 'test-template.html.twig';
        $expectedFilterName = 'test-filter-name';
        $expectedDisplayName = 'Test Filter Display Name';
        $expectedJsPluginSelector = RenderDataBuilder::JS_PLUGIN_SELECTOR;
        $expectedTooltip = 'Translated Tooltip';

        $element1 = new Element(Uuid::randomHex(), 'Element 1');
        $element2 = new Element(Uuid::randomHex(), 'Element 2');
        $extractedElements = [$element1, $element2];

        $configMock->method('getTwigTemplate')
            ->willReturn($expectedTemplate);
        $configMock->method('getFilterName')
            ->willReturn($expectedFilterName);
        $configMock->method('getDisplayName')
            ->willReturn($expectedDisplayName);

        $this->elementsExtractorMock
            ->expects($this->once())
            ->method('extractElementsFromAggregations')
            ->with($this->identicalTo($configMock), $this->identicalTo($aggregationResultsMock))
            ->willReturn($extractedElements);

        $this->translatorMock
            ->expects($this->once())
            ->method('trans')
            ->with('listing.disabledFilterTooltip')
            ->willReturn($expectedTooltip);

        $renderData = $this->renderDataBuilder->buildRenderData($configMock, $aggregationResultsMock);

        $this->assertInstanceOf(RenderData::class, $renderData);
        $this->assertSame($expectedTemplate, $renderData->twigTemplate);

        $expectedArray = [
            'name' => $expectedFilterName,
            'displayName' => $expectedDisplayName,
            'pluginSelector' => $expectedJsPluginSelector,
            'dataPluginSelectorOptions' => [
                'name' => $expectedFilterName,
                'displayName' => $expectedDisplayName,
                'snippets' => [
                    'disabledFilterText' => $expectedTooltip,
                ],
            ],
            'elements' => $extractedElements,
        ];

        $this->assertEquals($expectedArray, $renderData->toArray());
    }
}
